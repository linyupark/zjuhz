/*==============================================================*/
/* DBMS name:      MySQL 5.0                                    */
/* Created on:     2008-4-28 22:14:59                           */
/*==============================================================*/


drop database if exists zjuhz_user;

/*==============================================================*/
/* Database: zjuhz_user                                         */
/*==============================================================*/
create database zjuhz_user;

use zjuhz_user;

/*==============================================================*/
/* Table: tbl_user                                              */
/*==============================================================*/
create table zjuhz_user.tbl_user
(
   uid                  int(10) unsigned not null auto_increment,
   username             char(16) not null,
   password             char(32) not null,
   realName             char(6) not null,
   nickname             char(8) not null,
   everName             varchar(10) default NULL,
   sex                  char(1) not null,
   birthday             date default NULL,
   hometown_p           char(8),
   hometown_c           char(11),
   hometown_a           char(16),
   location_p           char(8),
   location_c           char(11),
   location_a           char(16),
   lastModi             int(10) not null default 0,
   regIp                char(15) default NULL,
   regTime              timestamp not null default CURRENT_TIMESTAMP,
   ikey                 char(10) default NULL,
   iuid                 int(10) not null default 0,
   primary key (uid)
)
type = MYISAM;

/*==============================================================*/
/* Index: idx_username                                          */
/*==============================================================*/
create unique index idx_username on tbl_user
(
   username
);

/*==============================================================*/
/* Index: idx_nickname                                          */
/*==============================================================*/
create index idx_nickname on tbl_user
(
   nickname
);

/*==============================================================*/
/* Table: tbl_user_address_card                                 */
/*==============================================================*/
create table tbl_user_address_card
(
   cid                  char(10) not null,
   gid                  char(5) not null,
   uid                  int(10) unsigned not null,
   cname                char(6) not null,
   mobile               varchar(11) default NULL,
   eMail                varchar(50) default NULL,
   qq                   varchar(15) default NULL,
   msn                  varchar(50) default NULL,
   address              varchar(80) default NULL,
   postcode             char(6) default NULL,
   memo                 varchar(255) default NULL,
   lastModi             int unsigned not null default 0,
   status               tinyint(1) unsigned not null default 3,
   iuid                 int(10) unsigned not null default 0,
   primary key (cid)
)
type = MYISAM;

/*==============================================================*/
/* Index: idx_gid                                               */
/*==============================================================*/
create index idx_gid on tbl_user_address_card
(
   gid
);

/*==============================================================*/
/* Index: idx_uid                                               */
/*==============================================================*/
create index idx_uid on tbl_user_address_card
(
   uid
);

/*==============================================================*/
/* Index: idx_status                                            */
/*==============================================================*/
create index idx_status on tbl_user_address_card
(
   status
);

/*==============================================================*/
/* Table: tbl_user_address_group                                */
/*==============================================================*/
create table tbl_user_address_group
(
   gid                  char(5) not null,
   uid                  int(10) unsigned not null,
   gname                char(10) not null,
   lastModi             int unsigned not null default 0,
   primary key (gid)
)
type = MYISAM;

/*==============================================================*/
/* Index: idx_uid                                               */
/*==============================================================*/
create index idx_uid on tbl_user_address_group
(
   uid
);

/*==============================================================*/
/* Table: tbl_user_contact                                      */
/*==============================================================*/
create table zjuhz_user.tbl_user_contact
(
   uid                  int(10) unsigned not null,
   mobile               varchar(11) default NULL,
   phone                varchar(13) default NULL,
   eMail                varchar(50) default NULL,
   qq                   varchar(15) default NULL,
   msn                  varchar(50) default NULL,
   address              varchar(80) default NULL,
   postcode             char(6) default NULL,
   other                varchar(50) default NULL,
   lastModi             int unsigned not null default 0,
   primary key (uid)
)
type = MYISAM;

/*==============================================================*/
/* Table: tbl_user_ext                                          */
/*==============================================================*/
create table zjuhz_user.tbl_user_ext
(
   uid                  int(10) unsigned not null,
   status               tinyint(1) unsigned not null default 0,
   lastIp               char(15) default NULL,
   lastLogin            int(10) unsigned not null default 0,
   editNick             enum('Y','N') not null default 'N',
   initAsk              enum('Y','N') not null default 'N',
   initClass            enum('Y','N') not null default 'N',
   primary key (uid)
)
type = MYISAM;

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- ���ݿ�: 'zjuhz_user'
--

--
-- �������е����� 'tbl_user'
--

INSERT INTO tbl_user (uid, username, password, realName, nickname, everName, sex, birthday, hometown_p, hometown_c, hometown_a, location_p, location_c, location_a, lastModi, regIp, regTime, ikey, iuid) VALUES
(1, 'zjuhz', 'e10adc3949ba59abbe56e057f20f883e', 'У�ѻ�', 'У�ѻ�', NULL, '��', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, '2008-04-29 09:16:55', NULL, 0);

--
-- �������е����� 'tbl_user_contact'
--

INSERT INTO tbl_user_contact (uid, mobile, eMail, qq, msn, address, postcode, other, lastModi) VALUES
(1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0);

--
-- �������е����� 'tbl_user_ext'
--

INSERT INTO tbl_user_ext (uid, status, lastIp, lastLogin, editNick, initAsk, initClass) VALUES
(1, 2, NULL, 0, 'N', 'N', 'N');
