<?php
// declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class HaiFriendTest extends TestCase
{
    public function testHaiFriendAddRequest(): void
    {

        $client = getApiClient();

        $request = new Akaxin\Proto\Plugin\HaiFriendAddRequest();
        $request->setSiteUserId(getSiteUserIdForTest());
        $request->setFriendSiteUserId(getFriendUserIdForTest());

        $responseData = $client->request("hai/friend/add", $request);

        $response = new Akaxin\Proto\Plugin\HaiFriendAddResponse();
        $response->mergeFromString($responseData);

        $this->assertInstanceOf(
            Akaxin\Proto\Plugin\HaiFriendAddResponse::class,
            $response
        );

        $this->assertEquals(
            $client->errorCode(),
            ERROR_CODE_SUCCESS
        );

        $this->assertEmpty($client->errorInfo());


    }

    // public function testHaiFriendAddRequest(): void
    // {
    //
    //     $client = getApiClient();
    //
    //     $request = new Akaxin\Proto\Plugin\HaiFriendAddRequest();
    //     $request->setSiteUserId(getSiteUserIdForTest());
    //     $request->setFriendSiteUserId(getFriendUserIdForTest());
    //
    //     $response = $client->request("hai/friend/add", $request);
    //
    //
    //     $this->assertInstanceOf(
    //         Akaxin\Proto\Plugin\HaiFriendAddResponse,
    //         $response
    //     );
    // }
}
