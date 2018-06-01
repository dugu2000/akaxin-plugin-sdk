<?php

$pluginApiHost = "127.0.0.1";        // 对应启动服务器时的 -Dhttp.address 参数
$pluginApiPort = 8280;               // 对应启动服务器时的 -Dhttp.port 参数
$pluginAuthKey = "SgG64DAKUhS2eroo";// 管理平台->扩展列表，点击相应的扩展获取。
$pluginId = 3;


define("ERROR_CODE_SUCCESS", "success");


require_once(__DIR__ . "/../AkaxinPluginApiClient.php");
$akaxinApiClient = new AkaxinPluginApiClient($pluginApiHost, $pluginApiPort, $pluginId, $pluginAuthKey);

function getApiClient() {
    global $akaxinApiClient;
    return $akaxinApiClient;
}

function getSiteUserIdForTest() {
    return "63694fc0-7236-4dc8-aa3d-0956fa6d80c9";
}

function getFriendUserIdForTest() {
    return "eeebfae2-797c-4785-8d2a-c3d26310faf3";
}

function getGroupIdForTest() {

}
