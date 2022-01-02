<?php

namespace MinuteOfLaravel\MediaValidator;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Concerns\ValidatesAttributes;
use MinuteOfLaravel\MediaValidator\Traits\MediaFile;

class MediaValidator {

    use ValidatesAttributes, MediaFile;

    public function __construct()
    {
        $this->createFFProbe();
    }

    public static function boot() {
        self::addValidationRules();
        self::replaceMessages();
    }

    public static function addValidationRules() {
        Validator::extend(
            'audio',
            'MinuteOfLaravel\MediaValidator\MediaValidator@validateAudio',
            'The :attribute must be a audio.',
        );

        Validator::extend(
            'video',
            'MinuteOfLaravel\MediaValidator\MediaValidator@validateVideo',
            'The :attribute must be a video.',
        );

        Validator::extend(
            'codec',
            'MinuteOfLaravel\MediaValidator\MediaValidator@validateCodec',
            'The :attribute codec must be one of these: :codec',
        );

        Validator::extend(
            'duration',
            'MinuteOfLaravel\MediaValidator\MediaValidator@validateDuration',
            'The :attribute must be :duration seconds duration.'
        );

        Validator::extend(
            'duration_max',
            'MinuteOfLaravel\MediaValidator\MediaValidator@validateDurationMax',
            'The :attribute duration must be less than :duration_max seconds.'
        );

        Validator::extend(
            'duration_min',
            'MinuteOfLaravel\MediaValidator\MediaValidator@validateDurationMin',
            'The :attribute duration must be greater than :duration_min seconds.'
        );

        Validator::extend(
            'video_width',
            'MinuteOfLaravel\MediaValidator\MediaValidator@validateVideoWidth',
            'The :attribute width must be :video_width.'
        );

        Validator::extend(
            'video_height',
            'MinuteOfLaravel\MediaValidator\MediaValidator@validateVideoHeight',
            'The :attribute height must be :video_height.'
        );

        Validator::extend(
            'video_max_width',
            'MinuteOfLaravel\MediaValidator\MediaValidator@validateVideoMaxWidth',
            'The :attribute width must be less than :video_max_width.'
        );

        Validator::extend(
            'video_max_height',
            'MinuteOfLaravel\MediaValidator\MediaValidator@validateVideoMaxHeight',
            'The :attribute height must be  than :video_max_height.'
        );

        Validator::extend(
            'video_min_width',
            'MinuteOfLaravel\MediaValidator\MediaValidator@validateVideoMinWidth',
            'The :attribute width must be greater than :video_min_width.'
        );

        Validator::extend(
            'video_min_height',
            'MinuteOfLaravel\MediaValidator\MediaValidator@validateVideoMinHeight',
            'The :attribute height must be greater than :video_min_height.'
        );
    }

    public static function replaceMessages() {
        Validator::replacer('codec', function ($message, $attribute, $rule, $parameters) {
            return str_replace(':' . $rule, implode($parameters), $message);
        });

        Validator::replacer('duration', function ($message, $attribute, $rule, $parameters) {
            return str_replace(':' . $rule, $parameters[0], $message);
        });

        Validator::replacer('duration_max', function ($message, $attribute, $rule, $parameters) {
            return str_replace(':' . $rule, $parameters[0], $message);
        });

        Validator::replacer('duration_min', function ($message, $attribute, $rule, $parameters) {
            return str_replace(':' . $rule, $parameters[0], $message);
        });

        Validator::replacer('video_width', function ($message, $attribute, $rule, $parameters) {
            return str_replace(':' . $rule, $parameters[0], $message);
        });

        Validator::replacer('video_height', function ($message, $attribute, $rule, $parameters) {
            return str_replace(':' . $rule, $parameters[0], $message);
        });

        Validator::replacer('video_max_width', function ($message, $attribute, $rule, $parameters) {
            return str_replace(':' . $rule, $parameters[0], $message);
        });

        Validator::replacer('video_max_height', function ($message, $attribute, $rule, $parameters) {
            return str_replace(':' . $rule, $parameters[0], $message);
        });

        Validator::replacer('video_min_width', function ($message, $attribute, $rule, $parameters) {
            return str_replace(':' . $rule, $parameters[0], $message);
        });

        Validator::replacer('video_min_height', function ($message, $attribute, $rule, $parameters) {
            return str_replace(':' . $rule, $parameters[0], $message);
        });
    }

    public function validateAudio(string $attribute, $value): bool
    {
        return $this->isAudio($value);
    }

    public function validateVideo(string $attribute, $value): bool
    {
        return $this->isVideo($value);
    }


    public function validateCodec(string $attribute, $value, array $parameters): bool
    {
        $this->requireParameterCount(1, $parameters, 'codec');

        if ($this->isAudio($value)) {
            $codecName = $this->getAudioStream()->get('codec_name');

            return in_array($codecName, $parameters);
        }

        if ($this->isVideo($value)) {
            $codecName = $this->getVideoStream()->get('codec_name');

            return in_array($codecName, $parameters);
        }

        return true;
    }

    public function validateDuration(string $attribute, $value, array $parameters): bool
    {
        if (!$this->isAudio($value) && !$this->isVideo($value)) return true;

        $this->requireParameterCount(1, $parameters, 'duration');
        $duration = $this->getMediaDuration($value);

        if (!$duration) return false;

        return $duration == $parameters[0];
    }

    public function validateDurationMax(string $attribute, $value, array $parameters): bool
    {
        if (!$this->isAudio($value) && !$this->isVideo($value)) return true;

        $this->requireParameterCount(1, $parameters, 'duration_max');
        $duration = $this->getMediaDuration($value);

        if (!$duration) return false;

        return $duration <= $parameters[0];
    }

    public function validateDurationMin(string $attribute, $value, array $parameters): bool
    {
        if (!$this->isAudio($value) && !$this->isVideo($value)) return true;

        $this->requireParameterCount(1, $parameters, 'duration_min');
        $duration = $this->getMediaDuration($value);

        if (!$duration) return false;

        return $duration >= $parameters[0];
    }

    public function validateVideoWidth(string $attribute, $value, array $parameters): bool
    {
        if (!$this->isVideo($value) ) return true;

        $this->requireParameterCount(1, $parameters, 'video_width');
        $dimensions = $this->getMediaDimensions($value);

        if (!$dimensions) return false;

        return $dimensions['width'] == $parameters[0];
    }

    public function validateVideoHeight(string $attribute, $value, array $parameters): bool
    {
        if (!$this->isVideo($value) ) return true;

        $this->requireParameterCount(1, $parameters, 'video_height');
        $dimensions = $this->getMediaDimensions($value);

        if (!$dimensions) return false;

        return $dimensions['height'] == $parameters[0];
    }

    public function validateVideoMaxWidth(string $attribute, $value, array $parameters): bool
    {
        if (!$this->isVideo($value) ) return true;

        $this->requireParameterCount(1, $parameters, 'video_max_width');
        $dimensions = $this->getMediaDimensions($value);

        if (!$dimensions) return false;

        return $dimensions['width'] <= $parameters[0];
    }

    public function validateVideoMaxHeight(string $attribute, $value, array $parameters): bool
    {
        if (!$this->isVideo($value) ) return true;

        $this->requireParameterCount(1, $parameters, 'video_max_height');
        $dimensions = $this->getMediaDimensions($value);

        if (!$dimensions) return false;

        return $dimensions['height'] <= $parameters[0];
    }

    public function validateVideoMinWidth(string $attribute, $value, array $parameters): bool
    {
        if (!$this->isVideo($value) ) return true;

        $this->requireParameterCount(1, $parameters, 'video_min_width');
        $dimensions = $this->getMediaDimensions($value);

        if (!$dimensions) return false;

        return $dimensions['width'] >= $parameters[0];
    }

    public function validateVideoMinHeight(string $attribute, $value, array $parameters): bool
    {
        if (!$this->isVideo($value) ) return true;

        $this->requireParameterCount(1, $parameters, 'video_min_height');
        $dimensions = $this->getMediaDimensions($value);

        if (!$dimensions) return false;

        return $dimensions['height'] >= $parameters[0];
    }

}
