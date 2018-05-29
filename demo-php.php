<?php
//
// Akaxin
// ====
//
// > Github: https://github.com/akaxincom/openzaly
//
//
//  Akaxin扩展服务与站点InnerAPI通信教程源码
//
//  * 本源码只做原理演示，请勿直接用于生产环境。
//  * 请根据自己具体情况，封装相关代码。
//  * 目前提供PHP、Java两种语言的SDK。
//
//  * 如需帮助，请进我们的QQ群咨询：655249600
//
//



// 加载Google Protobuf Library
// ==========================
require_once(__DIR__ . "/vendor/autoload.php");



// 加载AkaxinProtoSDK
// ==================
// Akaxin\Proto 有很多，在sdk-php文件夹下，具体接口使用哪一个，请参考接口文档。
function autoloadForAkaxinProtoSDK($className) {
    $className = str_replace("\\", "/", $className);
    require_once(__DIR__ . "/sdk-php/{$className}.php");
}
spl_autoload_register("autoloadForAkaxinProtoSDK");


// 初始化设置
// =========
// 本源码以获取用户Profile接口为例
$innerApiHost = "127.0.0.1";        // 对应启动服务器时的 -Dhttp.address 参数
$innerApiPort = 8280;               // 对应启动服务器时的 -Dhttp.port 参数
$action_name = "hai/user/profile";  // 请查阅相关接口文档
$pluginAuthKey = "XxlgqWBc6N4fMWZF";// 管理平台->扩展列表，点击相应的扩展获取。
$siteUserIdForQuery = "c15c4e03-ab76-4d1e-bf89-dc8699ae02ca";


// 构造Request
// ==========
$bodyData = new Akaxin\Proto\Plugin\HaiUserProfileRequest();
$bodyData->setSiteUserId($siteUserIdForQuery);

// 底层网络->数据包封装开始
// ===================
// 这部分逻辑是通用的，应该抽离成单独的方法
$requestPackage = new Akaxin\Proto\Core\ProxyPluginPackage();
$data = $bodyData->serializeToString();
$data = base64_encode($data); //千万不要忘了这一步！
$requestPackage->setData($data);

// 底层网络->设置ProxyPluginPackage.header
// ============================
$requestPakcageHeader = array(
    Akaxin\Proto\Core\PluginHeaderKey::PLUGIN_TIMESTAMP => time()*1000, // 安全作用
    Akaxin\Proto\Core\PluginHeaderKey::CLIENT_SITE_USER_ID => "daaaaaa-ab76-bbbb-bf89-dc8699aecccc", // 代表当前操作这个行为的用户

);
$requestPackage->setPluginHeader($requestPakcageHeader);

// 底层网络->加密数据包
// ========
$requestPackageSerialized = $requestPackage->serializeToString();

// 底层网络->扩展需要支持向量。
$postData = openssl_encrypt($requestPackageSerialized , "AES-128-ECB", $pluginAuthKey, OPENSSL_RAW_DATA);

// 底层网络->执行网络请求
// ==========
$ch = curl_init("http://{$innerApiHost}:{$innerApiPort}/{$action_name}");
$httpHeader = array(
    "site-plugin-id: 3", // 替换为自己的扩展ID
    "X-Forwarded-For: 127.0.0.1" // 这个接口即将被废弃
);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
curl_setopt($ch, CURLOPT_HTTPHEADER, $httpHeader);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$responseInBinary = curl_exec($ch); // curl可能会出错，要容错。


// 底层网络->开始解析Response
// ===============
$responseData = openssl_decrypt($responseInBinary, "AES-128-ECB", $pluginAuthKey, OPENSSL_RAW_DATA); // 解密可能失败
$responsePackage = new Akaxin\Proto\Core\ProxyPluginPackage();
$responsePackage->mergeFromString($responseData);

// 业务逻辑，开始解析Response
// ===============
$userProfileResponse = new Akaxin\Proto\Plugin\HaiUserProfileResponse();
$data = base64_decode($responsePackage->getData());
$userProfileResponse->mergeFromString($data);
$userProfile = $userProfileResponse->getUserProfile();

// 查看请求返回的值
var_dump($userProfile->getSiteUserId(), $userProfile->getUserName());
