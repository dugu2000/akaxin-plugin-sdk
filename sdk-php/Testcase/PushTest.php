<?php

use PHPUnit\Framework\TestCase;

final class HaiPushTest extends TestCase
{
    public function testHaiPushNoticesRequest(): void
    {
        $admin = Context::getInstance()->getAdminUserID();
        $userA = Context::getInstance()->getUserA();

        $client = getApiClient();

        $request = new Akaxin\Proto\Plugin\HaiPushNoticesRequest();
        $request->setContent("测试push通知");
        $request->setSubtitle("测试");
        $responseData = $client->request("/hai/push/notices", $request);

        $response = new Akaxin\Proto\Plugin\HaiPushNoticesResponse();
        $response->mergeFromString($responseData);


        $this->assertInstanceOf(
            Akaxin\Proto\Plugin\HaiPushNoticesResponse::class,
            $response
        );

        $this->assertEquals(
            ERROR_CODE_SUCCESS,
            $client->errorCode()
        );
        $this->assertEmpty($client->errorInfo());


    }


}
