-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Apr 05, 2017 at 08:53 AM
-- Server version: 10.1.19-MariaDB
-- PHP Version: 7.0.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `alokkk`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT 'Only First name',
  `last_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(60) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `signup_token` varchar(16) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `username` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT 'unique',
  `profilepic` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `role` int(11) NOT NULL DEFAULT '0' COMMENT '0=guest, 1=customer, 2=buyer, 3=merchant, 4=manager, 5=admin',
  `status` int(11) NOT NULL DEFAULT '1' COMMENT '0=pending, 1=active, 2=inactive, 3=rejected, 4=deleted',
  `status_set_by` int(11) NOT NULL DEFAULT '0' COMMENT 'user id',
  `campaign_mode` varchar(64) NOT NULL DEFAULT '0' COMMENT '0=flashsale,1=dailyspecial,3=wholesale,4=shop',
  `reset_code` varchar(8) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `device_id` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `login_token` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `wallet_balance` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'refund money and redeemed gift cert will be added here',
  `for_shop_name` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `last_name`, `email`, `password`, `remember_token`, `created_at`, `updated_at`, `signup_token`, `username`, `profilepic`, `role`, `status`, `status_set_by`, `campaign_mode`, `reset_code`, `device_id`, `login_token`, `wallet_balance`, `for_shop_name`) VALUES
(1, 'FlashSale Admin', 'vgj', 'admin@flashsale.com', '$2y$10$hv3nPsTYwIzbZtfNfMBd2.JewbAA5eqewiuNSaFmHdoIf3tM.XCA2', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 'admin', '/image/profileavatar_1_1455535489.jpg', 5, 1, 0, '0', '334137', '', '1', 30157, ''),
(2, 'FlashSale mer', '', 'merchant@flashsale.com', '$2y$10$hv3nPsTYwIzbZtfNfMBd2.JewbAA5eqewiuNSaFmHdoIf3tM.XCA2', NULL, '0000-00-00 00:00:00', '2016-03-21 04:19:48', '', 'merchant', '', 3, 2, 1, '0', '', '', '', 100000, ''),
(5, 'Danial', 'victory', 'vinidubey@gmail.com', '$2y$10$UcgDXdgq4NYZETjvf9oPD.XiWkKsG9q.xLL2Mozf08b2o32aLZcZe', NULL, '2016-01-14 02:11:14', '2016-12-02 05:05:58', '', 'danialvicdbd', '', 1, 1, 1, '0', '', '', '', 100000, ''),
(37, 'vinisupplier', 'supplier2', 'vinisupplier@globussoft.com', '$2y$10$UnA8UFmtTQDTiJoM0HEFtePJ/C.n0LfAtysvhxCDqyixuikGy.4UC', NULL, '2016-01-15 01:04:04', '2016-03-09 01:37:36', '', 'supplierhills', '/image/useravatar_37_1455535208.jpg', 3, 2, 1, '0', '', '', '', 100000, ''),
(49, 'suphjkh', 'hjol', 'supplierfvghkjgj@globussoft.com', '$2y$10$sPvgctKIJYnTFQeY5FvwIu54ScpGsLqh4vND69K.267iEudZ9s1oi', NULL, '2016-03-01 08:15:26', '2016-03-09 01:08:14', '', 'suppligvuj', '/image/useravatar_58_1456985382.jpg', 4, 1, 1, '0', '', '', '', 100000, ''),
(57, 'vini', 'Dubey', 'vinidubey@globussoft.com', '$2y$10$8hXyg2slpswqt77AeNoK6OQ0jX2/aIRfyO.J1AE3Q9Cvnk365L1Bm', NULL, '2016-02-17 04:32:33', '2016-12-02 05:06:04', '', 'vinidubey', '', 1, 2, 1, '0', '', '', '', 100000, ''),
(58, 'supp', 'lier', 'supplier@globussoft.com', '$2y$10$sPvgctKIJYnTFQeY5FvwIu54ScpGsLqh4vND69K.267iEudZ9s1oi', NULL, '2016-03-01 08:15:26', '2016-03-09 01:08:14', '', 'supplier', '/image/useravatar_58_1458569402.jpg', 3, 1, 1, '0', '', '', '', 100000, ''),
(59, 'Danish', 'Federal', 'danish@globussoft.com', '$2y$10$MK8wkOhCZh5hYXSfgf9IKOrZmNcmAeS21Y.F2Y81l3JBDsXbd0EBa', NULL, '2016-05-13 01:46:34', '2016-05-13 01:48:31', '', 'danishfederal', '/assets/images/avatar-placeholder.jpg', 3, 1, 1, '0', '', '', '', 100000, ''),
(73, 'FlashSale Admin', '', 'asdasdadmin@flashsale.com', 'ASd', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 'asdasdas', '', 5, 1, 0, '0', '', '', '', 100000, ''),
(74, 'test', 'user', 'testuser@flashsale.com', '$2y$10$.D1RaU0YD4JCY9GmMGM5iu.7iuE5SSNDQ3oeSNHdmfdsAvmxwAcEm', NULL, '2016-12-03 00:14:42', '2016-12-03 00:14:42', '', 'testuser', '', 1, 1, 0, '0', '', '', '', 0, ''),
(75, 'Test', 'Buyer', 'testbuyer@flashsale.com', '$2y$10$UATHh72DfwvYD9FpnOr4C.4CjxZx4HzXvE0V6snFhVtsrmON66ITi', NULL, '2016-12-03 01:30:21', '2016-12-03 01:30:21', '', 'testbuyer', '', 2, 0, 0, '0', '', '', '', 0, ''),
(76, 'test', 'user', 'testuser1@flashsale.com', '$2y$10$eJJPLDkyX8lyVunSGExU/Or666KFjWFtivC/T5jK8FPasUeguaLy6', NULL, '2016-12-12 04:56:15', '2016-12-12 04:56:15', '', 'testuser1', '', 1, 1, 0, '0', '', '', '', 0, ''),
(81, 'Akash', 'testuser', 'akashpai@globussoft.in', '$2y$10$lfOGBRuOEWwPL2kFTKL3n.JmShSC0O/m3wMioNwV/crP7Rb6EVRwW', NULL, '2016-12-12 06:55:06', '2016-12-12 06:55:06', '', 'akashtstuser1', '', 1, 1, 0, '0', '', '', '', 0, ''),
(82, 'Test', 'user', 'testuser101@flashsale.com', '$2y$10$X7zwQYFpEUXEQEfsxXV.jONWVppxKlhPKeVDxCvZE2bs0tC72/2za', NULL, '2016-12-17 03:26:26', '2016-12-17 03:26:26', '', 'testuser101', '', 1, 1, 0, '0', '', '', '', 0, ''),
(83, 'Test', 'user', 'testuser102@flashsale.com', '$2y$10$EjskuoB8bEKnsS1wvHlS2.5ovBfUjZJ2LFWWRsJ1kKfib10G/bjh.', NULL, '2016-12-17 03:26:37', '2016-12-17 03:26:37', '', 'testuser102', '', 1, 1, 0, '0', '', '', '', 0, ''),
(84, 'Test', 'user', 'testuser103@flashsale.com', '$2y$10$Uv9AGJL7Bp95WcsgT16GtuIpRYHCx3wGlVN01ZBaEWPtkzfMqz8Ny', NULL, '2016-12-17 03:28:39', '2016-12-17 03:28:39', '', 'testuser103', '', 1, 1, 0, '0', '', '', '', 0, ''),
(85, 'Test', 'user', 'testuser104@flashsale.com', '$2y$10$iHGxObSZnOpinUkttN5fDe7WVwX5niVyUZrVGhQVFo92xtmRkKKZK', NULL, '2016-12-17 03:35:39', '2016-12-17 03:35:39', '', 'testuser104', '', 1, 1, 0, '0', '', '', '', 0, ''),
(86, 'Test', 'user', 'testuser106@flashsale.com', '$2y$10$NkRLTTppyEN3PNI6a2qw.O7BzyRH8Ak4ubVDWgFpdrCrEsihbtYju', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 'testuser106', '', 1, 1, 0, '0', '', '', '', 0, ''),
(87, 'Test', 'user', 'testuser105@flashsale.com', '$2y$10$kLEn52MU4Wol.gWYj234quQO3D4XeJkeTU8PT5x3.ZQC9PSi0DMsK', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 'testuser105', '', 1, 1, 0, '0', '', '', '', 0, ''),
(88, 'Test', 'User', 'testuser107@flashsale.com', '$2y$10$7UC87SN2m9WFkarZ2jVu..orXPYX9hNkSSNO8RRpstppNo9slgyVC', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 'testuser107', '', 1, 1, 0, '0', '', '', '', 0, ''),
(89, 'Test', 'User', 'testuser108@flashsale.com', '$2y$10$C/pWiPBA8BjssnAuGECwHuaLybPGzCYV0h232bVIXA1vXuIhtqyjS', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 'testuser108', '', 1, 1, 0, '0', '', '', '', 0, ''),
(90, 'alok', 'k', 'alok097@gmail.com', '$2y$10$xLSVX0rylAppK8T8aJSe1ODAL1dVoXbMRzhvSGiP2AE7dPH.L786G', NULL, '2017-03-22 00:04:52', '2017-03-22 00:04:52', '', 'alok097', '', 1, 0, 0, '0', '', '', '', 0, ''),
(91, 'alk', 'alok', 'alokchaturvedi@globussoft.in', '$2y$10$cWT6cpyD4B0jWC6yOVaRFuhMGJwhYdWDocuAS0rO7vm3RmiFcqXJ2', NULL, '2017-03-22 00:05:32', '2017-03-22 00:05:32', '', 'alok0973', '', 1, 0, 0, '0', '', '', '', 0, ''),
(92, 'Test', 'Supplier', 'testsupplier@flashsale.com', '$2y$10$8MvmsjGo9CuS/vQBi9u2sedC0uTaF4yUy5dRh3ZgXRTuzaj0JeDDy', NULL, '2017-03-28 05:53:08', '2017-03-28 05:53:08', '', 'testsupplier123', '/assets/images/avatar-placeholder.jpg', 3, 1, 0, '0', '', '', '', 0, ''),
(93, 'aaaaa', 'sssssssss', 'admina1@flashsale.com', '$2y$10$bwg.fYLp4ROyZPcolxVfu.dZ7gw/Tdt32N1JwkhnehwS8dXsptY9q', NULL, '2017-03-29 07:39:25', '2017-03-29 07:39:25', '', 'aaaaa', '/assets/images/avatar-placeholder.jpg', 3, 0, 0, '0', '', '', '', 0, ''),
(94, 'aaaaa', 'sssssssss', 'admina1@flashsale.coma', '$2y$10$lScgBRjrsi9TU/G.nuw4QuZxT2KGnEpnyCLi5WIrHUJECIUAres.O', NULL, '2017-03-29 08:28:24', '2017-03-29 08:28:24', '', 'aaaaaxdxxxx', '/assets/images/avatar-placeholder.jpg', 3, 0, 0, '0', '', '', '', 0, ''),
(95, 'qq', 'ww', 'admin1@flashsale.comsd', '$2y$10$pKfwEs9hfdMdDrqAAt6msOpiAt4IhC46fQrl7MBppDiJUyAa6A1kS', NULL, '2017-03-29 08:35:32', '2017-03-29 08:35:32', '', 'df', '/assets/images/avatar-placeholder.jpg', 3, 0, 0, '0', '', '', '', 0, ''),
(96, 'aaaaa', 'aaaaa', 'alok@alok.com', '$2y$10$1Mpkpbz01vKPof//UmES9eArBSM6ET0EpJzWgyz18faRpTlDxFccm', NULL, '2017-03-29 08:49:50', '2017-03-29 08:49:50', '', 'aaaaaa', '/assets/images/avatar-placeholder.jpg', 3, 1, 0, '0', '', '', '', 0, 'alokalok'),
(97, 'q', 'w', 'admin1@flashsale.comccccc', '$2y$10$/QEUQi5hajg6jO9pkWU9.OOnQ84greno6GYeJE1X6yI2FwpSaf8XW', NULL, '2017-03-29 23:21:35', '2017-03-29 23:21:35', '', 'c', '/assets/images/avatar-placeholder.jpg', 3, 0, 0, '0', '', '', '', 0, ''),
(98, 'aaaaa', 'aaaaa', 'admina1@flashsale.comwswswsws', '$2y$10$E2/Lxi.ZKGMiyUFD2l6E/.ouKe7sMNDvn6hmoos5GtIca27sgoSNC', NULL, '2017-03-29 23:25:13', '2017-03-29 23:25:13', '', 'aaaaaaaadcdcd', '/assets/images/avatar-placeholder.jpg', 3, 0, 0, '0', '', '', '', 0, ''),
(99, 'er', 'yu', 'admin1@flashsale.comgykyui', '$2y$10$mS5CGN9GMCOsLNYrueLY7O0R1FoF3YIaL3HUnhM0GNQyte42Tl/Pq', NULL, '2017-03-29 23:28:23', '2017-03-29 23:28:23', '', 'tryrty', '/assets/images/avatar-placeholder.jpg', 3, 0, 0, '0', '', '', '', 0, ''),
(100, 'rtrtr', 'rtrtrt', 'admin1@flashsale.comnnnnn', '$2y$10$6gl7AmP6q/kngEGk05SQeuQzZLVddfpW8an8WcBWyk55l9doYUHP2', NULL, '2017-03-29 23:35:45', '2017-03-29 23:35:45', '', 'mnmnnmmn', '/assets/images/avatar-placeholder.jpg', 3, 0, 0, '0', '', '', '', 0, ''),
(101, 'ccc', 'cccc', 'admin1@flashsale.comttttt', '$2y$10$i4MU4sbLmUZMEFpWmXaWKu9JvVN.5XwR8fAbrgNJNz7d2hZbPteBy', NULL, '2017-03-29 23:40:21', '2017-03-29 23:40:21', '', 'qwqwqwqw', '/assets/images/avatar-placeholder.jpg', 3, 0, 0, '0', '', '', '', 0, ''),
(102, 'aaaaa', 'sssss', 'admin1@flashsale.commjn', '$2y$10$63sSplNmOc1jVcsJwtuyxejCgD.yOzyqr/KVrYAJlsw3jhcYNefFS', NULL, '2017-03-29 23:58:45', '2017-03-29 23:58:45', '', 'qnx', '/assets/images/avatar-placeholder.jpg', 3, 0, 0, '0', '', '', '', 0, ''),
(103, 'aaaaa', 'sssss', 'admin1@flashsale.commjner', '$2y$10$vSB2RtrGZ0K2pChmD5/4CuckGvt4PPqj5X3kWPuI66vXHvY8/sqb6', NULL, '2017-03-29 23:59:37', '2017-03-29 23:59:37', '', 'qnxsd', '/assets/images/avatar-placeholder.jpg', 3, 0, 0, '0', '', '', '', 0, ''),
(104, 'alk', 'alk', 'alok@gmail.com', '$2y$10$JU7FXQPpAKurQhTvZM0/Aego2RAx/iQNedrGEor9kJiTSo6DuJ5km', 'C2czY6GzcDhRt8INbEU2qBS3LJkb5uhFn3MDI2vYcyQ8X0T8A0vFXXWAeygq', '2017-03-30 00:18:48', '2017-03-30 00:23:35', '', 'alokalok', '/assets/images/avatar-placeholder.jpg', 3, 1, 0, '0', '', '', '', 0, ''),
(105, 'alok', 'alok', 'alok@alok.alok', '$2y$10$zE8a7V8YZHIueRuEf9pBoue06l7IBmI4b6/TvTZPFIq9DvEed6Pdy', NULL, '2017-03-30 01:06:27', '2017-03-30 01:06:27', '', 'alokalokalok', '/assets/images/avatar-placeholder.jpg', 3, 1, 0, '0', '', '', '', 0, ''),
(106, 'cvbcvbc', 'bcbvcvbcvb', 'admin1fgfgf@flashsale.com', '$2y$10$mA5VngL3YJjiHTVQJG4JmeZUFBMydBrLl3My6r9wAkRYaMrj1FiPO', NULL, '2017-03-30 01:24:59', '2017-03-30 01:24:59', '', 'cbvcvbvcb', '', 1, 1, 0, '0', '', '', '', 0, ''),
(107, 'alok', 'c', 'alok12345@gmail.com', '$2y$10$66fYvXAN0V7RRuBoc9COAuZZ.4IJ5vfoADNaK3VZ7pwQnKJ0xhpjG', NULL, '2017-03-30 02:13:31', '2017-03-30 02:13:31', '', 'alokchatur', '', 1, 1, 0, '0', '', '', '', 0, ''),
(108, 'alok', 'c', 'alok123456@gmail.com', '$2y$10$QuCj.VQRpl9ARH/i0V.zJeTg5yfHJ6AL2r8IOQX3G/W0GcvqNvb56', NULL, '2017-03-30 02:20:53', '2017-03-30 02:20:53', '', 'alokchaturvedi', '', 1, 1, 0, '0', '', '', '', 0, ''),
(109, 'alok', 'c', 'alok1234567@gmail.com', '$2y$10$m3NPOcmnXlrBJ0HBAMLfe.EhR8aYakMat13nsxeP1NT8GRv9jAOPW', NULL, '2017-03-30 02:22:17', '2017-03-30 02:22:17', '', 'alokchaturvedii', '', 1, 1, 0, '0', '', '', '', 0, ''),
(110, 'alok', 'c', 'alok12345678@gmail.com', '$2y$10$LqPBZIkNgkx65umF2wIr3eAbYsvOEsxLUTX7CFLKMaosGO2nxbqMS', NULL, '2017-03-30 02:29:27', '2017-03-30 02:29:27', '', 'alokchaturvediii', '', 1, 1, 0, '0', '', '', '', 0, ''),
(111, 'gdfg', 'dfgdfgfd', 'admin345345431@flashsale.com', '$2y$10$ZjbHytHYweEaXux1cN4YreC.cM4tYK4eoK7Qujgne8/93lKmguYFG', NULL, '2017-03-30 02:47:11', '2017-03-30 02:47:11', '', 'dfgdfgdfg', '', 1, 1, 0, '0', '', '', '', 0, ''),
(112, 'gdfg', 'dfgdfgfd', 'admin34534tyuty543y1@flashsale.com', '$2y$10$2O3eZrBhm46Ir8QYa08lL.CbDhC68e210VdY/5iyjfMK2ET8XsuQu', NULL, '2017-03-30 02:50:59', '2017-03-30 02:50:59', '', 'dfgdfgdfgtyutyur', '', 1, 1, 0, '0', '', '', '', 0, ''),
(113, 'gdfg', 'dfgdfgfd', 'admin34534tyuty543yrty1@flashsale.com', '$2y$10$zv7UUxo90eeZyIm6nHXOGef.MmXqQ.jI8jNhsV8oQ6YKQePd70aZG', NULL, '2017-03-30 02:53:40', '2017-03-30 02:53:40', '', 'dfgdfgdfgtyutyurtry', '', 1, 1, 0, '0', '', '', '', 0, ''),
(114, 'gdfg', 'dfgdfgfd', 'admin34534tyuty543yfghgfrty1@flashsale.com', '$2y$10$H/S.Qd5RJLCy5VLQ3gIPK.Ik/Y5I6E5zIAC7GZNWB5vfpn1DlKH4m', NULL, '2017-03-30 02:57:54', '2017-03-30 02:57:54', '', 'dfgdfgdfgtyutyurtryfghf', '', 1, 1, 0, '0', '', '', '', 0, ''),
(115, 'dfgdfgdf', 'gdfgdfgdf', 'adminsdfsdfsd1@flsdashsale.com', '$2y$10$1orWLCXOoV.d2eYMIdeN2.Y0BAdSF0rqHc5YrkNAW/i152fOnu8V2', NULL, '2017-03-30 04:53:50', '2017-03-30 04:53:50', '', 'gfdgfdgfdg', '', 1, 1, 0, '0', '', '', '', 0, ''),
(116, 'first_name', 'last_name', 'email', '$2y$10$FkFrAP0SoWV8d6pDv.f/4OxtW17Yczh0IinqerVPb.0AwRqffBwEi', NULL, '2017-03-30 04:58:42', '2017-03-30 04:58:42', '', 'username', '', 1, 1, 0, '0', '', '', '', 0, ''),
(118, 'first_name', 'lastasd_name', 'emaiasdl', '$2y$10$n8W1Aj1XDI72LsH5jVTnt.B0rHvE90DWX0yXQ0Zh8ThMOgKXyIrXy', NULL, '2017-03-30 05:00:19', '2017-03-30 05:00:19', '', 'username1w', '', 1, 1, 0, '0', '', '', '', 0, ''),
(120, 'first_name', 'lastasd_name', 'emaiasasdl', '$2y$10$nBoeVBYI3ThOgCQntEKrpuikTVTR6K.jianwrddDG85cNMevDtMdm', NULL, '2017-03-30 05:02:46', '2017-03-30 05:02:46', '', 'usernaame1w', '', 1, 1, 0, '0', '', '', '', 0, ''),
(121, 'test', 'user', 'testuser401@flashsale.com', '$2y$10$HdZTXk7WCKXsYDdB0qbkwOLP.Q6cigWDZKXG4G8MwrRfHIA9HIjH2', NULL, '2017-03-30 05:08:25', '2017-03-30 05:08:25', '', 'testuser401', '', 1, 1, 0, '0', '', '', '', 0, ''),
(122, 'test', 'user', 'testuser402@flashsale.com', '$2y$10$lW8NuTzZf2tAoAhjDBcMPuvlHq7MDFM6v5ALPK1xsCXj3yOyTAslO', NULL, '2017-03-30 05:10:05', '2017-03-30 05:10:05', '', 'testuser402', '', 1, 1, 0, '0', '', '', '', 0, ''),
(123, 'test', 'user', 'testuser403@flashsale.com', '$2y$10$HToOipVBkBEbmKSIJX8x5umTQ8mGzxW.l9NX/UwrJyiAHHzM0rsh.', NULL, '2017-03-30 05:12:57', '2017-03-30 05:12:57', '', 'testuser403', '', 1, 1, 0, '0', '', '', '', 0, ''),
(124, 'test', 'user', 'testuser404@flashsale.com', '$2y$10$NpUo7j4QrgUjhORbCNGZDe1wjA//T.IEvHz8Zw8mbhX6cMNjutmqK', NULL, '2017-03-30 05:13:36', '2017-03-30 05:13:36', '', 'testuser404', '', 1, 1, 0, '0', '', '', '', 0, ''),
(125, 'test', 'user', 'testuser405@flashsale.com', '$2y$10$Gzv8/.GEf7uX0JEWZq1uEuZCt4xBnKPmERxI7srctG0ojWZKSJDXO', NULL, '2017-03-30 05:15:14', '2017-03-30 05:15:14', '', 'testuser405', '', 1, 1, 0, '0', '', '', '', 0, ''),
(126, 'test', 'user', 'testuser406@flashsale.com', '$2y$10$CjYoW6RvOXme.nK6LV51IuLGeC4PjM.Gp6dI0ORNRHkV7SnPia6.S', NULL, '2017-03-30 05:17:02', '2017-03-30 05:17:02', '', 'testuser406', '', 1, 1, 0, '0', '', '', '', 0, ''),
(127, 'test', 'user', 'testuser407@flashsale.com', '$2y$10$hha4R6/2JzVhe7bpury3heMR6qEHOHNiD/9vEUgSFO9lyyDe8fBgq', NULL, '2017-03-30 05:21:58', '2017-03-30 05:21:58', '', 'testuser407', '', 1, 1, 0, '0', '', '', '', 0, 'thfgh'),
(128, 'test', 'user', 'testuser408@flashsale.com', '$2y$10$nifAM9ylQeM6nSTH6grJreQ7W4m2lA1r.x.svjib8GoWYNzKt4zfa', NULL, '2017-03-30 05:22:46', '2017-03-30 05:22:46', '', 'testuser408', '', 1, 1, 0, '0', '', '', '', 0, 'thfgh'),
(129, 'sdfsdfsdf', 'sdfsdfsdf', 'sdgsdgsdf@wefwesf.com', '$2y$10$NpGcVzXwpxhYgrdKtpkhru7mdlj/FYeRD3c59YDX8caScT5sLjHwy', NULL, '2017-03-30 05:27:20', '2017-03-30 05:27:20', '', 'sdgfdsgdgds', '', 1, 1, 0, '0', '', '', '', 0, 'thfgh'),
(130, 'opoo', 'opopp', 'asdadasd@flashsale.com', '$2y$10$eDLCpxDmb729OQxJgJGrouBOWWSrFmDPiOc06mu3hgjnrlHuCLeLO', NULL, '2017-03-30 06:15:22', '2017-03-30 06:15:22', '', 'dsfsdfsdf', '/assets/images/avatar-placeholder.jpg', 3, 0, 0, '0', '', '', '', 0, ''),
(133, 'opoo', 'opopp', 'test@flashsale.com', '$2y$10$9gkA/kQHfAKgX/A9UfCG2uxQDIWHAsg21KDqLVV3gBCkBT7nVZtsq', NULL, '2017-03-30 06:32:21', '2017-03-30 06:32:21', '', 'testtest', '/assets/images/avatar-placeholder.jpg', 3, 0, 0, '0', '', '', '', 0, ''),
(134, 'u', 'u', 'u@u.com', '$2y$10$7HK55Kp8tdAQdJjfRBg6FueTdhNxOeaMRmOj32Rf2V1Ms2epauGdO', NULL, '2017-03-30 06:56:33', '2017-03-30 06:56:33', '', 'u', '/assets/images/avatar-placeholder.jpg', 3, 0, 0, '0', '', '', '', 0, ''),
(135, 'tyuty', 'tyuytuyt', 'b@flashsale.com', '$2y$10$7NJlbv1LaR.XKoMYkKNXl.x.f4ObIIYcvaKozbzZLxEzfbP6be7Tq', NULL, '2017-03-30 07:55:17', '2017-03-30 07:55:17', '', 'bb', '/assets/images/avatar-placeholder.jpg', 3, 0, 0, '0', '', '', '', 0, ''),
(136, 'yyyy', 'yyyy', 'yyy@yyy.com', '$2y$10$hNx5HrQa.ftui2CYe9aR4.kBuUTBzOwQSY1UZ.cHOttBTAABOYKvC', NULL, '2017-03-30 08:02:44', '2017-03-30 08:02:44', '', 'yyy', '/assets/images/avatar-placeholder.jpg', 3, 0, 0, '0', '', '', '', 0, ''),
(138, 'i', 'i', 'i@i.i', '$2y$10$dYhRbmZSgxR42ippXdix0OkRiF8hCXgDQaqbpnC/PrceoYWEdWBaG', NULL, '2017-03-30 08:48:26', '2017-03-30 08:48:26', '', 'i', '/assets/images/avatar-placeholder.jpg', 3, 1, 0, '0', '', '', '', 0, 'i'),
(139, 'ty', 'ty', 'ty@ty.com', '$2y$10$yCabRM5RT/q3rzAwTI6ORevhAPQUEf.S9tNWnBk5JR74b0EYAk6Qi', NULL, '2017-04-01 00:10:50', '2017-04-01 00:10:50', '', 'ty', '/assets/images/avatar-placeholder.jpg', 3, 1, 0, '0', '', '', '', 0, 'ty'),
(140, 'aa', 'aa', 'aa@aa.com', '$2y$10$oLa9A14CObOZt0pZJcl4du6ZEd/G43tE0E7kEziwuHAo7WgYxdW8K', NULL, '2017-04-01 01:40:32', '2017-04-01 01:40:32', '', 'aa', '/assets/images/avatar-placeholder.jpg', 3, 1, 0, '0', '', '', '', 0, 'aa'),
(141, 'aaa', 'aaa', 'admin1@flashsale.com', '$2y$10$mwJyeBmeZpy5vOpXKYGhQuc.cvooTT36wWz9Zb84p0Q6kZgUicbR.', NULL, '2017-04-01 02:03:04', '2017-04-01 02:03:04', '', 'aaa', '/assets/images/avatar-placeholder.jpg', 3, 1, 0, '0', '', '', '', 0, 'aaa');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
