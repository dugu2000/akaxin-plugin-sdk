<?php

//
// Akaxin 扩展开发Demo示例 ---- 初级教程
//
// Akaxin 服务端源码：https://github.com/akaxincom/openzaly
// Akaxin 扩展SDK：https://github.com/akaxincom/akaxin-plugin-sdk


//
// 这里，我们预先找好了几张图片
//
date_default_timezone_set("Asia/Shanghai");
$config = array();
$config[] = array(
    array("https://media.giphy.com/media/xUA7aT8modk0HwqAHS/200w_d.gif", 200, 113),
    array("https://media.giphy.com/media/VkeFpl1JBOA6s/200w_d.gif", 200, 101),
);
$config[] = array(
    array("https://media.giphy.com/media/NWLUwtaivTes8/200w_d.gif", 200, 139),
    array("https://media.giphy.com/media/FHWf7V75zrDa/200w_d.gif", 200, 200),
);


//
// 设置相关变量
//
$host = "127.0.0.1";    // pluginApiServer
$port = 8280;           // pluginApiServer port，默认为8280

$pluginId = 3;
$pluginAuthKey = "3VLJVK5CVfeL0ugS";
$siteHost = "192.168.3.5";

//
// 如何在启动服务器的时候，更改pluginApiServer与Port，请参考：
// https://www.akaxin.com/docs/install/step-3-run/index.html
//



//
// 引用SDK文件，sdk-php里的所有文件都是需要的
//
require_once(__DIR__ . "/../../sdk-php/AkaxinPluginApiClient.php");

//
// AkaxinReferer::getInstance() 可以对当前扩展运行的上下文进行解析，比如正在哪一个群组等。
//
$akaxinUrl = AkaxinReferer::getInstance();
$rightGroupId = $akaxinUrl->getChatGroupId();
$sessionid = $akaxinUrl->getAkaxinSessionId();

//
// $_GET["group"]，当用户点击图片后，会发送一个ajax请求过来，附带这个参数
//
if (isset($_GET["group"])) {
    $rightGroupId = $_GET["group"];
}

//
// 如果没有sessionid，则为非法请求
//
if (empty($sessionid)) {
    die("no session");
}


//
// 这是一个函数，用于代发消息到客户端
//
function sendWebMsg($url, $width, $height) {
    global $host, $port, $pluginId, $pluginAuthKey, $rightGroupId, $sessionid;


    // 生成 AkaxinPluginApiClient 实例
    $client = new AkaxinPluginApiClient($host, $port, $pluginId, $pluginAuthKey);

    // 第一个API：开始查询sessionid对应的用户
    $request = new \Akaxin\Proto\Plugin\HaiSessionProfileRequest();
    $request->setBase64SafeUrlSessionId($sessionid);
    $responseData = $client->request("hai/session/profile", $request);
    $response = new \Akaxin\Proto\Plugin\HaiSessionProfileResponse();
    $response->mergeFromString($responseData);
    $siteUserId = $response->getUserProfile()->getSiteUserId();

    //
    // 应该判断siteUserId是否在当前群组里，如果不是则退出
    //
    // 因为我们是Demo，就不判断了。
    //
    if (empty($siteUserId)) {
        return;
    }





    $html = <<<EOT
    <!doctype html>
    <html>
        <head>
            <style>
                html, body {background: #f4f4f4; padding: 0px; margin: 0px;}
            </style>
            <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        </head>
        <body>
        <img src="{$url}" width="100%" />
        </body>
    </html>
EOT;

    //
    // 生成一个 GroupWeb 消息，这是一个Webview消息，可以大幅提高消息展现能力
    //
    // Webview消息不允许执行Javascript
    //
    $webmsg = new \Akaxin\Proto\Core\GroupWeb();
    $webmsg->setSiteGroupId($rightGroupId); // 发到哪一个群组
    $webmsg->setSiteUserId($siteUserId);    // 以谁的名义来发
    $webmsg->setMsgId(time().mt_rand(1000, 2000)); // 消息id一定要是一个不重复的随机数
    $webmsg->setWebCode($html); // web消息代码，比如<html><body><h1>i am a demo</h1></body></html>
    $webmsg->setWidth($width);
    $webmsg->setHeight($height);
    $webmsg->setTime(time() * 1000 );

    // ImCtsMessageRequest 是对消息的一层封装
    $msg = new \Akaxin\Proto\Site\ImCtsMessageRequest();
    $msg->setType(\Akaxin\Proto\Core\MsgType::GROUP_WEB); // 设置消息类型
    $msg->setGroupWeb($webmsg); // 设置消息内容


    // 第二个API：代发消息
    // HaiMessageProxyRequest 才是代发消息真正的Request
    $request = new \Akaxin\Proto\Plugin\HaiMessageProxyRequest();
    $request->setProxyMsg($msg);//
    $response = $client->request("hai/message/proxy", $request);
}


$pk1 = isset($_GET["pkey1"]) ? $_GET["pkey1"] : "";
$pk2 = isset($_GET["pkey2"]) ? $_GET["pkey2"] : "";
if (!empty($config[$pk1][$pk2])) {
    sendWebMsg($config[$pk1][$pk2][0], $config[$pk1][$pk2][1], $config[$pk1][$pk2][2]);
}


// 下面是页面，没有什么好讲的了
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
        <script src="//s1.url.cn/tim/docs/desktop/libs/jquery.min-b8d64d.js"></script>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <style>
        .row {margin-top: 16px;}
        </style>
    </head>
    <body>
        <div class="container">


<?php foreach ($config as $key=>$val):?>

  <div class="row">
    <div class="col-6">
        <img src="<?php echo $val[0][0]?>" width="100%" pkey="<?php echo $key;?>" pkey2="<?php echo 0;?>" />
    </div>
    <div class="col-6">
            <img src="<?php echo $val[1][0]?>" width="100%" pkey="<?php echo $key;?>" pkey2="<?php echo 1;?>" />
    </div>
  </div>
<?php endforeach; ?>

</div>
    </body>
</html>

<script>

$(document).ready(function(){
    $("img").click(function(){
        var k1 = $(this).attr("pkey");
        var k2 = $(this).attr("pkey2");
        console.log(k1, k2);

        $.getJSON(
            "./index.php",
            {pkey1: k1, pkey2: k2, group: "<?php echo $rightGroupId?>"},
            function(e){
                console.log(e)
            }
        );
    });

})
</script>
