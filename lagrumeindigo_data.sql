-- admin user --
INSERT INTO `user` (`id`, `email`, `roles`, `password`, `name`, `phone`) VALUES
(0x1ed5ac9d37db6a549392fd43ffba848c, 'tech@lagrumeindigo.com', '[\"ROLE_ADMIN\"]', '$2y$13$huC7YXKF94Fw1bKa42Mmou1abF1ZuiF4F6JcKfzgYXvQ2ihJ4YC4a', 'agrume indigo', NULL);


-- data --
INSERT INTO `user` (`id`, `email`, `roles`, `password`, `name`, `phone`) VALUES
(0x1ed5acd62fd762caa63f453d634f9fc6, 'bordeaux@lagrumeindigo.com', '[\"ROLE_FRANCHISE\"]', '$2y$13$7ew9S5aa1Gblapaj2tQf7.xym91JVuWro7cAz.u/KYxMxF12f7jya', 'Marie-Philip Poulin', '01 23 45 67 89'),
(0x1ed5ad0a9ebb691495f99f56da46b3c6, 'bordeaux.abdominal@lagrumeindigo.com', '[\"ROLE_GYM\"]', '$2y$13$EnXn31z.0KrpYruKk65scebrQMHeCscU0..o.stiA5b4YT8cRX6L6', 'Pierre-Olivier Joseph', '01 23 45 67 89');

INSERT INTO `franchise` (`id`, `user_id`, `name`, `active`) VALUES
(0x1ed5acdda5476468a46c758caf2596cd, 0x1ed5acd62fd762caa63f453d634f9fc6, 'Bordeaux', 1);

INSERT INTO `gym` (`id`, `user_id`, `name`, `active`, `address`, `franchise_id`) VALUES
(0x1ed5ad13cee1661283619b81ee2bf292, 0x1ed5ad0a9ebb691495f99f56da46b3c6, '87 Abdominal', 1, '87 avenue Abdominal\r\nBordeaux 33300\r\nFrance', 0x1ed5acdda5476468a46c758caf2596cd);

INSERT INTO `contract` (`id`, `franchise_id`, `gym_id`, `send_newsletter`, `team_planning`, `sell_drinks`, `promotion`, `payment_schedules`, `statistics`) VALUES
(0x00000000000000000000000000000000, 0x1ed5acdda5476468a46c758caf2596cd, NULL, 1, 1, 0, 0, 1, 1),
(0x1ed5ad1d0dab6d9e82cc1536343e6769, NULL, 0x1ed5ad13cee1661283619b81ee2bf292, 1, 1, 0, 0, 0, 1);
