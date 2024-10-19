<?php
require_once "../../include/check/index_v1.php";
// use RunApp;

class ThisClass
{
  function main(): string
  {
    $runApp = getMainRunApp();
    return json_encode(array("success" => $runApp->app->id));
  }
}

$this_class = new ThisClass();
echo $this_class->main();