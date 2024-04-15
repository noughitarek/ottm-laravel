<?php

return [
    'dashboard' => ['consult'],
    'desks' => ['consult', 'create', 'edit', 'delete'],
    'wilayas' => ['consult', 'edit'],
    'commune' => ['consult', 'edit'],

    'products' => ['consult', 'create', 'edit', 'delete'],
    'conversations' => ['consult'],
    'orders' => ['consult', 'restricted_consult', 'create', 'edit', 'restricted_edit', 'delete', 'restricted_delete'],
    'messagestemplates' => ['consult', 'create', 'edit', 'delete'],
    'stock' => ['consult', 'create', 'edit', 'delete'],

    'remarketing_categories' => ['consult', 'create', 'edit', 'delete'],
    'remarketing' => ['consult', 'create', 'edit', 'delete'],
    'remarketing_interval' => ['consult', 'create', 'edit', 'delete'],
    'tracking' => ['consult', 'edit'],
    'responder' => ['consult', 'edit'],
    'invoicer' => ['consult', 'upload'],

    'users' => ['consult', 'create', 'edit', 'delete'],
    'settings' => ['consult', 'edit'],

    'facebook' => ['consult', 'edit', 'reconnect']
];