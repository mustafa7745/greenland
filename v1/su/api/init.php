<?php
require_once "../../include/check/index.php";
use Check\RunApp;

class ThisClass
{
  function main(): string
  {
    // print_r(getId(array("id"=>123)));
    return json_encode((new RunApp())->runApp());
  }
}

$this_class = new ThisClass();
echo $this_class->main();