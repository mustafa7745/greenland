<?php
require_once 'post_data.php';

class OffersAttribute
{
    public $table_name = "offers";
    public $id = "id";
    public $price = "price";
    public $name = "name";
    public $description = "description";
    public $image = "image";
    public $isEnabled = "isEnabled";
    public $createdAt = "createdAt";
    public $updatedAt = "updatedAt";
    public $expireAt = "expireAt";
    function path_image()
    {
      return __DIR__ . "/../../images/offers/";
    }
    
 
}
