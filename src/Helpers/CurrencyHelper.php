<?php


namespace Bixie\PkFramework\Helpers;

use Pagekit\Application as App;


class CurrencyHelper {

    protected static $_symbols = [
        'EUR' => '&euro; ',
        'USD' => '$'
    ];

	/**
	 * @param        $number
	 * @param string $currency
	 * @return string
	 */
	public static function format ($number, $currency = 'EUR') {
		return self::$_symbols[$currency] . number_format($number, 2, ',', '.');
	}

}