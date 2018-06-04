<?php

$pluginApiHost = "127.0.0.1";        // 对应启动服务器时的 -Dhttp.address 参数
$pluginAuthKey = "klg9V82TJnzBeRRS";// 管理平台->扩展列表，点击相应的扩展获取。
$pluginId = 3;


define("ERROR_CODE_SUCCESS", "success");
define("BASE_DIR", __DIR__ . "/" );

define("CONF_SITE_PORT", "12021");
define("CONF_PLUGINAPI_PORT", "18080");
define("CONF_ADMIN_PORT", "18028");

require_once(__DIR__ . "/../AkaxinPluginApiClient.php");
$akaxinApiClient = new AkaxinPluginApiClient($pluginApiHost, CONF_PLUGINAPI_PORT, $pluginId, $pluginAuthKey);

function getApiClient() {
    global $akaxinApiClient;
    return $akaxinApiClient;
}

function startupServer() {

    $confSitePort = CONF_SITE_PORT;
    $confPluginapiPort = CONF_PLUGINAPI_PORT;
    $confAdminPort = CONF_ADMIN_PORT;
    system("cp openzaly-server.jar openzaly-server-for-phpunit.jar");

    $command = "java -jar openzaly-server-for-phpunit.jar -Dsite.port={$confSitePort} -Dhttp.port={$confPluginapiPort} -Dsite.admin.port={$confAdminPort} > /dev/null ";
    system($command);
}

function getSiteUserIdForTest() {
    return "63694fc0-7236-4dc8-aa3d-0956fa6d80c9";
}

function getFriendUserIdForTest() {
    return "eeebfae2-797c-4785-8d2a-c3d26310faf3";
}

function getGroupIdForTest() {

}


class Context {

    private static $instance = null;

    public static function getInstance() {
        if (null === self::$instance) {
            self::$instance = new Context();
        }
        return self::$instance;
    }

    function getAdminUserID() {
        return "b182fa30-a9ff-4d5a-b9fd-012facb54d84";
    }

    function getUserA() {
        return "8e8030a5-668a-4cd4-a8b1-1adbac2c3029";
    }

    function getUserB() {
        return "016ff467-89c9-4f69-b662-65c3e2e2dce2";
    }

    function getUserC() {
        return "a59509b1-a15c-4bad-975f-614ea88a3e72";
    }

    function getUserD() {
        return "8bd0b734-40b6-4803-9a2f-510ecbc67dc1";
    }

    function getGroupA() {
        return "10001";
    }

    function getGroupB() {
        return "10002";
    }



    function startupServer() {

        chdir(BASE_DIR);

        if (!file_exists("openzaly-server.jar")) {
            trigger_error("Cannot find openzaly-server.jar", E_USER_ERROR);
            die();
        }

        system("rm -rf ./workspace-for-phpunit");
        system("mkdir ./workspace-for-phpunit");
        system("cp openzaly-server.jar openzaly-server-for-phpunit.jar");
        system("cp openzalyDB.sqlite3 ./workspace-for-phpunit/openzalyDB.sqlite3");

        $confSitePort = CONF_SITE_PORT;
        $confPluginapiPort = CONF_PLUGINAPI_PORT;
        $confAdminPort = CONF_ADMIN_PORT;

        $command = "java -jar -Dsite.baseDir=workspace-for-phpunit -Dsite.port={$confSitePort} -Dpluginapi.port={$confPluginapiPort} -Dsite.admin.port={$confAdminPort} openzaly-server-for-phpunit.jar > /dev/null 2>/dev/null &";
        system($command);

        // wait the server starts
        sleep(5);
    }

    function restartServer() {
        $this->killServer();
        sleep(1);
        $this->startupServer();
    }

    function killServer() {
        system("ps aux | grep openzaly-server-for-phpunit.jar > ps.log");
        $awkCommand = "awk '{print \$2}'";
        system("ps aux | grep openzaly-server-for-phpunit.jar | grep -v grep | {$awkCommand} | xargs kill -9");
    }

    function __destruct() {
        $this->killServer();
    }
}

Context::getInstance()->startupServer();
