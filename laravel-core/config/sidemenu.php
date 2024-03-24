<?php

return array(
    array("type" => "text", "content"=> "Pages", "permissions" => ["consult_dashboard", "consult_conversations", "consult_orders"]),
    array(
        "type" => "link",
        "content" => "Dashboard",
        "permissions" => "consult_dashboard",
        "section" => "dashboard",
        "route" => "dashboard",
        "icon" => array("type" => "feather", "content" => "sliders"),
    ),
    array(
        "type" => "link",
        "content" => "Conversations",
        "permissions" => "consult_conversations",
        "section" => "conversations",
        "route" => "conversations",
        "icon" => array("type" => "feather", "content" => "message-square"),
    ),
    array(
        "type" => "link",
        "content" => "Orders",
        "permissions" => "consult_orders",
        "section" => "orders",
        "route" => "orders",
        "icon" => array("type" => "feather", "content" => "shopping-cart"),
    ),
    array("type" => "text", "content"=> "Administration", "permissions" => ["consult_dashboard"]),
    array(
        "type" => "link",
        "content" => "Users",
        "permissions" => "consult_users",
        "section" => "users",
        "route" => "users",
        "icon" => array("type" => "feather", "content" => "users"),
    ),
    array(
        "type" => "link",
        "content" => "Settings",
        "permissions" => "consult_settings",
        "section" => "settings",
        "route" => "settings",
        "icon" => array("type" => "feather", "content" => "settings"),
    ),
);