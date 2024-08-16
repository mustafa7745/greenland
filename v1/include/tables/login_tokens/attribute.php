<?php
require_once 'post_data.php';

class LoginTokensAttribute
{
    public $table_name = "login_tokens";
    public $id = "id";
    public $userSessionId = "userSessionId";
    public $loginToken = "loginToken";
    public $expireAt = "expireAt";
    public $createdAt = "createdAt";
    public $updatedAt = "updatedAt";
}
