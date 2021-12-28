<?php

namespace MinuteOfLaravel\MediaValidator;

use Illuminate\Support\ServiceProvider;

class MediaValidatorServiceProvider extends ServiceProvider
{
    public function boot()
    {
        MediaValidator::boot();
    }
}
