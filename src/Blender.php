<?php

namespace TDD;


class Blender
{
	/**
	 * @var int
	 */
	public $speed = 0;

	/**
	 * @var bool
	 */
	public $full = false;


	public function getSpeed()
	{
		return $this->speed;
	}

	public function setSpeed($speed)
	{
		if ($speed > 9)
			$speed = 9;

		if ($speed < 0)
			$speed = 0;

		if ( (abs($this->speed) - $speed) > 1)
			throw new \RuntimeException('Skipped step');

		if ( ! $this->isFull() )
			throw new \RuntimeException('Empty');

		$this->speed = (int) $speed;
	}

	/**
	 * @return bool
	 */
	public function isFull()
	{
		return $this->full;
	}

	public function fill()
	{
		if ($this->isFull())
			throw new \RuntimeException('Already full');

		$this->full = true;
	}

	public function void()
	{
		if ( ! $this->isFull())
			throw new \RuntimeException('Nothing to empty');

		$this->full = false;
	}
}