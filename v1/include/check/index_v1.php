<?php

// namespace Check;
require_once "middleware_v1.php";
require_once __DIR__ . '/../check/apps/helper.php';
require_once __DIR__ . '/../check/devices/helper.php';
require_once __DIR__ . '/../check/devices_sessions/helper.php';
require_once __DIR__ . '/../check/devices_sessions_ips/helper.php';
require_once __DIR__ . '/../check/permissions/helper.php';
require_once __DIR__ . '/../check/permissions_groups/helper.php';
require_once __DIR__ . '/../check/users/helper.php';
require_once __DIR__ . '/../check/failed_attemps_logs/helper.php';

$runApp = null;
function getMainRunApp($isEncrypted = 0)
{
    global $runApp;
    if ($runApp == null) {
        $runApp = (new RunApp())->runApp($isEncrypted);
    }
    return $runApp;
}
class RunApp
{
    function runApp($isEncrypted)
    {
        // if (getPostData1()->appVersion < 20) {
        //     exitFromScript("لايمكن استخدام اصدار قديم","");
        // };
        // $runApp = (new RunApp())->runApp();
        $permissionName = "RUN_APP";
        $app = Check\getAppsHelper()->getData(getPostData1()->packageName, getPostData1()->appSha);
        // if ($app->id == '3') {
        //     exitFromScript(json_encode($_POST),"f");
        // }
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
        return $this->initDevice($app, $isEncrypted);

    }
    private function initDevice($app, $isEncrypted)
    {
        $permissionName = "INIT_DEVICE";
        $permission = getPermissionsHelper()->getDataByName($permissionName);
        getPermissionsGroupsHelper()->getData($permissionName, $permission->id, $app->groupId);
        $device = Check\getDevicesHelper()->getData(getPostData1()->deviceId);
        if ($device == null) {
            if ($isEncrypted == 1)
                $device = Check\getDevicesHelper()->addData(getPostData1()->deviceId, getPostData1()->deviceInfo, getPublicKeyStandalone());
            else
                $device = Check\getDevicesHelper()->addData_v1(getPostData1()->deviceId, getPostData1()->deviceInfo);
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