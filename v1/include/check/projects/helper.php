<?php
namespace Check;

require_once 'sql.php';

class ProjectsHelper extends ProjectsSql
{
    function getData($number, $password)
    {
        $sql = $this->readSql("'$number'", "'$password'");
        $data = shared_execute_read1_no_json_sql($sql);
        if (count($data) != 1) {
            return null;
        }
        return $data[0];
    }
}
$projects1_helper = null;
function getProjectsHelper()
{
    global $projects1_helper;
    if ($projects1_helper == null) {
        $projects1_helper = new ProjectsHelper();
    }
    return $projects1_helper;
}
