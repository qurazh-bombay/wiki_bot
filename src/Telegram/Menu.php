<?php
declare(strict_types = 1);

namespace App\Telegram;

use App\Telegram\KeyBoard\Button;
use App\Telegram\KeyBoard\KeyBoard;
use App\Telegram\KeyBoard\Row;

/**
 * Class Menu
 */
class Menu
{
	/**
	 * @return array
	 */
	public static function createMainMenu(): array
	{
		$keyBoard = new KeyBoard();

		$keyBoard->add(
			(new Row())->add(
				new Button('on that day', '/today'),
				new Button('calendar', '/calendar')
			)
		);

		return [
			'inline_keyboard' => $keyBoard->toArray(),
			'resize_keyboard' => true,
		];
	}

	/**
	 * @param string|null $selectedInputDate
	 *
	 * @return array
	 */
	public static function createGimmeMoreMenu(?string $selectedInputDate = null): array
	{
		$keyBoard = new KeyBoard();

		$keyBoard->add(
			(new Row())->add(
				new Button('gimme more', '/today'),
				new Button('calendar', '/calendar')
			)
		);

		if ($selectedInputDate !== null) {
			$date      = explode('/', $selectedInputDate);
			$gimmeMore = 'gimme more on ' . $date[1] . '/' . $date[0];

			$keyBoard->clear()->add(
				(new Row())->add(
					new Button($gimmeMore, $selectedInputDate),
					new Button('calendar', '/calendar')
				),
				(new Row())->add(
					new Button('menu', '/start')
				)
			);
		}

		return [
			'inline_keyboard' => $keyBoard->toArray(),
			'resize_keyboard' => true,
		];
	}

	/**
	 * returns 3 rows of buttons, each row contains 4 buttons with month short name
	 *
	 * @return array
	 */
	public static function createMonthsMenu(): array
	{
		$months   = Calendar::getMonthShortName();
		$keyBoard = new KeyBoard();

		// add 3 rows of month buttons
		for ($i = 0; $i < 3; $i++) {
			$keyBoard->add(new Row());
		}

		$rowIndex = 0;

		foreach ($months as $monthIndex => $month) {
			$row = $keyBoard->getRow($rowIndex);

			if ($row === null) {
				break;
			}

			$row->add(new Button($month, (string) $monthIndex));

			// row will contain 4 month buttons
			if ($monthIndex % 4 === 0) {
				$rowIndex++;
			}
		}

		// add link to start after month buttons
		$keyBoard->add(
			(new Row())->add(new Button('menu', '/start'))
		);

		return [
			'inline_keyboard' => $keyBoard->toArray(),
			'resize_keyboard' => true,
		];
	}

	/**
	 * returns 5 rows of buttons, each row contains 7 buttons with day date
	 *
	 * @param string $monthNum
	 *
	 * @return array
	 */
	public static function createDaysMenu(string $monthNum): array
	{
		$days     = Calendar::countMonthDays($monthNum);
		$keyBoard = new KeyBoard();

		// add 5 rows of day buttons
		for ($i = 0; $i < 5; $i++) {
			$keyBoard->add(new Row());
		}

		$rowIndex = 0;

		for ($i = 1; $i <= $days; $i++) {
			$day      = (string) $i;
			$fullDate = $monthNum . '/' . $day;

			$row = $keyBoard->getRow($rowIndex);

			if ($row === null) {
				break;
			}

			$row->add(new Button($day, (string) $fullDate));

			// row will contain 7 day buttons
			if (($i) % 7 === 0) {
				$rowIndex++;
			}
		}

		// add links to start and calendar after month buttons
		$keyBoard->add(
			(new Row())->add(
				new Button('calendar', '/calendar'),
				new Button('menu', '/start')
			)
		);

		return [
			'inline_keyboard' => $keyBoard->toArray(),
			'resize_keyboard' => true,
		];
	}
}
