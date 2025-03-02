-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 02, 2025 at 04:45 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `slothtravel`
--

-- --------------------------------------------------------

--
-- Table structure for table `aboutus`
--

CREATE TABLE `aboutus` (
  `AboutID` int(10) NOT NULL,
  `AboutImg` varchar(255) NOT NULL,
  `AboutTitle` varchar(100) NOT NULL,
  `AboutProfile` varchar(255) NOT NULL,
  `AboutDetail` text NOT NULL,
  `AboutSubTitle` varchar(100) NOT NULL,
  `AboutSubDetail` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `aboutus`
--

INSERT INTO `aboutus` (`AboutID`, `AboutImg`, `AboutTitle`, `AboutProfile`, `AboutDetail`, `AboutSubTitle`, `AboutSubDetail`) VALUES
(7, 'loginBGn.jpg', 'What Are We Doing', 'profile.jpg', 'ในการจัดทำเว็ปไซด์นี้มีวัตถุประสงค์เพื่อเป็นการศึกษาและเรียนรู้การทำเว็ปไซด์ในการทำงานจริงและการเก็บรวบรวมประสบการณ์การทำงานตลอดช่วงเวลา 4 เดือนตั้งแต่ 11 พฤศจิกายน พ.ศ. 2567 จนถึง 28 กุมภาพันธ์ พ.ศ. 2568 ทางผมขอขอบคุณหัวหน้าและพี่ๆ จากห้างหุ้นส่วนจำกัด เชียงใหม่โซนดอทคอมที่คอยมอบความรู้และแนะนำสิ่งต่างๆ ให้กับผม และผมจะนำความรู้เหล่าไปใช้ต่อยอดในอนาคตต่อไป', 'นาย ปิยวัฒน์ แหลมหลัก', 'นักศึกษาคณะวิทยาศาสตร์ สาขาวิทยาการคอมพิวเตอร์ มหาวิทยาลัยนเรศวร');

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `AdminID` int(10) NOT NULL,
  `AdminUserName` varchar(100) NOT NULL,
  `AdminPassword` varchar(255) NOT NULL,
  `AdminEmail` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`AdminID`, `AdminUserName`, `AdminPassword`, `AdminEmail`) VALUES
(1, 'SlothAdmin', '$2y$10$nkEgLQ4HRYHeKKhMlOnxwOf2wmqXAG84eu6E38py51D/4WWIWqdlO', 'slothadmin@gmail.com'),
(2, 'slothjiAdmin', 'slothji567', 'slothjiAdmin@gmail.com'),
(3, 'imadmin', '$2y$10$IEg80adpeoB6vcE/bkyoA.b0g8pBJjrbWOX2KVGZNOvdrZc0shpCC', 'imadmin23@gmail.com'),
(4, 'itsme', '$2y$10$EG9LDWxaK/ByuDeywDqL/uUVQNjN.UZiC493F7849LxTLkBC5gUhK', 'itsme@gmail.com'),
(20, 'me', '$2y$10$PCT5QWAVptUTPGGBVIVaue.p4QXh.HYRcgQ1IVUe.vo3Rxjk4fBQ2', 'meadmin@gmail.com'),
(34, 'meadm', '$2y$10$5E9Sa5YDLn8KgXMdYdyMJ.c/4qD1orcfwV1.pM36t1HT0R5PLbjdS', 'meadmin@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `allvisitors`
--

CREATE TABLE `allvisitors` (
  `allvisitorID` int(11) NOT NULL,
  `PageName` varchar(50) NOT NULL,
  `VisitCount` int(11) DEFAULT 0,
  `LastUpdated` date NOT NULL,
  `VisitDateTime` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `allvisitors`
--

INSERT INTO `allvisitors` (`allvisitorID`, `PageName`, `VisitCount`, `LastUpdated`, `VisitDateTime`) VALUES
(5, 'homepage', 2, '2025-02-07', '2025-02-07 04:58:47'),
(6, 'homepage', 4, '2025-01-01', '2025-01-01 10:59:09'),
(7, 'homepage', 3, '0000-00-00', '2025-02-09 18:28:44'),
(8, 'homepage', 3, '0000-00-00', '2025-02-10 09:36:19'),
(9, 'homepage', 5, '0000-00-00', '2025-02-11 05:21:05'),
(10, 'homepage', 2, '0000-00-00', '2025-02-13 10:14:50'),
(11, 'homepage', 1, '0000-00-00', '2025-02-14 04:15:42'),
(12, 'homepage', 1, '0000-00-00', '2025-02-17 05:42:15'),
(13, 'homepage', 2, '0000-00-00', '2025-02-18 08:57:09'),
(14, 'homepage', 2, '0000-00-00', '2025-02-19 06:13:44'),
(15, 'homepage', 5, '0000-00-00', '2025-02-21 10:13:08'),
(16, 'homepage', 1, '0000-00-00', '2025-02-22 03:15:39'),
(17, 'homepage', 5, '0000-00-00', '2025-02-23 07:57:36'),
(18, 'homepage', 2, '0000-00-00', '2025-02-24 08:27:51'),
(19, 'homepage', 23, '0000-00-00', '2025-02-25 05:11:31'),
(20, 'homepage', 43, '0000-00-00', '2025-02-26 15:35:50'),
(21, 'homepage', 69, '0000-00-00', '2025-02-27 13:33:36'),
(22, 'homepage', 34, '0000-00-00', '2025-02-28 09:21:27');

-- --------------------------------------------------------

--
-- Table structure for table `gallery`
--

CREATE TABLE `gallery` (
  `GalleryID` int(10) NOT NULL,
  `GalleryImg` varchar(255) NOT NULL,
  `PlaceID` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `gallery`
--

INSERT INTO `gallery` (`GalleryID`, `GalleryImg`, `PlaceID`) VALUES
(79, 'PL20241226092858_4686733.jpg', 42),
(80, 'PL20241226092858_d60c961.jpg', 42),
(81, 'PL20241226092858_0079589.jpg', 42),
(82, 'PL20241226095451_27eb229.jpg', 43),
(83, 'PL20241226102442_0ba7118.jpg', 44),
(84, 'PL20241226102442_8093817.jpg', 44),
(85, 'PL20241226102442_6b46857.png', 44),
(86, 'PL20241227035244_dbc8948.jpg', 45),
(87, 'PL20241227035244_377e742.jpg', 45),
(88, 'PL20241227045214_c87f422.jpg', 47),
(89, 'PL20241227045214_7cb4586.jpg', 47),
(90, 'PL20241227045214_7698730.jpg', 47),
(91, 'PL20241227051957_1fab182.jpg', 48),
(92, 'PL20241227051957_ca5a540.jpg', 48),
(93, 'PL20241227051958_6d23973.jpg', 48),
(94, 'PL20241227085859_6724189.jpg', 49),
(95, 'PL20241227085859_62ef476.jpg', 49),
(96, 'PL20241227085859_dbc4135.jpg', 49),
(97, 'PL20241227091219_3d07875.jpg', 46),
(98, 'PL20241227091219_3627161.jpg', 46),
(99, 'PL20241227091219_d22e549.jpg', 46),
(100, 'PL20241227093543_3138396.jpg', 50),
(101, 'PL20241227093543_cc64244.jpg', 50),
(102, 'PL20241227093543_59f4875.jpg', 50),
(106, 'PL20250106045534_fb93832.jpg', 52),
(107, 'PL20250106045534_93e5771.jpg', 52),
(108, 'PL20250106045534_9e20659.jpg', 52),
(109, 'PL20250106051206_da8a626.jpg', 53),
(110, 'PL20250106051206_f9cc635.jpg', 53),
(111, 'PL20250106051206_9384311.jpg', 53),
(112, 'PL20250106051206_0dd3832.jpg', 53),
(113, 'PL20250108051954_3672863.jpg', 43),
(114, 'PL20250108051954_8597423.jpg', 43),
(115, 'PL20250108100036_d428837.jpg', 51),
(116, 'PL20250108100036_07bb763.jpg', 51),
(118, 'PL20250109100829_73bf199.jpg', 45),
(215, 'PL20250302043700_a5dd292.jpg', 94),
(216, 'PL20250302043700_9b89912.jpg', 94),
(217, 'PL20250302043700_1d24203.jpg', 94);

-- --------------------------------------------------------

--
-- Table structure for table `homeslide`
--

CREATE TABLE `homeslide` (
  `HomeID` int(10) NOT NULL,
  `HomeImg` varchar(255) NOT NULL,
  `HomeStatus` tinyint(1) NOT NULL DEFAULT 1,
  `HomeSort` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `homeslide`
--

INSERT INTO `homeslide` (`HomeID`, `HomeImg`, `HomeStatus`, `HomeSort`) VALUES
(9, '1740367289_mountainEx.jpg', 1, 5),
(10, '1740367342_mountainEx2.jpg', 1, 1),
(11, '1740555072_Majestic Wachirathan Waterfall.jpg', 1, 3),
(12, '1740367453_hotelEx.jpg', 1, 2),
(13, '1740367475_templeEx3.jpg', 1, 4),
(24, '1740730873_resized_image.jpg', 1, 6);

-- --------------------------------------------------------

--
-- Table structure for table `places`
--

CREATE TABLE `places` (
  `PlaceID` int(10) NOT NULL,
  `PlaceName` varchar(100) NOT NULL,
  `PlaceImg` varchar(150) NOT NULL,
  `PlaceTitle` varchar(100) NOT NULL,
  `PlaceSubTitle` text NOT NULL,
  `PlaceDetail` text NOT NULL,
  `PlaceLocation` text NOT NULL,
  `isVisible` tinyint(1) DEFAULT 1,
  `PlaceNumbers` int(10) NOT NULL DEFAULT 0,
  `TypeID` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `places`
--

INSERT INTO `places` (`PlaceID`, `PlaceName`, `PlaceImg`, `PlaceTitle`, `PlaceSubTitle`, `PlaceDetail`, `PlaceLocation`, `isVisible`, `PlaceNumbers`, `TypeID`) VALUES
(42, 'ต๋อง เต็ม โต๊ะ ', 'PL20250108091201_fa08846.jpg', 'ต๋อง เต็ม โต๊ะ ', 'ร้านอาหารเหนือย่านนิมมานห์ บรรยากาศนั่งสบาย มีเมนูอาหารมากมายให้เลือก', 'เป็นร้านอาหารสไตล์ภาคเหนือ มีเมนูอาหารที่หลายหลายน่ารับประทาน บรรยากาศร้านมีความร่มรื่น เป็นร้านอาหารชื่อดัง กลางวันคิวจะไม่ค่อยเยอะ แต่กลางคืนจะมีคนแน่นหนา ร้านตั้งอยู่ที่ 11 ถนน นิมมานเหมินท์ ซอย 13 ตำบลสุเทพ อำเภอเมืองเชียงใหม่ เชียงใหม่ 50200', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3590.150571228959!2d98.96499237497011!3d18.796588082351313!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x30da3a628a7efc09%3A0xf5ab324f73da2d39!2z4LiV4LmL4Lit4LiH4LmA4LiV4LmH4Lih4LmC4LiV4LmK4LiwIOC4i-C4reC4oiAxMw!5e1!3m2!1sth!2sth!4v1735200817009!5m2!1sth!2sth', 1, 4, 1),
(43, 'กระเพราซาวเดียว', 'PL20250108084853_64c5178.jpg', 'กระเพราซาวเดียว', 'ร้านอาหารที่มีเมนูเด็ดเป็นกะเพรา โดยมีเมนูแนะนำคือกระเพราราคา 20 บาท!!!', 'ร้านอาหารตามสั่งที่ไม่ธรรมดา มีเมนูกะเพราที่หลากหลายรสชาติถูกปาก สามารถเติมข้าวได้ มีการจัดจานที่สวยงาม บรรยากาศร้านสบายๆ ร้านตั้งอยู่ที่ โครงการเชียงใหม่บิสเนสพาร์ค 143 ซอย แม่คาว หมู่ 4 ตำบลหนองป่าครั่ง อำเภอเมืองเชียงใหม่ เชียงใหม่ 50000 ร้านเปิดเวลา 9.00 ถึง 20.00 น. หยุดทุกวันเสาร์', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d57442.410681452064!2d98.92739474220328!3d18.79658356405505!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x30da25e88b24d529%3A0xc630394a41e0e60f!2z4LiB4Lij4Liw4LmA4Lie4Lij4Liy4LiL4Liy4Lin4LmA4LiU4Li14Lii4LinIOC4quC4suC4guC4siBDQlDguYDguIrguLXguKLguIfguYPguKvguKHguYjguJrguLTguKrguIvguLTguYDguJnguKrguJ7guLLguKPguYzguIQ!5e1!3m2!1sth!2sth!4v1735203179603!5m2!1sth!2sth', 1, 1, 1),
(44, 'ข้าวสุดซอยเชียงใหม่', 'PL20250108090001_0760367.jpg', 'ข้าวสุดซอยเชียงใหม่', 'ร้านข้าวซอยไก่ที่ควรมาลิ้มลองสักครั้ง บรรยากาศสบายๆ เหมาะแก่การมานั่งชิล', 'ร้านอาหารภาคเหนือที่มีความน่าสนใจ มีเมนูเด็ดคือ ข้าวซอยไก่ ที่มีรสชาติดีเยี่ยม บรรยากาศร้านมีความสบายๆ การตกแต่งร้านมีความน่าสนใจและดูทันสมัย ร้านตั้งอยู่ที่ 108/3-8 M.4 Nongpakrang, เชียงใหม่ 50000 ร้านเปิดทุกวันเวลา 10.00 ถึง 17.30 น.', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d57442.410681452064!2d98.92739474220328!3d18.79658356405505!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x30da25e88b24d529%3A0xc630394a41e0e60f!2z4LiB4Lij4Liw4LmA4Lie4Lij4Liy4LiL4Liy4Lin4LmA4LiU4Li14Lii4LinIOC4quC4suC4guC4siBDQlDguYDguIrguLXguKLguIfguYPguKvguKHguYjguJrguLTguKrguIvguLTguYDguJnguKrguJ7guLLguKPguYzguIQ!5e1!3m2!1sth!2sth!4v1735203179603!5m2!1sth!2sth', 1, 5, 2),
(45, 'น้ำตกแม่ยะ', 'PL20250108090137_bd21973.jpg', 'น้ำตกแม่ยะ', 'น้ำตกชื่อดังแห่งดอยอินทนนท์ ทีดีกรีเป็นน้ำตกที่สวยอันดับต้นๆของประเทศไทย สำหรับคนที่มาเที่ยวเชียงใหม่แล้ว ไม่ควรพลาด!!', 'เป็นน้ำตกที่ตั้งอยู่ในส่วนพื้นที่ของดอยอินทนนท์ ตัวน้ำตกมีขนาดใหญ่และมีหลายชั้นทำให้มีความสวยงาม ในบริเวณน้ำตกมีสิ่งอำนวยความสะดวกอยู่ครบทั้ง ร้านอาหาร ที่จอดรถ ร้านค้าและห้องน้ำพร้อมบริการ ที่ตั้งอยู่ที่ ทางหลวงหมายเลข 1009 บ้านหลวง จอมทอง เชียงใหม่ ', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d57442.410681452064!2d98.92739474220328!3d18.79658356405505!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x30da25e88b24d529%3A0xc630394a41e0e60f!2z4LiB4Lij4Liw4LmA4Lie4Lij4Liy4LiL4Liy4Lin4LmA4LiU4Li14Lii4LinIOC4quC4suC4guC4siBDQlDguYDguIrguLXguKLguIfguYPguKvguKHguYjguJrguLTguKrguIvguLTguYDguJnguKrguJ7guLLguKPguYzguIQ!5e1!3m2!1sth!2sth!4v1735203179603!5m2!1sth!2sth', 1, 6, 1),
(46, 'หมู่บ้านลึกลับ เมืองไดโนเสาร์ ', 'PL20250108084305_9a5f287.jpg', 'หมู่บ้านลึกลับ เมืองไดโนเสาร์ ', 'ที่เที่ยวแนะนำสำหรับครอบครัว เข้าสู่ดลกแห่งจินตนาการไปกับเหล่าไดโนเสาร์สุดแสนมหัศจรรย์', 'สถานที่ท่องเที่ยวที่เหมาะกับการไปกับครอบครัว ภายในมีไดโนเสาร์จำลองหลายสายพันธุ์ มีฟาร์มสัตว์เล็ก บ่อปลาคราฟและมีสนามเด็ดเล่นไว้ให้เด็กอีกด้วย ตั้งอยู่ที่ 77/19 ตำบล สันผีเสื้อ อำเภอเมืองเชียงใหม่ เชียงใหม่', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3589.371423561025!2d98.99501457497101!3d18.83308728232211!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x30da3b241f06702f%3A0x8e214fc6dae3ea39!2z4Lir4Lih4Li54LmI4Lia4LmJ4Liy4LiZ4Lil4Li24LiB4Lil4Lix4Lia4LmA4Lih4Li34Lit4LiH4LmE4LiU4LmC4LiZ4LmA4Liq4Liy4Lij4LmM4LmA4LiK4Li14Lii4LiH4LmD4Lir4Lih4LmIIChISURERU4gVklMTEFHRSBDSElBTkcgTUFJKQ!5e1!3m2!1sth!2sth!4v1735894626288!5m2!1sth!2sth', 1, 2, 1),
(47, 'วัดเจดีย์หลวงวรวิหาร', 'PL20250108090823_2929579.jpg', 'วัดเจดีย์หลวงวรวิหาร', 'วัดเจดีย์หลวงใจกลางเมืองเชียงใหม่ สถานที่ทางประวัติที่ยังคงสวยงามแม้ผ่านไปนานเท่าไหร่ก็ตาม', 'เป็นพระอารามหลวงในจังหวัดเชียงใหม่ มีชื่อเรียกหลายชื่อ ได้แก่ ราชกุฏาคาร วัดโชติการาม สร้างขึ้นในรัชสมัยพญาแสนเมืองมา พระมหากษัตริย์รัชกาลที่ 7 แห่งราชวงศ์มังราย ตั้งอยู่ที่ 103 ถ. พระปกเกล้า ตำบลศรีภูมิ อำเภอเมืองเชียงใหม่ เชียงใหม่', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d57442.410681452064!2d98.92739474220328!3d18.79658356405505!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x30da25e88b24d529%3A0xc630394a41e0e60f!2z4LiB4Lij4Liw4LmA4Lie4Lij4Liy4LiL4Liy4Lin4LmA4LiU4Li14Lii4LinIOC4quC4suC4guC4siBDQlDguYDguIrguLXguKLguIfguYPguKvguKHguYjguJrguLTguKrguIvguLTguYDguJnguKrguJ7guLLguKPguYzguIQ!5e1!3m2!1sth!2sth!4v1735203179603!5m2!1sth!2sth', 1, 7, 1),
(48, 'เซ็นทรัลเฟสติวัล เชียงใหม่', 'PL20250108092131_85f3449.jpg', 'เซ็นทรัลเฟสติวัล เชียงใหม่', 'ห้างสรรพสินค้าชั้นนำ ของย่านเชียงใหม่ มีร้านค้าหลากหลายให้เลือกช็อป', 'ศูนย์รวมร้านค้า ร้านอาหารชั้นนำของเชียงใหม่ มีสินค้าหลากหลายให้เลือกซื้อ สามารถพาครอบครัวมาทานอาหารหรือเที่ยวชมภายในห้างได้ อีกทั้งยังมีโซนกิจกรรมและโซนโรงหนังอีกกด้วย ตั้งอยู่ที่ 99, 99/1 99/2 หมู่ที่ 4 ถ. ซุปเปอร์ไฮเวย์ เชียงใหม่-ลำปาง ตำบล ฟ้าฮ่าม อำเภอเมืองเชียงใหม่ เชียงใหม่', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d57442.410681452064!2d98.92739474220328!3d18.79658356405505!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x30da25e88b24d529%3A0xc630394a41e0e60f!2z4LiB4Lij4Liw4LmA4Lie4Lij4Liy4LiL4Liy4Lin4LmA4LiU4Li14Lii4LinIOC4quC4suC4guC4siBDQlDguYDguIrguLXguKLguIfguYPguKvguKHguYjguJrguLTguKrguIvguLTguYDguJnguKrguJ7guLLguKPguYzguIQ!5e1!3m2!1sth!2sth!4v1735203179603!5m2!1sth!2sth', 1, 3, 1),
(49, 'รัง​ษิ​มา​เซรามิค', 'PL20250108092905_d20f766.jpg', 'รัง​ษิ​มา​เซรามิค', 'ร้านขายจานชามเซรามิคคชื่อดังของเชียงใหม่ที่มีสินค้าเครื่องใช้เซราคาที่หลากหลาย', 'เป็นร้านขายสินค้าจำพวกเครื่องจานชามเซรามิคมีทั้งเครื่องชาม ถ้วย แก้วน้ำ และจานลายต่างๆ ที่มีความสวยงาม ราคาเป็นกันเอง ตั้งอยู่ที่ 69 1 ถ. ซุปเปอร์ไฮเวย์ เชียงใหม่-ลำปาง ตำบลหนองป่าครั่ง อำเภอเมืองเชียงใหม่ เชียงใหม่', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d57442.410681452064!2d98.92739474220328!3d18.79658356405505!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x30da25e88b24d529%3A0xc630394a41e0e60f!2z4LiB4Lij4Liw4LmA4Lie4Lij4Liy4LiL4Liy4Lin4LmA4LiU4Li14Lii4LinIOC4quC4suC4guC4siBDQlDguYDguIrguLXguKLguIfguYPguKvguKHguYjguJrguLTguKrguIvguLTguYDguJnguKrguJ7guLLguKPguYzguIQ!5e1!3m2!1sth!2sth!4v1735203179603!5m2!1sth!2sth', 1, 8, 1),
(50, 'โฮม สุขภัณฑ์', 'PL20250108093003_0e3b643.jpg', 'โฮม สุขภัณฑ์', 'ห้างสรรพสินค้าที่ขายเฟอร์นิเจอร์เกี่ยวกับสุขภัณฑ์ ครบเครื่องเรื่องห้องสุขภัณฑ์', 'เป็นห้างสรรพสินค้าที่เน้นขายสินค้าเกี่ยวกับสุขภัณฑ์ รวมไปถึงกระเบื้องห้องน้ำ มีเฟอร์นิเจอร์ให้เลือกซื้อมากมายในราคาที่จับต้องได้ ตั้งอยู่ที่ 56 ซอยแม่ย้อยใต้หมู่ 2 สันพระเนตร อำเภอสันทราย เชียงใหม่', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1588.016643227148!2d99.03165357227459!3d18.799874302313032!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x30da2540f989923d%3A0x4e65a4d9de292deb!2sHome%20Sukkapan!5e0!3m2!1sen!2sth!4v1735288518525!5m2!1sen!2sth', 1, 9, 1),
(51, 'Nap In Fest', 'PL20250108100036_1689263.jpg', 'Nap In Fest', 'โรงแรมระดับ 3 ดาวใกล้ Central festival ในราคาที่จับต้องได้ ', 'เป็นโรงแรมที่มีพนักงานรักษาความปลอดภัยบริการตลอด 24 ชั่วโมง ที่พักมีความสะอาดเรียบร้อย มีอาหารสไตล์ยุโรปและอิตาลีพร้อมให้บริการ โรงแรมตั้งอยู่ที่ 9/8 หมู่ 4 ถนนเชียงใหม่-ดอยสะเก็ด ฟ้าฮ่าม เมือง เชียงใหม่ ประเทศไทย ฟ้าฮ่าม อำเภอเมืองเชียงใหม่ เชียงใหม่', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1794.9848391700796!2d99.01760668869386!3d18.80506810186018!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x30da2536747ffab3%3A0x5b47b2c99e744f2d!2z4LmC4Lij4LiH4LmB4Lij4Lih4LmB4LiZ4Lib4Lit4Li04LiZ4LmA4Lif4Liq!5e1!3m2!1sth!2sth!4v1736326686362!5m2!1sth!2sth', 1, 11, 1),
(52, 'Dream Living Chiang Mai Pool Villa', 'PL20250108101607_050b142.jpg', 'Dream Living Chiang Mai Pool Villa', 'Pool Villa ที่มีสิ่งอำนวยความสะดวกอยู่ครบครัน เหมาะสำหรับการพาครอบครัวมาปาร์ตี้', 'เป็นบ้านพักสไตล์ Pool Villa ที่มีสิ่งอำนวยความสะดวกมากมายอย่างมรห้องนั่งเล่นขนาดใหญ่ มีสระว่ายน้ำกลางแจ้ง มีอ่างอาบน้ำ มีสนามกอร์ฟและอื่นๆ ตั้งอยู่ที่ 379 ซ.หมู่บ้านสินธนา อ.สันทราย เชียงใหม่', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1794.9768347244533!2d99.03036661193349!3d18.805818399999996!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x30da253efdf83a7b%3A0xc0a59af876f6e064!2sDream%20Living%20Chiang%20Mai%20Pool%20Villa!5e1!3m2!1sth!2sth!4v1736135710321!5m2!1sth!2sth', 1, 10, 1),
(53, 'Premier Hostel Chiang Mai', 'PL20250106051206_2336241.jpg', 'Premier Hostel Chiang Mai', 'โรงแรมใกล้วัดเจดีย์หลวงที่มีความน่าสนใจ มีสิ่งอำนวยความสะดวกพร้อม และมีการตกแต่งที่สวยงาม', 'เป็นโรงแรมที่ตั้งอยู่ในทำเลสะดวกในเชียงใหม่ อยู่ใกล้สถานที่ท่องเที่ยวที่สำคัญอย่างวัดเจดีย์หลวง ประตูท่าแพ ตลาดช้างเผือกและอื่นๆ มีสอ่งอำนวยความสะดวกหลายอย่าง และภายในโรงแรมมีการตกแต่งที่มีความร่มรื่นและสวยงาม', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1795.0829320896548!2d98.98196579839477!3d18.795870999999998!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x30da3b781754b4bf%3A0xa8a58399e2610e97!2sPremier%20Hostel%20Chiang%20Mai!5e1!3m2!1sth!2sth!4v1736136704311!5m2!1sth!2sth', 1, 12, 1),
(94, 'ยีราฟ', 'PL20250302043700_92ab936.jpg', 'k', 'u', 'uk', 'ku', 1, 13, 15);

-- --------------------------------------------------------

--
-- Table structure for table `reviewcounts`
--

CREATE TABLE `reviewcounts` (
  `PlaceID` int(10) NOT NULL,
  `TotalReviews` int(11) NOT NULL DEFAULT 0,
  `MonthlyReviews` int(11) NOT NULL DEFAULT 0,
  `DailyReviews` int(11) NOT NULL DEFAULT 0,
  `LastUpdated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviewcounts`
--

INSERT INTO `reviewcounts` (`PlaceID`, `TotalReviews`, `MonthlyReviews`, `DailyReviews`, `LastUpdated`) VALUES
(43, 1, 1, 1, '2025-02-10 14:44:57'),
(44, 1, 1, 1, '2025-02-25 10:26:39'),
(45, 2, 2, 2, '2025-02-07 09:56:34'),
(46, 4, 4, 4, '2025-02-26 15:41:36'),
(47, 1, 1, 1, '2025-02-10 14:44:30');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `ReviewID` int(10) NOT NULL,
  `UserID` int(10) NOT NULL,
  `PlaceID` int(10) NOT NULL,
  `Rating` int(1) NOT NULL,
  `Comment` text NOT NULL,
  `ReviewDate` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`ReviewID`, `UserID`, `PlaceID`, `Rating`, `Comment`, `ReviewDate`) VALUES
(91, 4, 45, 4, 'go', '2025-01-17 11:06:38'),
(92, 4, 45, 4, 'นานา', '2025-01-17 11:39:49'),
(93, 4, 45, 3, 'ไก่กา', '2025-01-17 11:39:57'),
(94, 4, 45, 4, '555', '2025-01-17 11:40:05'),
(95, 4, 45, 1, 'ไม่ชอบเลย', '2025-01-17 11:40:14'),
(96, 4, 45, 5, 'สวยมาก', '2025-01-17 11:40:22'),
(97, 4, 45, 3, 'น่าสนใจ', '2025-01-17 11:40:33'),
(98, 4, 46, 5, 'ดีย์', '2025-01-17 14:03:59'),
(99, 4, 47, 4, 'wow', '2025-01-17 16:15:27'),
(100, 4, 42, 5, 'hoho', '2025-01-17 16:15:51'),
(101, 4, 43, 4, 'lol', '2025-01-17 16:19:17'),
(102, 4, 43, 5, 'kuru kuru', '2025-01-29 11:12:28'),
(103, 4, 45, 5, 'ok', '2025-02-05 10:01:14'),
(104, 4, 47, 4, 'okok', '2025-02-05 10:01:25'),
(105, 4, 52, 3, 'cookie', '2025-02-05 10:01:40'),
(114, 4, 45, 5, '555', '2025-02-07 09:56:08'),
(115, 4, 45, 4, '567', '2025-02-07 09:56:34'),
(116, 4, 47, 3, '5656', '2025-02-10 14:44:30'),
(117, 4, 43, 5, 'yum yum', '2025-02-10 14:44:57'),
(118, 4, 46, 5, '5656', '2025-02-18 14:57:38'),
(119, 4, 46, 4, 'so fun', '2025-02-25 09:45:20'),
(120, 4, 44, 5, 'อร่อยดี', '2025-02-25 10:26:39'),
(121, 4, 46, 5, 'ไดโนเสาร์ตัวใหญ่มากกกกกกกกกก', '2025-02-25 15:37:50'),
(122, 4, 46, 5, '2131', '2025-02-26 15:41:36');

--
-- Triggers `reviews`
--
DELIMITER $$
CREATE TRIGGER `update_review_counts` AFTER INSERT ON `reviews` FOR EACH ROW BEGIN
    INSERT INTO reviewcounts (PlaceID, TotalReviews, MonthlyReviews, DailyReviews, LastUpdated)
    VALUES (
        NEW.PlaceID, 
        1, 
        IF(MONTH(NEW.ReviewDate) = MONTH(CURRENT_DATE), 1, 0), 
        IF(DATE(NEW.ReviewDate) = CURRENT_DATE, 1, 0),
        NOW()
    )
    ON DUPLICATE KEY UPDATE 
        TotalReviews = TotalReviews + 1,
        MonthlyReviews = IF(MONTH(NEW.ReviewDate) = MONTH(CURRENT_DATE), MonthlyReviews + 1, MonthlyReviews),
        DailyReviews = IF(DATE(NEW.ReviewDate) = CURRENT_DATE, DailyReviews + 1, DailyReviews),
        LastUpdated = NOW();
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `typeplace`
--

CREATE TABLE `typeplace` (
  `TypeID` int(10) NOT NULL,
  `TypeTitle` varchar(100) NOT NULL,
  `TypeImg` varchar(255) NOT NULL,
  `TypeDetail` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `typeplace`
--

INSERT INTO `typeplace` (`TypeID`, `TypeTitle`, `TypeImg`, `TypeDetail`) VALUES
(1, 'ท่องเที่ยว', 'maeya waterfall 2.jpg', 'กำลังมองหาสถานที่ท่องเที่ยวที่น่าสนใจอยู่ใช่ไหม?'),
(2, 'ร้านอาหาร', 'vicky-ng-NT5oqzp-050-unsplash.jpg', 'กำลังมองหาร้านอาหารที่น่าสนใจอยู่ใช่ไหม?'),
(3, 'ร้านค้า', 'retno-dwinika-dG3s-gHU2Qo-unsplash.jpg', 'กำลังมองหาร้านค้าที่น่าสนใจอยู่ใช่ไหม?'),
(15, 'ที่พัก', 'chiangmai hotel.jpg', 'กำลังมองหาที่พักที่น่าสนใจอยู่ใช่ไหม?');

-- --------------------------------------------------------

--
-- Table structure for table `userlogins`
--

CREATE TABLE `userlogins` (
  `LoginID` int(10) NOT NULL,
  `UserID` int(10) NOT NULL,
  `LoginDate` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `userlogins`
--

INSERT INTO `userlogins` (`LoginID`, `UserID`, `LoginDate`) VALUES
(1, 4, '2025-02-07 10:06:53'),
(2, 4, '2025-02-10 14:44:15'),
(3, 4, '2025-02-11 10:14:57'),
(4, 4, '2025-02-13 16:14:50'),
(5, 4, '2025-02-18 14:57:09'),
(6, 4, '2025-02-23 13:38:07'),
(7, 4, '2025-02-25 09:45:03'),
(8, 4, '2025-02-25 15:37:14'),
(9, 4, '2025-02-26 15:41:24'),
(11, 7, '2025-02-27 10:24:21'),
(12, 7, '2025-02-27 11:39:35');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `UserID` int(10) NOT NULL,
  `UserName` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Passwords` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Emails` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`UserID`, `UserName`, `Passwords`, `Emails`) VALUES
(2, 'Moodeng', '$2y$10$JMIjsl6vezNoHTNeJtkHU.6zwa28UVewsC3SQxe2RFP1Rz/M/kI9u', 'moodeng@gmail.com'),
(3, 'PlagudThong', '0878ecf8048e6266bc783eb0ece26b56', 'plagudth@gmail.com'),
(4, 'KimMan', '$2y$10$CBZtiNOEehO9OdSDZ9hx/ecGkBSVOoJscchaAhygkgDOTqIW7yklm', 'kimman23@gmail.com'),
(5, 'slowmotion', '$2y$10$17B3vC9Xo9BnXRLwMTsvLOxO2QG/4Be6OZ9myBKQSrSTo1nr0qIgS', 'slomo@gmail.com'),
(6, 'kimmy', '$2y$10$2yqiTHYKfpd96nAS0IPXGeI8YCYwNYYCcM2uVKPp8CI5EY.q1Er6.', 'kimmy@gmail.com'),
(7, 'Slothji', '$2y$10$uHi/.djItOAxt5ELX8w2GOXBz6eFUIJC9BxCh/49av.BzaJC2o1Kq', 'slothza567@gmail.com'),
(11, 'slowmotion', '$2y$10$BigqrsaibnE9aCCMIGl38O6IpzSwhIOY.QKuZtw7iumfEYgYYWmVG', 'kimman23@gmail.com'),
(12, 'me', '$2y$10$.ZCfZG1Ua49O3CkWKJmQyOrLen2XMv9j0mS7IW4CZwUIe3fszuaXC', 'kimman23@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `visitcount`
--

CREATE TABLE `visitcount` (
  `id` int(10) NOT NULL,
  `PlaceID` int(10) NOT NULL,
  `TypeID` int(10) NOT NULL,
  `VisitDate` date NOT NULL,
  `VisitMonth` int(11) NOT NULL,
  `VisitYear` int(11) NOT NULL,
  `VisitCount` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `visitcount`
--

INSERT INTO `visitcount` (`id`, `PlaceID`, `TypeID`, `VisitDate`, `VisitMonth`, `VisitYear`, `VisitCount`) VALUES
(18, 48, 3, '2025-02-05', 2, 2025, 2),
(19, 49, 3, '2025-02-05', 2, 2025, 1),
(20, 46, 1, '2025-02-05', 2, 2025, 1),
(21, 45, 1, '2025-02-05', 2, 2025, 2),
(23, 47, 1, '2025-02-05', 2, 2025, 2),
(31, 43, 2, '2025-01-31', 1, 2025, 4),
(35, 43, 2, '2024-12-05', 2, 2025, 4),
(39, 46, 1, '2025-02-06', 2, 2025, 5),
(40, 45, 1, '2025-02-06', 2, 2025, 1),
(41, 47, 1, '2025-02-06', 2, 2025, 1),
(65, 45, 1, '2025-02-07', 2, 2025, 5),
(70, 45, 1, '2025-02-09', 2, 2025, 1),
(71, 47, 1, '2025-02-10', 2, 2025, 2),
(73, 43, 2, '2025-02-10', 2, 2025, 2),
(76, 48, 3, '2025-02-13', 2, 2025, 1),
(77, 46, 1, '2025-02-18', 2, 2025, 22),
(99, 45, 1, '2025-02-23', 2, 2025, 1),
(100, 46, 1, '2025-02-24', 2, 2025, 1),
(101, 46, 1, '2025-02-25', 2, 2025, 10),
(107, 47, 1, '2025-02-25', 2, 2025, 1),
(108, 44, 2, '2025-02-25', 2, 2025, 3),
(115, 46, 1, '2025-02-26', 2, 2025, 3),
(118, 44, 2, '2025-02-26', 2, 2025, 1),
(119, 43, 1, '2025-02-26', 2, 2025, 1),
(120, 43, 1, '2025-02-27', 2, 2025, 1),
(121, 46, 1, '2025-02-27', 2, 2025, 1),
(122, 47, 1, '2025-02-27', 2, 2025, 1),
(123, 50, 1, '2025-02-27', 2, 2025, 1),
(124, 52, 1, '2025-02-27', 2, 2025, 1),
(125, 43, 1, '2025-02-28', 2, 2025, 6);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `aboutus`
--
ALTER TABLE `aboutus`
  ADD PRIMARY KEY (`AboutID`);

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`AdminID`);

--
-- Indexes for table `allvisitors`
--
ALTER TABLE `allvisitors`
  ADD PRIMARY KEY (`allvisitorID`);

--
-- Indexes for table `gallery`
--
ALTER TABLE `gallery`
  ADD PRIMARY KEY (`GalleryID`),
  ADD KEY `PlaceID` (`PlaceID`);

--
-- Indexes for table `homeslide`
--
ALTER TABLE `homeslide`
  ADD PRIMARY KEY (`HomeID`);

--
-- Indexes for table `places`
--
ALTER TABLE `places`
  ADD PRIMARY KEY (`PlaceID`);

--
-- Indexes for table `reviewcounts`
--
ALTER TABLE `reviewcounts`
  ADD PRIMARY KEY (`PlaceID`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`ReviewID`),
  ADD KEY `UserID` (`UserID`),
  ADD KEY `PlaceID` (`PlaceID`);

--
-- Indexes for table `typeplace`
--
ALTER TABLE `typeplace`
  ADD PRIMARY KEY (`TypeID`);

--
-- Indexes for table `userlogins`
--
ALTER TABLE `userlogins`
  ADD PRIMARY KEY (`LoginID`),
  ADD KEY `UserID` (`UserID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`UserID`);

--
-- Indexes for table `visitcount`
--
ALTER TABLE `visitcount`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_visit` (`PlaceID`,`VisitDate`),
  ADD KEY `TypeID` (`TypeID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `aboutus`
--
ALTER TABLE `aboutus`
  MODIFY `AboutID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `AdminID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `allvisitors`
--
ALTER TABLE `allvisitors`
  MODIFY `allvisitorID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `gallery`
--
ALTER TABLE `gallery`
  MODIFY `GalleryID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=218;

--
-- AUTO_INCREMENT for table `homeslide`
--
ALTER TABLE `homeslide`
  MODIFY `HomeID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `places`
--
ALTER TABLE `places`
  MODIFY `PlaceID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=95;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `ReviewID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=123;

--
-- AUTO_INCREMENT for table `typeplace`
--
ALTER TABLE `typeplace`
  MODIFY `TypeID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `userlogins`
--
ALTER TABLE `userlogins`
  MODIFY `LoginID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `UserID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `visitcount`
--
ALTER TABLE `visitcount`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=131;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `gallery`
--
ALTER TABLE `gallery`
  ADD CONSTRAINT `gallery_ibfk_1` FOREIGN KEY (`PlaceID`) REFERENCES `places` (`PlaceID`) ON DELETE CASCADE;

--
-- Constraints for table `reviewcounts`
--
ALTER TABLE `reviewcounts`
  ADD CONSTRAINT `reviewcounts_ibfk_1` FOREIGN KEY (`PlaceID`) REFERENCES `places` (`PlaceID`) ON DELETE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`),
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`PlaceID`) REFERENCES `places` (`PlaceID`);

--
-- Constraints for table `userlogins`
--
ALTER TABLE `userlogins`
  ADD CONSTRAINT `userlogins_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE;

--
-- Constraints for table `visitcount`
--
ALTER TABLE `visitcount`
  ADD CONSTRAINT `visitcount_ibfk_1` FOREIGN KEY (`PlaceID`) REFERENCES `places` (`PlaceID`),
  ADD CONSTRAINT `visitcount_ibfk_2` FOREIGN KEY (`TypeID`) REFERENCES `typeplace` (`TypeID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
