<?php

use PHPUnit\Framework\TestCase;

final class HaiSiteTest extends TestCase
{
    public function testHaiSiteGetConfigRequest(): void
    {
        $admin = Context::getInstance()->getAdminUserID();

        $client = getApiClient();

        $request = new Akaxin\Proto\Plugin\HaiSiteGetConfigRequest();
        $responseData = $client->request("/hai/site/getConfig", $request);

        $response = new Akaxin\Proto\Plugin\HaiSiteGetConfigResponse();
        $response->mergeFromString($responseData);
        $siteBackConfig = $response->getSiteConfig();
        $this->assertInstanceOf(
            Akaxin\Proto\Plugin\HaiSiteGetConfigResponse::class,
            $response
        );

        $this->assertEquals(
            ERROR_CODE_SUCCESS,
            $client->errorCode()
        );
        $this->assertEmpty($client->errorInfo());

        $this->assertEquals(
            ERROR_CODE_SUCCESS,
            $client->errorCode()
        );


        $this->assertNotEmpty($siteBackConfig);
        $this->assertEquals(
            $admin,
            $siteBackConfig->getSiteConfig()[12]
        );

    }


}
