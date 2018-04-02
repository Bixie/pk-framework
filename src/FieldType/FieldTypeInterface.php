<?php

namespace Bixie\PkFramework\FieldType;

use Bixie\PkFramework\Field\FieldBase;
use Bixie\PkFramework\FieldValue\FieldValueBase;

interface FieldTypeInterface  {

	/**
	 * @return string
	 */
	public function getLabel ();

	/**
	 * @return array
	 */
	public function getExtensions ();

	/**
	 * @param FieldBase $field
	 * @return array
	 */
	public function getOptions (FieldBase $field);

	/**
	 * @param FieldBase $field
	 * @param FieldValueBase $fieldValue
	 * @return array
	 */
	public function prepareValue (FieldBase $field, FieldValueBase $fieldValue);

	/**
	 * @param FieldBase $field
	 * @param FieldValueBase $fieldValue
	 * @return array
	 */
	public function formatValue (FieldBase $field, FieldValueBase $fieldValue);

    /**
     * @param \Pagekit\View\Asset\AssetManager $scripts
     * @param                                  $version
     * @return
     */
	public function registerScripts ($scripts, $version);

	/**
	 * @param \Pagekit\View\Asset\AssetManager $styles
	 */
	public function addStyles ($styles);

	/**
	 * @return array
	 */
	public function toArray();
}