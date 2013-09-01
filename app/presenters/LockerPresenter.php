<?php

use Robbo\Presenter\Presenter;

class LockerPresenter extends Presenter {

	public function presentStatusCssClass()
	{
		switch ($this->status) {
			case Locker::STATUS_TAKEN:
				return 'danger';
			case Locker::STATUS_RESERVED:
				return 'warning';
			case Locker::STATUS_VACANT:
				return 'success';
		}
	}

	public function presentSizeCssClass()
	{
		return "locker-{$this->size}";
	}

	public function presentCssClass()
	{
		return "{$this->presentStatusCssClass()} locker {$this->presentSizeCssClass()}";
	}

}