<?php
class DevicesSessionsAttribute
{
    public $table_name = "devices_sessions";
    public $id = "id";
    public $deviceId = "deviceId";
    public $appId = "appId";
    public $appToken = "appToken";
    public $createdAt = "createdAt";
    public $updatedAt = "updatedAt";

    function NATIVE_INNER_JOIN(): string
    {
        $inner = NATIVE_INNER_JOIN($this->table_name, $this->id);
        return $inner;
    }
}
