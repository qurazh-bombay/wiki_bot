<?php
declare(strict_types = 1);

namespace App\Telegram;

/**
 * Class Calendar
 */
class Calendar
{
	// to get 29 Feb days
	const LEAP_YEAR = 2000;

	/**
	 * returns list of months where key is a month number with leading 0 and value is month short name
	 *
	 * example: ['06' => 'Jun']
	 *
	 * @param boolean $lowerCase
	 *
	 * @return array
	 */
	public static function getMonthShortName(bool $lowerCase = false): array
	{
		$months = [];
		for ($i = 1; $i <= 12; $i++) {
			$monthShortName = date('M', mktime(0, 0, 0, $i, 1));
			$key            = str_pad((string) $i, 2, '0', STR_PAD_LEFT);
			$months[$key]   = $lowerCase ? strtolower($monthShortName) : $monthShortName;
		}

		return $months;
	}

	/**
	 * @param string $monthNum
	 *
	 * @return integer
	 */
	public static function countMonthDays(string $monthNum): int
	{
		return \cal_days_in_month(0, (int) $monthNum, self::LEAP_YEAR);
	}

	/**
	 * @param string $value
	 *
	 * @return boolean
	 */
	public static function isDateExist(string $value): bool
	{
		preg_match('#^(?<month>\d{2})\/(?<day>\d?\d)$#s', $value, $matches);

		if (isset($matches['month']) and isset($matches['day'])) {
			$month = (int) $matches['month'];
			$day   = (int) $matches['day'];

			return checkdate($month, $day, self::LEAP_YEAR);
		}

		return false;
	}

	/**
	 * @param string $value
	 *
	 * @return string
	 */
	public static function createDateAsStringFromInput(string $value): string
	{
		$date = explode('/', $value);
		$day  = str_pad($date[1], 2, '0', STR_PAD_LEFT);

		return sprintf('%s-%s-%s', self::LEAP_YEAR, $date[0], $day);
	}
}
