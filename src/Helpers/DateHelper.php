<?php


namespace Bixie\PkFramework\Helpers;


use Pagekit\Application as App;

class DateHelper {

	protected static $intl2php = [
		'yyyy' => 'Y',		//A full numeric representation of a year, 4 digits
		'yy' => 'y',		//A two digit representation of a year
		'y' => 'Y',			//A full numeric representation of a year, 4 digits
		'MMMM' => 'F',		//A full textual representation of a month, such as January or March
		'MMM' => 'M',		//A short textual representation of a month, three letters
		'MM' => 'm',		//Numeric representation of a month, with leading zeros
		'M' => 'n',			//Numeric representation of a month, without leading zeros
		'dd' => 'd',		//Day of the month without leading zeros+English ordinal suffix for the day of the month, 2 characters
		'd' => 'j',			//Day of the month without leading zeros
		'HH' => 'H',		//24-hour format of an hour with leading zeros
		'H' => 'G',			//24-hour format of an hour without leading zeros
		'hh' => 'h',		//12-hour format of an hour with leading zeros
		'h' => 'g',			//12-hour format of an hour without leading zeros
		'mm' => 'i',		//Minutes with leading zeros
		'm' => 'i',			//Minutes with leading zeros
		'sss' => 'u',		//Microseconds
		'ss' => 's',		//Seconds with leading zeros
		's' => 's',			//Seconds with leading zeros
		'EEEE' => 'l',		//A full textual representation of the day of the week
		'EEE' => 'D',		//A textual representation of a day, three letters
		'E' => 'N',			//ISO-8601 numeric representation of the day of the week
		'a' => 'a',			//Lowercase Ante meridiem and Post meridiem
		'Z' => 'P',			//Difference to Greenwich time (GMT) with colon between hours and minutes
		'ww' => 'W',		//	ISO-8601 week number of year, weeks starting on Monday
		'w' => 'W'			//	ISO-8601 week number of year, weeks starting on Monday
	];

	/**
	 * @param string|\DateTime $date
	 * @param string $format
	 * @param string $tz
	 * @return string
	 */
	public static function format ($date, $format = 'mediumDate', $tz = '') {
		try {

			if (is_string($date)) {
				$date = new \DateTime($date);
			}
            if ($tz) {
                $date->setTimezone(new \DateTimeZone($tz));
            }

            $format = static::getFormat($format);

			return static::translate($date->format($format), $format);

		} catch (\Exception $e) {

			return $date;

		}
	}

    /**
     * Convert moment(like) jsformat to PHP date format
     * @param $format
     * @return string
     */
	protected static function getFormat ($format) {
		$intl = App::module('system/intl');
		$formats = $intl->getFormats($intl->getLocale());
		$moment_format = isset($formats['DATETIME_FORMATS'][$format]) ? $formats['DATETIME_FORMATS'][$format] : $format;
		return strtr($moment_format, static::$intl2php);
	}

    /**
     * Translate dates
     * @param $formatted_date
     * @param $format
     * @return string
     */
	protected static function translate ($formatted_date, $format) {
	    if (!preg_match('/[F|M|l|D]/', $format)) {
	        //bail out when no textual repres
	        return $formatted_date;
        }
		$intl = App::module('system/intl');
		$formats_us = $intl->getFormats('en_US');
		$formats_locale = $intl->getFormats($intl->getLocale());
        $translations = array_merge(
            array_combine($formats_us['DATETIME_FORMATS']['MONTH'], $formats_locale['DATETIME_FORMATS']['MONTH']),
            array_combine($formats_us['DATETIME_FORMATS']['DAY'], $formats_locale['DATETIME_FORMATS']['DAY']),
            array_combine($formats_us['DATETIME_FORMATS']['SHORTMONTH'], $formats_locale['DATETIME_FORMATS']['SHORTMONTH']),
            array_combine($formats_us['DATETIME_FORMATS']['SHORTDAY'], $formats_locale['DATETIME_FORMATS']['SHORTDAY'])
        );
		return strtr($formatted_date, $translations);
	}
}