<?php
class UsersLocationsAttribute
{
    public $name = "userLocation";
    public $table_name = "users_locations";
    public $id = "id";
    public $userId = "userId";

    public $city = "city";
    public $latLong = "latLong";
    public $url = "url";
    public $contactPhone = "contactPhone";
    public $nearTo = "nearTo";
    public $street = "street";
    public $type = "type";
    public $createdAt = "createdAt";
    public $updatedAt = "updatedAt";

    public $locations_types_attribute;
    function initForignkey()
    {
        require_once(__DIR__ . '/../location_types/attribute.php');
        $this->locations_types_attribute = new LocationTypesAttribute();
    }
    function INNER_JOIN(): string
    {
      $this->initForignkey();
      $inner =
        FORIGN_KEY_ID_INNER_JOIN($this->locations_types_attribute->NATIVE_INNER_JOIN(), $this->table_name, $this->type);
      return $inner;
    }
}
