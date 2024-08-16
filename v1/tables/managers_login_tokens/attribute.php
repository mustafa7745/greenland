<?php
require_once 'post_data.php';

class ManagersLoginTokensAttribute
{
    public $table_name = "managers_login_tokens";
    public $id = "id";
    public $userSessionId = "userSessionId";
    public $managerId = "managerId";
    public $token = "token";
    public $expireAt = "expireAt";
    public $createdAt = "createdAt";
    public $updatedAt = "updatedAt";
}
