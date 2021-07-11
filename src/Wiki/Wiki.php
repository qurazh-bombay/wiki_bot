<?php
declare(strict_types = 1);

namespace App\Wiki;

use App\Settings;
use App\Telegram\Calendar;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Response;

/**
 * Class Wiki
 *
 * @example api route: https://ru.wikipedia.org/api/rest_v1/
 * @example api `on this day` request: /feed/onthisday/{type}/{mm}/{dd}, where type
 * of events: all, selected, births, deaths, events, holidays
 *
 */
class Wiki
{
	public const URL = [
		'ru' => Settings::WIKI_URL_RU,
		'en' => Settings::WIKI_URL_EN,
	];
	public const WIKI_REQUESTED_EVENT = 'events';

	/**
	 * @param string      $lang
	 * @param string|null $dateInput
	 *
	 * @return array|string[]
	 */
	public function getEvents(string $lang = 'ru', ?string $dateInput = null): array
	{
		$response = $this->getWikiResponseOrNull($lang, self::WIKI_REQUESTED_EVENT, $dateInput);

		if ($response === null) {
			return [
				'Empty response'
			];
		}

		$jsonResponse = $response->getBody()->getContents();
		$data         = json_decode($jsonResponse, true);

		if (!isset($data['events']) or empty($data['events'])) {
			// no events on that day
			return [];
		}

		$events = $this->getEventsAsHtml($data['events']);

		return $this->getRandomEvents($events);
	}

	/**
	 * @param string      $lang
	 * @param string      $type
	 * @param string|null $dateInput
	 *
	 * @return Response|null
	 */
	private function getWikiResponseOrNull(string $lang, string $type, ?string $dateInput = null): ?Response
	{
		try {
			$client = new Client([
				'base_uri' => self::URL[$lang],
			]);

			if ($dateInput !== null) {
				$dateAsString = Calendar::createDateAsStringFromInput($dateInput);
				$date         = (new \DateTime($dateAsString))->format('/m/d');
			} else {
				$date = (new \DateTime())->format('/m/d');
			}

			/** @var Response $response */
			$response = $client->get('feed/onthisday/' . $type . $date);
		} catch (GuzzleException $e) {
			$response = null;
		} catch (\Exception $e) {
			$response = null;
		}

		return $response;
	}

	/**
	 * @param array $events
	 *
	 * @return array
	 */
	private function getRandomEvents(array $events): array
	{
		$eventsNumber  = 5; // define how many events we want to show to user
		$eventMessages = $events;

		if (count($events) > $eventsNumber) {
			$eventMessages = array_map(function ($key) use ($events) {
				return $events[$key];
			}, array_rand($events, $eventsNumber));
		}

		return $eventMessages;
	}

	/**
	 * @param array $eventsRaw
	 *
	 * @return array
	 */
	private function getEventsAsHtml(array $eventsRaw): array
	{
		$events = [];

		foreach ($eventsRaw as $event) {
			$html = sprintf('<b>%s</b> | %s', $event['year'], $event['text']);

			if (count($event['pages'])) {
				$html .= "\n";
				foreach ($event['pages'] as $page) {
					$contentUrl = $page['content_urls']['desktop']['page']; // 'desktop' or 'mobile'
					$link       = sprintf('** <a href="%s">%s</a>', $contentUrl, $page['displaytitle']);
					$html       .= $link . "\n";
				}
			}

			$events[] = $html;
		}

		return $events;
	}
}
