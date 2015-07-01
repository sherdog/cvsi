-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jul 01, 2015 at 05:45 PM
-- Server version: 5.6.21
-- PHP Version: 5.5.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `cvsi`
--

-- --------------------------------------------------------

--
-- Table structure for table `access`
--

CREATE TABLE IF NOT EXISTS `access` (
`AccessID` int(10) unsigned NOT NULL,
  `AccessTitle` varchar(255) NOT NULL DEFAULT ''
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `alerts`
--

CREATE TABLE IF NOT EXISTS `alerts` (
`alerts_id` int(11) NOT NULL,
  `alerts_content` text NOT NULL,
  `alerts_date_added` int(11) NOT NULL DEFAULT '0',
  `alerts_title` varchar(255) NOT NULL DEFAULT '',
  `client_id` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `articles`
--

CREATE TABLE IF NOT EXISTS `articles` (
`article_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL DEFAULT '0',
  `author` int(11) NOT NULL DEFAULT '0',
  `article_title` varchar(255) NOT NULL DEFAULT '',
  `article_text` text NOT NULL,
  `article_url` varchar(255) NOT NULL DEFAULT '',
  `article_seo_title` varchar(255) NOT NULL DEFAULT '',
  `article_description` varchar(255) NOT NULL DEFAULT '',
  `article_seo_keyword` varchar(255) NOT NULL DEFAULT '',
  `article_sort_order` tinyint(4) NOT NULL DEFAULT '0',
  `article_added` int(11) NOT NULL DEFAULT '0',
  `article_publish_date` int(11) NOT NULL DEFAULT '0',
  `article_status` enum('pending','active','inactive') NOT NULL DEFAULT 'active',
  `article_sticky` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `banners`
--

CREATE TABLE IF NOT EXISTS `banners` (
`banner_id` int(11) NOT NULL,
  `banner_title` varchar(255) NOT NULL DEFAULT '',
  `banner_url` varchar(255) NOT NULL DEFAULT '',
  `banner_url_target` varchar(30) NOT NULL DEFAULT '',
  `banner_filename` varchar(255) NOT NULL DEFAULT '',
  `banner_publish_date` int(11) NOT NULL DEFAULT '0',
  `banner_date_added` int(11) NOT NULL DEFAULT '0',
  `client_id` int(11) NOT NULL DEFAULT '0',
  `banners_category` enum('mainpage','sidebar','top','sidebar_homepage') NOT NULL DEFAULT 'sidebar'
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `bids`
--

CREATE TABLE IF NOT EXISTS `bids` (
`bids_id` int(10) unsigned NOT NULL,
  `bid_amount` float(10,2) NOT NULL DEFAULT '0.00',
  `bid_name` varchar(45) NOT NULL DEFAULT '',
  `bid_anon` smallint(5) unsigned NOT NULL DEFAULT '0',
  `bid_email_address` varchar(45) NOT NULL DEFAULT '',
  `bid_terms` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `address` varchar(45) NOT NULL DEFAULT '',
  `zipcode` varchar(45) NOT NULL DEFAULT '',
  `phone` varchar(45) NOT NULL DEFAULT '',
  `bid_time` int(10) unsigned NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `bid_current`
--

CREATE TABLE IF NOT EXISTS `bid_current` (
  `bid_current_id` int(10) unsigned NOT NULL DEFAULT '0',
  `bid_id` varchar(45) NOT NULL DEFAULT '',
  `bid_amount` float(10,2) NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `blog`
--

CREATE TABLE IF NOT EXISTS `blog` (
`id` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `content` text COLLATE utf8_unicode_ci NOT NULL,
  `publish_date` int(11) NOT NULL,
  `status` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `category` int(11) NOT NULL,
  `filename` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `snippet` text COLLATE utf8_unicode_ci NOT NULL,
  `author` int(11) NOT NULL,
  `podcast` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `blog_comments`
--

CREATE TABLE IF NOT EXISTS `blog_comments` (
`id` int(11) NOT NULL,
  `text` text COLLATE utf8_unicode_ci NOT NULL,
  `date_added` int(11) NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `blog_id` int(11) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `calendar_events`
--

CREATE TABLE IF NOT EXISTS `calendar_events` (
`calendar_events_id` int(10) unsigned NOT NULL,
  `calendar_events_title` varchar(255) NOT NULL DEFAULT '',
  `calendar_events_description` text NOT NULL,
  `calendar_events_date_added` int(10) unsigned NOT NULL DEFAULT '0',
  `calendar_events_changed_date` int(10) unsigned NOT NULL DEFAULT '0',
  `author` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `calendar_events_categories_id` int(10) unsigned NOT NULL DEFAULT '0',
  `calendar_events_description_short` text NOT NULL,
  `calendar_events_status` varchar(45) NOT NULL DEFAULT '',
  `calendar_events_featured` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `calendar_events_url` varchar(255) NOT NULL DEFAULT '',
  `calendar_events_start_date` int(10) unsigned NOT NULL DEFAULT '0',
  `calendar_events_end_date` int(10) unsigned NOT NULL DEFAULT '0',
  `calendar_events_image` varchar(255) NOT NULL DEFAULT '',
  `calendar_events_main_image` varchar(255) NOT NULL DEFAULT '',
  `calendar_events_publish_date` int(10) unsigned NOT NULL DEFAULT '0',
  `calendar_events_start_time` int(10) unsigned NOT NULL DEFAULT '0',
  `calendar_events_end_time` int(10) unsigned NOT NULL DEFAULT '0',
  `calendar_events_repeats` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `calendar_events_type` varchar(45) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `calendar_events_categories`
--

CREATE TABLE IF NOT EXISTS `calendar_events_categories` (
`calendar_events_categories_id` int(10) unsigned NOT NULL,
  `calendar_events_categories_title` varchar(255) NOT NULL DEFAULT '',
  `calendar_events_categories_description` text NOT NULL,
  `calendar_events_id` int(10) unsigned NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `calendar_events_items`
--

CREATE TABLE IF NOT EXISTS `calendar_events_items` (
`calendar_events_items_id` int(10) unsigned NOT NULL,
  `calendar_events_id` int(10) unsigned NOT NULL DEFAULT '0',
  `calendar_events_items_start_date` int(10) unsigned NOT NULL DEFAULT '0',
  `calendar_events_items_end_date` int(10) unsigned NOT NULL DEFAULT '0',
  `calendar_events_items_start_time` int(10) unsigned NOT NULL DEFAULT '0',
  `calendar_events_items_end_time` int(10) unsigned NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='InnoDB free: 34816 kB';

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE IF NOT EXISTS `categories` (
`id` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `date_added` int(11) NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `image` int(11) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `title`, `date_added`, `description`, `image`) VALUES
(1, 'Podcast', 0, 'Audio blog post', 0),
(2, 'Video Post', 0, 'Video blog post', 0);

-- --------------------------------------------------------

--
-- Table structure for table `client_auth_keys`
--

CREATE TABLE IF NOT EXISTS `client_auth_keys` (
  `client_auth_key` int(11) NOT NULL DEFAULT '0',
  `client_auth_key_added` int(11) NOT NULL DEFAULT '0',
  `client_auth_key_expires` int(11) NOT NULL DEFAULT '0',
`client_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `client_config`
--

CREATE TABLE IF NOT EXISTS `client_config` (
  `client_config_id` int(11) NOT NULL DEFAULT '0',
  `client_id` int(11) NOT NULL DEFAULT '0',
  `client_max_user` int(11) NOT NULL DEFAULT '0',
  `client_max_pages` int(11) NOT NULL DEFAULT '0',
  `client_max_email_lists` int(11) NOT NULL DEFAULT '0',
  `client_store_user` varchar(255) NOT NULL DEFAULT '',
  `client_store_password` varchar(255) NOT NULL DEFAULT '',
  `client_absolute_path` varchar(255) NOT NULL DEFAULT '',
  `client_relative_path` varchar(255) NOT NULL DEFAULT '',
  `client_database_host` varchar(255) NOT NULL DEFAULT '',
  `client_database_username` varchar(255) NOT NULL DEFAULT '',
  `client_database_password` varchar(255) NOT NULL DEFAULT '',
  `client_database_name` varchar(255) NOT NULL DEFAULT '',
  `client_modules` varchar(255) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `client_config`
--

INSERT INTO `client_config` (`client_config_id`, `client_id`, `client_max_user`, `client_max_pages`, `client_max_email_lists`, `client_store_user`, `client_store_password`, `client_absolute_path`, `client_relative_path`, `client_database_host`, `client_database_username`, `client_database_password`, `client_database_name`, `client_modules`) VALUES
(0, 0, 0, 0, 0, '', '', '0', 'www.razzle.me', 'localhost', 'interact_dbadmin', 'interact_db@dm1n', '', 'pages,news,events,articles,banners');

-- --------------------------------------------------------

--
-- Table structure for table `contact`
--

CREATE TABLE IF NOT EXISTS `contact` (
`id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `question` text NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `customercomments`
--

CREATE TABLE IF NOT EXISTS `customercomments` (
`CustomerCommentsID` int(10) unsigned NOT NULL,
  `CustomerCommentsText` text NOT NULL,
  `CustomerCommentsFrom` varchar(255) NOT NULL DEFAULT '',
  `CustomerCommentsRating` smallint(5) unsigned NOT NULL DEFAULT '0',
  `CustomerCommentsRead` smallint(5) unsigned NOT NULL DEFAULT '0',
  `CustomerCommentsApprove` smallint(5) unsigned NOT NULL DEFAULT '0',
  `CustomerCommentsDateAdded` int(10) unsigned NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `email_queue`
--

CREATE TABLE IF NOT EXISTS `email_queue` (
`email_queue_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL DEFAULT '0',
  `email_queue_date_added` int(11) NOT NULL DEFAULT '0',
  `email_queue_release_date` int(11) NOT NULL DEFAULT '0',
  `email_queue_title` varchar(255) NOT NULL DEFAULT '',
  `email_queue_recipients` text NOT NULL,
  `email_queue_subject` varchar(255) NOT NULL DEFAULT '',
  `email_queue_email_text` text NOT NULL,
  `email_template_id` int(11) NOT NULL DEFAULT '0',
  `email_queue_from` varchar(255) NOT NULL DEFAULT '',
  `email_queue_status` enum('pending','onhold') NOT NULL DEFAULT 'pending',
  `email_display_home` tinyint(4) NOT NULL DEFAULT '0',
  `email_queue_attachment` varchar(255) NOT NULL DEFAULT ''
) ENGINE=MyISAM AUTO_INCREMENT=358 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `email_queue_from`
--

CREATE TABLE IF NOT EXISTS `email_queue_from` (
`email_queue_from_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL DEFAULT '0',
  `email_queue_from_name` varchar(255) NOT NULL DEFAULT '',
  `email_queue_from_email` varchar(255) NOT NULL DEFAULT ''
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `email_queue_from`
--

INSERT INTO `email_queue_from` (`email_queue_from_id`, `client_id`, `email_queue_from_name`, `email_queue_from_email`) VALUES
(12, 0, 'CVSi Motorsports', 'cvsi@cvsimotorsports.com');

-- --------------------------------------------------------

--
-- Table structure for table `email_queue_sent`
--

CREATE TABLE IF NOT EXISTS `email_queue_sent` (
`email_queue_sent` int(11) NOT NULL,
  `client_id` int(11) NOT NULL DEFAULT '0',
  `email_queue_date_sent` int(11) NOT NULL DEFAULT '0',
  `email_queue_subject` varchar(255) NOT NULL DEFAULT '',
  `email_queue_content` text NOT NULL,
  `email_templates_id` int(11) NOT NULL DEFAULT '0',
  `email_recipients` text NOT NULL,
  `email_display_home` tinyint(4) NOT NULL DEFAULT '0',
  `email_log_file` varchar(50) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `email_templates`
--

CREATE TABLE IF NOT EXISTS `email_templates` (
`email_templates_id` int(11) NOT NULL,
  `email_templates_name` varchar(255) NOT NULL DEFAULT '',
  `email_templates_desc` varchar(255) NOT NULL DEFAULT '',
  `email_templates_date_added` int(11) NOT NULL DEFAULT '0',
  `client_id` int(11) NOT NULL DEFAULT '0',
  `email_templates_code` text NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `email_templates`
--

INSERT INTO `email_templates` (`email_templates_id`, `email_templates_name`, `email_templates_desc`, `email_templates_date_added`, `client_id`, `email_templates_code`) VALUES
(2, 'Main Email Design', 'Main Email Design', 1253740404, 0, 'Coming Soon.'),
(3, '2 Column Text Block Img Left', '2 Column Block with image on the left', 1274991238, 0, '&lt;table border=&quot;0&quot; cellspacing=&quot;0&quot; cellpadding=&quot;5&quot; width=&quot;98%&quot;&gt;\r\n&lt;tbody&gt;\r\n&lt;tr&gt;\r\n&lt;td width=&quot;200&quot; valign=&quot;top&quot;&gt;&lt;img src=&quot;http://interactivearmy.com/files/siteimages/picture_placeholder.jpg&quot; alt=&quot;&quot; width=&quot;200&quot; height=&quot;150&quot; /&gt;&lt;br /&gt;&lt;/td&gt;\r\n&lt;td valign=&quot;top&quot;&gt;\r\n&lt;p&gt;&lt;span style=&quot;font-size: 18px; color: #6c522c; font-family: Verdana, Geneva, sans-serif;&quot;&gt;Title goes here&lt;/span&gt;&lt;/p&gt;\r\n&lt;p&gt;&lt;span style=&quot;font-size: 12px; color: #000000; font-family: Verdana, Geneva, sans-serif;&quot;&gt;Lorem ipsum dolor sit amet, consectetur adipiscing elit. In faucibus posuere libero id bibendum. Praesent sit amet augue nisi, nec eleifend eros. Cras neque quam, vulputate a molestie ut, dictum ut tellus. Donec vitae urna ac est eleifend ornare tempus in quam.&lt;/span&gt;&lt;/p&gt;\r\n&lt;/td&gt;\r\n&lt;/tr&gt;\r\n&lt;/tbody&gt;\r\n&lt;/table&gt;\r\n&lt;hr /&gt;'),
(4, '2 Column Text Block Img Right', '2 Column Block with image on the right', 1274991263, 0, '&lt;table border=&quot;0&quot; cellspacing=&quot;0&quot; cellpadding=&quot;5&quot; width=&quot;98%&quot;&gt;\r\n&lt;tbody&gt;\r\n&lt;tr&gt;\r\n&lt;td valign=&quot;top&quot;&gt;&lt;span style=&quot;font-size: 18px; color: #6c522c; font-family: Verdana, Geneva, sans-serif;&quot;&gt;Title goes here&lt;/span&gt;\r\n&lt;p&gt;&lt;span style=&quot;font-size: 12px; color: #000000; font-family: Verdana, Geneva, sans-serif;&quot;&gt;Lorem ipsum dolor sit amet, consectetur adipiscing elit. In faucibus posuere libero id bibendum. Praesent sit amet augue nisi, nec eleifend eros. Cras neque quam, vulputate a molestie ut, dictum ut tellus. Donec vitae urna ac est eleifend ornare tempus in quam. &lt;/span&gt;&lt;/p&gt;\r\n&lt;/td&gt;\r\n&lt;td width=&quot;200&quot; valign=&quot;top&quot;&gt;&lt;img style=&quot;display: block; margin-left: auto; margin-right: auto;&quot; src=&quot;http://interactivearmy.com/files/siteimages/picture_placeholder.jpg&quot; alt=&quot;&quot; width=&quot;200&quot; height=&quot;150&quot; /&gt;&lt;/td&gt;\r\n&lt;/tr&gt;\r\n&lt;/tbody&gt;\r\n&lt;/table&gt;\r\n&lt;hr /&gt;'),
(5, 'Content 3 Col 1 Row', '3 Column Block', 1274991290, 0, '&lt;table border=&quot;0&quot; cellspacing=&quot;0&quot; cellpadding=&quot;5&quot; width=&quot;98%&quot;&gt;\r\n&lt;tbody&gt;\r\n&lt;tr&gt;\r\n&lt;td width=&quot;33%&quot; valign=&quot;top&quot;&gt;&lt;span style=&quot;font-size: 18px; color: #6c522c; font-family: Verdana, Geneva, sans-serif;&quot;&gt;Title goes here&lt;/span&gt;\r\n&lt;p&gt;&lt;img style=&quot;display: block; margin-left: auto; margin-right: auto;&quot; src=&quot;http://interactivearmy.com/files/siteimages/picture_placeholder_150.jpg&quot; alt=&quot;&quot; width=&quot;150&quot; height=&quot;113&quot; /&gt;&lt;/p&gt;\r\n&lt;p&gt;&lt;span style=&quot;font-size: 14px; color: #333; font-family: Verdana; font-weight: bold;&quot;&gt;Subheading Here.&lt;/span&gt;&lt;/p&gt;\r\n&lt;p&gt;&lt;span style=&quot;font-size: 14px; color: #000000; font-family: Verdana, Geneva, sans-serif;&quot;&gt;Lorem ipsum dolor sit amet, consectetur adipiscing elit. In faucibus posuere libero id bibendum. Praesent sit amet augue nisi, nec eleifend eros. Cras neque quam, vulputate a molestie ut, dictum ut tellus. Donec vitae urna ac est eleifend ornare tempus in quam. &lt;/span&gt;&lt;/p&gt;\r\n&lt;/td&gt;\r\n&lt;td&gt;&lt;img src=&quot;http://interactivearmy.com/bunker/emailTemplates/images/vertical_sep.jpg&quot; alt=&quot;&quot; width=&quot;1&quot; height=&quot;432&quot; /&gt;&lt;/td&gt;\r\n&lt;td width=&quot;33%&quot; valign=&quot;top&quot;&gt;&lt;span style=&quot;font-size: 18px; color: #6c522c; font-family: Verdana, Geneva, sans-serif;&quot;&gt;Title goes here&lt;/span&gt;\r\n&lt;p&gt;&lt;img style=&quot;display: block; margin-left: auto; margin-right: auto;&quot; src=&quot;http://interactivearmy.com/files/siteimages/picture_placeholder_150.jpg&quot; alt=&quot;&quot; width=&quot;150&quot; height=&quot;113&quot; /&gt;&lt;/p&gt;\r\n&lt;p&gt;&lt;span style=&quot;font-size: 14px; color: #333; font-family: Verdana; font-weight: bold;&quot;&gt;Subheading Here.&lt;/span&gt;&lt;/p&gt;\r\n&lt;p&gt;&lt;span style=&quot;font-size: 14px; color: #000000; font-family: Verdana, Geneva, sans-serif;&quot;&gt;Lorem ipsum dolor sit amet, consectetur adipiscing elit. In faucibus posuere libero id bibendum. Praesent sit amet augue nisi, nec eleifend eros. Cras neque quam, vulputate a molestie ut, dictum ut tellus. Donec vitae urna ac est eleifend ornare tempus in quam. &lt;/span&gt;&lt;/p&gt;\r\n&lt;/td&gt;\r\n&lt;td&gt;&lt;img src=&quot;http://interactivearmy.com/bunker/emailTemplates/images/vertical_sep.jpg&quot; alt=&quot;&quot; width=&quot;1&quot; height=&quot;432&quot; /&gt;&lt;/td&gt;\r\n&lt;td width=&quot;33%&quot; valign=&quot;top&quot;&gt;&lt;span style=&quot;font-size: 18px; color: #6c522c; font-family: Verdana, Geneva, sans-serif;&quot;&gt;Title goes here&lt;/span&gt;\r\n&lt;p&gt;&lt;img style=&quot;display: block; margin-left: auto; margin-right: auto;&quot; src=&quot;http://interactivearmy.com/files/siteimages/picture_placeholder_150.jpg&quot; alt=&quot;&quot; width=&quot;150&quot; height=&quot;113&quot; /&gt;&lt;/p&gt;\r\n&lt;p&gt;&lt;span style=&quot;font-size: 14px; color: #333; font-family: Verdana; font-weight: bold;&quot;&gt;Subheading Here.&lt;/span&gt;&lt;/p&gt;\r\n&lt;p&gt;&lt;span style=&quot;font-size: 14px; color: #000000; font-family: Verdana, Geneva, sans-serif;&quot;&gt;Lorem ipsum dolor sit amet, consectetur adipiscing elit. In faucibus posuere libero id bibendum. Praesent sit amet augue nisi, nec eleifend eros. Cras neque quam, vulputate a molestie ut, dictum ut tellus. Donec vitae urna ac est eleifend ornare tempus in quam. &lt;/span&gt;&lt;/p&gt;\r\n&lt;/td&gt;\r\n&lt;/tr&gt;\r\n&lt;/tbody&gt;\r\n&lt;/table&gt;\r\n&lt;hr /&gt;'),
(6, 'Content Text Block', '1 Row 1 Column Text Block', 1274991319, 0, '&lt;table border=&quot;0&quot; cellspacing=&quot;0&quot; cellpadding=&quot;5&quot; width=&quot;98%&quot;&gt;\r\n&lt;tbody&gt;\r\n&lt;tr&gt;\r\n&lt;td valign=&quot;top&quot;&gt;&lt;span style=&quot;font-size: 18px; color: #6c522c; font-family: Verdana, Geneva, sans-serif;&quot;&gt;Title goes here&lt;/span&gt;\r\n&lt;p&gt;&lt;span style=&quot;font-size: 12px; color: #000000; font-family: Verdana, Geneva, sans-serif;&quot;&gt;Lorem ipsum dolor sit amet, consectetur adipiscing elit. In faucibus posuere libero id bibendum. Praesent sit amet augue nisi, nec eleifend eros. Cras neque quam, vulputate a molestie ut, dictum ut tellus. Donec vitae urna ac est eleifend ornare tempus in quam. &lt;/span&gt;&lt;/p&gt;\r\n&lt;p&gt;&lt;span style=&quot;font-size: 12px; color: #000000; font-family: Verdana, Geneva, sans-serif;&quot;&gt;Lorem ipsum dolor sit amet, consectetur adipiscing elit. In faucibus posuere libero id bibendum. Praesent sit amet augue nisi, nec eleifend eros. Cras neque quam, vulputate a molestie ut, dictum ut tellus. Donec vitae urna ac est eleifend ornare tempus in quam. &lt;/span&gt;&lt;/p&gt;\r\n&lt;/td&gt;\r\n&lt;/tr&gt;\r\n&lt;/tbody&gt;\r\n&lt;/table&gt;\r\n&lt;hr /&gt;'),
(7, '2 Column Text Block', 'Simple 2 Column Text Block No Image', 1274991349, 0, '&lt;table border=&quot;0&quot; cellspacing=&quot;0&quot; cellpadding=&quot;5&quot; width=&quot;98%&quot;&gt;\r\n&lt;tbody&gt;\r\n&lt;tr&gt;\r\n&lt;td width=&quot;50%&quot; valign=&quot;top&quot;&gt;&lt;span style=&quot;font-size: 18px; color: #6c522c; font-family: Verdana, Geneva, sans-serif;&quot;&gt;Title goes here&lt;/span&gt;\r\n&lt;p&gt;&lt;span style=&quot;font-size: 12px; color: #000000; font-family: Verdana, Geneva, sans-serif;&quot;&gt;Lorem ipsum dolor sit amet, consectetur adipiscing elit. In faucibus posuere libero id bibendum. Praesent sit amet augue nisi, nec eleifend eros. Cras neque quam, vulputate a molestie ut, dictum ut tellus. Donec vitae urna ac est eleifend ornare tempus in quam. &lt;/span&gt;&lt;/p&gt;\r\n&lt;/td&gt;\r\n&lt;td valign=&quot;top&quot;&gt;&lt;span style=&quot;font-size: 18px; color: #6c522c; font-family: Verdana, Geneva, sans-serif;&quot;&gt;Title goes here&lt;/span&gt;\r\n&lt;p&gt;&lt;span style=&quot;font-size: 12px; color: #000000; font-family: Verdana, Geneva, sans-serif;&quot;&gt;Lorem ipsum dolor sit amet, consectetur adipiscing elit. In faucibus posuere libero id bibendum. Praesent sit amet augue nisi, nec eleifend eros. Cras neque quam, vulputate a molestie ut, dictum ut tellus. Donec vitae urna ac est eleifend ornare tempus in quam. &lt;/span&gt;&lt;/p&gt;\r\n&lt;/td&gt;\r\n&lt;/tr&gt;\r\n&lt;/tbody&gt;\r\n&lt;/table&gt;\r\n&lt;hr /&gt;');

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

CREATE TABLE IF NOT EXISTS `employee` (
`employee_id` int(11) NOT NULL,
  `employee_name` varchar(255) NOT NULL DEFAULT '',
  `employee_bio` text NOT NULL,
  `employee_image` varchar(255) NOT NULL DEFAULT '',
  `employee_sort_order` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE IF NOT EXISTS `events` (
`event_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL DEFAULT '0',
  `author` int(11) NOT NULL DEFAULT '0',
  `event_title` varchar(255) NOT NULL DEFAULT '',
  `event_text` text NOT NULL,
  `event_start` int(11) NOT NULL DEFAULT '0',
  `event_end` int(11) NOT NULL DEFAULT '0',
  `event_start_time1` int(11) NOT NULL DEFAULT '0',
  `event_start_time2` int(11) NOT NULL DEFAULT '0',
  `event_end_time1` int(11) NOT NULL DEFAULT '0',
  `event_end_time2` int(11) NOT NULL DEFAULT '0',
  `event_sticky` tinyint(4) NOT NULL DEFAULT '0',
  `event_tag` varchar(255) NOT NULL DEFAULT '',
  `event_status` enum('pending','active','inactive') NOT NULL DEFAULT 'active',
  `event_created` int(11) NOT NULL DEFAULT '0',
  `event_url` varchar(255) NOT NULL DEFAULT '',
  `event_tags` text NOT NULL,
  `event_type` varchar(120) NOT NULL DEFAULT '',
  `event_image` varchar(255) NOT NULL DEFAULT '',
  `event_text_short` text NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `event_categories`
--

CREATE TABLE IF NOT EXISTS `event_categories` (
`event_category_id` int(10) unsigned NOT NULL,
  `event_id` int(10) unsigned NOT NULL DEFAULT '0',
  `event_category_title` varchar(255) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `event_sponsors`
--

CREATE TABLE IF NOT EXISTS `event_sponsors` (
`event_sponsors_id` int(10) unsigned NOT NULL,
  `event_id` int(10) unsigned NOT NULL DEFAULT '0',
  `sponsor_id` int(10) unsigned NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `gallery`
--

CREATE TABLE IF NOT EXISTS `gallery` (
`gallery_id` int(11) NOT NULL,
  `gallery_title` varchar(255) NOT NULL DEFAULT '',
  `gallery_desc` text NOT NULL,
  `gallery_date_added` int(11) NOT NULL DEFAULT '0',
  `gallery_sort` tinyint(4) NOT NULL DEFAULT '0',
  `gallery_url` varchar(255) NOT NULL DEFAULT '',
  `gallery_url_type` varchar(40) NOT NULL DEFAULT '',
  `gallery_feature` tinyint(4) NOT NULL DEFAULT '0',
  `client_id` int(11) NOT NULL DEFAULT '0',
  `gallery_active` tinyint(4) NOT NULL DEFAULT '0',
  `gallery_custom_1` varchar(255) NOT NULL DEFAULT '',
  `gallery_custom_2` text NOT NULL,
  `gallery_custom_3` varchar(255) NOT NULL DEFAULT '',
  `gallery_custom_4` text NOT NULL,
  `gallery_custom_5` text NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `gallery`
--

INSERT INTO `gallery` (`gallery_id`, `gallery_title`, `gallery_desc`, `gallery_date_added`, `gallery_sort`, `gallery_url`, `gallery_url_type`, `gallery_feature`, `client_id`, `gallery_active`, `gallery_custom_1`, `gallery_custom_2`, `gallery_custom_3`, `gallery_custom_4`, `gallery_custom_5`) VALUES
(21, '2008 Lexus is250', 'New 19&quot; Verde Rims wrapped in Yokahoma tires.&lt;br /&gt;', 1433998800, 21, '', '', 0, 0, 0, '', '', '', '', ''),
(22, '2003 Ford Explorer', 'Test', 1434344400, 1, '', '', 0, 0, 0, '', '', '', '', ''),
(23, '2000 Ford Explorer', 'Test', 1434344400, 1, '', '', 0, 0, 0, '', '', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `gallery_images`
--

CREATE TABLE IF NOT EXISTS `gallery_images` (
`gallery_image_id` int(11) NOT NULL,
  `gallery_id` int(11) NOT NULL DEFAULT '0',
  `gallery_image_filename` varchar(255) NOT NULL DEFAULT '',
  `gallery_date_added` int(11) NOT NULL DEFAULT '0',
  `gallery_image_caption` text NOT NULL,
  `gallery_image_sort_order` int(11) NOT NULL DEFAULT '0',
  `gallery_image_featured` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=MyISAM AUTO_INCREMENT=76 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `gallery_images`
--

INSERT INTO `gallery_images` (`gallery_image_id`, `gallery_id`, `gallery_image_filename`, `gallery_date_added`, `gallery_image_caption`, `gallery_image_sort_order`, `gallery_image_featured`) VALUES
(68, 21, '1_5-920x690.jpg', 1434162693, '', 2, 1),
(69, 21, '1_5-920x690.jpg', 1434162995, '', 3, 0),
(70, 21, '1_5-920x690.jpg', 1434163041, '', 4, 0),
(71, 21, '1_5-920x690.jpg', 1434163154, '', 5, 0),
(72, 21, '1_5-920x690.jpg', 1434163241, '', 6, 0),
(73, 21, '1_5-920x690.jpg', 1434163439, '', 7, 1),
(67, 21, '1_5-920x690.jpg', 1434161459, '', 1, 0),
(74, 22, '1_5-920x690.jpg', 1434426022, '', 1, 0),
(75, 23, '1_5-920x690.jpg', 1434426034, '', 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `global_config`
--

CREATE TABLE IF NOT EXISTS `global_config` (
`config_id` int(11) NOT NULL,
  `config_core_dir` varchar(255) NOT NULL DEFAULT '',
  `config_version` varchar(10) NOT NULL DEFAULT '',
  `config_active` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `members`
--

CREATE TABLE IF NOT EXISTS `members` (
`members_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL DEFAULT '0',
  `members_first_name` varchar(255) NOT NULL DEFAULT '',
  `members_last_name` varchar(255) NOT NULL DEFAULT '',
  `members_email_address` varchar(255) NOT NULL DEFAULT '',
  `members_pwd` varchar(255) NOT NULL DEFAULT '',
  `members_date_added` int(11) NOT NULL DEFAULT '0',
  `members_last_login` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

CREATE TABLE IF NOT EXISTS `news` (
`news_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL DEFAULT '0',
  `author` int(11) NOT NULL DEFAULT '0',
  `news_title` varchar(255) NOT NULL DEFAULT '',
  `news_text` text NOT NULL,
  `news_start` int(11) NOT NULL DEFAULT '0',
  `news_end` int(11) NOT NULL DEFAULT '0',
  `news_sticky` tinyint(4) NOT NULL DEFAULT '0',
  `news_tag` varchar(255) NOT NULL DEFAULT '',
  `news_status` enum('pending','active','inactive') NOT NULL DEFAULT 'active',
  `news_created` int(11) NOT NULL DEFAULT '0',
  `news_url` varchar(255) NOT NULL DEFAULT '',
  `news_type` varchar(120) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `newsletters`
--

CREATE TABLE IF NOT EXISTS `newsletters` (
`newsletter_id` int(11) NOT NULL,
  `newsletter_title` varchar(255) NOT NULL DEFAULT '',
  `newsletter_subject` varchar(255) NOT NULL DEFAULT '',
  `newsletter_content` text NOT NULL,
  `newsletter_date_added` int(11) NOT NULL DEFAULT '0',
  `newsletter_attachment` varchar(255) NOT NULL DEFAULT '',
  `email_queue_id` int(11) NOT NULL DEFAULT '0',
  `client_id` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `page_banners`
--

CREATE TABLE IF NOT EXISTS `page_banners` (
`page_banners_id` int(11) NOT NULL,
  `page_content_id` int(11) NOT NULL DEFAULT '0',
  `banners_id` int(11) NOT NULL DEFAULT '0',
  `date_added` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM AUTO_INCREMENT=37 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `page_calculators`
--

CREATE TABLE IF NOT EXISTS `page_calculators` (
`page_calculators_id` int(11) NOT NULL,
  `tools_calculators_id` int(11) NOT NULL DEFAULT '0',
  `page_content_id` int(11) NOT NULL DEFAULT '0',
  `date_added` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM AUTO_INCREMENT=91 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `page_content`
--

CREATE TABLE IF NOT EXISTS `page_content` (
`page_content_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL DEFAULT '0',
  `page_content_added` int(11) NOT NULL DEFAULT '0',
  `page_content_url` varchar(255) NOT NULL DEFAULT '',
  `page_content_publish_date` int(11) NOT NULL DEFAULT '0',
  `page_content_text` text NOT NULL,
  `page_content_title` varchar(255) NOT NULL DEFAULT '',
  `page_content_seo_title` varchar(255) NOT NULL DEFAULT '',
  `page_content_seo_description` text NOT NULL,
  `page_content_seo_keyword` text NOT NULL,
  `page_content_sort_order` int(11) NOT NULL DEFAULT '0',
  `page_content_author` varchar(255) NOT NULL DEFAULT '',
  `parent` int(11) NOT NULL DEFAULT '0',
  `page_content_status` enum('published','private','pending') NOT NULL DEFAULT 'published',
  `page_content_image` varchar(255) NOT NULL DEFAULT '',
  `page_content_member` tinyint(4) DEFAULT '0' COMMENT 'determines if the page is for members only',
  `page_content_custom_1` varchar(14) NOT NULL DEFAULT '0' COMMENT 'ipipeline module',
  `page_content_mirror` int(11) NOT NULL DEFAULT '0',
  `page_content_last_modified_date` int(11) NOT NULL DEFAULT '0',
  `page_content_last_modified_by` int(11) NOT NULL DEFAULT '0',
  `page_content_form` varchar(50) NOT NULL DEFAULT '0',
  `app_name` varchar(50) NOT NULL DEFAULT 'page',
  `page_content_show_in_menu` tinyint(4) NOT NULL DEFAULT '1',
  `custom_url` varchar(255) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `page_content`
--

INSERT INTO `page_content` (`page_content_id`, `client_id`, `page_content_added`, `page_content_url`, `page_content_publish_date`, `page_content_text`, `page_content_title`, `page_content_seo_title`, `page_content_seo_description`, `page_content_seo_keyword`, `page_content_sort_order`, `page_content_author`, `parent`, `page_content_status`, `page_content_image`, `page_content_member`, `page_content_custom_1`, `page_content_mirror`, `page_content_last_modified_date`, `page_content_last_modified_by`, `page_content_form`, `app_name`, `page_content_show_in_menu`, `custom_url`) VALUES
(1, 0, 1435711239, 'services', 1435640400, 'This page will not be shown, select a subpage', 'Services', 'Servicea', 'Services :: Audio, Video, Navigation System, Accessories, Wheels, Tires', 'Services :: Audio, Video, Navigation System, Accessories, Wheels, Tires', 1, '21', 0, 'published', '', 0, '0', 0, 0, 0, '', 'page', 1, 'services'),
(2, 0, 1435712000, 'audio-video', 1435640400, '<p><a href="http://cvsimotorsports.com/wp-content/uploads/2013/03/291.png"><img class="size-full wp-image-276 alignright" style="margin-left: 10px; margin-right: 10px; float: right;" src="http://cvsimotorsports.com/wp-content/uploads/2013/03/291.png" alt="291" width="291" height="100" /></a>Here  at CVSi Motorsports Inc. we really know about Car Audio. Our staff has  the knowledge to assist you in designing a stereo system to fit your  needs. Whether it&rsquo;s 50 Cent, Coldplay or Willie Nelson our fine audio  products and professional staff you can guarantee the sound will be  taken to a whole new level. For those of you who can&rsquo;t get enough bass,  we can really PUMP up the volume and make your system POUND! Come on in  and check out the latest in audio and video.</p>\r\n<p>We sell the latest in video and navigation products on the market!  Competitive pricing and an experienced staff, you&rsquo;re guaranteed to be  satisfied.</p>\r\n<p>&nbsp;</p>', 'Audio/Video', 'Audio Video', 'CVSi Motorsport has a large selection of the latest audio video selection in the Cedar Valley', 'Audio, Video, Navigation Systems For Cars,Trucks,Marine,Powersports,Watersports', 2, '21', 1, 'published', '', 0, '0', 0, 0, 0, '', 'page', 1, 'services/audio-video'),
(3, 0, 1435714726, 'wheels-tires', 1435640400, '<p>Upgrade your Car/Truck with new Wheels from CVSi Motorsports. We carry  the top brands of wheels and tires at competitive prices. Everything  from off-road to the sexy staggared look for your hot rod or import. We  have the wheel and tire package to fit you at the prices you can afford.</p>\r\n<h3>We have the largest selection of wheels in the Cedar Valley</h3>\r\n<p>Here are just a few Brands we carry, if you don&acirc;&euro;&trade;t see a brand you  like,we most likely sell it, give us a call or drop us an email.</p>\r\n<p><a href="http://cvsimotorsports.com/wp-content/uploads/2013/03/460.jpg"><img class="alignnone size-full wp-image-285" src="http://cvsimotorsports.com/wp-content/uploads/2013/03/460.jpg" alt="460" width="460" height="212" /></a></p>', 'Wheels &amp; Tires', 'Wheels & Tires', 'CVSi Motorsports has the largest selection of wheels and tires in the Cedar Valley at competitive prices', 'Wheels, Tires, Powersports, Auto, Trucks, American Racing, Akuza, Boss, KMC Wheels, Foose, Asanti, Giovanna, Eagle Allows, DUB, Dip, Boss', 1, '21', 1, 'published', '', 0, '', 0, 1435714726, 0, '', 'page', 1, 'services/wheels-tires'),
(4, 0, 1435714840, 'window-tinting', 1435640400, '<strong>CVSi Motorsports</strong> has been serving the Cedar Valley for  30 years for their window tinting needs. We service not only just cars  but commercial and residential window tinting.  Get your windows tined and&hellip; \r\n<ul>\r\n<li>Keeps interior cool</li>\r\n<li>Enhances your look</li>\r\n<li>Increases value</li>\r\n<li>Provides UV protection</li>\r\n<li>Reduces the glare in your eyes</li>\r\n</ul>\r\nWith competitive pricing and an experienced staff, you can rest assure  that you will get the best price with the best quality. Call us for  pricing or to schedule your appointed today!', 'Window Tinting', 'Window Tinting', 'CVSi Does professional auto window tinting - we also do residential and commercial windows call us for a quote', 'Auto WIndow Tintint, Residential Window Tinting, Commercial WIndow Tinting', 3, '21', 1, 'published', '', 0, '0', 0, 0, 0, '', 'page', 1, 'services/window-tinting'),
(5, 0, 1435715017, 'boat-servicing-accessories', 1435640400, '<div class="col-sm-8">CVSi Motorsports Inc. Now offers boat repair, maintenance, and upgrades!  With a Mercruiser Certified Master Technician you can assure we are  able to provide service both with quality and experience.\r\n<h3>Our services include:</h3>\r\nBoat Repair Service: Preventive maintenance, Season maintenance as well as electronic and performance upgrades. Boat Detailing: We offer a full complete detailing call us for pricing\r\n<h3>We now sell Manitou Pontoon Boats</h3>\r\nWe are now an authorized Manitou Boat Dealer. Come check out our line of  Pontoon boats and check see how &Acirc;&nbsp;Manitou Pontoon boats can make this  summer perfect! You can choose between leisure and enjoy hours of family  enjoyment. Or choose from their Performance boats to take family fun on  the water to a while new Level!\r\n<h3>Financing is available</h3>\r\nWe also offer financing, ask us about our financing program and see if we can make your next purchase affordable.</div>\r\n<div class="col-sm-4">\r\n<h3>Audio/Video</h3>\r\n<a href="http://cvsimotorsports.com/wp-content/uploads/2013/03/291.jpg"><img class="alignnone size-full wp-image-294" src="http://cvsimotorsports.com/wp-content/uploads/2013/03/291.jpg" alt="291" width="291" height="219" /></a>\r\n<h3>Servicing</h3>\r\n<a href="http://cvsimotorsports.com/wp-content/uploads/2013/03/wrap.jpg"><img class="alignnone size-full wp-image-296" src="http://cvsimotorsports.com/wp-content/uploads/2013/03/wrap.jpg" alt="wrap" width="291" height="217" /></a></div>', 'Boat Servicing &amp; Accessories', 'Boat Servicing & Accessories', 'CVSi Motorsports Inc. Now offers boat repair, maintenance, and upgrades! With a Mercruiser Certified Master Technician you can assure we are able to provide service both with quality and experience. ', 'Boat Servicing, WInterizing and Accessories', 0, '21', 1, 'published', '', 0, '', 0, 1435715017, 0, '', 'page', 1, 'services/boat-servicing-accessories'),
(6, 0, 1435715168, 'auto-marine-detailing', 1435640400, '<div class="entry-content">\r\n<p>It&rsquo;s all about the details, at CVSi Motorsports we understand that  keeping your car, boat, truck or toy clean not only extends the life of  it, but keeps it looking like new. We offer Car, Truck, Boat detailing  from a good shampoo and wax to full top to bottom rub down.</p>\r\n<p><strong>Call us for more information or to schedule an appointment 319-266-2867</strong></p>\r\n</div>', 'Auto &amp; Marine Detailing', 'Auto & Marine Servicing', '', 'Auto, Truck, Marine, Detailing, Shampooing, ', 0, '21', 1, 'published', '', 0, '0', 0, 0, 0, '', 'page', 1, 'servicing/auto-marine-servicing'),
(7, 0, 1435715220, 'contact-us', 1435640400, '&nbsp;We would love to hear from you! Please fill out this form and we will get in touch with you as soon as we can.', 'Contact us', 'Contact CVSi Motorsports', '', '', 0, '21', 0, 'published', '', 0, '0', 0, 0, 0, '', 'page', 1, 'contact-us');

-- --------------------------------------------------------

--
-- Table structure for table `page_content_downloads`
--

CREATE TABLE IF NOT EXISTS `page_content_downloads` (
`page_content_downloads_id` int(11) NOT NULL,
  `page_content_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `page_content_log`
--

CREATE TABLE IF NOT EXISTS `page_content_log` (
`page_content_log_id` int(11) NOT NULL,
  `page_content_log_timestamp` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `page_content_id` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM AUTO_INCREMENT=236 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `page_content_log`
--

INSERT INTO `page_content_log` (`page_content_log_id`, `page_content_log_timestamp`, `user_id`, `page_content_id`) VALUES
(1, 1286757392, 21, 1),
(2, 1286852387, 24, 5),
(3, 1288316095, 24, 9),
(4, 1288317289, 24, 9),
(5, 1288318448, 24, 3),
(6, 1288319453, 24, 16),
(7, 1289161570, 21, 5),
(8, 1289161617, 21, 9),
(9, 1289161650, 21, 15),
(10, 1289161654, 21, 10),
(11, 1289161660, 21, 13),
(12, 1289161665, 21, 11),
(13, 1289161683, 21, 10),
(14, 1289161756, 21, 13),
(15, 1289161768, 21, 13),
(16, 1289161779, 21, 13),
(17, 1289161785, 21, 9),
(18, 1289161791, 21, 4),
(19, 1289161795, 21, 3),
(20, 1289161802, 21, 16),
(21, 1289161810, 21, 1),
(22, 1289161816, 21, 2),
(23, 1289161821, 21, 17),
(24, 1289161826, 21, 6),
(25, 1289161831, 21, 11),
(26, 1289161835, 21, 5),
(27, 1289161952, 21, 7),
(28, 1289161978, 21, 12),
(29, 1289162023, 21, 4),
(30, 1289162029, 21, 14),
(31, 1289162034, 21, 10),
(32, 1289162038, 21, 12),
(33, 1289162043, 21, 13),
(34, 1289162048, 21, 9),
(35, 1289162052, 21, 3),
(36, 1289162057, 21, 16),
(37, 1289162062, 21, 7),
(38, 1289162064, 21, 7),
(39, 1289162069, 21, 8),
(40, 1289162073, 21, 15),
(41, 1289162078, 21, 1),
(42, 1289162083, 21, 1),
(43, 1289162087, 21, 2),
(44, 1289162092, 21, 17),
(45, 1289162096, 21, 6),
(46, 1289162101, 21, 11),
(47, 1289162105, 21, 5),
(48, 1289162186, 21, 4),
(49, 1289162191, 21, 14),
(50, 1289162392, 21, 6),
(51, 1289162408, 21, 7),
(52, 1289162428, 21, 8),
(53, 1289162440, 21, 9),
(54, 1289162466, 21, 11),
(55, 1289162481, 21, 15),
(56, 1289162499, 21, 16),
(57, 1289162515, 21, 1),
(58, 1289162533, 21, 2),
(59, 1289162552, 21, 3),
(60, 1289162604, 21, 13),
(61, 1289162617, 21, 12),
(62, 1289162693, 21, 10),
(63, 1289171965, 21, 11),
(64, 1289175511, 21, 4),
(65, 1289175555, 21, 9),
(66, 1289175690, 21, 17),
(67, 1289176153, 21, 17),
(68, 1289176245, 21, 4),
(69, 1289176432, 21, 5),
(70, 1289176509, 21, 9),
(71, 1289176531, 21, 2),
(72, 1289176551, 21, 8),
(73, 1289176565, 21, 4),
(74, 1289176581, 21, 3),
(75, 1289176615, 21, 15),
(76, 1289176640, 21, 9),
(77, 1289176819, 21, 3),
(78, 1289176850, 21, 3),
(79, 1289176869, 21, 3),
(80, 1289176891, 21, 3),
(81, 1289661256, 21, 5),
(82, 1289661286, 21, 5),
(83, 1289661289, 21, 5),
(84, 1289661293, 21, 5),
(85, 1289661330, 21, 5),
(86, 1289715433, 21, 11),
(87, 1290483524, 24, 4),
(88, 1291494717, 24, 15),
(89, 1291496021, 24, 14),
(90, 1291575270, 21, 6),
(91, 1291575298, 21, 6),
(92, 1292107371, 21, 18),
(93, 1292181640, 21, 5),
(94, 1292688835, 21, 1),
(95, 1292689011, 21, 4),
(96, 1292689030, 21, 10),
(97, 1292689038, 21, 4),
(98, 1292894328, 21, 15),
(99, 1292894344, 21, 12),
(100, 1292894370, 21, 13),
(101, 1292894382, 21, 11),
(102, 1292894400, 21, 6),
(103, 1292894412, 21, 7),
(104, 1295207175, 24, 9),
(105, 1295208291, 24, 15),
(106, 1295212835, 24, 1),
(107, 1295212908, 24, 1),
(108, 1295213185, 24, 10),
(109, 1295213459, 24, 11),
(110, 1295213885, 24, 13),
(111, 1295213934, 24, 13),
(112, 1295214676, 24, 6),
(113, 1295215138, 24, 7),
(114, 1295403539, 24, 4),
(115, 1295403639, 24, 14),
(116, 1295403664, 24, 10),
(117, 1295403723, 24, 13),
(118, 1295403791, 24, 11),
(119, 1295403857, 24, 9),
(120, 1295403960, 24, 16),
(121, 1295404002, 24, 1),
(122, 1295404058, 24, 6),
(123, 1295408672, 24, 1),
(124, 1295409132, 24, 1),
(125, 1295472944, 24, 10),
(126, 1295736516, 24, 19),
(127, 1295736745, 24, 20),
(128, 1295915488, 24, 10),
(129, 1295915595, 24, 14),
(130, 1295915654, 24, 18),
(131, 1295915713, 24, 19),
(132, 1295996325, 24, 19),
(133, 1296193414, 21, 19),
(134, 1296193431, 21, 19),
(135, 1296193452, 21, 19),
(136, 1296193514, 21, 19),
(137, 1296193647, 21, 19),
(138, 1296193653, 21, 19),
(139, 1296193743, 21, 19),
(140, 1296193894, 21, 19),
(141, 1296194184, 21, 19),
(142, 1296194214, 21, 19),
(143, 1296194650, 21, 19),
(144, 1296194733, 21, 19),
(145, 1297534136, 24, 19),
(146, 1297534898, 24, 17),
(147, 1298504552, 24, 19),
(148, 1298504617, 24, 19),
(149, 1298858658, 24, 13),
(150, 1298935688, 24, 19),
(151, 1298935798, 24, 19),
(152, 1298935860, 24, 19),
(153, 1298935882, 24, 19),
(154, 1298936014, 24, 13),
(155, 1299036630, 24, 17),
(156, 1299036692, 24, 17),
(157, 1299855340, 24, 8),
(158, 1301348282, 24, 19),
(159, 1301348292, 24, 9),
(160, 1301874116, 24, 13),
(161, 1301874342, 24, 10),
(162, 1301874470, 24, 19),
(163, 1305766169, 24, 9),
(164, 1305823551, 21, 8),
(165, 1306112514, 24, 19),
(166, 1306192443, 24, 19),
(167, 1306193136, 24, 19),
(168, 1306370784, 24, 1),
(169, 1306370824, 24, 17),
(170, 1306936155, 24, 17),
(171, 1306936214, 24, 17),
(172, 1307303289, 24, 17),
(173, 1307303722, 24, 17),
(174, 1307303951, 24, 17),
(175, 1308027775, 21, 1),
(176, 1309735164, 24, 19),
(177, 1309735244, 24, 19),
(178, 1310604545, 24, 19),
(179, 1310604646, 24, 19),
(180, 1310605023, 24, 19),
(181, 1310605371, 24, 19),
(182, 1310605401, 24, 19),
(183, 1311803303, 24, 19),
(184, 1312987022, 24, 13),
(185, 1316551080, 24, 19),
(186, 1316551246, 24, 19),
(187, 1318802709, 24, 19),
(188, 1318802847, 24, 19),
(189, 1322238607, 24, 19),
(190, 1326737688, 24, 19),
(191, 1326762161, 34, 21),
(192, 1326762255, 34, 21),
(193, 1326763818, 34, 21),
(194, 1330279452, 24, 19),
(195, 1330279464, 24, 19),
(196, 1330792624, 24, 19),
(197, 1330887478, 24, 19),
(198, 1330887557, 24, 19),
(199, 1330887715, 24, 19),
(200, 1354384120, 24, 19),
(201, 1373823060, 24, 8),
(202, 1378500106, 24, 15),
(203, 1427826497, 24, 17),
(204, 1427832694, 24, 17),
(205, 1427834279, 24, 17),
(206, 1427834744, 24, 19),
(207, 1427834801, 24, 19),
(208, 1427835017, 24, 19),
(209, 1427835079, 24, 19),
(210, 1427835126, 24, 19),
(211, 1427835242, 24, 19),
(212, 1427835276, 24, 19),
(213, 1427835370, 24, 19),
(214, 1427835413, 24, 19),
(215, 1428097016, 24, 21),
(216, 1428340410, 24, 2),
(217, 1428340745, 24, 2),
(218, 1428341233, 24, 3),
(219, 1428341283, 24, 3),
(220, 1428341360, 24, 3),
(221, 1428341486, 24, 3),
(222, 1428532370, 24, 3),
(223, 1428532431, 24, 3),
(224, 1428532497, 24, 3),
(225, 1428532549, 24, 3),
(226, 1428532570, 24, 3),
(227, 1428532594, 24, 3),
(228, 1428532643, 24, 3),
(229, 1428532688, 24, 3),
(230, 1428532742, 24, 3),
(231, 1428532823, 24, 3),
(232, 1428532864, 24, 3),
(233, 1428532886, 24, 3),
(234, 1435714726, 21, 3),
(235, 1435715017, 21, 5);

-- --------------------------------------------------------

--
-- Table structure for table `pod_casts`
--

CREATE TABLE IF NOT EXISTS `pod_casts` (
`pod_casts_id` int(11) NOT NULL,
  `pod_casts_title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `pod_casts_desc` text COLLATE utf8_unicode_ci NOT NULL,
  `pod_casts_filename` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `pod_casts_date_added` int(11) NOT NULL,
  `pod_casts_publish_date` int(11) NOT NULL,
  `pod_casts_status` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `pod_casts_author` int(11) NOT NULL,
  `pod_casts_date_updated` int(11) NOT NULL,
  `pod_casts_image` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `pod_casts_url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `pod_casts_feature` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=MyISAM AUTO_INCREMENT=37 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `pod_casts`
--

INSERT INTO `pod_casts` (`pod_casts_id`, `pod_casts_title`, `pod_casts_desc`, `pod_casts_filename`, `pod_casts_date_added`, `pod_casts_publish_date`, `pod_casts_status`, `pod_casts_author`, `pod_casts_date_updated`, `pod_casts_image`, `pod_casts_url`, `pod_casts_feature`) VALUES
(24, 'The Five Words', 'Sermon given by Senior Pastor Jimm Ites. The five power words of faith in action: Know, Believe, Receive, Give and Do.', '1365359286_01_Track_1.mp3', 1365359286, 1365310800, 'published', 0, 1431373636, '', 'the_five_words', 0),
(25, 'Sunday Sermon 3-14-13', 'Senior Pastor Jimm Ites message from Sunday service on April 14th.', '1366565285_01_20130414_sermon.mp3', 1366565285, 1366520400, 'published', 0, 1431373623, '', 'sunday_sermon_3-14-13', 0),
(26, 'Sunday Sermon 3-21-13', 'Sunday sermon message given by Pastor Steve.', '1366565595_01_20130421_Sermon.mp3', 1366565595, 1366520400, 'published', 0, 1431373609, '', 'sunday_sermon_3-21-13', 0),
(27, 'Sunday Sermon 7-14-13', 'The sermon message given by Pastor Jimm Ites on July 14th, 2013. It has been edited for time and message content only. (Echo will be fixed in next week\\''s recording)', '1373825469_20130714.mp3', 1373825469, 1373778000, 'published', 0, 1431373584, '', 'sunday_sermon_7-14-13', 0),
(34, 'Sunday Sermon 2-15-15', 'Sunday Service message given by Toni Tilkes. Approximate run time 23 minutes.', '1427223933_01_Sunday_Sermon_2-15-15.mp3', 1427223933, 1427173200, 'published', 0, 1431373742, '', 'sunday_sermon_2-15-15', 0),
(35, 'Sunday Sermon 3-22-15', 'Sunday Service message given by Senior Pastor Jimm Ites. Approximate run time 36 minutes.', '1427750916_Sunday_Sermon_3-22-15.mp3', 1427750916, 1427691600, 'published', 0, 1431373559, '', 'sunday_serm', 0),
(36, 'Sunday Sermon 4-19-15', 'Sunday Service message given by Senior Pastor Jimm Ites. Approximate run time 30 minutes.\r\n', '1430156604_Sunday_Sermon_4-19-2015.mp3', 1430156604, 1430110800, 'published', 0, 1431373552, '', 'sunday_sermon_4-19-15', 1),
(33, 'Sunday Sermon 2-22-15', 'Sunday Service message given by Senior Pastor Jimm Ites. Approximate run time 31 minutes.', '1427222975_01_Telling_God\\''s_Story.mp3', 1427221913, 1427173200, 'published', 0, 1431373576, '', 'sunday_sermon_2-22-15', 0);

-- --------------------------------------------------------

--
-- Table structure for table `polls`
--

CREATE TABLE IF NOT EXISTS `polls` (
`poll_id` int(11) NOT NULL,
  `poll_text` varchar(255) NOT NULL DEFAULT '',
  `poll_date_added` int(11) NOT NULL DEFAULT '0',
  `poll_date_remove` int(11) NOT NULL DEFAULT '0',
  `poll_type` varchar(50) NOT NULL DEFAULT '',
  `poll_active` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `poll_results`
--

CREATE TABLE IF NOT EXISTS `poll_results` (
`poll_results_id` int(11) NOT NULL,
  `poll_id` int(11) NOT NULL DEFAULT '0',
  `poll_selection_id` int(11) NOT NULL DEFAULT '0',
  `ip` varchar(50) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `poll_selections`
--

CREATE TABLE IF NOT EXISTS `poll_selections` (
`poll_selection_id` int(11) NOT NULL,
  `poll_selection_text` varchar(255) NOT NULL DEFAULT '',
  `poll_id` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `products_options_values`
--

CREATE TABLE IF NOT EXISTS `products_options_values` (
`products_options_values_id` int(11) NOT NULL,
  `products_options_name` varchar(255) NOT NULL DEFAULT '',
  `products_options_value` varchar(255) NOT NULL DEFAULT '',
  `products_options_price` float NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `request_prayer`
--

CREATE TABLE IF NOT EXISTS `request_prayer` (
`id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `text` text COLLATE utf8_unicode_ci NOT NULL,
  `date_added` int(11) NOT NULL,
  `approved` tinyint(4) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `request_prayer`
--

INSERT INTO `request_prayer` (`id`, `name`, `email`, `text`, `date_added`, `approved`) VALUES
(3, 'Duane Widen', 'eccchiro@mchsi.com', 'Please pray for me today regarding the complete healing of my body. I am suffering from exposure to poison ivy and poison oak. This began on Monday,,July the 12th. and is affecting my entire body.  It is most concentrated on my face, right arm and right groin area. I am experiencing itching,swelling and pain at new levels. I am using various products with some benefit.\r\nI am believing for a miracle recovery with super natural healing in my total body. I am claiming total healing today, through the Holy Spirit and in Jesus Holy name. \r\nThank you for coming into agreement with me and may this miracle healing glorify the Lord. May the Lord bless you abundantly through the works of the Holy Spirit as you pray faithfully.\r\nBlessings to all,\r\n  Duane Widen', 1310751074, 1),
(4, 'Ty Billings', 'Tybmusic@aol.com', 'Prayer for my marraige. Prayer for Christian friends in our life. We have none.\r\n\r\nLove ya\\''ll and miss you', 1321279758, 1),
(8, 'Nadine Wilson', 'nadinemarsonk@yahoo.com', 'I am asking for prayer for my husband and I.  We are recently married and we have two households.  We are trying to sell my condo and need prayer to get it sold.  This has been a big impact on our financial situation. We know that God will take care of us.', 1367869949, 1),
(16, 'Loni Hauser', 'lonihauser@yahoo.com', 'Hi,\r\nI don\\''t know if anyone noticed that I haven\\''t been to church for quite some time. I chose to help my almost 82 year old Dad so he can stay in his own home. I am unable to work because I am needed here with my dad all the time. This makes it hard to keep up with bills with only my son\\''s disability.  I spend most of my time with him in Clarksville but I go to Waterloo once in a great while when I can have my older brother come over to be here for him. My son takes care of our apartment in Waterloo and I get my mail there. Last year has been quite trying. The apartment that me and my son had in Waterloo got broken into two days in a row. The insurance paid for some of the loss but there was the deductibles. It wasn\\''t safe for us there anymore so I used most of the insurance money to move.  I was able to replace some of what was stolen, but not everything. My car broke down to the point of having to junk it. I was able to get a used car for my son. He then was in an accident and it totaled his car. I was able to get another used car for him with the insurance money but now that car seems to be undrivable. The devil keeps putting these issues in front of us but my faith in God never waivers. I know I am truly blessed with the opportunity to be there for my dad and in just knowing everyone at the Cornerstone Fellowship Church.  I guess my prayer request is for the strength and ability to keep doing what I am doing for my dad and my family. Tell everyone that I miss seeing everyone and thank you for your prayers!!!\r\nLoni Hauser', 1429990214, 1),
(14, 'Naomi strode', 'ngoodell88@gmail.com', 'My husband Patrick and I are about to have our first baby! We are due January 17th. I\\''m just asking for prayers that we have an easy delivery and a healthy baby. Thank you, Naomi', 1421166320, 1),
(15, 'Dave and Joy', 'bgdve123@yahoo.com', 'Pray for us as we begin the process of planning for our wedding and finding a church to call home as we start our new lives together, and start a new chapter in life as husband and wife. \r\n', 1429580938, 0);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE IF NOT EXISTS `settings` (
`settings_id` int(10) unsigned NOT NULL,
  `settings_key` varchar(255) NOT NULL DEFAULT '',
  `settings_value` text NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`settings_id`, `settings_key`, `settings_value`) VALUES
(9, 'contact_email', 'shedog@gmail.com\r\n');

-- --------------------------------------------------------

--
-- Table structure for table `sponsors`
--

CREATE TABLE IF NOT EXISTS `sponsors` (
`sponsor_id` int(10) unsigned NOT NULL,
  `sponsor_name` varchar(255) NOT NULL DEFAULT '',
  `sponsor_url` varchar(255) NOT NULL DEFAULT '',
  `sponsor_desc` text NOT NULL,
  `sponsor_date_added` int(10) unsigned NOT NULL DEFAULT '0',
  `sponsor_short_desc` text NOT NULL,
  `sponsor_logo` varchar(255) NOT NULL DEFAULT '',
  `sponsor_level` varchar(45) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `state`
--

CREATE TABLE IF NOT EXISTS `state` (
`state_id` int(11) NOT NULL,
  `state_abbr` varchar(4) COLLATE utf8_unicode_ci NOT NULL,
  `state_name` varchar(40) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=133 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `state`
--

INSERT INTO `state` (`state_id`, `state_abbr`, `state_name`) VALUES
(1, 'AL', 'Alabama'),
(2, 'AK', 'Alaska'),
(3, 'AS', 'American Samoa'),
(4, 'AZ', 'Arizona'),
(5, 'CA', 'California'),
(6, 'CO', 'Colorado'),
(7, 'CT', 'Connecticut'),
(8, 'DE', 'Delaware'),
(9, 'DC', 'District of Columbia'),
(10, 'FL', 'Florida'),
(11, 'GA', 'Georgia'),
(12, 'HI', 'Hawaii'),
(13, 'ID', 'Idaho'),
(14, 'IL', 'Illinois'),
(15, 'IN', 'Indiana'),
(16, 'IA', 'Iowa'),
(17, 'KS', 'Kansas'),
(18, 'KY', 'Kentucky'),
(19, 'LA', 'Louisiana'),
(20, 'ME', 'Maine'),
(21, 'MD', 'Maryland'),
(22, 'MA', 'Massachusetts'),
(23, 'MI', 'Michigan'),
(24, 'MN', 'Minnesota'),
(25, 'MS', 'Mississippi'),
(26, 'MO', 'Missouri'),
(27, 'MT', 'Montana'),
(28, 'NE', 'Nebraska'),
(29, 'NV', 'Nevada'),
(30, 'NH', 'New Hampshire'),
(31, 'NJ', 'New Jersey'),
(32, 'NM', 'New Mexico'),
(33, 'NY', 'New York'),
(34, 'NC', 'North Carolina'),
(35, 'ND', 'North Dakota'),
(36, 'OH', 'Ohio'),
(37, 'OK', 'Oklahoma'),
(38, 'OR', 'Oregon'),
(39, 'PA', 'Penssylvania'),
(40, 'PR', 'Puero Rico'),
(132, 'WY', 'Wyoming'),
(131, 'WI', 'Wisconsin'),
(130, 'WV', 'West Virginia'),
(129, 'WA', 'Washington'),
(128, 'VI', 'Virginia'),
(127, 'VT', 'Vermont'),
(126, 'UT', 'Utah'),
(125, 'TX', 'Texas'),
(124, 'TN', 'Tennesse'),
(123, 'SD', 'South Dakota'),
(122, 'SC', 'South Carolina'),
(121, 'RI', 'Rhode Island');

-- --------------------------------------------------------

--
-- Table structure for table `states`
--

CREATE TABLE IF NOT EXISTS `states` (
`state_id` int(11) NOT NULL,
  `state_long` varchar(40) NOT NULL DEFAULT '',
  `state_abbr` varchar(40) NOT NULL DEFAULT '',
  `country_id` int(11) NOT NULL DEFAULT '1'
) ENGINE=MyISAM AUTO_INCREMENT=51 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `states`
--

INSERT INTO `states` (`state_id`, `state_long`, `state_abbr`, `country_id`) VALUES
(1, 'Alabama', 'AL', 1),
(2, 'Alaska', 'AK', 1),
(3, 'Arizona', 'AZ', 1),
(4, 'Arkansas', 'AR', 1),
(5, 'California', 'CA', 1),
(6, 'Colorado', 'CO', 1),
(7, 'Connecticut', 'CT', 1),
(8, 'Delaware', 'DE', 1),
(9, 'Florida', 'FL', 1),
(10, 'Georgia', 'GA', 1),
(11, 'Hawaii', 'HI', 1),
(12, 'Idaho', 'ID', 1),
(13, 'Illinois', 'IL', 1),
(14, 'Indiana', 'IN', 1),
(15, 'Iowa', 'IA', 1),
(16, 'Kansas', 'KS', 1),
(17, 'Kentucky', 'KY', 1),
(18, 'Louisiana', 'LA', 1),
(19, 'Maine', 'ME', 1),
(20, 'Maryland', 'MD', 1),
(21, 'Massachusetts', 'MA', 1),
(22, 'Michigan', 'MI', 1),
(23, 'Minnesota', 'MN', 1),
(24, 'Mississippi', 'MS', 1),
(25, 'Missouri', 'MO', 1),
(26, 'Montana', 'MT', 1),
(27, 'Nebraska', 'NE', 1),
(28, 'Nevada', 'NV', 1),
(29, 'New Hampshire', 'NH', 1),
(30, 'New Jersey', 'NJ', 1),
(31, 'New Mexico', 'NM', 1),
(32, 'New York', 'NY', 1),
(33, 'North Carolina', 'NC', 1),
(34, 'North Dakota', 'ND', 1),
(35, 'Ohio', 'OH', 1),
(36, 'Oklahoma', 'OK', 1),
(37, 'Oregon', 'OR', 1),
(38, 'Pennsylvania', 'PA', 1),
(39, 'Rhode Island', 'RI', 1),
(40, 'South Carolina', 'SC', 1),
(41, 'South Dakota', 'SD', 1),
(42, 'Tennessee', 'TN', 1),
(43, 'Texas', 'TX', 1),
(44, 'Utah', 'UT', 1),
(45, 'Vermont', 'VT', 1),
(46, 'Virginia', 'VA', 1),
(47, 'Washington', 'WA', 1),
(48, 'West Virginia', 'WV', 1),
(49, 'Wisconsin', 'WI', 1),
(50, 'Wyoming', 'WY', 1);

-- --------------------------------------------------------

--
-- Table structure for table `store_cart`
--

CREATE TABLE IF NOT EXISTS `store_cart` (
`cart_id` int(11) NOT NULL,
  `cart_session` varchar(100) NOT NULL DEFAULT '',
  `cart_product_id` int(11) NOT NULL DEFAULT '0',
  `cart_product_title` varchar(255) NOT NULL DEFAULT '',
  `cart_added` int(11) NOT NULL DEFAULT '0',
  `cart_qty` tinyint(4) NOT NULL DEFAULT '0',
  `cart_product_stock_number` varchar(255) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `store_categories`
--

CREATE TABLE IF NOT EXISTS `store_categories` (
`categories_id` int(11) NOT NULL,
  `categories_title` varchar(255) NOT NULL DEFAULT '',
  `categories_image` varchar(255) NOT NULL DEFAULT '',
  `categories_sort_order` int(11) NOT NULL DEFAULT '0',
  `categories_date_added` int(11) NOT NULL DEFAULT '0',
  `categories_parent` int(11) NOT NULL DEFAULT '0',
  `categories_desc` text NOT NULL,
  `categories_display` tinyint(4) DEFAULT NULL,
  `categories_enable_purchase` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `store_categories`
--

INSERT INTO `store_categories` (`categories_id`, `categories_title`, `categories_image`, `categories_sort_order`, `categories_date_added`, `categories_parent`, `categories_desc`, `categories_display`, `categories_enable_purchase`) VALUES
(1, 'For Sale', '', 0, 1434288460, 0, 'For Sale Items for website', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `store_orders`
--

CREATE TABLE IF NOT EXISTS `store_orders` (
`orders_id` int(11) NOT NULL,
  `orders_time` int(11) NOT NULL DEFAULT '0',
  `orders_status` enum('pending','complete','onhold') NOT NULL DEFAULT 'pending',
  `orders_message` text NOT NULL,
  `orders_total` float NOT NULL DEFAULT '0',
  `orders_shipping` float NOT NULL DEFAULT '0',
  `orders_shipping_type` varchar(50) NOT NULL DEFAULT '',
  `orders_subtotal` float NOT NULL DEFAULT '0',
  `orders_company` varchar(255) NOT NULL DEFAULT '',
  `orders_customer_name` varchar(255) NOT NULL DEFAULT '',
  `orders_shipping_address` varchar(255) NOT NULL DEFAULT '',
  `orders_shipping_address2` varchar(255) NOT NULL DEFAULT '',
  `orders_shipping_city` varchar(255) NOT NULL DEFAULT '',
  `orders_shipping_state` varchar(30) NOT NULL DEFAULT '',
  `orders_shipping_zipcode` varchar(30) NOT NULL DEFAULT '',
  `orders_billing_address` varchar(255) NOT NULL DEFAULT '',
  `orders_billing_address2` varchar(255) NOT NULL DEFAULT '',
  `orders_billing_city` varchar(255) NOT NULL DEFAULT '',
  `orders_billing_state` varchar(30) NOT NULL DEFAULT '',
  `orders_billing_zipcode` varchar(15) NOT NULL DEFAULT '',
  `orders_customer_email` varchar(255) NOT NULL DEFAULT '',
  `orders_tracking_number` varchar(255) NOT NULL DEFAULT '',
  `orders_complete_date` int(11) NOT NULL DEFAULT '0',
  `orders_phone_number` varchar(25) NOT NULL DEFAULT '',
  `orders_fax_number` varchar(25) NOT NULL DEFAULT '',
  `orders_note` text NOT NULL,
  `orders_card_number` varchar(4) NOT NULL DEFAULT '',
  `orders_expire` varchar(8) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `store_orders_products`
--

CREATE TABLE IF NOT EXISTS `store_orders_products` (
`orders_products_id` int(11) NOT NULL,
  `orders_id` int(11) NOT NULL DEFAULT '0',
  `products_id` int(11) NOT NULL DEFAULT '0',
  `orders_products_title` varchar(255) NOT NULL DEFAULT '',
  `orders_products_price` float NOT NULL DEFAULT '0',
  `orders_products_qty` varchar(10) NOT NULL DEFAULT '',
  `category_id` int(11) NOT NULL DEFAULT '0',
  `orders_product_cert_qty` tinyint(4) NOT NULL DEFAULT '0',
  `subtotal` float NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `store_products`
--

CREATE TABLE IF NOT EXISTS `store_products` (
`products_id` int(11) NOT NULL,
  `products_title` varchar(255) NOT NULL DEFAULT '',
  `products_date_added` varchar(255) NOT NULL DEFAULT '',
  `products_release_date` int(11) NOT NULL DEFAULT '0',
  `products_remove_date` int(11) NOT NULL DEFAULT '0',
  `products_price` float NOT NULL DEFAULT '0',
  `products_sort_order` int(11) NOT NULL DEFAULT '0',
  `products_stock_number` varchar(255) NOT NULL DEFAULT '',
  `categories_id` int(11) NOT NULL DEFAULT '0',
  `products_status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `products_featured` tinyint(4) NOT NULL DEFAULT '0',
  `products_flat_shipping_price` float NOT NULL DEFAULT '0',
  `products_desc` text NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=53 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `store_products`
--

INSERT INTO `store_products` (`products_id`, `products_title`, `products_date_added`, `products_release_date`, `products_remove_date`, `products_price`, `products_sort_order`, `products_stock_number`, `categories_id`, `products_status`, `products_featured`, `products_flat_shipping_price`, `products_desc`) VALUES
(52, 'Test Product', '1434288742', 1434258000, 0, 40, 0, 'A1009', 1, 'active', 0, 0, 'This is a test description of something cool and stuff.');

-- --------------------------------------------------------

--
-- Table structure for table `store_products_images`
--

CREATE TABLE IF NOT EXISTS `store_products_images` (
`products_images_id` int(11) NOT NULL,
  `products_id` int(11) NOT NULL DEFAULT '0',
  `products_images_title` varchar(255) NOT NULL DEFAULT '',
  `products_images_default` tinyint(4) NOT NULL DEFAULT '0',
  `products_images_desc` text NOT NULL,
  `products_images_filename` varchar(255) NOT NULL DEFAULT ''
) ENGINE=MyISAM AUTO_INCREMENT=51 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `store_products_images`
--

INSERT INTO `store_products_images` (`products_images_id`, `products_id`, `products_images_title`, `products_images_default`, `products_images_desc`, `products_images_filename`) VALUES
(50, 52, 'Thing', 1, 'Just a thing', '1_5-920x690.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `store_products_info`
--

CREATE TABLE IF NOT EXISTS `store_products_info` (
`products_info_id` int(11) NOT NULL,
  `products_id` int(11) NOT NULL DEFAULT '0',
  `products_info_desc` text NOT NULL,
  `products_info_custom_1` varchar(255) NOT NULL DEFAULT '',
  `products_info_custom_2` varchar(255) NOT NULL DEFAULT '',
  `products_info_custom_3` varchar(255) NOT NULL DEFAULT '',
  `products_info_custom_4` varchar(255) NOT NULL DEFAULT '0',
  `products_info_custom_5` float NOT NULL DEFAULT '0',
  `products_info_custom_6` varchar(255) NOT NULL DEFAULT '',
  `products_info_custom_7` varchar(255) NOT NULL DEFAULT ''
) ENGINE=MyISAM AUTO_INCREMENT=51 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `store_products_info`
--

INSERT INTO `store_products_info` (`products_info_id`, `products_id`, `products_info_desc`, `products_info_custom_1`, `products_info_custom_2`, `products_info_custom_3`, `products_info_custom_4`, `products_info_custom_5`, `products_info_custom_6`, `products_info_custom_7`) VALUES
(50, 52, 'This will be the description for the product I should probably make this a thing', '', '', '', '', 0, '', '');

-- --------------------------------------------------------

--
-- Table structure for table `store_products_options`
--

CREATE TABLE IF NOT EXISTS `store_products_options` (
`products_options_id` int(11) NOT NULL,
  `products_id` int(11) NOT NULL DEFAULT '0',
  `products_options_title` varchar(255) NOT NULL DEFAULT '',
  `products_options_sort_order` tinyint(4) NOT NULL DEFAULT '0',
  `products_options_date_added` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `store_products_specials`
--

CREATE TABLE IF NOT EXISTS `store_products_specials` (
`products_specials_id` int(11) NOT NULL,
  `products_specials_title` varchar(255) NOT NULL DEFAULT '',
  `products_specials_date_start` int(11) NOT NULL DEFAULT '0',
  `products_specials_date_end` int(11) NOT NULL DEFAULT '0',
  `products_specials_price` float NOT NULL DEFAULT '0',
  `products_id` int(11) NOT NULL DEFAULT '0',
  `products_specials_shipping` float NOT NULL DEFAULT '0',
  `products_specials_discount` varchar(10) NOT NULL DEFAULT '',
  `products_specials_discount_type` enum('percentage','monetary') NOT NULL DEFAULT 'percentage',
  `products_specials_description` text NOT NULL,
  `products_specials_image_filename` varchar(255) NOT NULL DEFAULT '',
  `category_id` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `store_products_specials`
--

INSERT INTO `store_products_specials` (`products_specials_id`, `products_specials_title`, `products_specials_date_start`, `products_specials_date_end`, `products_specials_price`, `products_id`, `products_specials_shipping`, `products_specials_discount`, `products_specials_discount_type`, `products_specials_description`, `products_specials_image_filename`, `category_id`) VALUES
(1, 'Test', 1434258000, 1434344399, 0, 0, 0, '10.00', 'percentage', 'This is a test special.', '', 1);

-- --------------------------------------------------------

--
-- Table structure for table `store_products_to_options`
--

CREATE TABLE IF NOT EXISTS `store_products_to_options` (
`products_to_options_id` int(11) NOT NULL,
  `products_id` int(11) NOT NULL DEFAULT '0',
  `store_products_options_id` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `store_settings`
--

CREATE TABLE IF NOT EXISTS `store_settings` (
  `store_settings_key` varchar(255) NOT NULL DEFAULT '',
  `store_settings_value` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `store_settings`
--

INSERT INTO `store_settings` (`store_settings_key`, `store_settings_value`) VALUES
('order_email_from', 'orders@starlakeherefords.com'),
('order_email_to', 'mike@interactivearmy.com,sherdog@gmail.com, mikesheridan5@msn.com'),
('store_enable_checkout', '1'),
('store_tax_value', '07'),
('store_require_user_account', '0');

-- --------------------------------------------------------

--
-- Table structure for table `store_shipping`
--

CREATE TABLE IF NOT EXISTS `store_shipping` (
`shipping_id` int(11) NOT NULL,
  `embryo_shipping_price` float NOT NULL DEFAULT '0',
  `embryo_shipping_desc` text NOT NULL,
  `semen_shipping_price_1` float NOT NULL DEFAULT '0',
  `semen_shipping_price_2` float NOT NULL DEFAULT '0',
  `semen_shipping_desc` text NOT NULL,
  `semen_shipping_image` varchar(255) NOT NULL DEFAULT ''
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `subscribers`
--

CREATE TABLE IF NOT EXISTS `subscribers` (
`subscriber_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL DEFAULT '1',
  `subscriber_name` varchar(255) NOT NULL DEFAULT '',
  `subscriber_email_address` varchar(255) NOT NULL DEFAULT '',
  `subscriber_sms_agree` tinyint(4) NOT NULL DEFAULT '0',
  `subscriber_newsletter_agree` tinyint(4) NOT NULL DEFAULT '0',
  `subscriber_date_added` int(11) NOT NULL DEFAULT '0',
  `list_id` int(11) NOT NULL DEFAULT '1',
  `subscriber_phone` varchar(50) NOT NULL,
  `subscriber_mobile` varchar(50) NOT NULL,
  `subscriber_address` varchar(50) NOT NULL,
  `subscriber_address2` varchar(50) NOT NULL,
  `subscriber_city` varchar(50) NOT NULL,
  `subscriber_state` varchar(50) NOT NULL,
  `subscriber_zipcode` varchar(15) NOT NULL,
  `subscriber_notes` text NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `subscribers`
--

INSERT INTO `subscribers` (`subscriber_id`, `client_id`, `subscriber_name`, `subscriber_email_address`, `subscriber_sms_agree`, `subscriber_newsletter_agree`, `subscriber_date_added`, `list_id`, `subscriber_phone`, `subscriber_mobile`, `subscriber_address`, `subscriber_address2`, `subscriber_city`, `subscriber_state`, `subscriber_zipcode`, `subscriber_notes`) VALUES
(1, 1, 'Mike Sheridan', 'sherdog@gmail.com', 0, 1, 0, 1, '', '', '', '', '', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `subscriber_lists`
--

CREATE TABLE IF NOT EXISTS `subscriber_lists` (
`subscriber_lists_id` int(11) NOT NULL,
  `subscriber_lists_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `subscriber_lists_desc` text COLLATE utf8_unicode_ci NOT NULL,
  `subscriber_lists_date_added` int(11) NOT NULL,
  `subscriber_author` int(11) NOT NULL,
  `subscriber_lists_call_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `subscriber_lists`
--

INSERT INTO `subscriber_lists` (`subscriber_lists_id`, `subscriber_lists_name`, `subscriber_lists_desc`, `subscriber_lists_date_added`, `subscriber_author`, `subscriber_lists_call_name`) VALUES
(1, 'Newsletter', 'basic newsletter list, the contacts within this list would have signed up via the website.', 1286744215, 0, 'newsletter'),
(4, 'All Contacts', '', 1286853012, 0, 'All');

-- --------------------------------------------------------

--
-- Table structure for table `subscriber_to_lists`
--

CREATE TABLE IF NOT EXISTS `subscriber_to_lists` (
`subscriber_to_lists` int(11) NOT NULL,
  `subscriber_id` int(11) NOT NULL,
  `subscriber_lists_id` int(11) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=170 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `subscriber_to_lists`
--

INSERT INTO `subscriber_to_lists` (`subscriber_to_lists`, `subscriber_id`, `subscriber_lists_id`) VALUES
(48, 16, 4),
(136, 79, 4),
(135, 78, 4),
(134, 77, 4),
(133, 76, 4),
(6, 3, 4),
(7, 3, 2),
(8, 3, 1),
(9, 3, 5),
(19, 14, 4),
(146, 89, 4),
(74, 17, 4),
(163, 101, 4),
(24, 19, 1),
(40, 20, 5),
(26, 21, 4),
(167, 104, 1),
(28, 23, 4),
(46, 24, 4),
(30, 25, 4),
(39, 20, 3),
(38, 20, 1),
(37, 20, 2),
(36, 20, 4),
(131, 74, 4),
(47, 24, 1),
(49, 16, 1),
(50, 16, 3),
(166, 104, 4),
(164, 102, 4),
(54, 26, 4),
(145, 88, 4),
(56, 27, 4),
(57, 28, 4),
(58, 29, 4),
(59, 30, 4),
(60, 31, 4),
(61, 32, 4),
(91, 47, 4),
(132, 75, 4),
(64, 35, 4),
(65, 35, 1),
(66, 35, 3),
(67, 36, 4),
(68, 37, 4),
(69, 37, 1),
(70, 38, 4),
(71, 38, 1),
(78, 40, 1),
(77, 40, 4),
(79, 41, 4),
(80, 41, 1),
(81, 42, 4),
(82, 42, 1),
(88, 43, 1),
(87, 43, 4),
(85, 44, 4),
(86, 44, 1),
(92, 48, 4),
(169, 106, 4),
(168, 105, 1),
(95, 51, 4),
(96, 52, 4),
(97, 53, 4),
(161, 99, 4),
(99, 55, 4),
(100, 56, 4),
(101, 57, 4),
(102, 58, 4),
(103, 59, 4),
(104, 60, 4),
(105, 61, 4),
(106, 62, 4),
(107, 63, 4),
(108, 64, 4),
(109, 65, 4),
(110, 66, 4),
(111, 67, 4),
(112, 68, 4),
(113, 69, 4),
(162, 100, 4),
(115, 71, 4),
(157, 72, 5),
(156, 72, 3),
(155, 72, 1),
(154, 72, 2),
(153, 72, 4),
(126, 73, 4),
(127, 73, 2),
(128, 73, 1),
(129, 73, 3),
(130, 73, 5),
(137, 80, 4),
(138, 81, 4),
(139, 82, 4),
(140, 83, 4),
(141, 84, 4),
(142, 85, 4),
(143, 86, 4),
(144, 87, 4),
(159, 97, 4),
(148, 91, 4),
(149, 92, 4),
(158, 96, 4),
(151, 94, 4),
(160, 98, 4);

-- --------------------------------------------------------

--
-- Table structure for table `testimonials`
--

CREATE TABLE IF NOT EXISTS `testimonials` (
`testimonials_id` int(11) NOT NULL,
  `testimonials_text` text COLLATE utf8_unicode_ci NOT NULL,
  `testimonials_image` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `testimonials_author` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `testimonials_date_added` int(11) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `testimonials`
--

INSERT INTO `testimonials` (`testimonials_id`, `testimonials_text`, `testimonials_image`, `testimonials_author`, `testimonials_date_added`) VALUES
(3, 'After experiencing a healing on my knee, the message of faith has become even more real to me at CFC.', '', 'Steve Schneider', 1291482973),
(4, 'After receiving a couple\\''s story, I looked in the yellow pages to find a film company and found Mark IV Pictures. When we met, the head of Mark IV claimed their number was never listed, and when I looked again it was gone! It appeared just long enough to call. The film was \\&quot;All the King\\''s Horses\\&quot;.', '', 'Jimm Ites', 1428337305);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
`user_id` int(11) NOT NULL,
  `user_name` varchar(60) NOT NULL DEFAULT '',
  `user_password` varchar(255) NOT NULL DEFAULT '',
  `user_created` int(11) NOT NULL DEFAULT '0',
  `user_last_login` int(11) NOT NULL DEFAULT '0',
  `user_type` int(11) NOT NULL DEFAULT '0',
  `user_access` varchar(255) NOT NULL DEFAULT '0',
  `client_id` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM AUTO_INCREMENT=35 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `user_name`, `user_password`, `user_created`, `user_last_login`, `user_type`, `user_access`, `client_id`) VALUES
(21, 'mike@interactivearmy.com', 'michael5', 1275257640, 1435710529, 0, 'god,admin', 0);

-- --------------------------------------------------------

--
-- Table structure for table `user_access`
--

CREATE TABLE IF NOT EXISTS `user_access` (
`user_access_id` int(11) NOT NULL,
  `user_access_title` varchar(255) NOT NULL DEFAULT '',
  `user_access_desc` text NOT NULL,
  `user_access_name` varchar(255) NOT NULL DEFAULT '',
  `user_access_parent` int(11) NOT NULL DEFAULT '0',
  `display` tinyint(4) NOT NULL DEFAULT '1'
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_access`
--

INSERT INTO `user_access` (`user_access_id`, `user_access_title`, `user_access_desc`, `user_access_name`, `user_access_parent`, `display`) VALUES
(1, 'Website Administrator', 'This user has access to every feature and area of the website', 'admin', 0, 1),
(2, 'Content Editor', 'This user has access to the CMS and has edit permissions.', 'content', 0, 1),
(3, 'Communication Administrator', 'This user will be able to access the newsletter section.', 'communication', 0, 1),
(4, 'Event Administrator', 'This user has access to the events section of the website.', 'events', 0, 1),
(5, 'Banner Administrator', 'This user will have access to the banners section allowing them up edit / delete / add banners', 'banners', 0, 0),
(6, 'News Administrator', 'This user has access to the news area which is used in the product spotlights section of the website', 'news', 0, 1),
(7, 'Content Publisher', 'This user has access to the pages section which allows them to publish new content', 'content_publisher', 0, 1),
(8, 'God Mode', 'Has administratable privileges, manages clients and client setup information ', 'god', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `user_access_pages`
--

CREATE TABLE IF NOT EXISTS `user_access_pages` (
  `user_access_id` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `page_content_id` int(11) NOT NULL DEFAULT '0',
  `user_access_pages_type` enum('publisher','manager') NOT NULL DEFAULT 'manager'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_access_pages`
--

INSERT INTO `user_access_pages` (`user_access_id`, `user_id`, `page_content_id`, `user_access_pages_type`) VALUES
(0, 33, 17, 'publisher'),
(0, 34, 3, 'manager'),
(0, 34, 9, 'manager'),
(0, 34, 8, 'manager'),
(0, 34, 1, 'manager'),
(0, 32, 5, 'manager'),
(0, 32, 3, 'publisher'),
(0, 32, 21, 'publisher'),
(0, 32, 19, 'publisher'),
(0, 32, 17, 'publisher'),
(0, 32, 22, 'publisher'),
(0, 32, 2, 'publisher'),
(0, 32, 8, 'publisher'),
(0, 32, 1, 'publisher'),
(0, 32, 9, 'publisher'),
(0, 32, 6, 'publisher'),
(0, 32, 16, 'publisher'),
(0, 32, 15, 'publisher'),
(0, 32, 13, 'publisher'),
(0, 32, 10, 'publisher'),
(0, 32, 14, 'publisher'),
(0, 32, 4, 'publisher'),
(0, 32, 7, 'publisher'),
(0, 32, 5, 'publisher'),
(0, 32, 3, 'manager'),
(0, 32, 21, 'manager'),
(0, 32, 19, 'manager'),
(0, 32, 17, 'manager'),
(0, 32, 22, 'manager'),
(0, 32, 2, 'manager'),
(0, 32, 8, 'manager'),
(0, 32, 1, 'manager'),
(0, 32, 9, 'manager'),
(0, 32, 6, 'manager'),
(0, 32, 16, 'manager'),
(0, 32, 15, 'manager'),
(0, 32, 13, 'manager'),
(0, 32, 10, 'manager'),
(0, 32, 14, 'manager'),
(0, 32, 4, 'manager'),
(0, 32, 7, 'manager');

-- --------------------------------------------------------

--
-- Table structure for table `user_information`
--

CREATE TABLE IF NOT EXISTS `user_information` (
`user_information_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `user_first_name` varchar(60) NOT NULL DEFAULT '',
  `user_last_name` varchar(60) NOT NULL DEFAULT '',
  `user_address` varchar(255) NOT NULL DEFAULT '',
  `user_address2` varchar(255) NOT NULL DEFAULT '',
  `user_city` varchar(255) NOT NULL DEFAULT '',
  `user_state` varchar(255) NOT NULL DEFAULT '',
  `user_zipcode` varchar(255) NOT NULL DEFAULT '',
  `user_country` varchar(255) NOT NULL DEFAULT ''
) ENGINE=MyISAM AUTO_INCREMENT=35 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_information`
--

INSERT INTO `user_information` (`user_information_id`, `user_id`, `user_first_name`, `user_last_name`, `user_address`, `user_address2`, `user_city`, `user_state`, `user_zipcode`, `user_country`) VALUES
(21, 21, 'Mike', 'Sheridan', '', '', '', '', '', ''),
(28, 28, 'David', 'Osborn', '', '', '', '', '', ''),
(24, 24, 'Steve', 'Schneider', '', '', '', '', '', ''),
(31, 31, 'Liz', 'Harken', '', '', '', '', '', ''),
(32, 32, 'Wanda', 'Johnson', '', '', '', '', '', ''),
(33, 33, 'Jimm', 'Ites', '', '', '', '', '', ''),
(34, 34, 'Tyler', 'Steen', '', '', '', '', '', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `access`
--
ALTER TABLE `access`
 ADD PRIMARY KEY (`AccessID`);

--
-- Indexes for table `alerts`
--
ALTER TABLE `alerts`
 ADD PRIMARY KEY (`alerts_id`);

--
-- Indexes for table `articles`
--
ALTER TABLE `articles`
 ADD PRIMARY KEY (`article_id`);

--
-- Indexes for table `banners`
--
ALTER TABLE `banners`
 ADD PRIMARY KEY (`banner_id`);

--
-- Indexes for table `bids`
--
ALTER TABLE `bids`
 ADD PRIMARY KEY (`bids_id`);

--
-- Indexes for table `bid_current`
--
ALTER TABLE `bid_current`
 ADD PRIMARY KEY (`bid_current_id`);

--
-- Indexes for table `blog`
--
ALTER TABLE `blog`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `blog_comments`
--
ALTER TABLE `blog_comments`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `calendar_events`
--
ALTER TABLE `calendar_events`
 ADD PRIMARY KEY (`calendar_events_id`);

--
-- Indexes for table `calendar_events_categories`
--
ALTER TABLE `calendar_events_categories`
 ADD PRIMARY KEY (`calendar_events_categories_id`);

--
-- Indexes for table `calendar_events_items`
--
ALTER TABLE `calendar_events_items`
 ADD PRIMARY KEY (`calendar_events_items_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `client_auth_keys`
--
ALTER TABLE `client_auth_keys`
 ADD PRIMARY KEY (`client_id`);

--
-- Indexes for table `client_config`
--
ALTER TABLE `client_config`
 ADD PRIMARY KEY (`client_config_id`);

--
-- Indexes for table `contact`
--
ALTER TABLE `contact`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customercomments`
--
ALTER TABLE `customercomments`
 ADD PRIMARY KEY (`CustomerCommentsID`);

--
-- Indexes for table `email_queue`
--
ALTER TABLE `email_queue`
 ADD PRIMARY KEY (`email_queue_id`);

--
-- Indexes for table `email_queue_from`
--
ALTER TABLE `email_queue_from`
 ADD PRIMARY KEY (`email_queue_from_id`);

--
-- Indexes for table `email_queue_sent`
--
ALTER TABLE `email_queue_sent`
 ADD PRIMARY KEY (`email_queue_sent`);

--
-- Indexes for table `email_templates`
--
ALTER TABLE `email_templates`
 ADD PRIMARY KEY (`email_templates_id`);

--
-- Indexes for table `employee`
--
ALTER TABLE `employee`
 ADD PRIMARY KEY (`employee_id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
 ADD PRIMARY KEY (`event_id`), ADD FULLTEXT KEY `event_tags` (`event_tags`);

--
-- Indexes for table `event_categories`
--
ALTER TABLE `event_categories`
 ADD PRIMARY KEY (`event_category_id`);

--
-- Indexes for table `event_sponsors`
--
ALTER TABLE `event_sponsors`
 ADD PRIMARY KEY (`event_sponsors_id`);

--
-- Indexes for table `gallery`
--
ALTER TABLE `gallery`
 ADD PRIMARY KEY (`gallery_id`);

--
-- Indexes for table `gallery_images`
--
ALTER TABLE `gallery_images`
 ADD PRIMARY KEY (`gallery_image_id`);

--
-- Indexes for table `global_config`
--
ALTER TABLE `global_config`
 ADD PRIMARY KEY (`config_id`);

--
-- Indexes for table `members`
--
ALTER TABLE `members`
 ADD PRIMARY KEY (`members_id`);

--
-- Indexes for table `news`
--
ALTER TABLE `news`
 ADD PRIMARY KEY (`news_id`);

--
-- Indexes for table `newsletters`
--
ALTER TABLE `newsletters`
 ADD PRIMARY KEY (`newsletter_id`);

--
-- Indexes for table `page_banners`
--
ALTER TABLE `page_banners`
 ADD PRIMARY KEY (`page_banners_id`);

--
-- Indexes for table `page_calculators`
--
ALTER TABLE `page_calculators`
 ADD PRIMARY KEY (`page_calculators_id`);

--
-- Indexes for table `page_content`
--
ALTER TABLE `page_content`
 ADD PRIMARY KEY (`page_content_id`);

--
-- Indexes for table `page_content_downloads`
--
ALTER TABLE `page_content_downloads`
 ADD PRIMARY KEY (`page_content_downloads_id`);

--
-- Indexes for table `page_content_log`
--
ALTER TABLE `page_content_log`
 ADD PRIMARY KEY (`page_content_log_id`);

--
-- Indexes for table `pod_casts`
--
ALTER TABLE `pod_casts`
 ADD PRIMARY KEY (`pod_casts_id`);

--
-- Indexes for table `polls`
--
ALTER TABLE `polls`
 ADD PRIMARY KEY (`poll_id`);

--
-- Indexes for table `poll_results`
--
ALTER TABLE `poll_results`
 ADD PRIMARY KEY (`poll_results_id`);

--
-- Indexes for table `poll_selections`
--
ALTER TABLE `poll_selections`
 ADD PRIMARY KEY (`poll_selection_id`);

--
-- Indexes for table `products_options_values`
--
ALTER TABLE `products_options_values`
 ADD PRIMARY KEY (`products_options_values_id`);

--
-- Indexes for table `request_prayer`
--
ALTER TABLE `request_prayer`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
 ADD PRIMARY KEY (`settings_id`);

--
-- Indexes for table `sponsors`
--
ALTER TABLE `sponsors`
 ADD PRIMARY KEY (`sponsor_id`);

--
-- Indexes for table `state`
--
ALTER TABLE `state`
 ADD PRIMARY KEY (`state_id`);

--
-- Indexes for table `states`
--
ALTER TABLE `states`
 ADD PRIMARY KEY (`state_id`);

--
-- Indexes for table `store_cart`
--
ALTER TABLE `store_cart`
 ADD PRIMARY KEY (`cart_id`);

--
-- Indexes for table `store_categories`
--
ALTER TABLE `store_categories`
 ADD PRIMARY KEY (`categories_id`);

--
-- Indexes for table `store_orders`
--
ALTER TABLE `store_orders`
 ADD PRIMARY KEY (`orders_id`);

--
-- Indexes for table `store_orders_products`
--
ALTER TABLE `store_orders_products`
 ADD PRIMARY KEY (`orders_products_id`);

--
-- Indexes for table `store_products`
--
ALTER TABLE `store_products`
 ADD PRIMARY KEY (`products_id`);

--
-- Indexes for table `store_products_images`
--
ALTER TABLE `store_products_images`
 ADD PRIMARY KEY (`products_images_id`);

--
-- Indexes for table `store_products_info`
--
ALTER TABLE `store_products_info`
 ADD PRIMARY KEY (`products_info_id`);

--
-- Indexes for table `store_products_options`
--
ALTER TABLE `store_products_options`
 ADD PRIMARY KEY (`products_options_id`);

--
-- Indexes for table `store_products_specials`
--
ALTER TABLE `store_products_specials`
 ADD PRIMARY KEY (`products_specials_id`);

--
-- Indexes for table `store_products_to_options`
--
ALTER TABLE `store_products_to_options`
 ADD PRIMARY KEY (`products_to_options_id`);

--
-- Indexes for table `store_shipping`
--
ALTER TABLE `store_shipping`
 ADD PRIMARY KEY (`shipping_id`);

--
-- Indexes for table `subscribers`
--
ALTER TABLE `subscribers`
 ADD PRIMARY KEY (`subscriber_id`);

--
-- Indexes for table `subscriber_lists`
--
ALTER TABLE `subscriber_lists`
 ADD PRIMARY KEY (`subscriber_lists_id`);

--
-- Indexes for table `subscriber_to_lists`
--
ALTER TABLE `subscriber_to_lists`
 ADD PRIMARY KEY (`subscriber_to_lists`), ADD KEY `subscriber_id` (`subscriber_id`);

--
-- Indexes for table `testimonials`
--
ALTER TABLE `testimonials`
 ADD PRIMARY KEY (`testimonials_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
 ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `user_access`
--
ALTER TABLE `user_access`
 ADD PRIMARY KEY (`user_access_id`);

--
-- Indexes for table `user_access_pages`
--
ALTER TABLE `user_access_pages`
 ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `user_information`
--
ALTER TABLE `user_information`
 ADD PRIMARY KEY (`user_information_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `access`
--
ALTER TABLE `access`
MODIFY `AccessID` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `alerts`
--
ALTER TABLE `alerts`
MODIFY `alerts_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `articles`
--
ALTER TABLE `articles`
MODIFY `article_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `banners`
--
ALTER TABLE `banners`
MODIFY `banner_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=21;
--
-- AUTO_INCREMENT for table `bids`
--
ALTER TABLE `bids`
MODIFY `bids_id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `blog`
--
ALTER TABLE `blog`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `blog_comments`
--
ALTER TABLE `blog_comments`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `calendar_events`
--
ALTER TABLE `calendar_events`
MODIFY `calendar_events_id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `calendar_events_categories`
--
ALTER TABLE `calendar_events_categories`
MODIFY `calendar_events_categories_id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `calendar_events_items`
--
ALTER TABLE `calendar_events_items`
MODIFY `calendar_events_items_id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `client_auth_keys`
--
ALTER TABLE `client_auth_keys`
MODIFY `client_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `contact`
--
ALTER TABLE `contact`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `customercomments`
--
ALTER TABLE `customercomments`
MODIFY `CustomerCommentsID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `email_queue`
--
ALTER TABLE `email_queue`
MODIFY `email_queue_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=358;
--
-- AUTO_INCREMENT for table `email_queue_from`
--
ALTER TABLE `email_queue_from`
MODIFY `email_queue_from_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT for table `email_queue_sent`
--
ALTER TABLE `email_queue_sent`
MODIFY `email_queue_sent` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `email_templates`
--
ALTER TABLE `email_templates`
MODIFY `email_templates_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `employee`
--
ALTER TABLE `employee`
MODIFY `employee_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
MODIFY `event_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `event_categories`
--
ALTER TABLE `event_categories`
MODIFY `event_category_id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `event_sponsors`
--
ALTER TABLE `event_sponsors`
MODIFY `event_sponsors_id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `gallery`
--
ALTER TABLE `gallery`
MODIFY `gallery_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=24;
--
-- AUTO_INCREMENT for table `gallery_images`
--
ALTER TABLE `gallery_images`
MODIFY `gallery_image_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=76;
--
-- AUTO_INCREMENT for table `global_config`
--
ALTER TABLE `global_config`
MODIFY `config_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `members`
--
ALTER TABLE `members`
MODIFY `members_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `news`
--
ALTER TABLE `news`
MODIFY `news_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `newsletters`
--
ALTER TABLE `newsletters`
MODIFY `newsletter_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `page_banners`
--
ALTER TABLE `page_banners`
MODIFY `page_banners_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=37;
--
-- AUTO_INCREMENT for table `page_calculators`
--
ALTER TABLE `page_calculators`
MODIFY `page_calculators_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=91;
--
-- AUTO_INCREMENT for table `page_content`
--
ALTER TABLE `page_content`
MODIFY `page_content_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `page_content_downloads`
--
ALTER TABLE `page_content_downloads`
MODIFY `page_content_downloads_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `page_content_log`
--
ALTER TABLE `page_content_log`
MODIFY `page_content_log_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=236;
--
-- AUTO_INCREMENT for table `pod_casts`
--
ALTER TABLE `pod_casts`
MODIFY `pod_casts_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=37;
--
-- AUTO_INCREMENT for table `polls`
--
ALTER TABLE `polls`
MODIFY `poll_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `poll_results`
--
ALTER TABLE `poll_results`
MODIFY `poll_results_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `poll_selections`
--
ALTER TABLE `poll_selections`
MODIFY `poll_selection_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `products_options_values`
--
ALTER TABLE `products_options_values`
MODIFY `products_options_values_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `request_prayer`
--
ALTER TABLE `request_prayer`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
MODIFY `settings_id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `sponsors`
--
ALTER TABLE `sponsors`
MODIFY `sponsor_id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `state`
--
ALTER TABLE `state`
MODIFY `state_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=133;
--
-- AUTO_INCREMENT for table `states`
--
ALTER TABLE `states`
MODIFY `state_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=51;
--
-- AUTO_INCREMENT for table `store_cart`
--
ALTER TABLE `store_cart`
MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `store_categories`
--
ALTER TABLE `store_categories`
MODIFY `categories_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `store_orders`
--
ALTER TABLE `store_orders`
MODIFY `orders_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `store_orders_products`
--
ALTER TABLE `store_orders_products`
MODIFY `orders_products_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `store_products`
--
ALTER TABLE `store_products`
MODIFY `products_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=53;
--
-- AUTO_INCREMENT for table `store_products_images`
--
ALTER TABLE `store_products_images`
MODIFY `products_images_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=51;
--
-- AUTO_INCREMENT for table `store_products_info`
--
ALTER TABLE `store_products_info`
MODIFY `products_info_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=51;
--
-- AUTO_INCREMENT for table `store_products_options`
--
ALTER TABLE `store_products_options`
MODIFY `products_options_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `store_products_specials`
--
ALTER TABLE `store_products_specials`
MODIFY `products_specials_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `store_products_to_options`
--
ALTER TABLE `store_products_to_options`
MODIFY `products_to_options_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `store_shipping`
--
ALTER TABLE `store_shipping`
MODIFY `shipping_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `subscribers`
--
ALTER TABLE `subscribers`
MODIFY `subscriber_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `subscriber_lists`
--
ALTER TABLE `subscriber_lists`
MODIFY `subscriber_lists_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `subscriber_to_lists`
--
ALTER TABLE `subscriber_to_lists`
MODIFY `subscriber_to_lists` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=170;
--
-- AUTO_INCREMENT for table `testimonials`
--
ALTER TABLE `testimonials`
MODIFY `testimonials_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=35;
--
-- AUTO_INCREMENT for table `user_access`
--
ALTER TABLE `user_access`
MODIFY `user_access_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `user_information`
--
ALTER TABLE `user_information`
MODIFY `user_information_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=35;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
