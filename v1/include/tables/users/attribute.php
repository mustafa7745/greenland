<?php

require_once 'post_data.php';

class UsersAttribute
{
  public $table_name = "users";
  public $id = "id";
  public $name = "name";
  public $phone = "phone";
  public $password = "password";
  public $createdAt = "createdAt";
  public $updatedAt = "updatedAt";
  //////////
  function NATIVE_INNER_JOIN(): string
  {
    $inner = NATIVE_INNER_JOIN($this->table_name, $this->id);
    return $inner;
  }
}
