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
        $this->assertEmpty($groupProfiles);
    }
}
