<?php

return [
    'image_resize' => [
        'medium' => env("IMAGE_MEDIUM",700),
        'small' => env("IMAGE_SMALL",550),
        'extra_small' => env("IMAGE_EXTRA_SMALL",250)
    ],
    'video_resize' => [
        'medium' => env("VIDEO_MEDIUM",500),
        'small' => env("VIDEO_SMALL",250)
    ],
];