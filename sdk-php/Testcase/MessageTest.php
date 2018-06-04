<?php

use PHPUnit\Framework\TestCase;

final class HaiMessageTest extends TestCase
{

    /**
     * @afterClass
     */
    public static function tearDownEnv()
    {
        Context::getInstance()->restartServer();
    }


    public function testHaiMessageProxyRequest(): void
    {
        $admin = Context::getInstance()->getAdminUserID();
        $userA = Context::getInstance()->getUserA();

        $client = getApiClient();

        $request = new Akaxin\Proto\Plugin\HaiMessageProxyRequest();
        $imCtsMessageRequest = new Akaxin\Proto\Site\ImCtsMessageRequest();
        $msgText = new Akaxin\Proto\Core\MsgText();
        $msgText->setText("你好这是测试消息");
        $msgText->setTime("0");
        $msgText->setSiteUserId($admin);
        $msgText->setMsgId("001");
        $msgText->setSiteFriendId($userA);
        $imCtsMessageRequest->setText($msgText);
        $imCtsMessageRequest->setType(3);
        $request->setProxyMsg($imCtsMessageRequest);
        $responseData = $client->request("/hai/message/proxy", $request);

        $response = new Akaxin\Proto\Plugin\HaiGroupUpdateResponse();
        $response->mergeFromString($responseData);


        $this->assertInstanceOf(
            Akaxin\Proto\Plugin\HaiGroupUpdateResponse::class,
            $response
        );

        $this->assertEquals(
            ERROR_CODE_SUCCESS,
            $client->errorCode()
        );
        $this->assertEmpty($client->errorInfo());


    }


}
