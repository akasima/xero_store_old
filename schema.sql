-- Create syntax for TABLE 'xe_rich_shop_cart_options'
CREATE TABLE `xe_rich_shop_cart_options` (
  `id` varchar(36) NOT NULL DEFAULT '',
  `cart_id` varchar(36) NOT NULL DEFAULT '',
  `user_id` varchar(36) NOT NULL DEFAULT '',
  `product_id` varchar(36) NOT NULL DEFAULT '',
  `option_id` varchar(36) NOT NULL DEFAULT '',
  `quantity` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Create syntax for TABLE 'xe_rich_shop_carts'
CREATE TABLE `xe_rich_shop_carts` (
  `id` varchar(36) NOT NULL DEFAULT '',
  `user_id` varchar(36) NOT NULL DEFAULT '',
  `product_id` varchar(36) NOT NULL DEFAULT '',
  `seller_id` varchar(36) NOT NULL DEFAULT '',
  `amount` int(11) NOT NULL,
  `delivery_fee` int(11) NOT NULL,
  `option_quantity` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `ipaddress` varchar(16) NOT NULL DEFAULT '',
  `order_id` varchar(36) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Create syntax for TABLE 'xe_rich_shop_options'
CREATE TABLE `xe_rich_shop_options` (
  `id` varchar(36) NOT NULL DEFAULT '',
  `product_id` varchar(36) NOT NULL DEFAULT '',
  `option_name` varchar(200) NOT NULL DEFAULT '',
  `parent_id` varchar(36) DEFAULT '',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `stock_quantity` int(11) NOT NULL DEFAULT '0',
  `additional_price` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Create syntax for TABLE 'xe_rich_shop_order_status_logs'
CREATE TABLE `xe_rich_shop_order_status_logs` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` varchar(36) NOT NULL DEFAULT '',
  `status` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `memo` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Create syntax for TABLE 'xe_rich_shop_orders'
CREATE TABLE `xe_rich_shop_orders` (
  `id` varchar(36) NOT NULL DEFAULT '',
  `user_id` varchar(36) NOT NULL DEFAULT '',
  `amount` int(11) NOT NULL,
  `delivery_fee` int(11) NOT NULL,
  `sum` int(11) NOT NULL,
  `cart_option_ids` text NOT NULL,
  `recv_name` varchar(200) NOT NULL DEFAULT '',
  `recv_phone` varchar(20) NOT NULL DEFAULT '',
  `recv_postcode` varchar(8) NOT NULL DEFAULT '',
  `recv_address1` varchar(255) NOT NULL DEFAULT '',
  `recv_address2` varchar(255) NOT NULL DEFAULT '',
  `shipping_memo` varchar(255) NOT NULL DEFAULT '',
  `status` int(11) NOT NULL DEFAULT '0',
  `payment_type` int(11) NOT NULL,
  `payment_id` varchar(36) NOT NULL DEFAULT '',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `ipaddress` varchar(20) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Create syntax for TABLE 'xe_rich_shop_product_categories'
CREATE TABLE `xe_rich_shop_product_categories` (
  `product_category_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` varchar(36) NOT NULL DEFAULT '',
  `selected_item_id` int(11) NOT NULL,
  `item_id1` int(11) NOT NULL,
  `item_id2` int(11) DEFAULT NULL,
  `item_id3` int(11) DEFAULT NULL,
  `item_id4` int(11) DEFAULT NULL,
  `item_id5` int(11) DEFAULT NULL,
  `item_id6` int(11) DEFAULT NULL,
  `item_id7` int(11) DEFAULT NULL,
  `item_id8` int(11) DEFAULT NULL,
  `item_id9` int(11) DEFAULT NULL,
  `item_id10` int(11) DEFAULT NULL,
  PRIMARY KEY (`product_category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8;

-- Create syntax for TABLE 'xe_rich_shop_products'
CREATE TABLE `xe_rich_shop_products` (
  `id` varchar(36) NOT NULL DEFAULT '',
  `seller_id` varchar(36) NOT NULL DEFAULT '',
  `product_name` varchar(200) NOT NULL DEFAULT '',
  `product_sub_name` varchar(200) NOT NULL DEFAULT '',
  `product_real_name` varchar(200) DEFAULT '',
  `product_model_name` varchar(200) NOT NULL,
  `product_code` varchar(200) NOT NULL,
  `product_manage_code` varchar(200) NOT NULL,
  `product_type` int(1) NOT NULL,
  `product_image_id` varchar(36) NOT NULL,
  `product_detail_image_id` varchar(36) DEFAULT NULL,
  `product_detail_image_order` text,
  `description` text NOT NULL,
  `description_mobile` text NOT NULL,
  `sub_description` varchar(200) DEFAULT NULL,
  `tags` varchar(250) NOT NULL,
  `price` int(11) NOT NULL,
  `supply_price` int(11) NOT NULL,
  `margin_rate` int(11) NOT NULL DEFAULT '10',
  `margin_add_price` int(11) NOT NULL DEFAULT '0',
  `tax_type` int(11) NOT NULL DEFAULT '1',
  `tax_rate` int(11) NOT NULL DEFAULT '10',
  `buy_item_limit` int(11) NOT NULL DEFAULT '0',
  `buy_item_limit_min` int(11) DEFAULT NULL,
  `buy_item_limit_max` int(11) DEFAULT NULL,
  `display` int(11) NOT NULL,
  `sale` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Create syntax for TABLE 'xe_rich_shop_sellers'
CREATE TABLE `xe_rich_shop_sellers` (
  `user_id` varchar(36) NOT NULL DEFAULT '',
  `corp_no` varchar(100) NOT NULL DEFAULT '',
  `corp_name` varchar(140) NOT NULL DEFAULT '',
  `corp_address` varchar(140) NOT NULL DEFAULT '',
  `rep_name` varchar(100) NOT NULL DEFAULT '',
  `rep_phone` varchar(40) NOT NULL DEFAULT '',
  `rep_email` varchar(140) NOT NULL DEFAULT '',
  `mail_order_business_no` varchar(100) NOT NULL DEFAULT '',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Create syntax for TABLE 'xe_rich_shop_shipping_info'
CREATE TABLE `xe_rich_shop_shipping_info` (
  `id` varchar(36) NOT NULL DEFAULT '',
  `user_id` varchar(36) NOT NULL DEFAULT '',
  `title` varchar(100) NOT NULL DEFAULT '',
  `recv_name` varchar(200) NOT NULL DEFAULT '',
  `recv_phone` varchar(20) NOT NULL DEFAULT '',
  `recv_postcode` varchar(8) NOT NULL DEFAULT '',
  `recv_address1` varchar(255) NOT NULL DEFAULT '',
  `recv_address2` varchar(255) NOT NULL DEFAULT '',
  `type` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Create syntax for TABLE 'xe_rich_shop_slug'
CREATE TABLE `xe_rich_shop_slug` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `target_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `instance_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `board_slug_slug_unique` (`slug`),
  KEY `rich_shop_slug_title_index` (`title`),
  KEY `rich_shop_slug_targetid_index` (`target_id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;