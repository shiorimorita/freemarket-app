<?php

return [

    // 他のサービス（mailgun / github など）がある場合はそのまま

    'stripe' => [
        'secret' => env('STRIPE_SECRET'),
        'public' => env('STRIPE_PUBLIC'),
    ],
];
