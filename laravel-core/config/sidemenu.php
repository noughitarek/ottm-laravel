<?php

return array(
    array("type" => "text", "content"=> "Pages", "permissions" => ["dashboard_consult", "desks_consult", "wilayas_consult"]),
    array(
        "type" => "link",
        "content" => "Dashboard",
        "permissions" => "dashboard_consult",
        "section" => "dashboard",
        "route" => "dashboard",
        "icon" => array("type" => "feather", "content" => "pie-chart"),
    ),
    array(
        "type" => "link",
        "content" => "Desks",
        "permissions" => "desks_consult",
        "section" => "desks",
        "route" => "desks",
        "icon" => array("type" => "feather", "content" => "airplay"),
    ),
    array(
        "type" => "link",
        "content" => "Wilayas",
        "permissions" => "wilayas_consult",
        "section" => "wilayas",
        "route" => "wilayas",
        "icon" => array("type" => "feather", "content" => "map"),
    ),
    array("type" => "text", "content"=> "Orders", "permissions" => ["products_consult", "conversations_consult", ["orders_consult", "orders_restricted_consult"], "stock_consult"]),
    
    array(
        "type" => "link",
        "content" => "Products",
        "permissions" => "products_consult",
        "section" => "products",
        "route" => "products",
        "icon" => array("type" => "feather", "content" => "box"),
    ),
    array(
        "type" => "link",
        "content" => "Conversations",
        "permissions" => "conversations_consult",
        "section" => "conversations",
        "route" => "conversations",
        "icon" => array("type" => "feather", "content" => "message-square"),
    ),
    array(
        "type" => "group",
        "content" => "Orders",
        "permissions" => ["orders_consult", "orders_restricted_consult", "orders_create"],
        "section" => "orders",
        "route" => "orders",
        "icon" => array("type" => "feather", "content" => "shopping-cart"),
        "sub-links" => array(
            array(
                "type" => "link",
                "content" => "Create",
                "permissions" => "orders_create",
                "route" => "orders_create",
            ),
            array(
                "type" => "link",
                "content" => "Pending",
                "permissions" => ["orders_consult", "orders_restricted_consult"],
                "route" => "orders_pending",
            ),
            array(
                "type" => "link",
                "content" => "To wilaya",
                "permissions" => ["orders_consult", "orders_restricted_consult"],
                "route" => "orders_towilaya",
            ),
            array(
                "type" => "link",
                "content" => "Delivery",
                "permissions" => ["orders_consult", "orders_restricted_consult"],
                "route" => "orders_delivery",
            ),
            array(
                "type" => "link",
                "content" => "Delivered",
                "permissions" => ["orders_consult", "orders_restricted_consult"],
                "route" => "orders_delivered",
            ),
            array(
                "type" => "link",
                "content" => "Back",
                "permissions" => ["orders_consult", "orders_restricted_consult"],
                "route" => "orders_back",
            ),
            array(
                "type" => "link",
                "content" => "Archived",
                "permissions" => "orders_restricted",
                "route" => "orders_archived",
            ),
        )
    ),
    array(
        "type" => "link",
        "content" => "Stock",
        "permissions" => "stock_consult",
        "section" => "stock",
        "route" => "stock",
        "icon" => array("type" => "feather", "content" => "clipboard"),
    ),
    array("type" => "text", "content"=> "Tools", "permissions" => ["remarketing_consult", "tracking_consult"]),
    array(
        "type" => "link",
        "content" => "OTTM",
        "permissions" => "tracking_consult",
        "section" => "tracking",
        "route" => "tracking",
        "icon" => array("type" => "feather", "content" => "compass"),
    ),
    array(
        "type" => "link",
        "content" => "RTM",
        "permissions" => "remarketing_consult",
        "section" => "remarketing",
        "route" => "remarketing",
        "icon" => array("type" => "feather", "content" => "volume-2"),
    ),
    array("type" => "text", "content"=> "Administration", "permissions" => ["users_consult", "settings_consult"]),
    array(
        "type" => "link",
        "content" => "Users",
        "permissions" => "users_consult",
        "section" => "users",
        "route" => "users",
        "icon" => array("type" => "feather", "content" => "users"),
    ),
    array(
        "type" => "link",
        "content" => "Settings",
        "permissions" => "settings_consult",
        "section" => "settings",
        "route" => "settings",
        "icon" => array("type" => "feather", "content" => "settings"),
    ),
);