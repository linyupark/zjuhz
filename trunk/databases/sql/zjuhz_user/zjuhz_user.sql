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
   username             char(16) not null,
   password             char(32) not null,
   realName             char(16) not null,
   nickname             char(16) not null,
   everName             varchar(11) default NULL,
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
