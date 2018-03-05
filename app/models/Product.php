<?php

/*
 * Previous product IDs - 2013/14
 *     const ID_EESOC_LOCKER = 20226;
 *     const ID_EESOC_CHRISTMAS_DINNER = 5901;
 */


 /*
 * Previous product IDs - 2016/17
 *     const ID_EESOC_LOCKER = 17390;
 *     const ID_EESOC_DINNER = 18961;
 */


class Product {

    const ID_EESOC_LOCKER = 23605;
    const ID_EESOC_DINNER = 24788;
    const ID_EESOC_DINNER_NON_MEMBER = 24787;
    const ID_EESOC_BAR_NIGHT = 23992;
    const ID_EESOC_HOODIE = 23865;
    const ID_EESOC_SWEAT_SHIRT = 23868;
    const ID_EESOC_MEMBERSHIP = 22544;

    public static function totalQuantity($product_id){
        return Sale::productPurchases($product_id)->sum('quantity');
    }

}
