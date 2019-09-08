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

/*
 * Previous product IDs - 2017/18
 *      const ID_EESOC_LOCKER = 23605;
 *      const ID_EESOC_DINNER = 24788;
 *      const ID_EESOC_DINNER_NON_MEMBER = 24787;
 *      const ID_EESOC_BAR_NIGHT = 23992;
 *      const ID_EESOC_HOODIE = 23865;
 *      const ID_EESOC_SWEAT_SHIRT = 23868;
 *      const ID_EESOC_MEMBERSHIP = 22544;
 */


class Product {

    const ID_EESOC_LOCKER = 28476;
    const ID_EESOC_MEMBERSHIP = 26565;
    
    const ID_EESOC_DINNER = 29966;
    const ID_EESOC_DINNER_NON_MEMBER = 29967;

    const ID_EESOC_BAR_NIGHT = 29157;
    
    const ID_EESOC_HOODIE = 29821;
    const ID_EESOC_SWEAT_SHIRT = 29822;

    /*
     * REMEMBER TO KEEP THIS SYNC WITH ABOVE
     * SET ALL OFF-SALE OR NOT REQUIRES SYNC ITEMS TO 0
     * SET ALL ON-SALE + REQUIRES SYNC ITEMS TO 1
     * SEE 'app\commands\SyncEActivitiesSalesCommand.php' FOR MORE DETAILS
     * USE TRUNC OPERATION FROM PHP_MYADMIN ON SALES EVERY YEAR
     */
    public static function is_product_syncable(){
        return array(
                        Product::ID_EESOC_LOCKER => 1, 
                        Product::ID_EESOC_MEMBERSHIP => 1,
                        Product::ID_EESOC_DINNER => 1,              //OFF-SALE
                        Product::ID_EESOC_DINNER_NON_MEMBER => 1,   //OFF-SALE
                        Product::ID_EESOC_BAR_NIGHT => 1,           //OFF-SALE
                        Product::ID_EESOC_HOODIE => 1,
                        Product::ID_EESOC_SWEAT_SHIRT => 1,
                );
    }


    public static function totalQuantity($product_id){
        return Sale::productPurchases($product_id)->sum('quantity');
    }

}
