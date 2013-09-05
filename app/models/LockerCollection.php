<?php

use Illuminate\Database\Eloquent\Collection;

class LockerCollection extends Collection {

	/**
	 * Get total number of rows. Number starts from 0.
	 * @return integer
	 */
	public function totalRows()
	{
		$rows = $this->lists('row');
		return empty($rows) ? -1 : max($rows);
	}

	/**
	 * Get total number of columns. Number starts from 0.
	 * @return integer
	 */
	public function totalColumns()
	{
		$columns = $this->lists('column');
		return empty($columns) ? -1 : max($columns);
	}

	/**
	 * Get locker at this particular position
	 * @param  integer $row    Zero-indexed.
	 * @param  integer $column Zero-indexed.
	 * @return Locker
	 */
	public function at($row, $column)
	{
		return array_first($this->items, function($key, $model) use ($row, $column) {
			return ((int) $model->row === $row && (int) $model->column === $column);
		});
	}

}