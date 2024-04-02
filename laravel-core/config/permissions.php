<?php

return [
    'dashboard' => ['consult'],
    'desks' => ['consult', 'create', 'edit', 'delete'],
    'wilayas' => ['consult', 'edit'],
    'commune' => ['consult', 'edit'],

    'products' => ['consult', 'create', 'edit', 'delete'],
    'conversations' => ['consult'],
    'orders' => ['consult', 'restricted_consult', 'create', 'edit', 'restricted_edit', 'delete', 'restricted_delete'],
    'stock' => ['consult', 'create', 'edit', 'delete'],

    'remarketing' => ['consult', 'create', 'edit', 'delete'],
    'tracking' => ['consult', 'edit'],

    'users' => ['consult', 'create', 'edit', 'delete'],
    'settings' => ['consult', 'edit'],

    'facebook' => ['consult', 'edit', 'reconnect']
];