
扩展可以通过调用站点主服务器的PluginAPI来增强产品功能。

基本介绍
====

1. 每一个PluginAPI，都有接口名、Request结构体、Response结构体。
2. PluginAPI的文档，请查阅
    * [PHP版](sdk-php/)

SDK
----

* PHPSDK：https://github.com/akaxincom/akaxin-plugin-sdk/tree/master/sdk-php
    * 示例代码：https://github.com/akaxincom/akaxin-plugin-sdk/tree/master/sdk-php/demo-php.php

快速示例
====

PHP通过SDK调用InnerAPI
----

```php
$akaxinApiClient = new AkaxinPluginApiClient($pluginApiHost, $pluginApiPort, $pluginId, $pluginAuthKey);

$requestMessage = new Akaxin\Proto\Plugin\HaiUserProfileRequest();
$requestMessage->setSiteUserId($siteUserIdForQuery);

$responseData = $akaxinApiClient->request("hai/user/profile", $requestMessage);
$userProfileResponse = new Akaxin\Proto\Plugin\HaiUserProfileResponse();
$userProfileResponse->mergeFromString($responseData);

var_dump($userProfileResponse->getUserProfile());
```

> 如果你自己有composer，并且已经安装了google/protobuf，可以使用下面的常量，禁用AkaxinLib里的lib，防止冲突。
>
> ```php
> define("DONNOT_USE_AKAXIN_GOOGLE_PROTOBUF_LIB", true);
> ```


常见问题
====

### 如果代发Web消息

> 1. 构造消息体
> 2. 把消息体封装在ImCtsMessageRequest，完成封装
> 3. 通过PluginApi HaiMessageProxyRequest 完成代发

### Web消息怎样点击到扩展

> 1. 对于Web与WebNotice类消息，可以设置hrefUrl字段。
>   * https://www.akaxin.com/docs/plugin/url-jump/index.html
> 2. 构造 plugin_for_group_chat 或 plugin_for_u2_chat
> 3. 设置在hrefUrl字段里，便可以完成跳转。

### Web消息跳转到扩展时，怎样传递一些参数给扩展页面

> 构造hrefUrl字段时候，增加 `akaxin_param` 字段，在扩展页面的落地页里获取HTTP_REFERER来获取此值。
>
> PHPSDK：`AkaxinReferer::getInstance()->getAkaxinParam();`

### Web消息跳转到扩展，点击后无反应？

> 检查hrefURL
>
> * domain、port是否正确，需要与用户客户端的地址一致。
> * plugin_id 是否正确
> * groupid与userid是否与用户点击消息时的聊天上下文一致。

### 怎样在扩展页面得知当前用户是谁？

> 1. 通过 `AkaxinReferer::getInstance()->getAkaxinSessionId();` 获取SessionID
> 2. 通过 `HaiSessionProfileRequest` 获取session对应的用户Profile


### 其他问题

> 请加入官方QQ群咨询。
