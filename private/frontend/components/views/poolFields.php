<?php
/* @var $field \common\models\PageFieldRecord */

if ($field) {
	echo $field->additionalField->label . ': <strong>' . $field->content . '</strong>';
}