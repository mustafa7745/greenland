<?php

class ModelDevice
{
    public $id;
    public $publicKey;
    public function __construct($device)
    {
        $this->id = getId($device);
        $this->publicKey = getPublicKey($device);
    }
}