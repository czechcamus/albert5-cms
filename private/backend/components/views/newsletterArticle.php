<?php
/* @var $article \common\models\Article */
/* @var $type string */

use pavlinter\display\DisplayImage;
use yii\helpers\Html;
use yii\helpers\Url;

$url = str_replace('admin/', '', Url::to(['site/content', 'id' => $article->id], true));
$image = '';
if ($article->image) {
	$image = DisplayImage::widget( [
		'width'    => Yii::$app->params['newsletterArticleImage']['width'],
		'height'   => Yii::$app->params['newsletterArticleImage']['height'],
		'category' => 'all',
		'image'    => $article->image->filename,
		'absolutePath' => true
	] );
}
?>
<tr>
	<td>
		<table style="width: 100%; background-color: <?= $type == 'main' ? '#C1E6F6;' : 'whitesmoke;'; ?> padding: 15px 15px 15px 15px; margin: 15px 0 15px 0;">
			<tr>
				<?php if ($image): ?>
					<td style="width: 30%; vertical-align: top; padding-right: 15px;"><?= Html::a($image, $url); ?></td>
				<?php endif; ?>
				<td style="vertical-align: top;">
					<h2 style="font-family: Impact, 'Techno CE', sans-serif; font-weight: normal; font-size: <?= $type == 'main' ? '24px;' : '18px;'; ?>;"><?= Html::a($article->title, $url, [
							'style' => 'color: #024A80;'
						]); ?></h2>
					<?= $article->perex; ?>
				</td>
			</tr>
		</table>
	</td>
</tr>