/*==============================================================*/
/* DBMS name:      MySQL 5.0                                    */
/* Created on:     2008-3-12 14:35:22                           */
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
   userName             char(16) not null,
   passWord             char(41) not null,
   realName             char(16) not null,
   nickName             char(22) not null,
   sex                  enum('M','F','S') not null default 'S',
   regIp                char(15) default NULL,
   regTime              timestamp not null default CURRENT_TIMESTAMP,
   iuid                 int(10) unsigned not null default 0,
   ikey                 char(10) default NULL,
   primary key (uid)
)
type = MYISAM;

/*==============================================================*/
/* Index: idx_userName                                          */
/*==============================================================*/
create unique index idx_userName on tbl_user
(
   userName
);

/*==============================================================*/
/* Index: idx_iuid                                              */
/*==============================================================*/
create index idx_iuid on tbl_user
(
   iuid
);

/*==============================================================*/
/* Table: tbl_user_extInfo                                      */
/*==============================================================*/
create table zjuhz_user.tbl_user_extInfo
(
   uid                  int(10) unsigned not null,
   status               tinyint(1) unsigned not null default 0,
   lastIp               char(15) default NULL,
   lastLogin            int(10) unsigned not null default 0,
   editNick             enum('Y','N') not null default 'Y',
   initAsk              enum('Y','N') not null default 'N',
   primary key (uid)
)
type = MYISAM;

/*==============================================================*/
/* Table: tbl_user_invite                                       */
/*==============================================================*/
create table zjuhz_user.tbl_user_invite
(
   iuid                 int(10) unsigned not null,
   sum                  smallint unsigned not null default 0,
   success              smallint unsigned not null default 0,
   primary key (iuid)
)
type = MYISAM;

/*==============================================================*/
/* Table: tbl_user_invite_detail                                */
/*==============================================================*/
create table zjuhz_user.tbl_user_invite_detail
(
   ikey                 char(10) not null,
   iuid                 int(10) unsigned not null,
   realName             char(16) not null,
   inviteTime           int unsigned not null default 0,
   regTime              int unsigned not null default 0,
   uid                  int unsigned not null default 0,
   status               tinyint(1) unsigned not null default 0
)
type = MYISAM;

/*==============================================================*/
/* Index: idx_invite                                            */
/*==============================================================*/
create unique index idx_invite on tbl_user_invite_detail
(
   ikey,
   iuid
);

/*==============================================================*/
/* Index: idx_status                                            */
/*==============================================================*/
create index idx_status on tbl_user_invite_detail
(
   status
);

/*==============================================================*/
/* Table: tbl_user_moreInfo                                     */
/*==============================================================*/
create table zjuhz_user.tbl_user_moreInfo
(
   uid                  int(10) unsigned not null,
   everName             varchar(51) default NULL,
   email                varchar(51) not null,
   primary key (uid)
)
type = MYISAM;
