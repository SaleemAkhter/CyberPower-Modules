
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `openb`
--

-- --------------------------------------------------------

--
-- Table structure for table `[[dbprefix]]categories`
--

CREATE TABLE `[[dbprefix]]categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(60) DEFAULT NULL,
  `url_name` varchar(200) DEFAULT NULL,
  `description` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `[[dbprefix]]categories`
--

INSERT INTO `[[dbprefix]]categories` VALUES
(1, 'Uncategorized', 'uncategorized', 'Uncategorized');

-- --------------------------------------------------------

--
-- Table structure for table `[[dbprefix]]comments`
--

CREATE TABLE `[[dbprefix]]comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `post_id` int(11) DEFAULT '0',
  `user_id` int(11) DEFAULT NULL,
  `author` varchar(50) DEFAULT NULL,
  `author_email` varchar(100) DEFAULT NULL,
  `author_website` varchar(200) DEFAULT NULL,
  `author_ip` varchar(100) NOT NULL,
  `content` text,
  `date` datetime DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `[[dbprefix]]languages`
--

CREATE TABLE `[[dbprefix]]languages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `language` varchar(100) DEFAULT NULL,
  `abbreviation` varchar(3) DEFAULT NULL,
  `author` varchar(100) DEFAULT NULL,
  `author_website` varchar(255) NOT NULL,
  `is_default` enum('0','1') DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `[[dbprefix]]languages`
--

INSERT INTO `[[dbprefix]]languages` VALUES
(1, 'english', 'en', 'Tomaz Muraus', 'http://www.open-blog.info', '1'),
(2, 'slovene', 'sl', 'Tomaz Muraus', 'http://www.open-blog.info', '0');

-- --------------------------------------------------------

--
-- Table structure for table `[[dbprefix]]links`
--

CREATE TABLE `[[dbprefix]]links` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `target` enum('blank','self','parent') DEFAULT 'blank',
  `description` varchar(100) DEFAULT NULL,
  `visible` enum('yes','no') DEFAULT 'yes',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `[[dbprefix]]links`
--

INSERT INTO `[[dbprefix]]links` VALUES
(1, 'Open Blog', 'http://www.open-blog.info', 'blank', 'Open Blog Website', 'yes'),
(2, 'CodeIgniter', 'http://www.codeigniter.com', 'blank', 'Codeigniter PHP Framework', 'yes');

-- --------------------------------------------------------

--
-- Table structure for table `[[dbprefix]]navigation`
--

CREATE TABLE `[[dbprefix]]navigation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) DEFAULT NULL,
  `description` varchar(100) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `external` enum('0','1') NOT NULL DEFAULT '0',
  `position` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `[[dbprefix]]navigation`
--

INSERT INTO `[[dbprefix]]navigation` VALUES
(1, 'Home', 'Index', 'index.php', '0', 1),
(2, 'Archive', 'Archive', 'blog/archive/', '0', 2);

-- --------------------------------------------------------

--
-- Table structure for table `[[dbprefix]]pages`
--

CREATE TABLE `[[dbprefix]]pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(200) DEFAULT NULL,
  `url_title` varchar(200) DEFAULT NULL,
  `author` int(11) DEFAULT '0',
  `date` date DEFAULT NULL,
  `content` text,
  `status` enum('active','inactive') DEFAULT 'active',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `[[dbprefix]]posts`
--

CREATE TABLE `[[dbprefix]]posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `author` int(11) NOT NULL DEFAULT '0',
  `date_posted` date NOT NULL DEFAULT '0000-00-00',
  `title` varchar(200) NOT NULL,
  `url_title` varchar(200) NOT NULL,
  `excerpt` text NOT NULL,
  `content` longtext NOT NULL,
  `allow_comments` enum('0','1') NOT NULL DEFAULT '1',
  `sticky` enum('0','1') NOT NULL DEFAULT '0',
  `status` enum('draft','published') NOT NULL DEFAULT 'published',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `[[dbprefix]]posts`
--

INSERT INTO `[[dbprefix]]posts` VALUES
(1, 1, '2009-01-01', 'Welcome to Open Blog', 'welcome-to-open-blog', '<p>Congratulations!</p>\n<p>If you can see this post, this means Open Blog was successfully installed.</p>\n<p>If you need help, don''t hesitate and visit the Open Blog <a href="http://www.open-blog.info" target="_blank">home page</a>.</p>\n<p>Sincerely,<br />The Open Blog team</p>\n<p><em>Since this is just an example post, feel free to delete it.</em></p>', '', '1', '0', 'published');

-- --------------------------------------------------------

--
-- Table structure for table `[[dbprefix]]posts_to_categories`
--

CREATE TABLE `[[dbprefix]]posts_to_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `post_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `[[dbprefix]]posts_to_categories`
--

INSERT INTO `[[dbprefix]]posts_to_categories` VALUES
(1, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `[[dbprefix]]settings`
--

CREATE TABLE `[[dbprefix]]settings` (
  `name` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `[[dbprefix]]settings`
--

INSERT INTO `[[dbprefix]]settings` VALUES
('admin_email', '[[admin_email]]'),
('allow_registrations', '1'),
('blog_description', '[[site_desc]]'),
('blog_title', '[[site_name]]'),
('enabled', '1'),
('enable_atom_comments', '1'),
('enable_atom_posts', '1'),
('enable_captcha', '1'),
('enable_delicious', '1'),
('enable_digg', '1'),
('enable_furl', '1'),
('enable_reddit', '1'),
('enable_rss_comments', '1'),
('enable_rss_posts', '1'),
('enable_stumbleupon', '1'),
('enable_technorati', '1'),
('links_per_box', '5'),
('meta_keywords', 'open blog, open source, freeware'),
('months_per_archive', '8'),
('offline_reason', ''),
('posts_per_page', '5'),
('recognize_user_agent', '1');

-- --------------------------------------------------------

--
-- Table structure for table `[[dbprefix]]sidebar`
--

CREATE TABLE `[[dbprefix]]sidebar` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `file` varchar(100) NOT NULL,
  `status` enum('enabled','disabled') NOT NULL,
  `position` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `[[dbprefix]]sidebar`
--

INSERT INTO `[[dbprefix]]sidebar` VALUES
(1, 'Search', 'search', 'enabled', 1),
(2, 'Archive', 'archive', 'enabled', 2),
(3, 'Categories', 'categories', 'enabled', 3),
(4, 'Tag_cloud', 'tag_cloud', 'enabled', 4),
(5, 'Feeds', 'feeds', 'enabled', 5),
(6, 'Links', 'links', 'enabled', 6),
(7, 'Other', 'other', 'enabled', 7);

-- --------------------------------------------------------

--
-- Table structure for table `[[dbprefix]]tags`
--

CREATE TABLE `[[dbprefix]]tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `[[dbprefix]]tags`
--

INSERT INTO `[[dbprefix]]tags` VALUES
(1, 'codeigniter'),
(2, 'blog');

-- --------------------------------------------------------

--
-- Table structure for table `[[dbprefix]]tags_to_posts`
--

CREATE TABLE `[[dbprefix]]tags_to_posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tag_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `[[dbprefix]]tags_to_posts`
--

INSERT INTO `[[dbprefix]]tags_to_posts` VALUES
(1, 1, 1),
(2, 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `[[dbprefix]]templates`
--

CREATE TABLE `[[dbprefix]]templates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `author` varchar(100) DEFAULT NULL,
  `path` varchar(100) DEFAULT NULL,
  `image` varchar(100) DEFAULT NULL,
  `is_default` enum('0','1') DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `[[dbprefix]]templates`
--

INSERT INTO `[[dbprefix]]templates` VALUES
(1, 'Colorvoid', 'Arcsin', 'colorvoid', 'colorvoid.jpg', '1'),
(2, 'Beautiful Day', 'Arcsin', 'beautiful_day', 'beautiful_day.jpg', '0'),
(3, 'Natural Essence', 'Arcsin', 'natural_essence', 'natural_essence.jpg', '0'),
(4, 'Contaminated', 'Arcsin', 'contaminated', 'contaminated.jpg', '0'),
(5, 'Emplode', 'Arcsin', 'emplode', 'emplode.jpg', '0'),
(6, 'Vector Lover', 'styleshout', 'vector_lover', 'vector_lover.jpg', '0');

-- --------------------------------------------------------

--
-- Table structure for table `[[dbprefix]]users`
--

CREATE TABLE `[[dbprefix]]users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(64) DEFAULT NULL,
  `wordpress_password` varchar(64) DEFAULT NULL,
  `secret_key` varchar(64) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `website` varchar(100) DEFAULT NULL,
  `msn_messenger` varchar(200) DEFAULT NULL,
  `jabber` varchar(100) DEFAULT NULL,
  `display_name` varchar(50) DEFAULT NULL,
  `about_me` text,
  `registered` datetime DEFAULT '0000-00-00 00:00:00',
  `last_login` datetime DEFAULT '0000-00-00 00:00:00',
  `level` enum('user','administrator') DEFAULT 'user',
  `status` enum('0','1') DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `[[dbprefix]]users`
--

INSERT INTO `[[dbprefix]]users` VALUES
(1, '[[admin_username]]', '[[admin_pass]]', NULL, '', '[[admin_email]]', NULL, NULL, NULL, '[[admin_realname]]', NULL, '[[regtime]]', '0000-00-00 00:00:00', 'administrator', '1');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
