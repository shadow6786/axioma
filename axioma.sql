/*
Navicat MySQL Data Transfer

Source Server         : Localhost
Source Server Version : 50524
Source Host           : localhost:3306
Source Database       : axioma

Target Server Type    : MYSQL
Target Server Version : 50524
File Encoding         : 65001

Date: 2013-11-26 18:40:13
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `cnf_configuraciones`
-- ----------------------------
DROP TABLE IF EXISTS `cnf_configuraciones`;
CREATE TABLE `cnf_configuraciones` (
  `variable_cnf` varchar(70) DEFAULT NULL,
  `valor_cnf` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of cnf_configuraciones
-- ----------------------------
INSERT INTO `cnf_configuraciones` VALUES ('logo', 'http://detail.herokuapp.com/img/logo.png');
INSERT INTO `cnf_configuraciones` VALUES ('nombre', 'axioma');
INSERT INTO `cnf_configuraciones` VALUES ('nombre_panel', 'axioma_system');
