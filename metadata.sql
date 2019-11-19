CREATE TABLE `data` (
  `id` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `qid` varchar(255) DEFAULT NULL,
  `score` decimal(4,2) DEFAULT NULL,
  `sender` varchar(255) DEFAULT NULL,
  `rcpt` varchar(255) DEFAULT NULL,
  `ip` varchar(50) DEFAULT NULL,
  `action` varchar(50) DEFAULT NULL,
  `data` longtext
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `data`
  ADD PRIMARY KEY (`id`);