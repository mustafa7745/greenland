<?php
require_once 'post_data.php';

class ProjectsAttribute
{
    ////Table Attribute
    public $table_name = "projects";
    public $id = "id";
    public $number = "number";
    public $password = "password";
    public $name = "name";
    public $ownerUserId = "owner_user_id";
    public $icon = "icon";
    public $deviceId = "deviceId";
    public $serviceAccountKey = "serviceAccountKey";
    public $priceDeliveryPer1km = "priceDeliveryPer1km";
    public $latLong = "latLong";
    public $requestOrderMessage = "requestOrderMessage";
    public $requestOrderStatus = "requestOrderStatus";

    public $createdAt = "createdAt";
    public $updatedAt = "updatedAt";

    // function path_image()
    // {
    //     return getPath() . "images/projects/icons/";
    // }

    function getProjectId($data)
    {
        return $data[$this->id];
    }
    function getOwnerUserId($data)
    {
        return $data[$this->ownerUserId];
    }
    function getProjectName($data)
    {
        return $data[$this->name];
    }
    function getProjectIcon($data)
    {
        return $data[$this->icon];
    }
    function getProjectDeviceId($data)
    {
        return $data[$this->deviceId];
    }
    function getProjectLangLat($data)
    {
        return $data["latLong"];
    }
    function getProjectPriceDelivery($data)
    {
        return $data[$this->priceDeliveryPer1km];
    }
}
