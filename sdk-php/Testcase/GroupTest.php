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

    /**
     * @afterClass
     */
    public static function tearDownEnv()
    {
        Context::getInstance()->restartServer();
    }

    public function testHaiGroupAddMemberRequest(): void
    {
        // 获取用户ID
        $userA = Context::getInstance()->getUserA();
        $userB = Context::getInstance()->getUserB();
        $userC = Context::getInstance()->getUserC();
        $userD = Context::getInstance()->getUserD();
        $groupA = Context::getInstance()->getGroupA();
        $admin = Context::getInstance()->getAdminUserID();

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

    public function testHaiGroupAddMemberRequest_already(): void
    {
        // 获取用户ID
        $userA = Context::getInstance()->getUserA();
        $userB = Context::getInstance()->getUserB();
        $userC = Context::getInstance()->getUserC();
        $userD = Context::getInstance()->getUserD();
        $groupA = Context::getInstance()->getGroupA();
        $admin = Context::getInstance()->getAdminUserID();

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

    public function testHaiGroupCheckMemberRequest(): void
    {
        // 获取用户ID
        $userA = Context::getInstance()->getUserA();
        $userB = Context::getInstance()->getUserB();
        $userC = Context::getInstance()->getUserC();
        $userD = Context::getInstance()->getUserD();
        $groupA = Context::getInstance()->getGroupA();
        $admin = Context::getInstance()->getAdminUserID();

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


    public function testHaiGroupRemoveMemberRequest(): void
    {
        // 获取用户ID
        $userA = Context::getInstance()->getUserA();
        $userB = Context::getInstance()->getUserB();
        $userC = Context::getInstance()->getUserC();
        $userD = Context::getInstance()->getUserD();
        $groupA = Context::getInstance()->getGroupA();
        $admin = Context::getInstance()->getAdminUserID();

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

    public function testHaiGroupRemoveMemberRequest_admin(): void
    {
        // 获取用户ID
        $userA = Context::getInstance()->getUserA();
        $userB = Context::getInstance()->getUserB();
        $userC = Context::getInstance()->getUserC();
        $userD = Context::getInstance()->getUserD();
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

        $this->assertEquals(
            ERROR_CODE_SUCCESS,
            $client->errorCode()
        );

        $this->assertEmpty($client->errorInfo());

    }

    public function testHaiGroupDeleteRequest(): void
    {
        // 获取用户ID
        $userA = Context::getInstance()->getUserA();
        $userB = Context::getInstance()->getUserB();
        $userC = Context::getInstance()->getUserC();
        $userD = Context::getInstance()->getUserD();
        $groupA = Context::getInstance()->getGroupA();
        $admin = Context::getInstance()->getAdminUserID();

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

    public function testHaiGroupListRequest(): void
    {
        // 获取用户ID
        $userA = Context::getInstance()->getUserA();
        $userB = Context::getInstance()->getUserB();
        $userC = Context::getInstance()->getUserC();
        $userD = Context::getInstance()->getUserD();
        $groupA = Context::getInstance()->getGroupA();
        $admin = Context::getInstance()->getAdminUserID();

        $client = getApiClient();

        $request = new Akaxin\Proto\Plugin\HaiGroupListRequest();
        $request->setSiteUserId();
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

//    查询c的群组
    public function testHaiGroupListRequest_C(): void
    {
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
        $this->assertEquals(
            0,
            count($groupProfiles)
        );
    }

    public function testHaiGroupMembersRequest(): void
    {
        // 获取用户ID
        $userA = Context::getInstance()->getUserA();
        $userB = Context::getInstance()->getUserB();
        $userC = Context::getInstance()->getUserC();
        $userD = Context::getInstance()->getUserD();
        $groupA = Context::getInstance()->getGroupA();
        $groupB = Context::getInstance()->getGroupB();
        $admin = Context::getInstance()->getAdminUserID();

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

    public function testHaiGroupNonmembersRequest(): void
    {
        // 获取用户ID
        $userA = Context::getInstance()->getUserA();
        $userB = Context::getInstance()->getUserB();
        $userC = Context::getInstance()->getUserC();
        $userD = Context::getInstance()->getUserD();
        $groupA = Context::getInstance()->getGroupA();
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
            count($groupMembers),
            0
        );
    }

    public function testHaiGroupProfileRequest(): void
    {
        // 获取用户ID
        $userA = Context::getInstance()->getUserA();
        $userB = Context::getInstance()->getUserB();
        $userC = Context::getInstance()->getUserC();
        $userD = Context::getInstance()->getUserD();
        $groupA = Context::getInstance()->getGroupA();
        $groupB = Context::getInstance()->getGroupB();
        $admin = Context::getInstance()->getAdminUserID();

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

    public function testHaiGroupUpdateRequest(): void
    {
        // 获取用户ID
        $userA = Context::getInstance()->getUserA();
        $userB = Context::getInstance()->getUserB();
        $userC = Context::getInstance()->getUserC();
        $userD = Context::getInstance()->getUserD();
        $groupA = Context::getInstance()->getGroupA();
        $groupB = Context::getInstance()->getGroupB();
        $admin = Context::getInstance()->getAdminUserID();

        $client = getApiClient();

        $request = new Akaxin\Proto\Plugin\HaiGroupUpdateRequest();
        $request->setGroupId($groupB);
        $profile = new Akaxin\Proto\Core\GroupProfile();
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
            $client->errorCode()
        );
        $this->assertEmpty($client->errorInfo());

    }


}
