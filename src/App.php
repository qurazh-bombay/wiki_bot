<?php
declare(strict_types = 1);

namespace App;

use App\Telegram\Bot;
use App\Wiki\Wiki;
use App\Telegram\Menu;
use App\Telegram\Calendar;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Class App
 */
class App
{
	/**
	 * @throws GuzzleException
	 */
	public function run(): void
	{
		$bot  = new Bot();
		$wiki = new Wiki();

		$update = $bot->getUpdate();
		$update = $update['callback_query'] ?? $update;

		if (!$update) {
			die('Web request');
		}

		$input = $update['data'] ?? $update['message']['text'];
		$input = strtolower($input);

		if (!$input) {
			die('Input is empty');
		}

		$username = $update['message']['from']['first_name'];
		$chatId   = $update['message']['chat']['id'];

		switch ($input) {
			case '/start':
			case '/menu':
				$markup = Menu::createMainMenu();
				$bot->sendMessage($chatId, 'Main menu', $markup);
				break;
			case '/help':
				$helpCommands = 'List of commands: /start, /menu, /calendar';
				$bot->sendMessage($chatId, $helpCommands);
				break;
			case '/today':
				$eventMessages = $wiki->getEvents();

				if (empty($eventMessages)) {
					$msg = 'There are not wiki events on that day';
					$bot->sendMessage($chatId, $msg);
				} else {
					$len = count($eventMessages);
					foreach ($eventMessages as $key => $html) {
						//$bot->sendMessage($chatId, $msg);
						//sleep(1);

						if ($len === $key + 1) {
							$markup = Menu::createGimmeMoreMenu();
							$bot->sendMessage($chatId, $html, $markup);
						} else {
							$bot->sendMessage($chatId, $html);
						}
					}
				}
				break;
			case '/calendar':
				$markup = Menu::createMonthsMenu();
				$bot->sendMessage($chatId, 'Please, select a month', $markup);
				break;
			case '01':
			case '02':
			case '03':
			case '04':
			case '05':
			case '06':
			case '07':
			case '08':
			case '09':
			case '10':
			case '11':
			case '12':
				$markup = Menu::createDaysMenu($input);
				$bot->sendMessage($chatId, 'Please, select a day', $markup);
				break;
			default:
				if (Calendar::isDateExist($input)) {
					$eventMessages = $wiki->getEvents('ru', $input);

					if (empty($eventMessages)) {
						$msg = 'There are not wiki events on that day';
						$bot->sendMessage($chatId, $msg);
					} else {
						$len = count($eventMessages);
						foreach ($eventMessages as $key => $html) {
							if ($len === $key + 1) {
								$markup = Menu::createGimmeMoreMenu($input);
								$bot->sendMessage($chatId, $html, $markup);
							} else {
								$bot->sendMessage($chatId, $html);
							}
						}
					}
				} else {
					$text     = $update['message']['text'];
					$data     = $update['data'] ?? 'there is no data';
					$dontKnow = sprintf('unknown input text [[ %s ]], or unknown input data [[ %s ]]', $text, $data);
					$bot->sendMessage($chatId, $dontKnow);
				}

				break;
		}
	}
}
