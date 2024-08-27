<?php

require_once 'User.php';
require_once 'UserSession.php';
require_once 'RunApp.php';
class ModelLogin
{
    public ModelUser $user;
    public ModelUserSession $userSession;
    public ModelRunApp $runApp;
    public function __construct($user, $userSession, $runApp)
    {
        $this->user = $user;
        $this->userSession = $userSession;
        $this->runApp = $runApp;
    }
}