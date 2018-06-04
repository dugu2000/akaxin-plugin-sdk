<?php


require_once(__DIR__ . "/autoload_for_doc.php");

// 获取用户ID
$userA = Context::getInstance()->getUserA();
$userB = Context::getInstance()->getUserB();
$userC = Context::getInstance()->getUserC();
$userD = Context::getInstance()->getUserD();
$groupA = Context::getInstance()->getGroupA();
$admin = Context::getInstance()->getAdminUserID();

$client = getApiClient();

$request = new Akaxin\Proto\Plugin\HaiGroupListRequest();
$request->setSiteUserId(array(
    $userC
));
$request->setPageNumber(1);
$request->setPageSize(10);
$responseData = $client->request("/hai/group/list", $request);

$response = new Akaxin\Proto\Plugin\HaiGroupListResponse();
$response->mergeFromString($responseData);



$groupProfiles = $response->getGroupProfile();
$count = $groupProfiles->count();
echo "\n";
echo $count;
echo "\n";
