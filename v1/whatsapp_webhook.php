<?php
$hub_verify_token = "774519161";
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['hub_challenge']) && isset($_GET['hub_verify_token']) && $_GET["hub_verify_token"] === $hub_verify_token) {
   echo $_GET['hub_challenge'];
   exit;
}