<?php
//
// Akaxin
// ====
//
// > Github: https://github.com/akaxincom/openzaly
// > 扩展体验：下载阿卡信客户端，访问 demo.akaxin.com 站点
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

// 体验前，先remove掉这一句，防止被别人访问。
// die("https://github.com/akaxincom/openzaly");


// 初始化设置
// =========
// 本源码以获取用户Profile接口为例
$innerApiHost = "127.0.0.1";        // 对应启动服务器时的 -Dhttp.address 参数
$innerApiPort = 8280;               // 对应启动服务器时的 -Dhttp.port 参数
$pluginAuthKey = "XxlgqWBc6N4fMWZF";// 管理平台->扩展列表，点击相应的扩展获取。
$pluginId = 3;


require_once(__DIR__ . "/AkaxinInnerApiClient.php");
$akaxinApiClient = new AkaxinInnerApiClient($innerApiHost, $innerApiPort, $pluginId, $pluginAuthKey);


// 构造Request
// ==========
$siteUserIdForQuery = "c15c4e03-ab76-4d1e-bf89-dc8699ae02ca";
$requestMessage = new Akaxin\Proto\Plugin\HaiUserProfileRequest();
$requestMessage->setSiteUserId($siteUserIdForQuery);


// 执行请求
// =======
$responseData = $akaxinApiClient->request("hai/user/profile", $requestMessage);


// 处理返回逻辑
// ========
$userProfileResponse = new Akaxin\Proto\Plugin\HaiUserProfileResponse();
$userProfileResponse->mergeFromString($responseData);
$userProfile = $userProfileResponse->getUserProfile();

var_dump($userProfile->getSiteUserId(), $userProfile->getUserName());
