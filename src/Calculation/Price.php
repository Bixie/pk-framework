<?php

namespace Bixie\PkFramework\Calculation;


use Bixie\PkFramework\Traits\JsonSerializableTrait;

class Price {

    use JsonSerializableTrait;

    public $min_quantity = 1;

    public $max_quantity = 1;

    public $price = 0;

    public $currency = 'EUR';

}