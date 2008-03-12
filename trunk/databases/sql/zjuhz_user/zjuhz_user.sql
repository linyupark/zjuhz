/*==============================================================*/
/* DBMS name:      MySQL 5.0                                    */
/* Created on:     2008-3-12 10:58:24                           */
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
   password             char(41) not null,
   realname             char(16) not null,
   nickname             char(16) not null,
   sex                  enum('M','F','S') not null default 'S',
   regip                char(15) default NULL,
   regtime              timestamp not null default CURRENT_TIMESTAMP,
   inviteuid            int(10) unsigned not null,
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
/* Index: idx_inviteuid                                         */
/*==============================================================*/
create index idx_inviteuid on tbl_user
(
   inviteuid
);

/*==============================================================*/
/* Table: tbl_user_extinfo                                      */
/*==============================================================*/
create table zjuhz_user.tbl_user_extinfo
(
   uid                  int(10) unsigned not null,
   status               tinyint(1) unsigned not null default 0,
   lastip               char(15) default NULL,
   lastlogin            int(10) unsigned not null default 0,
   editnick             enum('Y','N') not null default 'Y',
   initask              enum('Y','N') not null default 'N',
   primary key (uid)
)
type = MYISAM;

/*==============================================================*/
/* Table: tbl_user_invite                                       */
/*==============================================================*/
create table zjuhz_user.tbl_user_invite
(
   inviteuid            int(10) unsigned not null,
   sum                  smallint unsigned not null default 0,
   success              smallint unsigned not null default 0,
   primary key (inviteuid)
)
type = MYISAM;

/*==============================================================*/
/* Table: tbl_user_invite_detail                                */
/*==============================================================*/
create table zjuhz_user.tbl_user_invite_detail
(
   invitekey            char(10) not null,
   inviteuid            int(10) unsigned not null,
   realname             char(16) not null,
   invitetime           int unsigned not null default 0,
   regtime              int unsigned not null default 0,
   reguid               int unsigned not null default 0,
   status               tinyint(1) unsigned not null default 0
)
type = MYISAM;

/*==============================================================*/
/* Index: idx_invite                                            */
/*==============================================================*/
create unique index idx_invite on tbl_user_invite_detail
(
   invitekey,
   inviteuid
);

/*==============================================================*/
/* Index: idx_status                                            */
/*==============================================================*/
create index idx_status on tbl_user_invite_detail
(
   status
);

/*==============================================================*/
/* Table: tbl_user_moreinfo                                     */
/*==============================================================*/
create table zjuhz_user.tbl_user_moreinfo
(
   uid                  int(10) unsigned not null,
   evername             varchar(51) default NULL,
   email                varchar(51) not null,
   primary key (uid)
)
type = MYISAM;
