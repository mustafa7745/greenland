<?php
namespace Check;

require_once 'sql.php';

class AppsHelper extends AppsSql
{
    function getData($packageName, $appSha)
    {
        $sql = $this->readSql("'$packageName'", "'$appSha'");
        $data = shared_execute_read1_no_json_sql($sql);
        if (count($data) != 1) {
            $ar = "APP_NOT_AUTH";
            $en = "APP_NOT_AUTH";
            exitFromScript($ar, $en);
        }
        require_once __DIR__ . '/../../models/App.php';

        return new \ModelApp($data[0]);
    }

}
$apps1_helper = null;
function getAppsHelper()
{
    global $apps1_helper;
    if ($apps1_helper == null) {
        $apps1_helper = (new AppsHelper());
    }
    return $apps1_helper;
}
