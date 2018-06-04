<?php
// declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class HaiFriendTest extends TestCase
{

    /**
     * @afterClass
     */
    public static function tearDownEnv()
    {
        Context::getInstance()->restartServer();
    }

    public function testHaiFriendAddRequest(): void
    {
        // 获取用户ID
        $userA = Context::getInstance()->getUserA();
        $userB = Context::getInstance()->getUserB();
        $admin = Context::getInstance()->getAdminUserID();

        $client = getApiClient();

        $request = new Akaxin\Proto\Plugin\HaiFriendAddRequest();
        $request->setSiteUserId($userA);
        $request->setFriendSiteUserId($userB);

        $responseData = $client->request("hai/friend/add", $request);

        $response = new Akaxin\Proto\Plugin\HaiFriendAddResponse();
        $response->mergeFromString($responseData);

        $this->assertInstanceOf(
            Akaxin\Proto\Plugin\HaiFriendAddResponse::class,
            $response
        );

        $this->assertEquals(
            ERROR_CODE_SUCCESS,
            $client->errorCode()
        );

        $this->assertEmpty($client->errorInfo());
    }

    public function testHaiFriendApplyRequest(): void
    {
        // 获取用户ID
        // 这个Case有问题，A和B已经是好友了。
        $userA = Context::getInstance()->getUserA();
        $userB = Context::getInstance()->getUserB();
        $admin = Context::getInstance()->getAdminUserID();

        $client = getApiClient();

        $request = new Akaxin\Proto\Plugin\HaiFriendApplyRequest();
        $request->setSiteUserId($userA);
        $request->setFriendSiteUserId($userB);
        $request->setApplyReason("你好");
        $responseData = $client->request("/hai/friend/apply", $request);

        $response = new Akaxin\Proto\Plugin\HaiFriendApplyResponse();
        $response->mergeFromString($responseData);

        $this->assertInstanceOf(
            Akaxin\Proto\Plugin\HaiFriendApplyResponse::class,
            $response
        );

        $this->assertNotEquals(
            ERROR_CODE_SUCCESS,
            $client->errorCode()
        );

        $this->assertEmpty($client->errorInfo());
    }


    public function testHaiFriendRelationsRequest_friend(): void
    {
        // 获取用户ID
        $userA = Context::getInstance()->getUserA();
        $userB = Context::getInstance()->getUserB();
        $admin = Context::getInstance()->getAdminUserID();

        $client = getApiClient();

        $request = new Akaxin\Proto\Plugin\HaiFriendRelationsRequest();
        $request->setSiteUserId($userA);
        $request->setTargetSiteUserId(
            array(
                $admin
            )
        );

        $responseData = $client->request("/hai/friend/relations", $request);

        $response = new Akaxin\Proto\Plugin\HaiFriendRelationsResponse();
        $response->mergeFromString($responseData);

        $userProfile = $response->getUserProfile();
        $relation = $userProfile[0]->getRelation();

        $this->assertInstanceOf(
            Akaxin\Proto\Plugin\HaiFriendRelationsResponse::class,
            $response
        );

        $this->assertEquals(
            ERROR_CODE_SUCCESS,
            $client->errorCode()
        );

        $this->assertEmpty($client->errorInfo());

        $this->assertEquals(
            "1",
            $relation
        );
    }

    public function testHaiFriendRelationsRequest_Nofriend(): void
    {
        // 获取用户ID
        $userA = Context::getInstance()->getUserA();
        $userC = Context::getInstance()->getUserC();

        $client = getApiClient();

        $request = new Akaxin\Proto\Plugin\HaiFriendRelationsRequest();
        $request->setSiteUserId($userA);
        $request->setTargetSiteUserId(
            array(
                $userC
            )
        );

        $responseData = $client->request("/hai/friend/relations", $request);

        $response = new Akaxin\Proto\Plugin\HaiFriendRelationsResponse();
        $response->mergeFromString($responseData);

        $userProfile = $response->getUserProfile();
        $relation = $userProfile[0]->getRelation();

        $this->assertInstanceOf(
            Akaxin\Proto\Plugin\HaiFriendRelationsResponse::class,
            $response
        );

        $this->assertEquals(
            ERROR_CODE_SUCCESS,
            $client->errorCode()
        );

        $this->assertEmpty($client->errorInfo());

        $this->assertEquals(
            "0",
            $relation
        );
    }

}
