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
				if ($this->audit == Locker::AUDIT_GOOD){
					return 'success';
				}else{
					return 'danger';
				}
			case Locker::STATUS_TRANSITION:
                return 'warning';
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
			case Locker::STATUS_TRANSITION:
                return '<a href="#" class="btn btn-warning btn-sm btn-block disabled">Expired</a>';
            case Locker::STATUS_RESERVED:
                return '<a href="#" class="btn btn-warning btn-sm btn-block disabled">Reserved</a>';
            case Locker::STATUS_VACANT:
                $html = '<a href="'.URL::action('LockersController@getClaim', $this->id).'" class="btn btn-success btn-block btn-sm" data-disable-with="Wait&hellip;">';
                $html .= '<span class="glyphicon glyphicon-tower"></span> ';
                $html .= 'Claim';
                $html .= '</a>';
                return $html;
				
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

        if ( ! $this->is_transition) {
            $html .= '<a href="#" class="btn btn-warning">Expired</a>';
        }else
        if ( ! $this->is_reserved) {
            $html .= '<a href="#" class="btn btn-warning">Reserved</a>';
        }

        $html .= '</div>';

        return $html;
    }

    public function presentAnchorId()
    {
        return "locker-{$this->id}";
    }

}
