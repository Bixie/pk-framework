<?php

namespace Bixie\PkFramework\Traits;


trait CreatedModifiedTrait {

    /**
     * @Column(type="integer")
     * @var int
     */
	public $created_by = 0;

    /**
     * @Column(type="datetime")
     * @var \DateTime
     */
	public $created = '';

    /**
     * @Column(type="integer")
     * @var int
     */
	public $modified_by = 0;

    /**
     * @Column(type="datetime")
     * @var \DateTime
     */
	public $modified = '';

}