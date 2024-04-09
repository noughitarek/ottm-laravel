<?php
return [
    "id" => "ITCEM",
    "title" => "ITCentre - Ecommerce management",
    
    "notifications" =>[
        "username" => 'ottm',
        "password" => 'ottmottm',
        "api_token" => '575B52VB575BV75BD4DD696VE5TT6BV46BFBBFBTTF',
        "package" => 'com.ottm.app',
    ],

    "messages_template" => [
        'validating' => '',
        'shipping' => '',
        'wilaya' => '',
        'delivery' => '',
        'delivered' => '',
        'ready' => '',
        'recovering' => '',
        'back' => '',
        'back_Ready' => '',
    ],

    "scheduler" => [
        "conversations" => false,
        "orders_states_check" => false,
        "tokens_validity_check" => false,
        "remarketing_send" => false,
    ],

    "limits" => [
        'conversations' => 10,
        'message_per_conversation' => 1000,
        "max_simultaneous_message" => 10,
        "order_conversations_count" => 10
    ],

    "quantities" => [
        1 => '',
        2 => 'زوج/',
        3 => 'ثلاثة/',
        4 => 'ربعة/',
        5 => 'خمسة/',
        6 => 'ستة/',
        7 => 'سبعة/',
        8 => 'ثمنية/',
        9 => 'تسعة/',
        10 => 'عشرة/',
    ],

];