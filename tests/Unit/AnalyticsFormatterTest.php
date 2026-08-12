<?php

namespace Mynaparrot\Plugnmeet\Tests\Unit;

use Mynaparrot\Plugnmeet\AnalyticsFormatter;
use PHPUnit\Framework\TestCase;

class AnalyticsFormatterTest extends TestCase
{
    private function loadFixture(string $name): array
    {
        $path = __DIR__ . '/../fixtures/' . $name;
        $data = json_decode(file_get_contents($path), true);
        if (!is_array($data)) {
            throw new \RuntimeException("Failed to load fixture: $name");
        }
        return $data;
    }

    // ── formatUserJoinDuration ──────────────────────────────────────────

    public function testDurationHandlesDuplicateJoinsCorrectly(): void
    {
        // 6 joins, 4 leaves. Without the fix duplicate joins inflated duration.
        $data = $this->loadFixture('analytics_japleen.json');
        $formatter = new AnalyticsFormatter($data, 'UTC');
        $result = $formatter->getFormattedEventData();

        $user = $result['users'][0];
        $duration = $user['duration'];

        // Room duration is 3000 seconds.
        // With 6 joins and 4 leaves, 2 joins are duplicates and should be skipped.
        // Valid sessions: join1→leave1, join2→leave2, join6→leave4 (joins 3-5 skipped)
        // Duration should be well under room_duration (3000s).
        $this->assertGreaterThan(0, $duration, 'Duration should be positive');
        $this->assertLessThan(3000, $duration, 'Duration must not be inflated by duplicate joins');
        $this->assertLessThanOrEqual(3000, $duration, 'Duration must not exceed room duration');
    }

    public function testDurationZeroWhenNoJoins(): void
    {
        $data = [
            'room' => ['room_id' => 'test', 'room_creation' => '1000', 'room_ended' => '2000', 'room_duration' => '1000'],
            'users' => [
                ['user_id' => '1', 'name' => 'Test', 'is_admin' => false, 'ex_user_id' => '1', 'events' => []]
            ]
        ];
        $formatter = new AnalyticsFormatter($data, 'UTC');
        $result = $formatter->getFormattedEventData();
        $this->assertEquals(0, $result['users'][0]['duration']);
    }

    public function testDurationSingleSession(): void
    {
        $data = [
            'room' => ['room_id' => 'test', 'room_creation' => '1000', 'room_ended' => '3000', 'room_duration' => '2000'],
            'users' => [
                [
                    'user_id' => '1', 'name' => 'Test', 'is_admin' => false, 'ex_user_id' => '1',
                    'events' => [
                        ['name' => 'joined', 'total' => 1, 'values' => [['time' => '1500000', 'value' => '1500000']]],
                        ['name' => 'left', 'total' => 1, 'values' => [['time' => '2500000', 'value' => '2500000']]],
                    ]
                ]
            ]
        ];
        $formatter = new AnalyticsFormatter($data, 'UTC');
        $result = $formatter->getFormattedEventData();
        // 2500000 - 1500000 = 1000000 ms = 1000 seconds
        $this->assertEquals(1000, $result['users'][0]['duration']);
    }

    public function testDurationUnfinishedSessionExtendsToRoomEnd(): void
    {
        $data = [
            'room' => ['room_id' => 'test', 'room_creation' => '1000', 'room_ended' => '3000', 'room_duration' => '2000'],
            'users' => [
                [
                    'user_id' => '1', 'name' => 'Test', 'is_admin' => false, 'ex_user_id' => '1',
                    'events' => [
                        ['name' => 'joined', 'total' => 1, 'values' => [['time' => '1500000', 'value' => '1500000']]],
                    ]
                ]
            ]
        ];
        $formatter = new AnalyticsFormatter($data, 'UTC');
        $result = $formatter->getFormattedEventData();
        // 3000000 - 1500000 = 1500000 ms = 1500 seconds
        $this->assertEquals(1500, $result['users'][0]['duration']);
    }

    public function testDurationIgnoresUnmatchedLeaves(): void
    {
        // Leave without prior join should contribute nothing.
        $data = [
            'room' => ['room_id' => 'test', 'room_creation' => '1000', 'room_ended' => '3000', 'room_duration' => '2000'],
            'users' => [
                [
                    'user_id' => '1', 'name' => 'Test', 'is_admin' => false, 'ex_user_id' => '1',
                    'events' => [
                        ['name' => 'left', 'total' => 1, 'values' => [['time' => '2000000', 'value' => '2000000']]],
                        ['name' => 'joined', 'total' => 1, 'values' => [['time' => '2500000', 'value' => '2500000']]],
                    ]
                ]
            ]
        ];
        $formatter = new AnalyticsFormatter($data, 'UTC');
        $result = $formatter->getFormattedEventData();
        // Only session: 3000000 - 2500000 = 500000 ms = 500 seconds
        $this->assertEquals(500, $result['users'][0]['duration']);
    }

    public function testDurationMultipleSessions(): void
    {
        // Two complete sessions.
        $data = [
            'room' => ['room_id' => 'test', 'room_creation' => '1000', 'room_ended' => '5000', 'room_duration' => '4000'],
            'users' => [
                [
                    'user_id' => '1', 'name' => 'Test', 'is_admin' => false, 'ex_user_id' => '1',
                    'events' => [
                        ['name' => 'joined', 'total' => 2, 'values' => [
                            ['time' => '1500000', 'value' => '1500000'],
                            ['time' => '3500000', 'value' => '3500000'],
                        ]],
                        ['name' => 'left', 'total' => 2, 'values' => [
                            ['time' => '2500000', 'value' => '2500000'],
                            ['time' => '4500000', 'value' => '4500000'],
                        ]],
                    ]
                ]
            ]
        ];
        $formatter = new AnalyticsFormatter($data, 'UTC');
        $result = $formatter->getFormattedEventData();
        // Session 1: 1000s, Session 2: 1000s, Total: 2000s
        $this->assertEquals(2000, $result['users'][0]['duration']);
    }

    // ── getDurationFromEvents ───────────────────────────────────────────

    public function testDeviceDurationCalculatedCorrectly(): void
    {
        $data = [
            'room' => ['room_id' => 'test', 'room_creation' => '1000', 'room_ended' => '5000', 'room_duration' => '4000'],
            'users' => [
                [
                    'user_id' => '1', 'name' => 'Test', 'is_admin' => false, 'ex_user_id' => '1',
                    'events' => [
                        ['name' => 'mic_status', 'total' => 2, 'values' => [
                            ['time' => '1500000', 'value' => 'ANALYTICS_STATUS_STARTED'],
                            ['time' => '2500000', 'value' => 'ANALYTICS_STATUS_ENDED'],
                        ]],
                    ]
                ]
            ]
        ];
        $formatter = new AnalyticsFormatter($data, 'UTC');
        $result = $formatter->getFormattedEventData();
        // 2500000 - 1500000 = 1000000 ms = 1000 seconds
        $this->assertEquals(1000, $result['users'][0]['mic_duration']);
    }

    public function testDeviceDurationClosesAtUserLeave(): void
    {
        // Mic started, user left before mic ended. Duration = leave - start.
        $data = [
            'room' => ['room_id' => 'test', 'room_creation' => '1000', 'room_ended' => '5000', 'room_duration' => '4000'],
            'users' => [
                [
                    'user_id' => '1', 'name' => 'Test', 'is_admin' => false, 'ex_user_id' => '1',
                    'events' => [
                        ['name' => 'joined', 'total' => 1, 'values' => [['time' => '1400000', 'value' => '1400000']]],
                        ['name' => 'left', 'total' => 1, 'values' => [['time' => '2000000', 'value' => '2000000']]],
                        ['name' => 'mic_status', 'total' => 2, 'values' => [
                            ['time' => '1500000', 'value' => 'ANALYTICS_STATUS_STARTED'],
                            ['time' => '4000000', 'value' => 'ANALYTICS_STATUS_ENDED'],
                        ]],
                    ]
                ]
            ]
        ];
        $formatter = new AnalyticsFormatter($data, 'UTC');
        $result = $formatter->getFormattedEventData();
        // Mic should close at user leave (2000000), not at room end or mic end.
        // 2000000 - 1500000 = 500000 ms = 500 seconds
        $this->assertEquals(500, $result['users'][0]['mic_duration']);
    }

    public function testDeviceDurationHandlesPrefixedValues(): void
    {
        // Values like "ANALYTICS_STATUS_STARTED:node_01" should match.
        $data = [
            'room' => ['room_id' => 'test', 'room_creation' => '1000', 'room_ended' => '5000', 'room_duration' => '4000'],
            'users' => [
                [
                    'user_id' => '1', 'name' => 'Test', 'is_admin' => false, 'ex_user_id' => '1',
                    'events' => [
                        ['name' => 'webcam_status', 'total' => 2, 'values' => [
                            ['time' => '1500000', 'value' => 'ANALYTICS_STATUS_STARTED:node_01'],
                            ['time' => '2500000', 'value' => 'ANALYTICS_STATUS_ENDED'],
                        ]],
                    ]
                ]
            ]
        ];
        $formatter = new AnalyticsFormatter($data, 'UTC');
        $result = $formatter->getFormattedEventData();
        // Should match the prefixed START value
        $this->assertEquals(1000, $result['users'][0]['webcam_duration']);
    }

    // ── clampDuration / getRoomDurationLimit ────────────────────────────

    public function testDurationCappedAtRoomDuration(): void
    {
        // Create a join at 0 and no leave → duration would be room_ended - 0,
        // but room_duration is the cap.
        $data = [
            'room' => ['room_id' => 'test', 'room_creation' => '1000', 'room_ended' => '3000', 'room_duration' => '500'],
            'users' => [
                [
                    'user_id' => '1', 'name' => 'Test', 'is_admin' => false, 'ex_user_id' => '1',
                    'events' => [
                        ['name' => 'joined', 'total' => 1, 'values' => [['time' => '1100000', 'value' => '1100000']]],
                    ]
                ]
            ]
        ];
        $formatter = new AnalyticsFormatter($data, 'UTC');
        $result = $formatter->getFormattedEventData();
        // room_ended - join = 1900s, but room_duration = 500s, so capped at 500
        $this->assertEquals(500, $result['users'][0]['duration']);
    }

    public function testDurationPreservedWhenRoomMetadataMissing(): void
    {
        // No room_duration or room_ended → should return raw calculated duration.
        $data = [
            'room' => ['room_id' => 'test', 'room_creation' => '1000'],
            'users' => [
                [
                    'user_id' => '1', 'name' => 'Test', 'is_admin' => false, 'ex_user_id' => '1',
                    'events' => [
                        ['name' => 'joined', 'total' => 1, 'values' => [['time' => '1500000', 'value' => '1500000']]],
                        ['name' => 'left', 'total' => 1, 'values' => [['time' => '2500000', 'value' => '2500000']]],
                    ]
                ]
            ]
        ];
        $formatter = new AnalyticsFormatter($data, 'UTC');
        $result = $formatter->getFormattedEventData();
        // Should still calculate correctly: 1000 seconds
        $this->assertEquals(1000, $result['users'][0]['duration']);
    }

    // ── talk duration ───────────────────────────────────────────────────

    public function testTalkedDurationClamped(): void
    {
        // talked_duration total is in milliseconds, should be converted and clamped.
        $data = [
            'room' => ['room_id' => 'test', 'room_duration' => '10'],
            'users' => [
                [
                    'user_id' => '1', 'name' => 'Test', 'is_admin' => false, 'ex_user_id' => '1',
                    'events' => [
                        ['name' => 'talked_duration', 'total' => '50000', 'values' => []],
                    ]
                ]
            ]
        ];
        $formatter = new AnalyticsFormatter($data, 'UTC');
        $result = $formatter->getFormattedEventData();
        // 50000 ms = 50 seconds, but room_duration = 10s, so clamped to 10
        $this->assertEquals(10, $result['users'][0]['talked_duration']);
    }

    // ── formatTimestamp ─────────────────────────────────────────────────

    public function testFormatTimestampWithMilliseconds(): void
    {
        $data = ['room' => [], 'users' => []];
        $formatter = new AnalyticsFormatter($data, 'UTC');
        // 1779058704000 ms → 1779058704 seconds
        $result = $formatter->formatTimestamp('1779058704000', true);
        $this->assertStringContainsString('17-05-2026', $result);
        $this->assertStringContainsString('+00:00', $result);
    }

    public function testFormatTimestampWithoutMilliseconds(): void
    {
        $data = ['room' => [], 'users' => []];
        $formatter = new AnalyticsFormatter($data, 'UTC');
        $result = $formatter->formatTimestamp('1779058704', false);
        $this->assertStringContainsString('17-05-2026', $result);
    }

    public function testFormatTimestampReturnsEmptyForNonNumeric(): void
    {
        $data = ['room' => [], 'users' => []];
        $formatter = new AnalyticsFormatter($data, 'UTC');
        $this->assertEquals('', $formatter->formatTimestamp('not-a-number'));
    }

    // ── formatSecondsToTime ─────────────────────────────────────────────

    public function testFormatSecondsToTime(): void
    {
        $data = ['room' => [], 'users' => []];
        $formatter = new AnalyticsFormatter($data, 'UTC');
        $this->assertEquals('00:00:00', $formatter->formatSecondsToTime(0));
        $this->assertEquals('00:01:30', $formatter->formatSecondsToTime(90));
        $this->assertEquals('02:05:46', $formatter->formatSecondsToTime(7546));
    }

    public function testFormatSecondsToTimeHandlesNegative(): void
    {
        $data = ['room' => [], 'users' => []];
        $formatter = new AnalyticsFormatter($data, 'UTC');
        $this->assertEquals('00:00:00', $formatter->formatSecondsToTime(-100));
    }

    // ── Timezone fallback ───────────────────────────────────────────────

    public function testInvalidTimezoneFallsBackToUtc(): void
    {
        $data = ['room' => [], 'users' => []];
        $formatter = new AnalyticsFormatter($data, 'Invalid/Timezone');
        $result = $formatter->formatTimestamp('1779058704000', true);
        // Should still work, using UTC
        $this->assertStringContainsString('+00:00', $result);
    }

    // ── Edge cases ──────────────────────────────────────────────────────

    public function testEmptyRawDataDoesNotCrash(): void
    {
        $formatter = new AnalyticsFormatter(['room' => [], 'users' => []], 'UTC');
        $result = $formatter->getFormattedEventData();
        $this->assertIsArray($result);
        $this->assertArrayHasKey('room', $result);
        $this->assertArrayHasKey('users', $result);
        $this->assertEmpty($result['users']);
    }

    public function testEventsOutsideRoomBoundsIgnored(): void
    {
        // Join before room creation should be clamped to room start.
        $data = [
            'room' => ['room_id' => 'test', 'room_creation' => '2000', 'room_ended' => '5000', 'room_duration' => '3000'],
            'users' => [
                [
                    'user_id' => '1', 'name' => 'Test', 'is_admin' => false, 'ex_user_id' => '1',
                    'events' => [
                        ['name' => 'joined', 'total' => 1, 'values' => [['time' => '500000', 'value' => '500000']]],
                        ['name' => 'left', 'total' => 1, 'values' => [['time' => '3000000', 'value' => '3000000']]],
                    ]
                ]
            ]
        ];
        $formatter = new AnalyticsFormatter($data, 'UTC');
        $result = $formatter->getFormattedEventData();
        // Join at 500s before room_creation (2000s). Join clamped to 0 relative, left at 3000s.
        // But since join < roomStart, it's skipped entirely. So only left event - no active session.
        // The left without join → ignored. Duration = 0.
        $this->assertEquals(0, $result['users'][0]['duration']);
    }

    public function testDuplicateStartEventsIgnoredForDeviceDuration(): void
    {
        // Two START events without END between them → only first START counts.
        $data = [
            'room' => ['room_id' => 'test', 'room_creation' => '1000', 'room_ended' => '5000', 'room_duration' => '4000'],
            'users' => [
                [
                    'user_id' => '1', 'name' => 'Test', 'is_admin' => false, 'ex_user_id' => '1',
                    'events' => [
                        ['name' => 'mic_status', 'total' => 3, 'values' => [
                            ['time' => '1500000', 'value' => 'ANALYTICS_STATUS_STARTED'],
                            ['time' => '2000000', 'value' => 'ANALYTICS_STATUS_STARTED'],
                            ['time' => '2500000', 'value' => 'ANALYTICS_STATUS_ENDED'],
                        ]],
                    ]
                ]
            ]
        ];
        $formatter = new AnalyticsFormatter($data, 'UTC');
        $result = $formatter->getFormattedEventData();
        // Should use first START: 2500000 - 1500000 = 1000000 ms = 1000s
        // Not second START: 2500000 - 2000000 = 500s
        $this->assertEquals(1000, $result['users'][0]['mic_duration']);
    }

    public function testGetUserFieldsReturnsExpectedFields(): void
    {
        $formatter = new AnalyticsFormatter(['room' => [], 'users' => []], 'UTC');
        $fields = $formatter->getUserFields();
        $this->assertContains('duration', $fields);
        $this->assertContains('talked_duration', $fields);
        $this->assertContains('mic_duration', $fields);
        $this->assertContains('webcam_duration', $fields);
        $this->assertContains('joined', $fields);
        $this->assertContains('left', $fields);
    }

    public function testGetRoomFieldsReturnsExpectedFields(): void
    {
        $formatter = new AnalyticsFormatter(['room' => [], 'users' => []], 'UTC');
        $fields = $formatter->getRoomFields();
        $this->assertContains('room_duration', $fields);
        $this->assertContains('room_creation', $fields);
        $this->assertContains('room_ended', $fields);
    }
}
