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
        "content" => "Delivery mens",
        "permissions" => "deliverymens_consult",
        "section" => "deliverymens",
        "route" => "deliverymens",
        "icon" => array("type" => "feather", "content" => "truck"),
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
        "type" => "link",
        "content" => "Messages templates",
        "permissions" => "messagestemplates_consult",
        "section" => "messagestemplates",
        "route" => "messagestemplates",
        "icon" => array("type" => "feather", "content" => "message-circle"),
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
                "content" => "Import",
                "permissions" => "orders_import",
                "route" => "orders_import",
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
    array("type" => "text", "content"=> "Accounting", "permissions" => ["accounting_dashboard_consult", "accounting_investors_consult", "accounting_funding_consult", "accounting_sales_consult", "accounting_spending_consult", "accounting_stock_consult"]),
    array(
        "type" => "link",
        "content" => "Dashboard",
        "permissions" => "accounting_dashboard_consult",
        "section" => "accounting_dashboard",
        "route" => "orders_archived",
        "icon" => array("type" => "font-awesomes", "content" => "th-large"),
    ),
    array(
        "type" => "link",
        "content" => "Investors",
        "permissions" => "accounting_investors_consult",
        "section" => "accountinginvestors",
        "route" => "accountinginvestors",
        "icon" => array("type" => "font-awesome", "content" => "user-tag"),
    ),
    /*
    array(
        "type" => "link",
        "content" => "Funding",
        "permissions" => "stock_consult",
        "section" => "stock",
        "route" => "stock",
        "icon" => array("type" => "font-awesome", "content" => "hand-holding-usd"),
    ),*/
    array(
        "type" => "link",
        "content" => "Purchases",
        "permissions" => "accounting_purchases_consult",
        "section" => "accountingpurchases",
        "route" => "accountingpurchases",
        "icon" => array("type" => "font-awesome", "content" => "sign-in-alt"),
    ),
    array(
        "type" => "link",
        "content" => "Sales",
        "permissions" => "accounting_sales_consult",
        "section" => "accountingsales",
        "route" => "accountingsales",
        "icon" => array("type" => "font-awesome", "content" => "sign-out-alt"),
    ),
    array(
        "type" => "link",
        "content" => "Spending",
        "permissions" => "accounting_spending_consult",
        "section" => "accounting_spending",
        "route" => "orders_archived",
        "icon" => array("type" => "font-awesome", "content" => "volume-up"),
    ),
    array(
        "type" => "link",
        "content" => "Stocks",
        "permissions" => "accounting_stock_consult",
        "section" => "accounting_stock",
        "route" => "orders_archived",
        "icon" => array("type" => "font-awesome", "content" => "boxes"),
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
        "type" => "group",
        "content" => "RTM",
        "permissions" => "remarketing_consult",
        "section" => "remarketing",
        "route" => "remarketing",
        "icon" => array("type" => "feather", "content" => "volume-2"),
        "sub-links" => array(
            array(
                "type" => "link",
                "content" => "Categories",
                "permissions" => "remarketing_categories_consult",
                "route" => "remarketing_categories",
            ),
            array(
                "type" => "link",
                "content" => "Timeout",
                "permissions" => "remarketing_consult",
                "route" => "remarketing",
            ),
            array(
                "type" => "link",
                "content" => "Interval",
                "permissions" => "remarketing_interval_consult",
                "route" => "remarketing_interval",
            ),
        ),
    ),
    array(
        "type" => "link",
        "content" => "ARM",
        "permissions" => "responder_consult",
        "section" => "responder",
        "route" => "responder",
        "icon" => array("type" => "feather", "content" => "upload-cloud"),
    ),
    array(
        "type" => "link",
        "content" => "AIB",
        "permissions" => "invoicer_consult",
        "section" => "invoicer",
        "route" => "invoicer",
        "icon" => array("type" => "feather", "content" => "award"),
    ),
    array("type" => "text", "content"=> "Facebook bots", "permissions" => ["botsengine_consult", "accounts_consult", "group_joiner_consult", "group_poster_consult"]),
    array(
        "type" => "link",
        "content" => "Engine log",
        "permissions" => "botsengine_consult",
        "section" => "botsengine",
        "route" => "botsengine",
        "icon" => array("type" => "font-awesome", "content" => "microchip"),
    ),
    array(
        "type" => "link",
        "content" => "Accounts",
        "permissions" => "accounts_consult",
        "section" => "accounts",
        "route" => "accounts",
        "icon" => array("type" => "font-awesome", "content" => "users"),
    ),
    
    array(
        "type" => "group",
        "content" => "FGMT",
        "permissions" => "remarketing_consult",
        "section" => "FGMT",
        "route" => "remarketing",
        "icon" => array("type" => "icons", "content" => "sliders-h"),
        "sub-links" => array(
            array(
                "type" => "link",
                "content" => "Analytics",
                "permissions" => "users_consult",
                "section" => "settings",
                "route" => "settings",
            ),
            array(
                "type" => "link",
                "content" => "Joiner",
                "permissions" => "group_joiner_consult",
                "section" => "group_joiner",
                "route" => "group_joiner",
            ),
            array(
                "type" => "link",
                "content" => "Poster",
                "permissions" => "group_poster_consult",
                "section" => "group_poster",
                "route" => "group_poster",
            ),
        ),
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