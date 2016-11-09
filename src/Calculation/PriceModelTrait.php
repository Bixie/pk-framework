<?php

namespace Bixie\PkFramework\Calculation;


trait PriceModelTrait {

    /**
     * @Column(type="json_array")
     * @var Price[]
     */
    public $prices;

    /**
     * @return bool
     */
    public function hasPrices () {
        return count($this->getPrices()) > 0;
    }

    /**
     * @return Price[]
     */
    public function getPrices () {
        return array_map(function ($price) {
            return $price instanceof Price ? $price : new Price($price);
        }, $this->prices);
    }


}