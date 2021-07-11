<?php
declare(strict_types = 1);

namespace App\Telegram\KeyBoard;

/**
 * Class Row
 */
class Row implements KeyBoardInterface
{
	/**
	 * @var array|Button[]
	 */
	private $buttons;

	/**
	 * Row constructor.
	 */
	public function __construct()
	{
		$this->buttons = [];
	}

	/**
	 * @param Button ...$buttons
	 *
	 * @return $this
	 */
	public function add(Button ...$buttons): self
	{
		foreach ($buttons as $button) {
			$this->buttons[] = $button;
		}

		return $this;
	}

	/**
	 * @return array
	 */
	public function toArray(): array
	{
		return array_map(function (Button $button) {
			return $button->toArray();
		}, $this->buttons);
	}
}
