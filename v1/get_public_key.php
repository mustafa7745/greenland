<?php
require_once ("middleware.php");
sleep("5");
$publickey = fread(fopen($GLOBALS["path"] . "keys/publicKey.pem", "r"), 10000);

// $key = shared_execute_read1_no_json_sql("SELECT server_key_public FROM server_keys WHERE server_key_id = '1'")[0];
// sleep(5);
// die(preg_replace('/^.+\n/','',$publickey));
$publickey = str_replace('-----BEGIN PUBLIC KEY-----','',$publickey);
$publickey = str_replace('-----END PUBLIC KEY-----','',$publickey);
$publickey = trim($publickey);


die(json_encode($publickey));

