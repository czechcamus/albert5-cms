<?php

namespace backend\controllers;

use backend\models\NewsletterForm;
use backend\utilities\BackendController;
use common\models\EmailRecord;
use common\models\Newsletter;
use Yii;
use backend\models\NewsletterSearch;
use yii\db\Expression;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

/**
 * NewsletterController implements the CRUD actions for Newsletter model.
 */
class NewsletterController extends BackendController
{
	/**
	 * @inheritdoc$
	 */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
            'access' => [
	            'class' => AccessControl::className(),
	            'rules' => [
		            [
			            'actions' => ['view'],
			            'roles' => ['?'],
			            'allow' => true
		            ],
		            [
			            'roles' => ['user'],
			            'allow' => true
		            ]
	            ]
            ]
        ];
    }

    /**
     * Lists all Newsletter models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new NewsletterSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', compact('searchModel', 'dataProvider'));
    }

    /**
     * Creates a new Newsletter model.
     * If creation is successful, the browser will be redirected to the 'index' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new NewsletterForm();
	    $model->scenario = 'create';

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
	        $model->saveNewsletter();

	        $session = Yii::$app->session;
	        $session->setFlash('info', Yii::t('back', 'New page successfully added!'));

	        return $this->redirect(['index']);
        } else {
            return $this->render('create', compact('model'));
        }
    }

    /**
     * Updates an existing Newsletter model.
     * If update is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = new NewsletterForm($id);
        $model->scenario = 'update';

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
	        $model->saveNewsletter(false);

	        $session = Yii::$app->session;
	        $session->setFlash('info', Yii::t('back', 'Newsletter successfully updated!'));

	        return $this->redirect(['index']);
        } else {
            return $this->render('update', compact('model'));
        }
    }

    /**
     * Deletes an existing Newsletter model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
	    $model = new NewsletterForm($id);
	    $model->deleteNewsletter();

	    $session = Yii::$app->session;
	    $session->setFlash('info', Yii::t('back', 'Newsletter successfully deleted!'));

	    return $this->redirect(['index']);
    }


	public function actionView($id)
	{
		$model = Newsletter::findOne($id);
		if ($model) {
			$this->layout = '@backend/views/newsletter/layouts/' . $model->layout->filename;
			$viewMail = false;
			return $this->render('view', compact('model', 'viewMail'));
		} else {
			throw new NotFoundHttpException(Yii::t('back', 'The requested page does not exist.'));
		}
	}

	public function actionSend($id)
	{
		/** @var Newsletter $model */
		$model = Newsletter::findOne($id);
		if ($model) {
			$this->layout = '@backend/views/newsletter/layouts/' . $model->layout->filename;
			$viewMail = true;
			$htmlBody = $this->render('view', compact('model', 'viewMail'));
			$textBody = Yii::t('back', 'Dear user') . ",\n\n";
			$textBody .= Yii::t('back', 'we would send to you a pretty version of our newsletter. But your email client doesn\'t support it.') . "\n";
			$textBody .= Yii::t('back', 'Luckily you can see our newsletter at this link') . ": " . Url::to(['view', 'id' => $id], true) . "\n\n";
			$textBody .= Yii::t('back', 'Yours sincerely') . " " . Yii::$app->params['sendingEmailTitle'];

			$sendingEmail = Yii::$app->params['sendingEmail'];
			$targetEmailsChunks = array_chunk(EmailRecord::getActiveEmails(), Yii::$app->params['maxEmailsCount']);
			foreach ( $targetEmailsChunks as $targetEmailsChunk ) {
				Yii::$app->mailer->compose()
	                 ->setTo(Yii::$app->params['sendingEmail'])
	                 ->setBcc($targetEmailsChunk)
	                 ->setFrom([$sendingEmail => Yii::$app->params['sendingEmailTitle']])
	                 ->setSubject(Yii::$app->params['sendingEmailTitle'] . ' - ' . Yii::t('back', 'newsletter'))
	                 ->setTextBody($textBody)
	                 ->setHtmlBody($htmlBody)
	                 ->send();
			}

			$model->content_date = new Expression('DATE(NOW())');
			$model->content_time = new Expression('TIME(NOW())');
			$model->save(false);

			$session = Yii::$app->session;
			$session->setFlash('info', Yii::t('back', 'Newsletter successfuly sent!'));

			return $this->redirect(['index']);

		} else {
			throw new NotFoundHttpException(Yii::t('back', 'The requested page does not exist.'));
		}
	}
}
