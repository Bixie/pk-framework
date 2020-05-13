<?php


namespace Bixie\PkFramework\Helpers;


class CurrencyHelper {

    protected static $_symbols = [
        'EUR' => '&euro; ',
        'USD' => '$'
    ];

    /**
     * @param        $number
     * @param string $currency
     * @param null $symbol
     * @return string
     */
	public static function format ($number, $currency = 'EUR', $symbol = null) {
		return ($symbol ? $symbol : self::$_symbols[$currency]) . number_format(floatval($number), 2, ',', '.');
	}

}
