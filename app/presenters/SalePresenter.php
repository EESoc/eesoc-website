<?php

use Robbo\Presenter\Presenter;

class SalePresenter extends Presenter {

    public function presentDate()
    {
        return DateTime::createFromFormat('Y-h-d', $this->object->date)->format('d/h/Y');
    }

    public function presentUnitPrice()
    {
        return '&pound;' . number_format($this->object->unit_price, 2);
    }

    public function presentGrossPrice()
    {
        return '&pound;' . number_format($this->object->gross_price, 2);
    }

}
