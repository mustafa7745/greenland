<?php

require_once 'DeliveryManLoginToken.php';
require_once 'UserSession.php';
class ModelUDeliveryManLoginUserSession
{
    public ModelDeliveryManLoginToken $modeDeliveryManLoginToken;
    public ModelUserSession $modelUserSession;
    public function __construct($modeDeliveryManLoginToken, $modelUserSession)
    {
        $this->modeDeliveryManLoginToken = $modeDeliveryManLoginToken;
        $this->modelUserSession = $modelUserSession;
    }
}