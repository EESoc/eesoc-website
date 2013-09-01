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

	public function presentStatusAction()
	{
		switch ($this->status) {
			case Locker::STATUS_TAKEN:
				return '<a href="#" class="btn btn-danger btn-xs disabled">Taken</a>';
			case Locker::STATUS_RESERVED:
				return '<a href="#" class="btn btn-warning btn-xs disabled">Reserved</a>';
			case Locker::STATUS_VACANT:
				return '<a href="javascript:alert(\'TODO: Claim this locker\')" class="btn btn-success btn-xs">Claim</a>';
		}
	}

}