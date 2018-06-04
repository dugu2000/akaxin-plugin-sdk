<?php
// declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class HaiUserTest extends TestCase
{

    private $editUserName;

    private function getUserProfile() {
        // 获取用户ID
        $userA = Context::getInstance()->getUserA();

        $client = getApiClient();
        $request = new Akaxin\Proto\Plugin\HaiUserProfileRequest();
        $request->setSiteUserId($userA);

        $responseData = $client->request("/hai/user/profile", $request);
        $response = new Akaxin\Proto\Plugin\HaiUserProfileResponse();
        $response->mergeFromString($responseData);
        return $response->getUserProfile();
    }

    public function testHaiUserFriendsRequest() {
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

    public function testHaiUserGroupsRequest() {
        $userA = Context::getInstance()->getUserA();

        $client = getApiClient();
        $request = new Akaxin\Proto\Plugin\HaiUserGroupsRequest();
        $request->setSiteUserId($userA);

        $responseData = $client->request("/hai/user/groups", $request);
        $response = new Akaxin\Proto\Plugin\HaiUserGroupsResponse();
        $response->mergeFromString($responseData);

        $this->assertEquals(ERROR_CODE_SUCCESS, $client->errorCode());
    }

    public function testHaiUserListRequest() {
        $userA = Context::getInstance()->getUserA();

        $client = getApiClient();
        $request = new Akaxin\Proto\Plugin\HaiUserListRequest();
        $request->setPageNumber(1);
        $request->setPageSize(100);

        $responseData = $client->request("hai/user/list", $request);
        $response = new Akaxin\Proto\Plugin\HaiUserListResponse();
        $response->mergeFromString($responseData);

        $this->assertEquals(ERROR_CODE_SUCCESS, $client->errorCode());
        $this->assertEquals(100, count($client->getUserProfile()) );
    }

    public function testHaiUserPhoneRequest() {
        $userA = Context::getInstance()->getUserA();

        $client = getApiClient();
        $request = new Akaxin\Proto\Plugin\HaiUserListRequest();
        $request->setSiteUserId($userA);

        $responseData = $client->request("hai/user/phone", $request);
        $response = new Akaxin\Proto\Plugin\HaiUserPhoneResponse();
        $response->mergeFromString($responseData);
    }

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

}
