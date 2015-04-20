/*
MySQL - 5.6.17 : Database - vending
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
/*Table structure for table `coins` */

DROP TABLE IF EXISTS `coins`;

CREATE TABLE `coins` (
  `coins_id` int(10) NOT NULL AUTO_INCREMENT,
  `coins_value` decimal(3,2) NOT NULL,
  `coins_quantity` int(6) NOT NULL,
  `coins_active` tinyint(1) NOT NULL,
  PRIMARY KEY (`coins_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_danish_ci;

/*Data for the table `coins` */

insert  into `coins`(`coins_id`,`coins_value`,`coins_quantity`,`coins_active`) values (1,'0.01',50,1),(2,'0.02',50,1),(3,'0.05',50,1),(4,'0.10',50,1),(5,'0.20',50,1),(6,'0.50',50,1),(7,'1.00',50,1),(8,'2.00',0,0);

/*Table structure for table `products` */

DROP TABLE IF EXISTS `products`;

CREATE TABLE `products` (
  `prd_id` int(10) NOT NULL AUTO_INCREMENT,
  `prd_name` varchar(125) COLLATE utf8_danish_ci NOT NULL,
  `prd_img` varchar(125) COLLATE utf8_danish_ci NOT NULL,
  `prd_price` decimal(6,2) NOT NULL,
  `prd_quantity` int(6) NOT NULL,
  PRIMARY KEY (`prd_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_danish_ci;

/*Data for the table `products` */

insert  into `products`(`prd_id`,`prd_name`,`prd_img`,`prd_price`,`prd_quantity`) values (1,'Crisps','','0.65',50),(2,'Orange Juice','','0.85',50),(3,'Apple','','0.45',50),(4,'Chocolate','','1.20',50);

/*Table structure for table `sales` */

DROP TABLE IF EXISTS `sales`;

CREATE TABLE `sales` (
  `sales_id` int(10) NOT NULL AUTO_INCREMENT,
  `sales_product` int(10) NOT NULL,
  `sales_price` decimal(4,2) NOT NULL,
  `sales_datetime` datetime NOT NULL,
  PRIMARY KEY (`sales_id`)
) ENGINE=InnoDB AUTO_INCREMENT=261 DEFAULT CHARSET=utf8 COLLATE=utf8_danish_ci;

/*Data for the table `sales` */

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
