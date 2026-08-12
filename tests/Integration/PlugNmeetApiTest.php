<?php

namespace Mynaparrot\Plugnmeet\Tests\Integration;

use Mynaparrot\Plugnmeet\PlugNmeet;
use Mynaparrot\PlugnmeetProto\CreateRoomReq;
use Mynaparrot\PlugnmeetProto\GenerateTokenReq;
use Mynaparrot\PlugnmeetProto\GetActiveRoomInfoReq;
use Mynaparrot\PlugnmeetProto\IsRoomActiveReq;
use Mynaparrot\PlugnmeetProto\FetchRecordingsReq;
use Mynaparrot\PlugnmeetProto\RecordingInfoReq;
use Mynaparrot\PlugnmeetProto\RoomEndReq;
use Mynaparrot\PlugnmeetProto\RoomMetadata;
use Mynaparrot\PlugnmeetProto\RoomCreateFeatures;
use Mynaparrot\PlugnmeetProto\UserInfo;
use PHPUnit\Framework\TestCase;

class PlugNmeetApiTest extends TestCase
{
    private PlugNmeet $client;
    private string $testRoomId;
    private string $testRoomSid;

    protected function setUp(): void
    {
        $this->client = new PlugNmeet(
            'https://demo.plugnmeet.com',
            'plugnmeet',
            'zumyyYWqv7KR2kUqvYdq4z4sXg7XTBD2ljT6',
            30,
            true
        );

        $this->testRoomId = 'test-room-' . uniqid();
        $this->testRoomSid = 'test-sid-' . uniqid();
    }

    // ── Full lifecycle: create → join token → end ──────────────────────

    public function testFullRoomLifecycle(): void
    {
        // 1. Create a room
        $createReq = new CreateRoomReq();
        $createReq->setRoomId($this->testRoomId);
        $createReq->setMaxParticipants(10);
        $createReq->setEmptyTimeout(300);

        $roomFeatures = new RoomCreateFeatures();
        $roomFeatures->setAllowWebcams(true);
        $roomFeatures->setAllowScreenShare(true);

        $roomMetadata = new RoomMetadata();
        $roomMetadata->setRoomTitle('PHP SDK Test - ' . date('Y-m-d H:i:s'));
        $roomMetadata->setWelcomeMessage('Integration test room');
        $roomMetadata->setRoomFeatures($roomFeatures);
        $createReq->setMetadata($roomMetadata);

        $createRes = $this->client->createRoom($createReq);
        $this->assertTrue(
            $createRes->getStatus(),
            'Create room failed: ' . $createRes->getMsg()
        );

        $roomInfo = $createRes->getRoomInfo();
        $this->assertNotNull($roomInfo, 'Expected room info in create response');
        $roomId = $roomInfo->getRoomId();
        $roomSid = $roomInfo->getSid();
        $this->assertNotEmpty($roomId);
        $this->assertNotEmpty($roomSid);

        // 2. Verify room is active
        $activeReq = new IsRoomActiveReq();
        $activeReq->setRoomId($roomId);
        $activeRes = $this->client->isRoomActive($activeReq);
        $this->assertTrue($activeRes->getIsActive(), 'Room should be active after creation');

        // 3. Get join token
        $tokenReq = new GenerateTokenReq();
        $tokenReq->setRoomId($roomId);

        $userInfo = new UserInfo();
        $userInfo->setUserId('test-user-001');
        $userInfo->setName('Test User');
        $userInfo->setIsAdmin(true);
        $tokenReq->setUserInfo($userInfo);

        $tokenRes = $this->client->getJoinToken($tokenReq);
        $this->assertTrue(
            $tokenRes->getStatus(),
            'Get join token failed: ' . $tokenRes->getMsg()
        );
        $this->assertNotEmpty($tokenRes->getToken(), 'Join token should not be empty');

        // 4. End the room (cleanup)
        $endReq = new RoomEndReq();
        $endReq->setRoomId($roomId);
        $endRes = $this->client->endRoom($endReq);
        $this->assertTrue(
            $endRes->getStatus(),
            'End room failed: ' . $endRes->getMsg()
        );
    }

    // ── Read-only API checks ────────────────────────────────────────────

    public function testGetActiveRoomsInfoReturnsWellFormedResponse(): void
    {
        $res = $this->client->getActiveRoomsInfo();
        // Demo server may have no active rooms — that's fine.
        // The response should be well-formed regardless.
        $this->assertNotNull($res);
        $this->assertIsBool($res->getStatus());
        $this->assertIsString($res->getMsg());
    }

    public function testGetClientFiles(): void
    {
        $res = $this->client->getClientFiles();
        $this->assertNotNull($res);
        $this->assertTrue($res->getStatus(), 'getClientFiles failed: ' . $res->getMsg());
    }

    public function testIsRoomActiveReturnsFalseForNonexistentRoom(): void
    {
        $req = new IsRoomActiveReq();
        $req->setRoomId('nonexistent-room-' . uniqid());
        $res = $this->client->isRoomActive($req);
        $this->assertFalse($res->getIsActive());
    }

    public function testGetActiveRoomInfoHandlesNonexistentRoom(): void
    {
        $req = new GetActiveRoomInfoReq();
        $req->setRoomId('nonexistent-room-' . uniqid());
        $res = $this->client->getActiveRoomInfo($req);
        $this->assertFalse($res->getStatus(), 'Expected failure for nonexistent room');
    }

    public function testFetchRecordingsReturnsResult(): void
    {
        $req = new FetchRecordingsReq();
        $req->setRoomIds(['nonexistent-room-' . uniqid()]);
        $req->setFrom(0);
        $req->setLimit(5);
        try {
            $res = $this->client->fetchRecordings($req);
            $this->assertNotNull($res);
        } catch (\Exception $e) {
            $this->markTestSkipped('Demo server returned exception: ' . $e->getMessage());
        }
    }

    public function testRecordingInfoFailsForNonexistentRecording(): void
    {
        $req = new RecordingInfoReq();
        $req->setRecordId('nonexistent-record-' . uniqid());
        try {
            $res = $this->client->getRecordingInfo($req);
            $this->assertFalse($res->getStatus());
        } catch (\Exception $e) {
            $this->assertStringContainsStringIgnoringCase('not found', $e->getMessage());
        }
    }
}
