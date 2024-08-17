<?php

class UsersSessionsAttribute
{
  public $table_name = "users_sessions";
  public $id = "id";
  public $userId = "userId";
  public $deviceSessionId = "deviceSessionId";
  public $createdAt = "createdAt";
  public $lastLoginAt = "lastLoginAt";
  //////////

  public $devices_sessions_attribute;

  // 
  function initForignkey()
  {
    require_once __DIR__ . '/../devices_sessions/attribute.php';
    $this->devices_sessions_attribute = new DevicesSessionsAttribute();
  }

  function NATIVE_INNER_JOIN(): string
  {
    $inner = NATIVE_INNER_JOIN($this->table_name, $this->id);
    return $inner;
  }
  function INNER_JOIN(): string
  {
    $this->initForignkey();
    $inner =
      FORIGN_KEY_ID_INNER_JOIN($this->devices_sessions_attribute->NATIVE_INNER_JOIN(), $this->table_name, $this->deviceSessionId);
    return $inner;
  }
}
