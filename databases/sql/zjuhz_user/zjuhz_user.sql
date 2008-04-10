/*==============================================================*/
/* DBMS name:      MySQL 5.0                                    */
/* Created on:     2008-4-9 22:47:04                            */
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
   passWord             char(32) not null,
   realName             char(16) not null,
   nickName             char(16) not null,
   sex                  enum('M','F','S') not null default 'S',
   regIp                char(15) default NULL,
   regTime              timestamp not null default CURRENT_TIMESTAMP,
   ikey                 char(10) default NULL,
   iuid                 int(10) not null default 0,
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
/* Index: idx_nickName                                          */
/*==============================================================*/
create index idx_nickName on tbl_user
(
   nickName
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
   editNick             enum('Y','N') not null default 'N',
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
   status               tinyint(1) unsigned not null default 0,
   primary key (ikey)
)
type = MYISAM;

/*==============================================================*/
/* Index: idx_iuid                                              */
/*==============================================================*/
create index idx_iuid on tbl_user_invite_detail
(
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
   eMail                varchar(51) default NULL,
   primary key (uid)
)
type = MYISAM;
