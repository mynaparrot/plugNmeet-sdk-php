<?php

/*
 * Copyright (c) 2022 MynaParrot
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

namespace Mynaparrot\Plugnmeet\Parameters;

/**
 *
 */
class RoomFeaturesParameters
{
    /**
     * @var bool
     */
    protected $allowWebcams = true;
    /**
     * @var bool
     */
    protected $muteOnStart = false;
    /**
     * @var bool
     */
    protected $allowScreenShare = true;
    /**
     * @var bool
     */
    protected $allowRTMP = true;
    /**
     * @var bool
     */
    protected $adminOnlyWebcams = false;
    /**
     * @var bool
     */
    protected $allowViewOtherWebcams = true;
    /**
     * @var bool
     */
    protected $allowViewOtherParticipants = true;
    /**
     * @var bool
     */
    protected $allowPolls = true;
    /**
     * @var int
     */
    protected $roomDuration = 0;
    /**
     * @var bool
     */
    protected $enableAnalytics = false;
    /**
     * @var bool
     */
    protected $allowVirtualBg = true;
    /**
     * @var bool
     */
    protected $allowRaiseHand = true;
    /**
     * @var RecordingFeaturesParameters
     */
    protected $recordingFeatures;
    /**
     * @var ChatFeaturesParameters
     */
    protected $chatFeatures;
    /**
     * @var SharedNotePadFeaturesParameters
     */
    protected $sharedNotePadFeatures;
    /**
     * @var WhiteboardFeaturesParameters
     */
    protected $whiteboardFeatures;
    /**
     * @var ExternalMediaPlayerFeaturesParameters
     */
    protected $externalMediaPlayerFeatures;

    /**
     * @var WaitingRoomFeaturesParameters
     */
    protected $waitingRoomFeatures;

    /**
     * @var BreakoutRoomFeaturesParameters
     */
    protected $breakoutRoomFeatures;

    /**
     * @var DisplayExternalLinkFeaturesParameters
     */
    protected $displayExternalLinkFeatures;

    /**
     * @var IngressFeaturesParameters
     */
    protected $ingressFeatures;

    /**
     * @var SpeechToTextTranslationFeaturesParameters
     */
    protected $speechToTextTranslationFeatures;

    /**
     * @var EndToEndEncryptionFeaturesParameters
     */
    protected $endToEndEncryptionFeatures;

    /**
     *
     */
    public function __construct()
    {
    }

    /**
     * @return bool
     */
    public function isAllowWebcams(): bool
    {
        return $this->allowWebcams;
    }

    /**
     * @param bool $allowWebcams
     */
    public function setAllowWebcams(bool $allowWebcams)
    {
        $this->allowWebcams = filter_var($allowWebcams, FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * @return bool
     */
    public function isMuteOnStart(): bool
    {
        return $this->muteOnStart;
    }

    /**
     * @param bool $muteOnStart
     */
    public function setMuteOnStart(bool $muteOnStart): void
    {
        $this->muteOnStart = filter_var($muteOnStart, FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * @return bool
     */
    public function isAllowScreenShare(): bool
    {
        return $this->allowScreenShare;
    }

    /**
     * @param bool $allowScreenShare
     */
    public function setAllowScreenShare(bool $allowScreenShare)
    {
        $this->allowScreenShare = filter_var($allowScreenShare, FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * @return bool
     */
    public function isAllowRTMP(): bool
    {
        return $this->allowRTMP;
    }

    /**
     * @param bool $allowRTMP
     */
    public function setAllowRTMP(bool $allowRTMP)
    {
        $this->allowRTMP = filter_var($allowRTMP, FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * @return bool
     */
    public function isAdminOnlyWebcams(): bool
    {
        return $this->adminOnlyWebcams;
    }

    /**
     * @param bool $adminOnlyWebcams
     */
    public function setAdminOnlyWebcams(bool $adminOnlyWebcams)
    {
        $this->adminOnlyWebcams = filter_var($adminOnlyWebcams, FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * @return bool
     */
    public function isAllowViewOtherWebcams(): bool
    {
        return $this->allowViewOtherWebcams;
    }

    /**
     * @param bool $allowViewOtherWebcams
     */
    public function setAllowViewOtherWebcams(bool $allowViewOtherWebcams): void
    {
        $this->allowViewOtherWebcams = filter_var($allowViewOtherWebcams, FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * @return bool
     */
    public function isAllowViewOtherParticipants(): bool
    {
        return $this->allowViewOtherParticipants;
    }

    /**
     * @param bool $allowViewOtherParticipants
     */
    public function setAllowViewOtherParticipants(bool $allowViewOtherParticipants): void
    {
        $this->allowViewOtherParticipants = filter_var($allowViewOtherParticipants, FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * @return bool
     */
    public function isAllowPolls(): bool
    {
        return $this->allowPolls;
    }

    /**
     * @param bool $allowPolls
     */
    public function setAllowPolls(bool $allowPolls): void
    {
        $this->allowPolls = filter_var($allowPolls, FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * @return int
     */
    public function getRoomDuration(): int
    {
        return $this->roomDuration;
    }

    /**
     * @param int $roomDuration
     */
    public function setRoomDuration(int $roomDuration): void
    {
        if ($roomDuration > 0) {
            $this->roomDuration = $roomDuration;
        }
    }

    /**
     * @return bool
     */
    public function isEnableAnalytics(): bool
    {
        return $this->enableAnalytics;
    }

    /**
     * @param bool $enableAnalytics
     */
    public function setEnableAnalytics(bool $enableAnalytics): void
    {
        $this->enableAnalytics = filter_var($enableAnalytics, FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * @return bool
     */
    public function isAllowVirtualBg(): bool
    {
        return $this->allowVirtualBg;
    }

    /**
     * @param bool $allowVirtualBg
     * @return void
     */
    public function setAllowVirtualBg(bool $allowVirtualBg): void
    {
        $this->allowVirtualBg = filter_var($allowVirtualBg, FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * @return bool
     */
    public function isAllowRaiseHand(): bool
    {
        return $this->allowRaiseHand;
    }

    /**
     * @param bool $allowRaiseHand
     * @return void
     */
    public function setAllowRaiseHand(bool $allowRaiseHand): void
    {
        $this->allowRaiseHand = filter_var($allowRaiseHand, FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * @return RecordingFeaturesParameters
     */
    public function getRecordingFeatures(): RecordingFeaturesParameters
    {
        return $this->recordingFeatures;
    }

    /**
     * @param RecordingFeaturesParameters $recordingFeatures
     */
    public function setRecordingFeatures(RecordingFeaturesParameters $recordingFeatures): void
    {
        $this->recordingFeatures = $recordingFeatures;
    }

    /**
     * @return ChatFeaturesParameters
     */
    public function getChatFeatures(): ChatFeaturesParameters
    {
        return $this->chatFeatures;
    }

    /**
     * @param ChatFeaturesParameters $chatFeatures
     */
    public function setChatFeatures(ChatFeaturesParameters $chatFeatures)
    {
        $this->chatFeatures = $chatFeatures;
    }

    /**
     * @return SharedNotePadFeaturesParameters
     */
    public function getSharedNotePadFeatures(): SharedNotePadFeaturesParameters
    {
        return $this->sharedNotePadFeatures;
    }

    /**
     * @param SharedNotePadFeaturesParameters $sharedNotePadFeatures
     */
    public function setSharedNotePadFeatures(SharedNotePadFeaturesParameters $sharedNotePadFeatures): void
    {
        $this->sharedNotePadFeatures = $sharedNotePadFeatures;
    }

    /**
     * @return WhiteboardFeaturesParameters
     */
    public function getWhiteboardFeatures(): WhiteboardFeaturesParameters
    {
        return $this->whiteboardFeatures;
    }

    /**
     * @param WhiteboardFeaturesParameters $whiteboardFeatures
     */
    public function setWhiteboardFeatures(WhiteboardFeaturesParameters $whiteboardFeatures): void
    {
        $this->whiteboardFeatures = $whiteboardFeatures;
    }

    /**
     * @return ExternalMediaPlayerFeaturesParameters
     */
    public function getExternalMediaPlayerFeatures(): ExternalMediaPlayerFeaturesParameters
    {
        return $this->externalMediaPlayerFeatures;
    }

    /**
     * @param ExternalMediaPlayerFeaturesParameters $externalMediaPlayerFeatures
     */
    public function setExternalMediaPlayerFeatures(
        ExternalMediaPlayerFeaturesParameters $externalMediaPlayerFeatures
    ): void {
        $this->externalMediaPlayerFeatures = $externalMediaPlayerFeatures;
    }

    /**
     * @return WaitingRoomFeaturesParameters
     */
    public function getWaitingRoomFeatures(): WaitingRoomFeaturesParameters
    {
        return $this->waitingRoomFeatures;
    }

    /**
     * @param WaitingRoomFeaturesParameters $waitingRoomFeatures
     */
    public function setWaitingRoomFeatures(WaitingRoomFeaturesParameters $waitingRoomFeatures): void
    {
        $this->waitingRoomFeatures = $waitingRoomFeatures;
    }

    /**
     * @return BreakoutRoomFeaturesParameters
     */
    public function getBreakoutRoomFeatures(): BreakoutRoomFeaturesParameters
    {
        return $this->breakoutRoomFeatures;
    }

    /**
     * @param BreakoutRoomFeaturesParameters $breakoutRoomFeatures
     */
    public function setBreakoutRoomFeatures(BreakoutRoomFeaturesParameters $breakoutRoomFeatures): void
    {
        $this->breakoutRoomFeatures = $breakoutRoomFeatures;
    }

    /**
     * @return DisplayExternalLinkFeaturesParameters
     */
    public function getDisplayExternalLinkFeatures(): DisplayExternalLinkFeaturesParameters
    {
        return $this->displayExternalLinkFeatures;
    }

    /**
     * @param DisplayExternalLinkFeaturesParameters $displayExternalLinkFeatures
     */
    public function setDisplayExternalLinkFeatures(
        DisplayExternalLinkFeaturesParameters $displayExternalLinkFeatures
    ): void {
        $this->displayExternalLinkFeatures = $displayExternalLinkFeatures;
    }

    /**
     * @return IngressFeaturesParameters
     */
    public function getIngressFeatures(): IngressFeaturesParameters
    {
        return $this->ingressFeatures;
    }

    /**
     * @param IngressFeaturesParameters $ingressFeatures
     */
    public function setIngressFeatures(IngressFeaturesParameters $ingressFeatures): void
    {
        $this->ingressFeatures = $ingressFeatures;
    }

    /**
     * @return SpeechToTextTranslationFeaturesParameters
     */
    public function getSpeechToTextTranslationFeatures(): SpeechToTextTranslationFeaturesParameters
    {
        return $this->speechToTextTranslationFeatures;
    }

    /**
     * @param SpeechToTextTranslationFeaturesParameters $speechToTextTranslationFeatures
     */
    public function setSpeechToTextTranslationFeatures(
        SpeechToTextTranslationFeaturesParameters $speechToTextTranslationFeatures
    ): void {
        $this->speechToTextTranslationFeatures = $speechToTextTranslationFeatures;
    }

    /**
     * @return EndToEndEncryptionFeaturesParameters
     */
    public function getEndToEndEncryptionFeatures(): EndToEndEncryptionFeaturesParameters
    {
        return $this->endToEndEncryptionFeatures;
    }

    /**
     * @param EndToEndEncryptionFeaturesParameters $endToEndEncryptionFeatures
     */
    public function setEndToEndEncryptionFeatures(
        EndToEndEncryptionFeaturesParameters $endToEndEncryptionFeatures
    ): void {
        $this->endToEndEncryptionFeatures = $endToEndEncryptionFeatures;
    }

    /**
     * @return array
     */
    public function buildBody(): array
    {
        $body = array(
            "allow_webcams" => $this->isAllowWebcams(),
            "mute_on_start" => $this->isMuteOnStart(),
            "allow_screen_share" => $this->isAllowScreenShare(),
            "allow_rtmp" => $this->isAllowRTMP(),
            "admin_only_webcams" => $this->isAdminOnlyWebcams(),
            "allow_view_other_webcams" => $this->isAllowViewOtherWebcams(),
            "allow_view_other_users_list" => $this->isAllowViewOtherParticipants(),
            "allow_polls" => $this->isAllowPolls(),
            "room_duration" => $this->getRoomDuration(),
            "enable_analytics" => $this->isEnableAnalytics(),
            "allow_virtual_bg" => $this->isAllowVirtualBg(),
            "allow_raise_hand" => $this->isAllowRaiseHand()
        );

        if ($this->recordingFeatures !== null) {
            $body['recording_features'] = $this->getRecordingFeatures()->buildBody();
        }

        if ($this->chatFeatures !== null) {
            $body['chat_features'] = $this->getChatFeatures()->buildBody();
        }

        if ($this->sharedNotePadFeatures !== null) {
            $body['shared_note_pad_features'] = $this->getSharedNotePadFeatures()->buildBody();
        }

        if ($this->whiteboardFeatures !== null) {
            $body['whiteboard_features'] = $this->getWhiteboardFeatures()->buildBody();
        }

        if ($this->externalMediaPlayerFeatures !== null) {
            $body['external_media_player_features'] = $this->getExternalMediaPlayerFeatures()->buildBody();
        }

        if ($this->waitingRoomFeatures !== null) {
            $body['waiting_room_features'] = $this->getWaitingRoomFeatures()->buildBody();
        }

        if ($this->breakoutRoomFeatures !== null) {
            $body['breakout_room_features'] = $this->getBreakoutRoomFeatures()->buildBody();
        }

        if ($this->displayExternalLinkFeatures !== null) {
            $body['display_external_link_features'] = $this->getDisplayExternalLinkFeatures()->buildBody();
        }

        if ($this->ingressFeatures !== null) {
            $body['ingress_features'] = $this->getIngressFeatures()->buildBody();
        }

        if ($this->speechToTextTranslationFeatures !== null) {
            $body['speech_to_text_translation_features'] = $this->getSpeechToTextTranslationFeatures()->buildBody();
        }

        if ($this->endToEndEncryptionFeatures !== null) {
            $body['end_to_end_encryption_features'] = $this->getEndToEndEncryptionFeatures()->buildBody();
        }

        return $body;
    }
}
