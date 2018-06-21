/*
SQLyog Ultimate v12.09 (64 bit)
MySQL - 10.1.13-MariaDB : Database - huongnghiepdtm
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`huongnghiepdtm` /*!40100 DEFAULT CHARACTER SET utf8mb4 */;

USE `huongnghiepdtm`;

/*Table structure for table `accounts` */

DROP TABLE IF EXISTS `accounts`;

CREATE TABLE `accounts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(200) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `fullname` varchar(200) DEFAULT NULL,
  `avatar` varchar(100) DEFAULT NULL,
  `type` tinyint(1) DEFAULT '0',
  `status` tinyint(1) DEFAULT '1',
  `permission_id` varchar(500) DEFAULT NULL,
  `root` tinyint(1) DEFAULT '0',
  `gender` tinyint(1) DEFAULT '0',
  `created_at` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `token` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4;

/*Data for the table `accounts` */

insert  into `accounts`(`id`,`email`,`password`,`fullname`,`avatar`,`type`,`status`,`permission_id`,`root`,`gender`,`created_at`,`created_by`,`updated_at`,`updated_by`,`token`) values (1,'chauminhthien0212@gmail.com','932bcc400c4b5a0eb470ef60679836e4','Admin root','Data/Uploads/Avatars/ZueVCTFGEmQiIVTkyMZ6.png',1,1,'4',1,1,NULL,NULL,1527935510,NULL,''),(19,'minhthien1305@gmail.com','932bcc400c4b5a0eb470ef60679836e4','Châu minh Thiện','Data/Uploads/Avatars/vHGHCHSodHFGCpiPyF5v.png',1,1,'2,6,7,8,10,11,14,12,13,18',0,1,1527934817,1,1528640461,1,'becYR2LOOD19hK35FTnyDdkhxJxjhDor0V5rPqx5x1YSp9NjULQNkxlGgmflQYLf'),(22,'minhthien130asc5@gmail.com','932bcc400c4b5a0eb470ef60679836e4','Châu minh Thiện',NULL,1,0,NULL,0,1,1528642219,19,1528645057,1,NULL),(23,'chauminhthien02121@gmail.com','932bcc400c4b5a0eb470ef60679836e4','aaaaaaaaaa',NULL,0,1,NULL,0,1,1528644278,1,NULL,NULL,NULL);

/*Table structure for table `members` */

DROP TABLE IF EXISTS `members`;

CREATE TABLE `members` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(200) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `avatar` varchar(100) DEFAULT NULL,
  `fullname` varchar(100) DEFAULT NULL,
  `gender` tinyint(1) DEFAULT '0',
  `specialized_id` int(11) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `birthday` int(11) DEFAULT NULL,
  `profile` varchar(100) DEFAULT NULL,
  `job_location` int(11) DEFAULT NULL,
  `actived` tinyint(1) DEFAULT '0',
  `status` tinyint(1) DEFAULT '1',
  `created_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

/*Data for the table `members` */

insert  into `members`(`id`,`email`,`password`,`avatar`,`fullname`,`gender`,`specialized_id`,`phone`,`birthday`,`profile`,`job_location`,`actived`,`status`,`created_at`) values (1,'minhthien1305@gmail.com','0123',NULL,'Châu Minh Thiện',1,NULL,NULL,NULL,NULL,NULL,0,0,NULL);

/*Table structure for table `permissions` */

DROP TABLE IF EXISTS `permissions`;

CREATE TABLE `permissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) DEFAULT NULL,
  `key` varchar(200) DEFAULT NULL,
  `link` varchar(200) DEFAULT NULL,
  `parent` int(11) DEFAULT '0',
  `ordering` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4;

/*Data for the table `permissions` */

insert  into `permissions`(`id`,`name`,`key`,`link`,`parent`,`ordering`) values (2,'Accounts','accounts','#',0,2),(6,'Users','users','#',2,1),(7,'User List','userlist','users/',6,1),(8,'Users Create','usercreate','users/create',6,2),(9,'User Delete','userdelete','users/delete',6,3),(10,'User Change','userchange','users/change',6,4),(11,'User Permissions','userpermissions','users/permissions',6,5),(12,'Member List','member_list','members/',14,2),(13,'Member Change Status','member_change','members/change',14,2),(14,'Member','member','#',2,2),(17,'Member Delete','member_del','members/delete',14,3),(18,'Member View Profile','member_view','members/view',14,4);

/*Table structure for table `settings` */

DROP TABLE IF EXISTS `settings`;

CREATE TABLE `settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(200) DEFAULT NULL,
  `name` varchar(200) DEFAULT NULL,
  `extra` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

/*Data for the table `settings` */

insert  into `settings`(`id`,`type`,`name`,`extra`) values (1,'sendmail','Mailer','a:2:{s:6:\"server\";a:6:{s:8:\"hostname\";s:14:\"smtp.gmail.com\";s:8:\"username\";s:23:\"minhthien1305@gmail.com\";s:8:\"password\";s:12:\"Th0123456789\";s:4:\"port\";s:3:\"587\";s:6:\"secure\";s:3:\"tls\";s:7:\"replyTo\";s:23:\"minhthien1305@gmail.com\";}s:4:\"from\";a:2:{s:5:\"email\";s:23:\"minhthien1305@gmail.com\";s:4:\"name\";s:18:\"Châu Minh Thiện\";}}');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
