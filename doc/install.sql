CREATE TABLE `lhc_chatgpt_invalid` (`id` bigint(20) unsigned NOT NULL AUTO_INCREMENT, `reviewed` tinyint(1) unsigned NOT NULL DEFAULT 0, `aid` bigint(20) unsigned NOT NULL, `chat_id` bigint(20) unsigned NOT NULL, `ctime` bigint(20) unsigned NOT NULL, `user_id` bigint(20) unsigned NOT NULL,  `question` longtext COLLATE utf8mb4_unicode_ci NOT NULL, `answer` longtext COLLATE utf8mb4_unicode_ci NOT NULL,  PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `lhc_chatgpt_crawl` (
                                     `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                                     `crawl_frequency` int(11) DEFAULT NULL,
                                     `last_crawled_at` bigint(20) unsigned DEFAULT NULL,
                                     `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                                     `vector_storage_id` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                                     `url` text COLLATE utf8mb4_unicode_ci NOT NULL,
                                     `number_of_pages` int(11) unsigned DEFAULT NULL,
                                     `file_id` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                                     `status` tinyint(1) unsigned NOT NULL DEFAULT 0,
                                     `base_url` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
                                     `start_url` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
                                     `max_pages` int(11) unsigned NOT NULL DEFAULT 0,
                                     `type` tinyint(1) unsigned NOT NULL DEFAULT '0',
                                     `content` longtext NOT NULL DEFAULT '',
                                     PRIMARY KEY (`id`),
                                     KEY `status_last_crawled_at` (`status`,`last_crawled_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
