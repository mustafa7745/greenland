<?php
require_once "../../include/check/index.php";
// use RunApp;

class ThisClass
{
  function main(): string
  {
    // print_r(getId(array("id"=>123)));
    $runApp = (new RunApp())->runApp();
    return json_encode(array("success" => getProjectId(getApp($runApp))));
  }
}

$this_class = new ThisClass();
echo $this_class->main();