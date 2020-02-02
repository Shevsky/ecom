<?php

namespace Shevsky\Ecom\Services\Tracking;

use Shevsky\Ecom\Util\IArrayConvertable;
use Shevsky\Ecom\Util\IMemento;

class TrackingHistory implements \Countable, \ArrayAccess, \Iterator, IArrayConvertable, IMemento
{
	private $records;

	/**
	 * @param TrackingRecord[] $records
	 */
	public function __construct(array $records)
	{
		$this->records = $records;
	}

	/**
	 * @return mixed
	 */
	public function memento()
	{
		return array_map([__CLASS__, 'mementoRecord'], $this->toArray());
	}

	/**
	 * @param mixed $data
	 * @return static
	 * @throws \Exception
	 */
	public static function restore($data)
	{
		if (!is_array($data))
		{
			throw new \Exception('Не удалось восстановить снимок');
		}

		$records = array_map([TrackingRecord::class, 'build'], $data);

		return new self($records);
	}

	/**
	 * Count elements of an object
	 * @link https://php.net/manual/en/countable.count.php
	 * @return int The custom count as an integer.
	 * </p>
	 * <p>
	 * The return value is cast to an integer.
	 * @since 5.1.0
	 */
	public function count()
	{
		return count($this->records);
	}

	/**
	 * Whether a offset exists
	 * @link https://php.net/manual/en/arrayaccess.offsetexists.php
	 * @param mixed $offset <p>
	 * An offset to check for.
	 * </p>
	 * @return boolean true on success or false on failure.
	 * </p>
	 * <p>
	 * The return value will be casted to boolean if non-boolean was returned.
	 * @since 5.0.0
	 */
	public function offsetExists($offset)
	{
		return array_key_exists($offset, $this->records);
	}

	/**
	 * Offset to retrieve
	 * @link https://php.net/manual/en/arrayaccess.offsetget.php
	 * @param mixed $offset <p>
	 * The offset to retrieve.
	 * </p>
	 * @return mixed Can return all value types.
	 * @since 5.0.0
	 */
	public function offsetGet($offset)
	{
		return array_key_exists($offset, $this->records) ? $this->records[$offset] : null;
	}

	/**
	 * Offset to set
	 * @link https://php.net/manual/en/arrayaccess.offsetset.php
	 * @param mixed $offset <p>
	 * The offset to assign the value to.
	 * </p>
	 * @param mixed $value <p>
	 * The value to set.
	 * </p>
	 * @return void
	 * @since 5.0.0
	 */
	public function offsetSet($offset, $value)
	{
		$this->records[$offset] = $value;
	}

	/**
	 * Offset to unset
	 * @link https://php.net/manual/en/arrayaccess.offsetunset.php
	 * @param mixed $offset <p>
	 * The offset to unset.
	 * </p>
	 * @return void
	 * @since 5.0.0
	 */
	public function offsetUnset($offset)
	{
		unset($this->records[$offset]);
	}

	/**
	 * Return the current element
	 * @link https://php.net/manual/en/iterator.current.php
	 * @return mixed Can return any type.
	 * @since 5.0.0
	 */
	public function current()
	{
		return current($this->records);
	}

	/**
	 * Move forward to next element
	 * @link https://php.net/manual/en/iterator.next.php
	 * @return void Any returned value is ignored.
	 * @since 5.0.0
	 */
	public function next()
	{
		next($this->records);
	}

	/**
	 * Return the key of the current element
	 * @link https://php.net/manual/en/iterator.key.php
	 * @return mixed scalar on success, or null on failure.
	 * @since 5.0.0
	 */
	public function key()
	{
		return key($this->records);
	}

	/**
	 * Checks if current position is valid
	 * @link https://php.net/manual/en/iterator.valid.php
	 * @return boolean The return value will be casted to boolean and then evaluated.
	 * Returns true on success or false on failure.
	 * @since 5.0.0
	 */
	public function valid()
	{
		return $this->current() !== false;
	}

	/**
	 * Rewind the Iterator to the first element
	 * @link https://php.net/manual/en/iterator.rewind.php
	 * @return void Any returned value is ignored.
	 * @since 5.0.0
	 */
	public function rewind()
	{
		reset($this->records);
	}

	/**
	 * @return array
	 */
	public function toArray()
	{
		return $this->records;
	}

	/**
	 * @param TrackingRecord $record
	 * @return array
	 */
	private function mementoRecord(TrackingRecord $record)
	{
		return $record->memento();
	}
}
