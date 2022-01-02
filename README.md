# Laravel Audio & Video Validator
This package adds validators for audio and video files to your Laravel project.

## Installation
To use this package you should intall ffmpeg multimedia framework:

- On Debian/Ubuntu, run ```sudo apt install ffmpeg```
- On macOS with Homebrew: ```brew install ffmpeg```

After that install the package via composer:

```bash
composer require minuteoflaravel/laravel-audio-video-validator
```

## Validators

Package adds these validators:
- audio
- video
- codec
- duration
- duration_max
- duration_min
- video_width
- video_height
- video_max_width
- video_max_height
- video_min_width
- video_min_height

## Custom error messages

If you need to add your custom translatable error messages then just add them as always to resources/lang/en/validation.php file:

```php
  'audio' => 'The :attribute must be a audio.',
  'video' => 'The :attribute must be a video.',
  'codec' => 'The :attribute codec must be one of these: :codec',
  'duration' => 'The :attribute must be :duration seconds duration.',
  'duration_max' => 'The :attribute duration must be less than :duration_max seconds.',
  'duration_min' => 'The :attribute duration must be greater than :duration_min seconds.',
  'video_width' => 'The :attribute width must be :video_width.',
  'video_height' => 'The :attribute height must be :video_height.',
  'video_max_width' => 'The :attribute width must be less than :video_max_width.',
  'video_min_width' => 'The :attribute width must be greater than :video_min_width.',
  'video_min_height' => 'The :attribute height must be greater than :video_min_height.',
```

## Some examples

To check if file is audio file and audio duration is 60 seconds:

```php
$request->validate([
    'audio' => 'audio|duration:60',
]);
```

To check if file is audio file and audio duration is between 30 and 300 seconds:

```php
$request->validate([
    'audio' => 'audio|duration_min:30|duration_max:300',
]);
```

To check if file is video file and video duration is between 30 and 300 seconds:

```php
$request->validate([
    'video' => 'video|duration_min:30|duration_max:300',
]);
```

To check if file is video file and video dimensions are 1000x640:

```php
$request->validate([
    'video' => 'video|video_width:1000|video_height:640',
]);
```

To check if file is video file and video dimensions greater than 1000x640:

```php
$request->validate([
    'video' => 'video|video_min_width:1000|video_min_height:640',
]);
```

To check if file is audio file and codec is mp3 or pcm_s16le(wav):

```php
$request->validate([
    'audio' => 'audio|codec:mp3,pcm_s16le',
]);
```

To check if file is video file and codec is h264(mp4):

```php
$request->validate([
    'video' => 'video|codec:h264',
]);
```


## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.



