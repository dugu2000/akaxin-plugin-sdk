<?php


//require_once(__DIR__ . "/autoload_for_doc.php");
require_once(__DIR__ . "/../AkaxinPluginApiClient.php");
$client = new AkaxinPluginApiClient("127.0.0.1", "8280", 3, "U2COIxSQXnVppQAR");

/////////
$testGroupID = "565192f2-37b5-48ef-8b92-01ff38f6ff7c";
$testUserID = "49a1071c-28cc-4218-93f6-2087742ff04a";
$pluginId = 3;
$test = new Test();

$array = array(
    // 群聊测试，只在正确的群聊响应
    "zaly://192.168.3.72/goto?page=plugin_for_group_chat&plugin_id={$pluginId}&site_group_id={$testGroupID}",
    // 群聊测试，错误的URL，在哪里都不响应。
    "zaly://192.168.3.72/goto?page=plugin_for_group_chat&plugin_id={$pluginId}&site_group_id=nonasdfkljdasas",
    // 群聊测试，错误的URL，在哪里都不响应。
    "zaly://192.168.3.72/goto?page=plugin_for_group_chat&plugin_id=10000000000&site_group_id={$testGroupID}",
    // 私聊测试，只在正确的私聊响应
    "zaly://192.168.3.72/goto?page=plugin_for_u2_chat&plugin_id={$pluginId}&site_user_id={$testUserID}",
    // 错误的，在群聊私聊均不响应。
    "zaly://192.168.3.72/goto?page=friend_apply&plugin_id={$pluginId}&site_user_id={$testUserID}",

    "dsafdf;sljdafsklfad;af"
);

foreach ($array as $item) {
    $test->TestSend($client, $item);
}

class Test
{
    public function TestSend($client, $url)
    {
        var_dump($url);
        $request = new \Akaxin\Proto\Plugin\HaiMessageProxyRequest();
        $webmsg = new \Akaxin\Proto\Core\GroupWeb();
        $webmsg->setMsgId(time());
        $webmsg->setSiteGroupId("565192f2-37b5-48ef-8b92-01ff38f6ff7c");
        $webmsg->setSiteUserId("49a1071c-28cc-4218-93f6-2087742ff04a");
        $webmsg->setWebCode("<h1>Href URL</h1><br />{$url}");
        $webmsg->setWidth(250);
        $webmsg->setHeight(100);
        $webmsg->setTime(time() * 1000);
        $webmsg->setHrefUrl($url);
        $msg = new \Akaxin\Proto\Site\ImCtsMessageRequest();
        $msg->setType(\Akaxin\Proto\Core\MsgType::GROUP_WEB);
        $msg->setGroupWeb($webmsg);
        $request->setProxyMsg($msg);
        $response = $client->request("hai/message/proxy", $request);

        var_dump($client->errorCode(), $client->errorInfo());
        sleep(1);
        echo "\n";
    }
}
