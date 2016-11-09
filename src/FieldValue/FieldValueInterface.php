<?php

namespace Bixie\PkFramework\FieldValue;


interface FieldValueInterface {

	/**
	 * @return \Bixie\PkFramework\FieldType\FieldTypeBase
	 */
	public function getFieldType ();

	/**
	 * @return array
	 */
	public function toFormattedArray ();

	/**
	 * @return array
	 */
	public function formatValue ();

}