<?php

use Illuminate\Database\Eloquent\Collection;

class LockerCollection extends Collection {

	public function totalRows()
	{
		$rows = $this->lists('row');
		return empty($rows) ? -1 : max($rows);
	}

	public function totalColumns()
	{
		$columns = $this->lists('column');
		return empty($columns) ? -1 : max($columns);
	}

	public function at($row, $column)
	{
		return array_first($this->items, function($key, $model) use ($row, $column) {
			return ($model->row === $row && $model->column === $column);
		});
	}

}