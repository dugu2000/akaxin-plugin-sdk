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

    //测试正常情况添加好友
    public function testHaiFriendAddRequest(): void
    {
        //已知userA和userB不是好友
        $userA = Context::getInstance()->getUserA();
        $userB = Context::getInstance()->getUserB();

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

    //测试错误的UserId
    public function testHaiFriendAddRequest_WrongId(): void
    {
        //userA userB 不是已存在的 ID
        $userA = Context::getInstance()->getWrongId();
        $userB = Context::getInstance()->getWrongId();

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
        //应该不返回success
        $this->assertNotEquals(
            ERROR_CODE_SUCCESS,
            $client->errorCode()
        );
    }

    //测试申请添加好友
    public function testHaiFriendApplyRequest(): void
    {
        //已知userA和userC不是好友
        $userA = Context::getInstance()->getUserA();
        $userC = Context::getInstance()->getUserC();

        $client = getApiClient();
        $request = new Akaxin\Proto\Plugin\HaiFriendApplyRequest();
        $request->setSiteUserId($userA);
        $request->setFriendSiteUserId($userC);
        $request->setApplyReason("你好");

        $responseData = $client->request("/hai/friend/apply", $request);
        $response = new Akaxin\Proto\Plugin\HaiFriendApplyResponse();
        $response->mergeFromString($responseData);

        $this->assertInstanceOf(
            Akaxin\Proto\Plugin\HaiFriendApplyResponse::class,
            $response
        );

        $this->assertEquals(
            ERROR_CODE_SUCCESS,
            $client->errorCode()
        );

        $this->assertEmpty($client->errorInfo());
    }

    //测试错误的Id
    public function testHaiFriendApplyRequest_WrongId(): void
    {
        //已知userA和userC是错误的id
        $userA = Context::getInstance()->getWrongId();
        $userC = Context::getInstance()->getWrongId();

        $client = getApiClient();
        $request = new Akaxin\Proto\Plugin\HaiFriendApplyRequest();
        $request->setSiteUserId($userA);
        $request->setFriendSiteUserId($userC);
        $request->setApplyReason("你好");

        $responseData = $client->request("/hai/friend/apply", $request);

        $response = new Akaxin\Proto\Plugin\HaiFriendApplyResponse();
        $response->mergeFromString($responseData);

        $this->assertInstanceOf(
            Akaxin\Proto\Plugin\HaiFriendApplyResponse::class,
            $response
        );
        //不应该返回success
        $this->assertNotEquals(
            ERROR_CODE_SUCCESS,
            $client->errorCode()
        );

    }

    //测试获取好友关系,已知是好友
    public function testHaiFriendRelationsRequest_friend(): void
    {
        //userA和admin 是好友
        $userA = Context::getInstance()->getUserA();
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

    //测试获取好友关系,已知不是好友
    public function testHaiFriendRelationsRequest_NotFriend(): void
    {
        //userA和userC 已知不是好友
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

    //测试错误的Id
    public function testHaiFriendRelationsRequest_WrongId(): void
    {
        //已知userA和userC 是错误的id
        $userA = Context::getInstance()->getWrongId();
        $userC = Context::getInstance()->getWrongId();

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

        $this->assertInstanceOf(
            Akaxin\Proto\Plugin\HaiFriendRelationsResponse::class,
            $response
        );
        //不应该返回success
        $this->assertNotEquals(
            ERROR_CODE_SUCCESS,
            $client->errorCode()
        );


    }

}
