/*
 Navicat Premium Data Transfer

 Source Server         : 127.0.0.1
 Source Server Type    : MySQL
 Source Server Version : 50726
 Source Host           : 127.0.0.1:3306
 Source Schema         : orderlei

 Target Server Type    : MySQL
 Target Server Version : 50726
 File Encoding         : 65001

 Date: 21/05/2020 19:45:40
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for admin
-- ----------------------------
DROP TABLE IF EXISTS `admin`;
CREATE TABLE `admin` (
  `id` int(3) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL COMMENT '管理员名称',
  `actualname` varchar(10) CHARACTER SET utf8 COLLATE utf8_croatian_ci NOT NULL COMMENT '真实姓名',
  `password` varchar(255) NOT NULL COMMENT '管理员密码',
  `phone` bigint(255) unsigned NOT NULL COMMENT '管理员电话',
  `id_card` bigint(1) unsigned NOT NULL COMMENT '管理员身份证',
  `address` varchar(255) NOT NULL COMMENT '管理员住址',
  `age` int(2) unsigned NOT NULL COMMENT '管理员年龄',
  `email` varchar(255) NOT NULL COMMENT '管理员邮箱',
  `addtime` int(255) unsigned NOT NULL DEFAULT '0' COMMENT '添加管理员时间',
  `status` int(1) unsigned NOT NULL DEFAULT '0' COMMENT '管理员状态 0正常 1禁用',
  `logintime` int(255) unsigned NOT NULL DEFAULT '0' COMMENT '最近登录时间',
  `IP_login` varchar(255) CHARACTER SET utf8 COLLATE utf8_croatian_ci NOT NULL DEFAULT '0' COMMENT '最近登录ip',
  `role_id` int(10) unsigned NOT NULL COMMENT '角色id',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8 COMMENT='管理员表';

-- ----------------------------
-- Records of admin
-- ----------------------------
BEGIN;
INSERT INTO `admin` VALUES (1, 'youyandong', '游延东', '0192023a7bbd73250516f069df18b500', 18286004945, 522124199811091614, '贵州省贵阳市', 21, 'youyandong.new@gmail.com', 1588408655, 0, 1590059025, '127.0.0.1', 1);
INSERT INTO `admin` VALUES (6, 'user', '张三', '0192023a7bbd73250516f069df18b500', 18286004945, 522123122311231200, '贵州省贵阳市观山湖区金阳新世界', 26, 'yodong.new@gmail.com', 1589261237, 0, 1589771249, '127.0.0.1', 2);
INSERT INTO `admin` VALUES (16, 'user2', '李良静', 'e10adc3949ba59abbe56e057f20f883e', 18286004945, 542234233433453432, '贵州省贵阳市', 12, 'youyandong.new@gmail.com', 1589709417, 0, 1589709425, '127.0.0.1', 2);
COMMIT;

-- ----------------------------
-- Table structure for auth
-- ----------------------------
DROP TABLE IF EXISTS `auth`;
CREATE TABLE `auth` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `auth_name` varchar(10) CHARACTER SET utf8 COLLATE utf8_croatian_ci NOT NULL COMMENT '权限名称',
  `auth_pid` int(11) NOT NULL COMMENT '父级id',
  `auth_controller` varchar(20) CHARACTER SET utf8 COLLATE utf8_croatian_ci NOT NULL COMMENT '控制器名称',
  `auth_method` varchar(20) CHARACTER SET utf8 COLLATE utf8_croatian_ci NOT NULL COMMENT '操作方法名称',
  `auth_path` varchar(50) CHARACTER SET utf8 COLLATE utf8_croatian_ci NOT NULL COMMENT '全链接',
  `auth_level` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '操作级别',
  `icon_font` varchar(255) CHARACTER SET utf8 COLLATE utf8_croatian_ci DEFAULT NULL COMMENT '图标',
  `sort` int(11) unsigned DEFAULT NULL COMMENT '排序',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=utf8 COMMENT='权限表\n';

-- ----------------------------
-- Records of auth
-- ----------------------------
BEGIN;
INSERT INTO `auth` VALUES (1, '管理员管理', 0, ' ', ' ', ' ', 0, 'fa fa-user-o', 1);
INSERT INTO `auth` VALUES (2, '管理员列表', 1, 'admin', 'index', 'admin/index', 1, '', NULL);
INSERT INTO `auth` VALUES (3, '添加管理员', 2, 'admin', 'add', 'admin/add', 2, ' ', NULL);
INSERT INTO `auth` VALUES (4, '角色列表', 1, 'role', 'index', 'role/index ', 1, '', NULL);
INSERT INTO `auth` VALUES (5, '网站设置', 0, ' ', ' ', ' ', 0, 'fa fa-cog', 3);
INSERT INTO `auth` VALUES (6, '网站设置', 5, 'setup', 'index', 'setup/index', 1, '', NULL);
INSERT INTO `auth` VALUES (9, '添加角色', 4, 'role', 'add', 'role/add', 2, ' ', NULL);
INSERT INTO `auth` VALUES (10, '修改角色', 4, 'role', 'edit', 'role/edit', 2, ' ', NULL);
INSERT INTO `auth` VALUES (11, '修改管理员', 2, 'admin', 'edit', 'admin/edit', 2, ' ', NULL);
INSERT INTO `auth` VALUES (12, '删除角色', 4, 'role', 'del', 'role/del', 2, ' ', NULL);
INSERT INTO `auth` VALUES (13, '删除管理员', 2, 'admin', 'del', 'admin/del', 2, ' ', NULL);
INSERT INTO `auth` VALUES (14, '批量删除管理员', 2, 'admin', 'delall', 'admin/delall', 2, ' ', NULL);
INSERT INTO `auth` VALUES (15, '查看管理员详情', 2, 'admin', 'view', 'admin/view', 2, '', NULL);
INSERT INTO `auth` VALUES (16, '查看角色详情', 4, 'role', 'view', 'role/view', 2, ' ', NULL);
INSERT INTO `auth` VALUES (17, '菜单管理', 0, ' ', ' ', ' ', 0, 'fa fa-reorder (alias)', 2);
INSERT INTO `auth` VALUES (18, '菜单列表', 17, 'menu', 'index', 'menu/index', 1, ' ', NULL);
INSERT INTO `auth` VALUES (43, '删除菜单', 18, 'menu', 'del', 'menu/del', 2, '', 0);
INSERT INTO `auth` VALUES (44, '编辑菜单', 18, 'menu', 'edit', 'menu/edit', 2, '', 0);
INSERT INTO `auth` VALUES (45, '添加顶级目录', 18, 'menu', 'add1', 'menu/add1', 2, '', 0);
INSERT INTO `auth` VALUES (46, '添加二级目录', 18, 'menu', 'add2', 'menu/add2', 2, '', 0);
INSERT INTO `auth` VALUES (47, '添加三级目录', 18, 'menu', 'add3', 'menu/add3', 2, '', 0);
COMMIT;

-- ----------------------------
-- Table structure for role
-- ----------------------------
DROP TABLE IF EXISTS `role`;
CREATE TABLE `role` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `role_name` varchar(20) CHARACTER SET utf8 COLLATE utf8_croatian_ci NOT NULL COMMENT '权限名称',
  `role_auth_id` varchar(255) CHARACTER SET utf8 COLLATE utf8_croatian_ci NOT NULL COMMENT '权限id',
  `description` varchar(255) CHARACTER SET utf8 COLLATE utf8_croatian_ci NOT NULL COMMENT '权限描述',
  `addtime` int(11) unsigned NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='角色表\n';

-- ----------------------------
-- Records of role
-- ----------------------------
BEGIN;
INSERT INTO `role` VALUES (1, '总管理员', '1,2,4,3,11,13,14,15,9,10,12,16,5,6,17,18,43,44,45,46,47', '本站最高管理员', 1588732146);
INSERT INTO `role` VALUES (2, '测试', '1,2,3,11', '测试账号', 1588832205);
COMMIT;

-- ----------------------------
-- Table structure for setup
-- ----------------------------
DROP TABLE IF EXISTS `setup`;
CREATE TABLE `setup` (
  `id` int(11) NOT NULL,
  `title_name` varchar(255) NOT NULL COMMENT '网站名称',
  `is_show` int(1) NOT NULL DEFAULT '0' COMMENT '是否可以访问本网站',
  `desc` varchar(255) DEFAULT NULL COMMENT '禁止访问网站提示',
  `update_time` int(11) DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of setup
-- ----------------------------
BEGIN;
INSERT INTO `setup` VALUES (1, '雷胖子订单平台', 0, '网站维护中', 1590061073);
COMMIT;

SET FOREIGN_KEY_CHECKS = 1;
