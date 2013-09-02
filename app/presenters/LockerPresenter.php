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

	public function presentBaseCssClass()
	{
		return "locker {$this->presentSizeCssClass()}";
	}

	public function presentCssClass()
	{
		return "{$this->presentStatusCssClass()} {$this->presentBaseCssClass()}";
	}

	public function presentOwnerCssClass()
	{
		return "active {$this->presentBaseCssClass()}";
	}

	public function presentSelectedCssClass()
	{
		return "success {$this->presentBaseCssClass()}";
	}

	public function presentMutedCssClass()
	{
		return "muted {$this->presentBaseCssClass()}";
	}

	public function presentStatusAction()
	{
		switch ($this->status) {
			case Locker::STATUS_TAKEN:
				return '<a href="#" class="btn btn-danger btn-sm btn-block disabled">Taken</a>';
			case Locker::STATUS_RESERVED:
				return '<a href="#" class="btn btn-warning btn-sm btn-block disabled">Reserved</a>';
			case Locker::STATUS_VACANT:
				return '<a href="'.URL::action('LockersController@getClaim', $this->id).'" class="btn btn-success btn-block btn-sm" data-disable-with="Wait&hellip;">Claim</a>';
		}
	}

	public function presentAdminActions()
	{
		$html = '<div class="btn-group-vertical btn-block btn-group-xs">';

		if ( ! $this->is_vacant) {
			$html .= '<a href="#" class="btn btn-success">Vacant</a>';
		}

		if ( ! $this->is_taken) {
			$html .= '<a href="#" class="btn btn-danger">Taken</a>';
		}

		if ( ! $this->is_reserved) {
			$html .= '<a href="#" class="btn btn-warning">Reserved</a>';
		}

		$html .= '</div>';

		return $html;
	}

}