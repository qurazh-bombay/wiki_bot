<?php
declare(strict_types = 1);

namespace App\Telegram\KeyBoard;

/**
 * Interface KeyBoardInterface
 */
interface KeyBoardInterface
{
	/**
	 * @return array
	 */
	public function toArray(): array;
}
