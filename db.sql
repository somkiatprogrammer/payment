CREATE TABLE `payment` (
  `tran_id` varchar(32) CHARACTER SET latin1 NOT NULL,
  `amount` double NOT NULL,
  `currency` varchar(3) CHARACTER SET latin1 NOT NULL,
  `create_time` int(11) NOT NULL,
  `shipping_name` text COLLATE utf8_unicode_ci NOT NULL,
  UNIQUE KEY `tran_id` (`tran_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;