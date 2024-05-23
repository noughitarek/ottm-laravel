<?php

return [
    'dashboard' => ['consult'],
    'desks' => ['consult', 'create', 'edit', 'delete'],
    'deliverymens' => ['consult', 'create', 'edit', 'delete'],
    'wilayas' => ['consult', 'edit'],
    'commune' => ['consult', 'edit'],

    'products' => ['consult', 'create', 'edit', 'delete'],
    'conversations' => ['consult'],
    'orders' => ['consult', 'restricted_consult', 'create', 'edit', 'restricted_edit', 'delete', 'restricted_delete'],
    'messagestemplates' => ['consult', 'create', 'edit', 'delete'],
    'stock' => ['consult', 'create', 'edit', 'delete'],

    'accounting_dashboard' => ['consult'],
    'botsengine' => ['consult'],
    'accounting_investors' => ['consult', 'create', 'edit', 'delete'],
    
    'accounting_fundings' => ['consult', 'create', 'edit', 'delete'],
    'accounting_purchases' => ['consult', 'create', 'edit', 'delete'],
    'accounting_sales' => ['consult', 'create', 'edit', 'delete'],
    'accounting_spending' => ['consult', 'create', 'edit', 'delete'],
    'accounting_stock' => ['consult'],



    'remarketing_categories' => ['consult', 'create', 'edit', 'delete'],
    'remarketing' => ['consult', 'create', 'edit', 'delete'],
    'remarketing_interval' => ['consult', 'create', 'edit', 'delete'],
    'tracking' => ['consult', 'edit'],
    'responder' => ['consult', 'create', 'edit', 'delete'],
    'invoicer' => ['consult', 'consult_product', 'create_product', 'edit_product', 'delete_product', 'upload'],

    'accounts' => ['consult', 'create', 'edit', 'delete'],
    'group_joiner' => ['consult', 'history', 'create', 'edit', 'delete'],
    'group_poster' => ['consult', 'create', 'edit', 'delete'],
    


    'users' => ['consult', 'create', 'edit', 'delete'],
    'settings' => ['consult', 'edit'],

    'facebook' => ['consult', 'edit', 'reconnect']
];