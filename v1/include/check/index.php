<?php

// namespace Check;
require_once "middleware.php";
require_once $path . 'check/apps/helper.php';
require_once $path . 'check/devices/helper.php';
require_once $path . 'check/devices_sessions/helper.php';
require_once $path . 'check/devices_sessions_ips/helper.php';
require_once $path . 'check/permissions/helper.php';
require_once $path . 'check/permissions_groups/helper.php';
require_once $path . 'check/users/helper.php';
require_once $path . 'check/failed_attemps_logs/helper.php';


function checkPermission($permissionName)
{
    // getRunApp()
    $runApp = getMainRunApp();
    $permission = getPermissionsHelper()->getDataByName($permissionName);
    getPermissionsGroupsHelper()->getData($permissionName, getId($permission), getGroupId(getApp($runApp)));
    $failedCount = getFailedAttempsLogsHelper()->getData(getId(getDevice($runApp)), getId($permission));

    if (getDeviceCount($failedCount) > 3) {
        P_BLOCKED($permissionName);
    }
    if (getIpCount($failedCount) > 3) {
        P_BLOCKED($permissionName);
    }
}




$runApp = null;
function getMainRunApp()
{
    global $runApp;
    if ($runApp == null) {
        $runApp = (new RunApp())->runApp();
    }
    return $runApp;
}

$modelRunApp = null;
function getModelMainRunApp()
{
    global $modelRunApp;
    if ($modelRunApp == null) {
        $runApp = getMainRunApp();
        require_once getPath() . 'models/RunApp.php';
        // print_r($runApp);
        $app = new ModelApp(getApp($runApp));
        $device = new ModelDevice(getDevice($runApp));
        $deviceSession = new ModelDeviceSession(getDeviceSession($runApp));
        $deviceSessionIp = new ModelDeviceSessionIp(getDeviceSessionIp($runApp));

        $modelRunApp = new ModelRunApp($app, $device, $deviceSession, $deviceSessionIp);
        // return $modelRunApp;
    }
    return $modelRunApp;
}

class RunApp
{
    function runApp()
    {
        // $runApp = (new RunApp())->runApp();
        $permissionName = "RUN_APP";
        $app = Check\getAppsHelper()->getData(getPostData1()->packageName, getPostData1()->appSha);
        // 
        if (strtotime(getCurruntDate()) > strtotime($app->expireAt)) {
            $ar = "APP_NEED_UPGRADE_PLAN_TIME";
            $en = "APP_NEED_UPGRADE_PLAN_TIME";
            exitFromScript($ar, $en);
        }
        // 
        $permission = getPermissionsHelper()->getDataByName($permissionName);
        getPermissionsGroupsHelper()->getData($permissionName, $permission->id, $app->groupId);
        // 
        return $this->initDevice($app);

    }
    private function initDevice($app)
    {
        $permissionName = "INIT_DEVICE";
        $permission = getPermissionsHelper()->getDataByName($permissionName);
        getPermissionsGroupsHelper()->getData($permissionName, $permission->id, $app->groupId);
        $device = Check\getDevicesHelper()->getData(getPostData1()->deviceId);
        if ($device == null) {
            $device = Check\getDevicesHelper()->addData(getPostData1()->deviceId, getPostData1()->deviceInfo, getPostData1()->devicePublicKey);
        }
        if ($device->publicKey != getPostData1()->devicePublicKey) {
            $device = Check\getDevicesHelper()->updatePublicKey(getPostData1()->deviceId, getPostData1()->devicePublicKey);
        }
        return $this->initDeviceSession($app, $device);
    }
    private function initDeviceSession($app, $device)
    {
        $permissionName = "INIT_DEVICE_SESSION";
        $permission = getPermissionsHelper()->getDataByName($permissionName);
        getPermissionsGroupsHelper()->getData($permissionName, $permission->id, $app->groupId);
        $deviceSession = Check\getDevicesSessionHelper()->getDataByDeviceIdAndAppId($device->id, $app->id);
        if ($deviceSession == null) {
            $deviceSession = Check\getDevicesSessionHelper()->addData(getPostData1()->deviceId, $app->id, getPostData1()->appDeviceToken);
        }
        if ($deviceSession->appToken != getPostData1()->appDeviceToken) {
            $deviceSession = Check\getDevicesSessionHelper()->updateAppToken($deviceSession->id, getPostData1()->appDeviceToken);
        }
        return $this->initDeviceSessionIp($app, $device, $deviceSession);
    }
    private function initDeviceSessionIp($app, $device, $deviceSession)
    {
        $permissionName = "INIT_DEVICE_SESSION_IP";
        $permission = getPermissionsHelper()->getDataByName($permissionName);
        getPermissionsGroupsHelper()->getData($permissionName, $permission->id, $app->groupId);
        $deviceSessionIp = Check\getDevicesSessionIpsHelper()->getDataByDeviceSessionIdAndIp($deviceSession->id, getIp());
        if ($deviceSessionIp == null) {
            $deviceSessionIp = Check\getDevicesSessionIpsHelper()->addData($deviceSession->id, getIp());
        }
        require_once __DIR__ . '/../../include/models/RunApp.php';
        return new ModelRunApp($app, $device, $deviceSession, $deviceSessionIp);
    }
}
