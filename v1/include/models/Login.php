<?php
class ModelLogin
{
    public $user;
    public $userSession;
    public $runApp;
    public function __construct($user, $userSession, $runApp)
    {
        $this->user = $user;
        $this->userSession = $userSession;
        $this->runApp = $runApp;
    }
}