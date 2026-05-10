<?php

/*
 * Copyright (c) 2022 onward MynaParrot
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

namespace Mynaparrot\Plugnmeet;

use Exception;
use Google\Protobuf\Internal\DescriptorPool;
use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\Message;
use Mynaparrot\PlugnmeetProto\RoomCreateFeatures;

/**
 * A builder class to create RoomCreateFeatures objects from an array.
 * This class simplifies the process of creating complex RoomCreateFeatures objects
 * by allowing you to pass a simple associative array of settings.
 *
 * @package PlugNmeet
 */
class RoomCreateFeaturesBuilder
{
    /**
     * @var array
     */
    private array $roomMetadata;

    /**
     * @param array $roomMetadata An associative array of room metadata.
     * The keys of the array should correspond to the properties of the RoomCreateFeatures object.
     */
    public function __construct(array $roomMetadata)
    {
        $this->roomMetadata = $roomMetadata;
    }

    /**
     * Builds and returns a RoomCreateFeatures object from the provided features.
     *
     * @return RoomCreateFeatures The fully constructed RoomCreateFeatures object.
     * @throws Exception If the Protobuf class is not found or if there is an error setting a field.
     */
    public function build(): RoomCreateFeatures
    {
        // Features can be passed in `room_features` or as top-level keys in `$roomMetadata`.
        // We're doing this with all of our plugins
        // We'll merge them, with `room_features` taking precedence.
        $roomMetadataFeatures = $this->roomMetadata['room_features'] ?? [];
        foreach ($this->roomMetadata as $k => $data) {
            if ($k === "room_features" || $k === "default_lock_settings" || $k === "copyright_conf") {
                continue;
            }
            if (!isset($roomMetadataFeatures[$k])) {
                $roomMetadataFeatures[$k] = $data;
            }
        }

        return $this->buildProtoMessageFromArray($roomMetadataFeatures, RoomCreateFeatures::class);
    }

    /**
     * Builds a Protobuf message object from a user-provided array.
     *
     * This method recursively builds nested message structures. It leverages the
     * Protobuf message's setters to handle type conversions, ensuring that
     * values are cast to the correct type as defined in the .proto file.
     * It also correctly omits optional string fields that are empty.
     *
     * @template T of Message
     * @param array $data The input array with snake_case keys.
     * @param class-string<T> $protoClassFqn The fully qualified class name of the Protobuf message.
     *
     * @return T The populated Protobuf message object.
     * @throws Exception
     */
    private function buildProtoMessageFromArray(array $data, string $protoClassFqn): Message
    {
        if (!class_exists($protoClassFqn)) {
            throw new Exception("Protobuf class not found: " . $protoClassFqn);
        }
        $messageInstance = new $protoClassFqn();
        $pool = DescriptorPool::getGeneratedPool();
        $desc = $pool->getDescriptorByClassName($protoClassFqn);

        if (!$desc) {
            return $messageInstance;
        }

        foreach ($data as $key => $value) {
            try {
                $snakeKey = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $key));
                $field = $desc->getFieldByName($snakeKey);

                if (!$field) {
                    continue;
                }

                $type = $field->getType();
                $setter = $field->getSetter();

                if ($type === GPBType::MESSAGE) {
                    if (is_array($value) && !empty($value)) {
                        $subMessageClass = $field->getMessageType()->getClass();
                        $subResult = $this->buildProtoMessageFromArray($value, $subMessageClass);
                        $messageInstance->$setter($subResult);
                    }
                } elseif ($type === GPBType::STRING && $value === '') {
                    continue;
                } else {
                    $messageInstance->$setter($value);
                }
            } catch (Exception $e) {
                throw new Exception(
                    "Failed to set field '$key' on message '$protoClassFqn': " . $e->getMessage(),
                    0,
                    $e
                );
            }
        }

        return $messageInstance;
    }
}
