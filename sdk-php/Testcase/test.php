<?php


require_once(__DIR__ . "/autoload_for_doc.php");
$client = getApiClient();

$request = new Akaxin\Proto\Plugin\HaiSiteGetConfigRequest();
$responseData = $client->request("/hai/site/getConfig", $request);

$response = new Akaxin\Proto\Plugin\HaiSiteGetConfigResponse();
$response->mergeFromString($responseData);
$siteBackConfig = $response->getSiteConfig();

$siteConfig = $siteBackConfig->getSiteConfig();
echo "\n ======";
echo $siteConfig[2];
echo "\n ======\n";
