<?php

$host = "127.0.0.1";
$port = 8280;
$pluginId = 3;
$pluginAuthKey = "3VLJVK5CVfeL0ugS";
$siteHost = "192.168.3.5";

///////////

$isWebNotice = false; //是否发送Notice消息
// $isWebNotice = true; //是否发送Notice消息
$rightGroupId = "7c43c33a-6bf7-4aba-bd5c-8743582b0d8f"; // 将消息发往那一个群组
$rightUserId = "8b7c1c50-94e3-4279-91c0-d8b2a950ccf1"; // 以谁的名义来发


$param = array(
    array(
        "正确的",
        600,
        600,
        "zaly://{$siteHost}/goto?page=plugin_for_group_chat&plugin_id={$pluginId}&site_group_id={$rightGroupId}"
    ),

    array(
        "错误跳转",
        150,
        150,
        "zaly://{$siteHost}/goto?page=plugin_for_group_chat&plugin_id={$pluginId}&site_group_id=abcde"
    ),

    array(
        "超小消息",
        10,
        10,
        "zaly://{$siteHost}/goto?page=plugin_for_group_chat&plugin_id={$pluginId}&site_group_id=abcde"
    ),
);



// 下面的代码，应该不用改
date_default_timezone_set("Asia/Shanghai");
require_once(__DIR__ . "/../AkaxinPluginApiClient.php");
function sendWebMsg($title, $isWebNotice, $width, $height, $zalyUrl) {
    global $host, $port, $pluginId, $pluginAuthKey;
    $client = new AkaxinPluginApiClient($host, $port, $pluginId, $pluginAuthKey);
    $request = new \Akaxin\Proto\Plugin\HaiMessageProxyRequest();
    $time = date("H:i:s", time());

    $isWebNotice = $isWebNotice ? "True" : "False";
    $html = <<<EOT
    <!doctype html>
    <html>
        <head>
            <style>
                html, body {background: #000; padding: 0px; margin: 0px;}
            </style>
            <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        </head>
        <body>
            <pre style="color:#fff;">
$title
isWebNotice: {$isWebNotice}
{$width} x {$height}
{$zalyUrl}
            </pre>
        </body>
    </html>
EOT;
    if ($isWebNotice) {
        $webmsg = new \Akaxin\Proto\Core\GroupWebNotice();
    } else {
        $webmsg = new \Akaxin\Proto\Core\GroupWeb();
    }

    global $rightGroupId;
    global $rightUserId;
    $webmsg->setSiteGroupId($rightGroupId);
    $webmsg->setSiteUserId($rightUserId);
    $webmsg->setMsgId(time());
    $webmsg->setWebCode($html);

    if (false == $isWebNotice) {
        $webmsg->setWidth($width);
        $webmsg->setHeight($height);
    } else {
        $webmsg->setHeight($height);
    }

    $webmsg->setTime(time() * 1000 );
    $webmsg->setHrefUrl($zalyUrl);

    $msg = new \Akaxin\Proto\Site\ImCtsMessageRequest();
    if ($isWebNotice) {
        $msg->setType(\Akaxin\Proto\Core\MsgType::GROUP_WEB_NOTICE);
        $msg->setGroupWebNotice($webmsg);
    } else {
        $msg->setType(\Akaxin\Proto\Core\MsgType::GROUP_WEB);
        $msg->setGroupWeb($webmsg);
    }
    $request->setProxyMsg($msg);
    $response = $client->request("hai/message/proxy", $request);
    var_dump($title, $client->errorCode(), $client->errorInfo());
}

foreach ($param as $v) {
    sendWebMsg($v[0], $isWebNotice, $v[1], $v[2], $v[3]);
}
