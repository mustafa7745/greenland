<?php
require_once "../../include/check/index.php";
// use RunApp;

class ThisClass
{
  function main(): string
  {
    $runApp = getModelMainRunApp();
    return json_encode(array("success" => $runApp->app->id));
  }
}

$this_class = new ThisClass();
echo $this_class->main();