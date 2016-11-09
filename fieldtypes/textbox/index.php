<?php
return [
	'id' => 'textbox',
	'label' => __('Text area'),
	'config' => [
		'hasOptions' => 0,
		'required' => -1,
		'multiple' => 0,
		'minLength' => 0,
		'maxLength' => 0,
		'rows' => 0,
		'placeholder' => ''
	],
	'formatValue' => function (\Bixie\PkFramework\Field\FieldBase $field, \Bixie\PkFramework\FieldValue\FieldValueBase $fieldValue) {
		return array_map(function ($val) {
			return nl2br($val);
		}, $fieldValue->getValue());
	}
];