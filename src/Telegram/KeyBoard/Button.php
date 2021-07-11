<?php
declare(strict_types = 1);

namespace App\Telegram\KeyBoard;

/**
 * Class Button
 */
class Button implements KeyBoardInterface
{
	/**
	 * @var string
	 */
	private $text;

	/**
	 * @var string
	 */
	private $callbackData;

	/**
	 * Button constructor.
	 *
	 * @param string $text
	 * @param string $callbackData
	 */
	public function __construct(string $text, string $callbackData)
	{
		$this->text         = $text;
		$this->callbackData = $callbackData;
	}

	/**
	 * @return array
	 */
	public function toArray(): array
	{
		return [
			'text'          => $this->text,
			'callback_data' => $this->callbackData
		];
	}
}
