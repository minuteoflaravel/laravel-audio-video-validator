<?php

namespace MinuteOfLaravel\MediaValidator\Traits;

use ProtoneMedia\LaravelFFMpeg\FFMpeg\FFProbe;

trait MediaFile {

    private $ffprobe;
    private $audioStream;
    private $videoStream;

    private function createFFProbe() {
        $this->ffprobe = FFProbe::create([
            'ffprobe.binaries' => [
                'ffprobe',
                '/usr/bin/ffprobe',
                '/usr/local/bin/ffprobe',
                'avprobe',
                '/usr/bin/avprobe',
                '/usr/local/bin/avprobe',
            ],
        ]);
    }

    private function isAudio($file)
    {
        return $this->mediaHasAudio($file) && !$this->mediaHasVideo($file);
    }

    private function isVideo($file)
    {
        return $this->mediaHasAudio($file) && $this->mediaHasVideo($file);
    }

    private function mediaHasAudio($file) {
        try {
            $stream = $this->ffprobe
                ->streams($file)
                ->audios()
                ->first();
        } catch (\Throwable $e) {
            return false;
        }

        if ($stream === null) {
            return false;
        }

        $this->setAudioStream($stream);

        return true;
    }

    private function mediaHasVideo($file) {
        try {
            $stream = $this->ffprobe
                ->streams($file)
                ->videos()
                ->first();
        } catch (\Throwable $e) {
            return false;
        }

        if ($stream === null) {
            return false;
        }

        $this->setVideoStream($stream);

        return true;
    }

    public function setAudioStream($stream) {
        $this->audioStream = $stream;
    }

    public function getAudioStream() {
        return $this->audioStream;
    }

    public function setVideoStream($stream) {
        $this->videoStream = $stream;
    }

    public function getVideoStream() {
        return $this->videoStream;
    }

    private function getMediaDuration($file) {
        if ($this->isAudio($file)) {
            return (int) $this->getAudioStream()->get('duration');
        }

        if ($this->isVideo($file)) {
            return (int) $this->getVideoStream()->get('duration');
        }

        return false;
    }

    private function getMediaDimensions($file) {
        if (!$this->isVideo($file)) return false;

        return [
            'width' => $this->getVideoStream()->get('width'),
            'height' => $this->getVideoStream()->get('height'),
        ];
    }
}
