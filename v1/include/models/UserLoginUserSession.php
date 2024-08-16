<?php

require_once 'UserLoginToken.php';
require_once 'UserSession.php';
class ModelUserLoginUserSession
{
    public ModelUserLoginToken $modelUserLoginToken;
    public ModelUserSession $modelUserSession;
    public function __construct($modelUserLoginToken, $modelUserSession)
    {
        $this->modelUserLoginToken = $modelUserLoginToken;
        $this->modelUserSession = $modelUserSession;
    }
}