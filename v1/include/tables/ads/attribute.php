<?php
require_once 'post_data.php';

class AdsAttribute
{
    public $table_name = "ads";
    public $id = "id";
    public $image = "image";
    public $isEnabled = "isEnabled";
    public $description = "description";
    public $type = "type";
    public $product_cat_id = "product_cat_id";
    public $expireAt = "expireAt";
    public $createdAt = "createdAt";
    public $updatedAt = "updatedAt";

    function path_image()
    {
        return __DIR__ . "/../../images/ads/";
    }

    public $ENABLED_STATUS = 1;
    public $DISABLED_STATUS = 0;

}
