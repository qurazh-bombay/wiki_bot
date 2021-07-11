<?php
declare(strict_types = 1);

namespace App\Telegram\KeyBoard;

/**
 * Class KeyBoard
 */
class KeyBoard implements KeyBoardInterface
{
	/**
	 * @var array|Row[]
	 */
	private $rows;

	/**
	 * KeyBoard constructor.
	 */
	public function __construct()
	{
		$this->rows = [];
	}

	/**
	 * @param integer $key
	 *
	 * @return Row|null
	 */
	public function getRow(int $key): ?Row
	{
		if (array_key_exists($key, $this->rows)) {
			return $this->rows[$key];
		}

		return null;
	}

	/**
	 * @return self
	 */
	public function clear(): self
	{
		$this->rows = [];

		return $this;
	}

	/**
	 * @param Row ...$rows
	 *
	 * @return self
	 */
	public function add(Row ...$rows): self
	{
		foreach ($rows as $row) {
			$this->rows[] = $row;
		}

		return $this;
	}

	/**
	 * @return array
	 */
	public function toArray(): array
	{
		return array_map(function (Row $row) {
			return $row->toArray();
		}, $this->rows);
	}
}
