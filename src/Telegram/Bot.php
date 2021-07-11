<?php
declare(strict_types=1);

namespace App\Telegram;

use App\Settings;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Class Bot
 */
class Bot
{
    /**
     * @return array|null
     */
    public function getUpdate(): ?array
    {
        return json_decode(file_get_contents('php://input'), true);
    }

    /**
     * @param int        $chatId
     * @param string     $text
     * @param array|null $markup
     *
     * @throws GuzzleException
     */
    public function sendMessage(int $chatId, string $text, ?array $markup = null): void
    {
        $url = Settings::TELEGRAM_BOT_URL . Settings::TELEGRAM_TOKEN . '/sendMessage';

        $client = new Client(
            [
                'base_uri' => $url,
                'headers' => [ 'Content-Type' => 'application/json' ]
            ]
        );

        $data = [
            'text'       => $text,
            'chat_id'    => $chatId,
            'parse_mode' => 'html',
        ];

        if ($markup !== null) {
            $data['reply_markup'] = $markup;
        }

        $json = json_encode($data);

        $client->post($url, ['body' => $json]);
    }

    /**
	 * registers App in Telegram
	 *
     * @return object
     * @throws GuzzleException
     */
    public function setWebHook(): object
    {
        return $this->doGetRequest('setWebhook', [
            'url' => 'https://' . $_SERVER['HTTP_HOST'],
        ]);
    }

    /**
	 * unregisters App in Telegram
	 *
     * @return object
     * @throws GuzzleException
     */
    public function deleteWebHook(): object
    {
        return $this->doGetRequest('deleteWebhook');
    }

	/**
	 * @param string $method
	 * @param array  $params
	 *
	 * @return object
	 * @throws GuzzleException
	 */
	private function doGetRequest(string $method, array $params = []): object
	{
		$url = Settings::TELEGRAM_BOT_URL . Settings::TELEGRAM_TOKEN . '/' . $method;

		if (!empty($params)) {
			$url = $url . '?' . http_build_query($params);
		}

		$client = new Client(
			[
				'base_uri' => $url,
			]
		);

		$result = $client->request('GET');

		return json_decode($result->getBody()->getContents());
	}
}
