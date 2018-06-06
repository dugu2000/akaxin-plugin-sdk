<?php
// declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class HaiUserTest extends TestCase
{

    // /**
    //  * @afterClass
    //  */
    // public static function tearDownEnv()
    // {
    //     Context::getInstance()->restartServer();
    // }

    private $editUserName;

    // 获取用户资料
    private function getUserProfile()
    {
        //userA是已知存在用户
        $userA = Context::getInstance()->getUserA();

        $client = getApiClient();
        $request = new Akaxin\Proto\Plugin\HaiUserProfileRequest();
        $request->setSiteUserId($userA);

        $responseData = $client->request("/hai/user/profile", $request);
        $response = new Akaxin\Proto\Plugin\HaiUserProfileResponse();
        $response->mergeFromString($responseData);
        return $response->getUserProfile();
    }

    //获取用户好友列表
    public function testHaiUserFriendsRequest()
    {
        //已知userA为存在用户
        $userA = Context::getInstance()->getUserA();

        $client = getApiClient();
        $request = new Akaxin\Proto\Plugin\HaiUserFriendsRequest();
        $request->setSiteUserId($userA);

        $responseData = $client->request("/hai/user/friends", $request);
        $response = new Akaxin\Proto\Plugin\HaiUserFriendsResponse();
        $response->mergeFromString($responseData);

        $this->assertEquals(ERROR_CODE_SUCCESS, $client->errorCode());
        $this->assertEquals(1, count($response->getProfile()));
        $this->assertEquals(1, $response->getPageTotalNum());
    }

    //测试错误id
    public function testHaiUserFriendsRequest_WrongId()
    {
        //已知userA为错误Id
        $userA = Context::getInstance()->getWrongId();

        $client = getApiClient();
        $request = new Akaxin\Proto\Plugin\HaiUserFriendsRequest();
        $request->setSiteUserId($userA);

        $responseData = $client->request("/hai/user/friends", $request);
        $response = new Akaxin\Proto\Plugin\HaiUserFriendsResponse();
        $response->mergeFromString($responseData);

        $this->assertNotEquals(ERROR_CODE_SUCCESS, $client->errorCode());
    }

    //获取用户群组列表
    public function testHaiUserGroupsRequest()
    {
        //已知userA为存在用户
        $userA = Context::getInstance()->getUserA();

        $client = getApiClient();
        $request = new Akaxin\Proto\Plugin\HaiUserGroupsRequest();
        $request->setSiteUserId($userA);

        $responseData = $client->request("/hai/user/groups", $request);
        $response = new Akaxin\Proto\Plugin\HaiUserGroupsResponse();
        $response->mergeFromString($responseData);

        $this->assertEquals(ERROR_CODE_SUCCESS, $client->errorCode());
    }

    //测试错误id
    public function testHaiUserGroupsRequest_Wrong()
    {
        //已知userA为错误id
        $userA = Context::getInstance()->getWrongId();

        $client = getApiClient();
        $request = new Akaxin\Proto\Plugin\HaiUserGroupsRequest();
        $request->setSiteUserId($userA);

        $responseData = $client->request("/hai/user/groups", $request);
        $response = new Akaxin\Proto\Plugin\HaiUserGroupsResponse();
        $response->mergeFromString($responseData);

        $this->assertNotEquals(ERROR_CODE_SUCCESS, $client->errorCode());
    }

    //获取站点上的用户
    public function testHaiUserListRequest()
    {

        $client = getApiClient();
        $request = new Akaxin\Proto\Plugin\HaiUserListRequest();
        $request->setPageNumber(1);
        $request->setPageSize(100);

        $responseData = $client->request("hai/user/list", $request);
        $response = new Akaxin\Proto\Plugin\HaiUserListResponse();
        $response->mergeFromString($responseData);

        $this->assertEquals(ERROR_CODE_SUCCESS, $client->errorCode());
        $this->assertEquals(100, count($response->getUserProfile()));
    }

    //只测试接口,无逻辑测试
    public function testHaiUserPhoneRequest()
    {
        $userA = Context::getInstance()->getUserA();

        $client = getApiClient();
        $request = new Akaxin\Proto\Plugin\HaiUserPhoneRequest();
        $request->setSiteUserId($userA);

        $responseData = $client->request("hai/user/phone", $request);
        $response = new Akaxin\Proto\Plugin\HaiUserPhoneResponse();
        $response->mergeFromString($responseData);
    }

    //测试 更新用户资料
    public function testHaiUserUpdateRequestAndHaiUserProfileRequest()
    {
        $this->editUserName = "abcde";
        $profile = $this->getUserProfile();
        $profile->setUserName($this->editUserName);

        $client = getApiClient();
        $request = new Akaxin\Proto\Plugin\HaiUserUpdateRequest();
        $request->setUserProfile($profile);

        $responseData = $client->request("hai/user/update", $request);
        $response = new Akaxin\Proto\Plugin\HaiUserUpdateResponse();
        $response->mergeFromString($responseData);
        $this->assertEquals(
            ERROR_CODE_SUCCESS,
            $client->errorCode()
        );

        $profile = $this->getUserProfile();
        $this->assertEquals($this->editUserName, $profile->getUserName());
        $this->assertEquals(Context::getInstance()->getUserA(), $profile->getSiteUserId());
        return true;
    }

    //测试错误id
    public function getUserProfile_WrongId()
    {
        //userA是错误id
        $userA = Context::getInstance()->getWrongId();

        $client = getApiClient();
        $request = new Akaxin\Proto\Plugin\HaiUserProfileRequest();
        $request->setSiteUserId($userA);

        $responseData = $client->request("/hai/user/profile", $request);
        $response = new Akaxin\Proto\Plugin\HaiUserProfileResponse();
        $response->mergeFromString($responseData);

        $this->assertNotEquals(ERROR_CODE_SUCCESS, $client->errorCode());
    }
}
