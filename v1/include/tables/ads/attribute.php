<?php
require_once 'post_data.php';

class AdsAttribute
{
    public $table_name = "ads";
    public $id = "id";
    public $image = "image";
    public $isEnabled = "isEnabled";
    public $description = "description";
    public $createdAt = "createdAt";
    public $updatedAt = "updatedAt";

    function path_image()
    {
        return __DIR__ . "/../../images/ads/";
    }
}
