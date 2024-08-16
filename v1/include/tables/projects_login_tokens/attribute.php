<?php
require_once 'post_data.php';

class ProjectsLoginTokensAttribute
{
    public $table_name = "projects_login_tokens";
    public $id = "id";
    public $userSessionId = "userSessionId";
    public $token = "token";
    public $projectId = "projectId";
    public $expireAt = "expireAt";
    public $createdAt = "createdAt";
    public $updatedAt = "updatedAt";
}
