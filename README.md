在线访问：https://www.akaxin.com/docs/

扩展可以通过调用站点主服务器的InnerAPI来增强产品功能。

基本介绍
====

1. 每一个InnerAPI，都有接口名、Request结构体、Response结构体。
2. InnerAPI的文档，请查阅
    * [PHP版](sdk-php/)
    * Java版

SDK
----

* PHPSDK：https://github.com/akaxincom/akaxin-plugin-sdk/tree/master/sdk-php
    * 示例代码：https://github.com/akaxincom/akaxin-plugin-sdk/tree/master/sdk-php/demo-php.php
* JavaSDK: 待添加


快速示例
====

PHP通过SDK调用InnerAPI
----

```php
$akaxinApiClient = new AkaxinInnerApiClient($innerApiHost, $innerApiPort, $pluginId, $pluginAuthKey);

$requestMessage = new Akaxin\Proto\Plugin\HaiUserProfileRequest();
$requestMessage->setSiteUserId($siteUserIdForQuery);

$responseData = $akaxinApiClient->request("hai/user/profile", $requestMessage);
$userProfileResponse = new Akaxin\Proto\Plugin\HaiUserProfileResponse();
$userProfileResponse->mergeFromString($responseData);

var_dump($userProfileResponse->getUserProfile());
```

> 使用自己的composer安装google/protobuf
>
> ```php
> define("DONNOT_USE_AKAXIN_GOOGLE_PROTOBUF_LIB", true);
> ```
