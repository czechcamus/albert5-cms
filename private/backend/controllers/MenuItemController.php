<?php

namespace backend\controllers;

use backend\models\MenuItemForm;
use backend\utilities\BackendController;
use backend\utilities\MenuFilter;
use common\models\Category;
use common\models\LanguageRecord;
use common\models\LayoutRecord;
use common\models\MenuRecord;
use common\models\Page;
use Yii;
use common\models\MenuItemRecord;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * MenuItemController implements the CRUD actions for MenuItemForm model.
 */
class MenuItemController extends BackendController
{
    private $_menu;

    /**
     * @inheritdoc
     * @return array
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'roles' => ['manager'],
                        'allow' => true
                    ]
                ]
            ],
            'menu' => [
                'class' => MenuFilter::className(),
                'except' => ['create-from-content', 'menu-list-options']
            ]
        ];
    }

    /**
     * Lists Menu items in one level.
     *
     * @param null $pid parent item ID
     * @return string
     * @throws \Exception
     */
    public function actionIndex($pid = null)
    {
        $model = new MenuItemForm($this->_menu->id, $pid);
        $model->scenario = 'index';

        if ($model->load(Yii::$app->request->post())) {
            foreach ($item_order = explode( ',', $model->item_order) as $key => $value) {
	            /** @var $item MenuItemRecord */
                $item = MenuItemRecord::findOne($value);
                $item->item_order = $key + 1;
                $item->update(false, ['item_order']);
            }
            $session = Yii::$app->session;
            $session->setFlash('info', Yii::t('back', 'Menu items order successfully saved!'));
        }

	    $menu_id = $this->_menu->id;
        $web_id = $this->_menu->web_id;
        return $this->render('index', compact('model', 'menu_id', 'web_id'));
    }

    /**
     * Creates a new MenuItemForm model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new MenuItemForm($this->_menu->id);
        $model->scenario = 'create';

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->saveMenuItem();

	        $session = Yii::$app->session;
	        $session->setFlash('info', Yii::t('back', 'New menu item successfully added!'));

	        $redirectUrl = ['index'];
	        if ($model->parent_id)
		        $redirectUrl['pid'] = $model->parent_id;

	        return $this->redirect($redirectUrl);
        } elseif (Yii::$app->request->isAjax) {
	        return $this->renderAjax('_form', compact('model'));
        }

	    return $this->render('_form', compact('model'));
    }

    /**
     * Updates an existing MenuItemForm model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        $model = new MenuItemForm($this->_menu->id, null, $id);
	    $model->scenario = 'update';

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
	        $model->saveMenuItem(false);

	        $session = Yii::$app->session;
	        $session->setFlash('info', Yii::t('back', 'Menu item successfully updated!'));

	        $redirectUrl = ['index'];
	        if ($model->parent_id)
		        $redirectUrl['pid'] = $model->parent_id;

	        return $this->redirect($redirectUrl);
        } elseif (Yii::$app->request->isAjax) {
	        return $this->renderAjax('_form', compact('model'));
        }

	    return $this->render('_form', compact('model'));
    }

    /**
     * Deletes an existing MenuItem record model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
	    $model = new MenuItemForm($this->_menu->id, null, $id);
	    $model->deleteMenuItem();

	    $session = Yii::$app->session;
	    $session->setFlash('info', Yii::t('back', 'Menu item successfully deleted!'));

	    $redirectUrl = ['index'];
	    if ($model->parent_id)
		    $redirectUrl['pid'] = $model->parent_id;

	    return $this->redirect($redirectUrl);
    }

    /**
     * Creates menu item from existing content
     * @param $content_type integer
     * @param $content_id integer
     *
     * @return string|Response
     */
    public function actionCreateFromContent( $content_type, $content_id ) {
        $model = new MenuItemForm;
        $model->scenario = 'createFromContent';

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->saveMenuItem();

            $session = Yii::$app->session;
            $session->setFlash('info', Yii::t('back', 'New menu item successfully added!'));

            $controllerId = $content_type == MenuItemRecord::CONTENT_PAGE ? 'page' : 'category';
            $redirectUrl = [$controllerId . '/index'];

            return $this->redirect($redirectUrl);
        } else {
            $model->content_type = $content_type;
            $model->content_id = $content_id;
            /** @var Page|Category $content */
            $content = $content_type == MenuItemRecord::CONTENT_PAGE ? Page::findOne($content_id) : Category::findOne($content_id);
	        $model->title = $content->title;
            $boxes = [];
            if ($content->active)
                $boxes[] = MenuItemForm::PROPERTY_ACTIVE;
            if ($content->public)
                $boxes[] = MenuItemForm::PROPERTY_PUBLIC;
            $model->boxes = $boxes;
	        if (Yii::$app->request->isAjax) {
                return $this->renderAjax('_formFromContent', compact('model'));
	        } else {
		        return $this->render('_formFromContent', compact('model'));
	        }
        }
    }

    /**
     * Sets $_menu property
     * @param $id
     *
     * @throws NotFoundHttpException
     */
    public function setMenu($id)
    {
        if (($this->_menu = MenuRecord::findOne($id)) === null)
            throw new NotFoundHttpException(Yii::t('back', 'The requested menu does not exist.'));
    }

    /**
     * Gets $_menu property
     * @return MenuRecord $_menu
     */
    public function getMenu() {
        return $this->_menu;
    }

	/**
	 * Returns dropdown content list options
	 * @param $tid integer content type id
	 * @return string
	 */
    public function actionContentListOptions($tid)
    {
	    $session = Yii::$app->session;
	    if (!$session['language_id'])
		    $session['language_id'] = LanguageRecord::getMainLanguageId();

	    $items = null;
        switch ($tid) {
            case MenuItemRecord::CONTENT_PAGE:
                $items = Page::find()->andWhere([
	                'language_id' => $session['language_id']
                ])->activeStatus()->orderBy('updated_at DESC')->all();
                break;
            case MenuItemRecord::CONTENT_CATEGORY:
                $items = Category::find()->andWhere([
	                'language_id' => $session['language_id']
                ])->activeStatus()->orderBy('updated_at DESC')->all();
                break;
            default:
                break;
        }
	    $itemsOptions = [
		    'arr' => false,
		    'prompt' => false
	    ];
	    return $this->renderPartial('_listOptions', compact('items', 'itemsOptions'));
    }

    /**
     * Returns dropdown layout list options
     * @param $tid integer content type id
     * @return \yii\console\Response|Response
     */
    public function actionLayoutListOptions($tid)
    {
        $items = null;
	    switch ($tid) {
		    case MenuItemRecord::CONTENT_PAGE:
			    $items = LayoutRecord::find()->where( [
				    'content' => LayoutRecord::CONTENT_PAGE
			    ] )->activeStatus()->orderBy( [ 'main' => SORT_DESC ] )->all();
			    break;
		    case MenuItemRecord::CONTENT_CATEGORY:
			    $items = LayoutRecord::find()->where( [
				    'content' => LayoutRecord::CONTENT_CATEGORY
			    ] )->activeStatus()->orderBy( [ 'main' => SORT_DESC ] )->all();
			    break;
		    default:
			    break;
	    }
	    $itemsOptions = [
		    'arr' => false,
		    'prompt' => false
	    ];
	    return $this->renderPartial('_listOptions', compact('items', 'itemsOptions'));
    }

	/**
	 * Gets menu options for dropdown
	 * @param $wid integer ID of web
	 * @return string
	 */
	public function actionMenuListOptions( $wid ) {
		$items = MenuRecord::find()->where(['web_id' => $wid])->activeStatus()->orderBy('title')->all();
		$itemsOptions = [
			'arr' => false,
			'prompt' => "-- " . Yii::t('back', 'Not selected') . " --"
		];
		return $this->renderPartial('_listOptions', compact('items', 'itemsOptions'));
    }

	/**
	 * Gets parent options for dropdown
	 * @return string
	 */
	public function actionParentListOptions() {
		$model = new MenuItemForm($this->_menu->id);
		$items = $model->getParentItems();
		$itemsOptions = [
			'arr' => true,
			'prompt' => "-- " . Yii::t('back', 'No parent') . " --"
		];
		return $this->renderPartial('_listOptions', compact('items', 'itemsOptions'));
	}
}
