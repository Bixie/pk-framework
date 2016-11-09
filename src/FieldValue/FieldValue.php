<?php

namespace Bixie\PkFramework\FieldValue;


use Pagekit\Application as App;
use Bixie\PkFramework\Field\FieldBase;

class FieldValue extends FieldValueBase {

	/**
	 * FieldValue constructor.
	 * @param FieldBase $field
	 * @param array $value
	 * @param array $data
	 */
	public function __construct (FieldBase $field, $value, $data) {
		$this->setField($field);
		$this->value = is_array($value) ? $value : (!empty($value) ? [$value] : []);
		$this->data = $data;
	}

}