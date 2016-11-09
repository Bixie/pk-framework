<?php
return [
	'id' => 'dob',
	'label' => __('Date of birth'),
	'config' => [
		'hasOptions' => 0,
		'required' => 0,
		'multiple' => 0,
		'dateFormat' => 'MM-DD-YYYY',
		'minAge' => 12,
		'maxAge' => 120
	],
	'dependancies' => ['uikit-form-select', 'uikit-datepicker'],
	'styles' => ['uikit-form-select' => 'app/assets/uikit/css/components/form-select.css'],
	'formatValue' => function (\Bixie\PkFramework\Field\FieldBase $field, \Bixie\PkFramework\FieldValue\FieldValueBase $fieldValue) {
		$formats = ['DD-MM-YYYY' => 'F, m Y', 'MM-DD-YYYY' => 'm F Y'];
		$value = $fieldValue->getValue();
		try {

			$date = new DateTime(reset($value));

			return [$date->format($formats[$field->get('dateFormat', 'MM-DD-YYYY')])];

		} catch (Exception $e) {
			return ['-'];
		}

	}
];