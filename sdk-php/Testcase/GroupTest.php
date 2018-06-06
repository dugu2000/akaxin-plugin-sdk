<?php
/**
 * Created by PhpStorm.
 * User: mino
 * Date: 2018/6/4
 * Time: 下午5:44
 */

use PHPUnit\Framework\TestCase;

final class HaiGroupTest extends TestCase
{

    // /**
    //  * @afterClass
    //  */
    // public static function tearDownEnv()
    // {
    //     Context::getInstance()->restartServer();
    // }

    //测试添加群成员
    public function testHaiGroupAddMemberRequest(): void
    {
        //已知 userA userB  不在 groupA  中
        $userA = Context::getInstance()->getUserA();
        $userB = Context::getInstance()->getUserB();
        $groupA = Context::getInstance()->getGroupA();

        $client = getApiClient();
        $request = new Akaxin\Proto\Plugin\HaiGroupAddMemberRequest();
        $request->setGroupId($groupA);
        $request->setMemberSiteUserId(
            array(
                $userA,
                $userB
            )
        );

        $responseData = $client->request("/hai/group/addMember", $request);
        $response = new Akaxin\Proto\Plugin\HaiGroupAddMemberResponse();
        $response->mergeFromString($responseData);

        $this->assertInstanceOf(
            Akaxin\Proto\Plugin\HaiGroupAddMemberResponse::class,
            $response
        );

        $this->assertEquals(
            ERROR_CODE_SUCCESS,
            $client->errorCode()
        );

        $this->assertEmpty($client->errorInfo());
    }

    //测试  错误的 id 情况
    public function testHaiGroupAddMemberRequest_WrongUserId(): void
    {
        //已知 userA userB   是错误的id
        $userA = Context::getInstance()->getWrongId();
        $userB = Context::getInstance()->getWrongId();
        $groupA = Context::getInstance()->getGroupA();

        $client = getApiClient();
        $request = new Akaxin\Proto\Plugin\HaiGroupAddMemberRequest();
        $request->setGroupId($groupA);
        $request->setMemberSiteUserId(
            array(
                $userA,
                $userB
            )
        );

        $responseData = $client->request("/hai/group/addMember", $request);
        $response = new Akaxin\Proto\Plugin\HaiGroupAddMemberResponse();
        $response->mergeFromString($responseData);

        $this->assertInstanceOf(
            Akaxin\Proto\Plugin\HaiGroupAddMemberResponse::class,
            $response
        );
        //不应该返回 success
        $this->assertNotEquals(
            ERROR_CODE_SUCCESS,
            $client->errorCode()
        );

    }

    //测试  错误的 group id 情况
    public function testHaiGroupAddMemberRequest_WrongGroupId(): void
    {
        $userA = Context::getInstance()->getWrongId();
        $userB = Context::getInstance()->getWrongId();
        //不存在的  groupId
        $groupA = "10008";

        $client = getApiClient();
        $request = new Akaxin\Proto\Plugin\HaiGroupAddMemberRequest();
        $request->setGroupId($groupA);
        $request->setMemberSiteUserId(
            array(
                $userA,
                $userB
            )
        );

        $responseData = $client->request("/hai/group/addMember", $request);
        $response = new Akaxin\Proto\Plugin\HaiGroupAddMemberResponse();
        $response->mergeFromString($responseData);

        $this->assertInstanceOf(
            Akaxin\Proto\Plugin\HaiGroupAddMemberResponse::class,
            $response
        );

        //
        //不应该返回 success
        $this->assertNotEquals(
            ERROR_CODE_SUCCESS,
            $client->errorCode()
        );

    }

    //测试 添加群成员  已经在本群的情况
    public function testHaiGroupAddMemberRequest_already(): void
    {
        // 已知 userA userB 已经在 groupA
        $userA = Context::getInstance()->getUserA();
        $userB = Context::getInstance()->getUserB();
        $groupA = Context::getInstance()->getGroupA();

        $client = getApiClient();
        $request = new Akaxin\Proto\Plugin\HaiGroupAddMemberRequest();
        $request->setGroupId($groupA);
        $request->setMemberSiteUserId(
            array(
                $userA,
                $userB
            )
        );

        $responseData = $client->request("/hai/group/addMember", $request);
        $response = new Akaxin\Proto\Plugin\HaiGroupAddMemberResponse();
        $response->mergeFromString($responseData);

        $this->assertInstanceOf(
            Akaxin\Proto\Plugin\HaiGroupAddMemberResponse::class,
            $response
        );

        $this->assertNotEquals(
            ERROR_CODE_SUCCESS,
            $client->errorCode()
        );
    }
    // 判断是否是本群成员
    // 返回的是一个集合  判断集合是否包含参数的ids
    // 无 wrongId 测试
    public function testHaiGroupCheckMemberRequest(): void
    {
        //已知 userA 和 userB 是groupA 成员
        $userA = Context::getInstance()->getUserA();
        $userB = Context::getInstance()->getUserB();
        $groupA = Context::getInstance()->getGroupA();

        $client = getApiClient();
        $request = new Akaxin\Proto\Plugin\HaiGroupCheckMemberRequest();
        $request->setGroupId($groupA);
        $request->setSiteUserId(
            array(
                $userA,
                $userB
            )
        );

        $responseData = $client->request("/hai/group/checkMember", $request);
        $response = new Akaxin\Proto\Plugin\HaiGroupCheckMemberResponse();
        $response->mergeFromString($responseData);

        $members = $response->getMembersSiteUserId();


        $this->assertInstanceOf(
            Akaxin\Proto\Plugin\HaiGroupCheckMemberResponse::class,
            $response
        );

        $this->assertEquals(
            ERROR_CODE_SUCCESS,
            $client->errorCode()
        );

        $this->assertEmpty($client->errorInfo());
        foreach ($members as $key => $member) {
            $this->assertContains($member, array(
                $userA,
                $userB
            ));
        }
    }

    // 剔除群成员
    public function testHaiGroupRemoveMemberRequest(): void
    {
        //已知 userA是本群成员
        $userA = Context::getInstance()->getUserA();
        $groupA = Context::getInstance()->getGroupA();

        $client = getApiClient();
        $request = new Akaxin\Proto\Plugin\HaiGroupRemoveMemberRequest();
        $request->setGroupId($groupA);
        $request->setGroupMember(
            array(
                $userA
            )
        );

        $responseData = $client->request("/hai/group/removeMember", $request);
        $response = new Akaxin\Proto\Plugin\HaiGroupRemoveMemberResponse();
        $response->mergeFromString($responseData);


        $this->assertInstanceOf(
            Akaxin\Proto\Plugin\HaiGroupRemoveMemberResponse::class,
            $response
        );

        $this->assertEquals(
            ERROR_CODE_SUCCESS,
            $client->errorCode()
        );

        $this->assertEmpty($client->errorInfo());

    }

    // 错误id 测试
    public function testHaiGroupRemoveMemberRequest_WrongId(): void
    {
        //已知 userA是错误id
        $userA = Context::getInstance()->getWrongId();
        $groupA = Context::getInstance()->getGroupA();

        $client = getApiClient();
        $request = new Akaxin\Proto\Plugin\HaiGroupRemoveMemberRequest();
        $request->setGroupId($groupA);
        $request->setGroupMember(
            array(
                $userA
            )
        );

        $responseData = $client->request("/hai/group/removeMember", $request);
        $response = new Akaxin\Proto\Plugin\HaiGroupRemoveMemberResponse();
        $response->mergeFromString($responseData);


        $this->assertInstanceOf(
            Akaxin\Proto\Plugin\HaiGroupRemoveMemberResponse::class,
            $response
        );
        //不应该返回success
        $this->assertNotEquals(
            ERROR_CODE_SUCCESS,
            $client->errorCode()
        );

    }

    // 删除管理员测试
    public function testHaiGroupRemoveMemberRequest_admin(): void
    {
        $groupA = Context::getInstance()->getGroupA();
        $admin = Context::getInstance()->getAdminUserID();

        $client = getApiClient();
        $request = new Akaxin\Proto\Plugin\HaiGroupRemoveMemberRequest();
        $request->setGroupId($groupA);
        $request->setGroupMember(
            array(
                $admin
            )
        );

        $responseData = $client->request("/hai/group/removeMember", $request);
        $response = new Akaxin\Proto\Plugin\HaiGroupRemoveMemberResponse();
        $response->mergeFromString($responseData);

        $this->assertInstanceOf(
            Akaxin\Proto\Plugin\HaiGroupRemoveMemberResponse::class,
            $response
        );
        //不能删除管理员

        $this->assertNotEquals(
            ERROR_CODE_SUCCESS,
            $client->errorCode()
        );

    }

    //删除群组
    public function testHaiGroupDeleteRequest(): void
    {
        // 获取用户ID
        $groupA = Context::getInstance()->getGroupA();

        $client = getApiClient();
        $request = new Akaxin\Proto\Plugin\HaiGroupDeleteRequest();
        $request->setGroupId($groupA);

        $responseData = $client->request("/hai/group/delete", $request);
        $response = new Akaxin\Proto\Plugin\HaiGroupDeleteResponse();
        $response->mergeFromString($responseData);

        $this->assertInstanceOf(
            Akaxin\Proto\Plugin\HaiGroupDeleteResponse::class,
            $response
        );

        $this->assertEquals(
            ERROR_CODE_SUCCESS,
            $client->errorCode()
        );
        $this->assertEmpty($client->errorInfo());

    }

    //删除错误的 groupId
    public function testHaiGroupDeleteRequest_WrongId(): void
    {
        // groupA 是错误的Id
        $groupA = "10008";

        $client = getApiClient();
        $request = new Akaxin\Proto\Plugin\HaiGroupDeleteRequest();
        $request->setGroupId($groupA);

        $responseData = $client->request("/hai/group/delete", $request);
        $response = new Akaxin\Proto\Plugin\HaiGroupDeleteResponse();
        $response->mergeFromString($responseData);


        $this->assertInstanceOf(
            Akaxin\Proto\Plugin\HaiGroupDeleteResponse::class,
            $response
        );

        $this->assertNotEquals(
            ERROR_CODE_SUCCESS,
            $client->errorCode()
        );

    }
    // 获取群列表
    // 无错误id 测试
    public function testHaiGroupListRequest(): void
    {
        $client = getApiClient();
        $request = new Akaxin\Proto\Plugin\HaiGroupListRequest();
        $request->setPageNumber(1);
        $request->setPageSize(10);

        $responseData = $client->request("/hai/group/list", $request);
        $response = new Akaxin\Proto\Plugin\HaiGroupListResponse();
        $response->mergeFromString($responseData);

        $this->assertInstanceOf(
            Akaxin\Proto\Plugin\HaiGroupListResponse::class,
            $response
        );

        $this->assertEquals(
            ERROR_CODE_SUCCESS,
            $client->errorCode()
        );
        $this->assertEmpty($client->errorInfo());

        $groupProfiles = $response->getGroupProfile();
        $this->assertNotEmpty($groupProfiles);
    }

    // 获取群组成员
    public function testHaiGroupMembersRequest(): void
    {
        //groupB是存在群组
        $groupB = Context::getInstance()->getGroupB();

        $client = getApiClient();
        $request = new Akaxin\Proto\Plugin\HaiGroupMembersRequest();
        $request->setGroupId($groupB);
        $request->setPageSize(1000);
        $request->setPageNumber(1);

        $responseData = $client->request("/hai/group/members", $request);
        $response = new Akaxin\Proto\Plugin\HaiGroupMembersResponse();
        $response->mergeFromString($responseData);

        $this->assertInstanceOf(
            Akaxin\Proto\Plugin\HaiGroupMembersResponse::class,
            $response
        );

        $this->assertEquals(
            ERROR_CODE_SUCCESS,
            $client->errorCode()
        );
        $this->assertEmpty($client->errorInfo());
        $groupMembers = $response->getGroupMember();
        $this->assertEquals(
            101,
            $groupMembers->count()
        );
    }

    //错误id测试
    public function testHaiGroupMembersRequest_WrongId(): void
    {
        //groupB是错误id
        $groupB = "10008";

        $client = getApiClient();
        $request = new Akaxin\Proto\Plugin\HaiGroupMembersRequest();
        $request->setGroupId($groupB);
        $request->setPageSize(1000);
        $request->setPageNumber(1);

        $responseData = $client->request("/hai/group/members", $request);
        $response = new Akaxin\Proto\Plugin\HaiGroupMembersResponse();
        $response->mergeFromString($responseData);

        $this->assertInstanceOf(
            Akaxin\Proto\Plugin\HaiGroupMembersResponse::class,
            $response
        );
        //不应该返回success
        $this->assertNotEquals(
            ERROR_CODE_SUCCESS,
            $client->errorCode()
        );
    }

    //获取非群组成员
    public function testHaiGroupNonmembersRequest(): void
    {
        // 获取用户ID
        $groupB = Context::getInstance()->getGroupB();
        $admin = Context::getInstance()->getAdminUserID();

        $client = getApiClient();
        $request = new Akaxin\Proto\Plugin\HaiGroupNonmembersRequest();
        $request->setGroupId($groupB);
        $request->setPageNumber(1);
        $request->setPageSize(1000);
        $request->setSiteUserId($admin);

        $responseData = $client->request("/hai/group/nonmembers", $request);

        $response = new Akaxin\Proto\Plugin\HaiGroupNonmembersResponse();
        $response->mergeFromString($responseData);


        $this->assertInstanceOf(
            Akaxin\Proto\Plugin\HaiGroupNonmembersResponse::class,
            $response
        );

        $this->assertEquals(
            ERROR_CODE_SUCCESS,
            $client->errorCode()
        );
        $this->assertEmpty($client->errorInfo());
        $groupMembers = $response->getGroupMember();
        $this->assertGreaterThan(
            0,
            count($groupMembers),
            count($groupMembers)
        );
    }

    //获取非群组成员错误 groupId
    public function testHaiGroupNonmembersRequest_WrongGroupId(): void
    {
        //groupB是错误id
        $groupB = "10008";
        $admin = Context::getInstance()->getAdminUserID();

        $client = getApiClient();
        $request = new Akaxin\Proto\Plugin\HaiGroupNonmembersRequest();
        $request->setGroupId($groupB);
        $request->setPageNumber(1);
        $request->setPageSize(1000);
        $request->setSiteUserId($admin);

        $responseData = $client->request("/hai/group/nonmembers", $request);
        $response = new Akaxin\Proto\Plugin\HaiGroupNonmembersResponse();
        $response->mergeFromString($responseData);

        $this->assertInstanceOf(
            Akaxin\Proto\Plugin\HaiGroupNonmembersResponse::class,
            $response
        );
        //不应该返回success
        $this->assertNotEquals(
            ERROR_CODE_SUCCESS,
            $client->errorCode()
        );
    }

    //获取非群组成员错误 userId
    public function testHaiGroupNonmembersRequest_WrongUserId(): void
    {
        //groupB是错误id
        $groupB = Context::getInstance()->getGroupB();
        $admin = Context::getInstance()->getWrongId();

        $client = getApiClient();
        $request = new Akaxin\Proto\Plugin\HaiGroupNonmembersRequest();
        $request->setGroupId($groupB);
        $request->setPageNumber(1);
        $request->setPageSize(1000);
        $request->setSiteUserId($admin);

        $responseData = $client->request("/hai/group/nonmembers", $request);
        $response = new Akaxin\Proto\Plugin\HaiGroupNonmembersResponse();
        $response->mergeFromString($responseData);

        $this->assertInstanceOf(
            Akaxin\Proto\Plugin\HaiGroupNonmembersResponse::class,
            $response
        );
        //不应该返回success
        $this->assertNotEquals(
            ERROR_CODE_SUCCESS,
            $client->errorCode()
        );
    }

    // 获取群组资料
    public function testHaiGroupProfileRequest(): void
    {
        //groupB是已知群组
        $groupB = Context::getInstance()->getGroupB();

        $client = getApiClient();
        $request = new Akaxin\Proto\Plugin\HaiGroupProfileRequest();
        $request->setGroupId($groupB);

        $responseData = $client->request("/hai/group/profile", $request);
        $response = new Akaxin\Proto\Plugin\HaiGroupProfileResponse();
        $response->mergeFromString($responseData);

        $this->assertInstanceOf(
            Akaxin\Proto\Plugin\HaiGroupProfileResponse::class,
            $response
        );

        $this->assertEquals(
            ERROR_CODE_SUCCESS,
            $client->errorCode()
        );
        $this->assertEmpty($client->errorInfo());
        $groupProfile = $response->getProfile();
        $name = $groupProfile->getName();
        $this->assertEquals(
            "撬剧1",
            $name
        );
    }

    // 测试错误id
    public function testHaiGroupProfileRequest_WrongId(): void
    {
        //groupB是错误id
        $groupB = "10008";

        $client = getApiClient();
        $request = new Akaxin\Proto\Plugin\HaiGroupProfileRequest();
        $request->setGroupId($groupB);

        $responseData = $client->request("/hai/group/profile", $request);
        $response = new Akaxin\Proto\Plugin\HaiGroupProfileResponse();
        $response->mergeFromString($responseData);

        $this->assertInstanceOf(
            Akaxin\Proto\Plugin\HaiGroupProfileResponse::class,
            $response
        );
        //不应该返回success
        $this->assertNotEquals(
            ERROR_CODE_SUCCESS,
            $client->errorCode()
        );
    }

    //更新群组资料
    public function testHaiGroupUpdateRequest(): void
    {
        //groupB是已知存在群组
        $groupB = Context::getInstance()->getGroupB();

        $client = getApiClient();
        $request = new Akaxin\Proto\Plugin\HaiGroupUpdateRequest();
        $profile = new Akaxin\Proto\Core\GroupProfile();
        $profile->setId($groupB);
        $profile->setName("测试群组2");
        $request->setProfile($profile);

        $responseData = $client->request("/hai/group/update", $request);
        $response = new Akaxin\Proto\Plugin\HaiGroupUpdateResponse();
        $response->mergeFromString($responseData);

        $this->assertInstanceOf(
            Akaxin\Proto\Plugin\HaiGroupUpdateResponse::class,
            $response
        );

        $this->assertEquals(
            ERROR_CODE_SUCCESS,
            $client->errorCode(),
            "errorInfo:" . $client->errorInfo()
        );
        $this->assertEmpty($client->errorInfo());

    }

    //错误id测试
    public function testHaiGroupUpdateRequest_WrongId(): void
    {
        //groupB是错误id
        $groupB = "10008";

        $client = getApiClient();
        $request = new Akaxin\Proto\Plugin\HaiGroupUpdateRequest();
        $profile = new Akaxin\Proto\Core\GroupProfile();
        $profile->setId($groupB);
        $profile->setName("测试群组2");
        $request->setProfile($profile);

        $responseData = $client->request("/hai/group/update", $request);
        $response = new Akaxin\Proto\Plugin\HaiGroupUpdateResponse();
        $response->mergeFromString($responseData);

        $this->assertInstanceOf(
            Akaxin\Proto\Plugin\HaiGroupUpdateResponse::class,
            $response
        );

        $this->assertNotEquals(
            ERROR_CODE_SUCCESS,
            $client->errorCode()
        );

    }

}
