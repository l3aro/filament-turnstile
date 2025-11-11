<?php

return [
    'key' => env('TURNSTILE_SITE_KEY'),
    'secret' => env('TURNSTILE_SECRET_KEY'),
    'reset_event' => env('TURNSTILE_RESET_EVENT', 'reset-captcha'),
];
