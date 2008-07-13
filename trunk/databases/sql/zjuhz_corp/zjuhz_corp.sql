/*==============================================================*/
/* DBMS name:      MySQL 5.0                                    */
/* Created on:     2008-6-16 14:59:05                           */
/*==============================================================*/


drop database if exists zjuhz_corp;

/*==============================================================*/
/* Database: zjuhz_corp                                         */
/*==============================================================*/
create database zjuhz_corp;

use zjuhz_corp;

/*==============================================================*/
/* Table: tbl_base                                              */
/*==============================================================*/
create table zjuhz_corp.tbl_base
(
   companies            int(10) unsigned not null default 0
)
type = MYISAM;

INSERT INTO `zjuhz_corp`.`tbl_base` (`companies`) VALUES ('0');

/*==============================================================*/
/* Table: tbl_corp                                              */
/*==============================================================*/
create table zjuhz_corp.tbl_corp
(
   uid                  int(10) unsigned not null,
   realName             char(6) not null,
   valid                tinyint(1) unsigned not null default 0,
   auditing             tinyint(1) unsigned not null default 0,
   untread              tinyint(1) unsigned not null default 0,
   msgs                 int unsigned not null default 0,
   primary key (uid)
)
type = MYISAM;

/*==============================================================*/
/* Table: tbl_corp_company                                      */
/*==============================================================*/
create table zjuhz_corp.tbl_corp_company
(
   cid                  char(10) not null,
   uid                  int(10) unsigned not null default 0,
   name                 varchar(50) not null,
   industry             tinyint unsigned not null default 0,
   property             tinyint unsigned not null default 0,
   province             char(8) not null,
   city                 char(11) not null,
   intro                text default NULL,
   pageview             int unsigned not null default 0,
   status               tinyint(1) unsigned not null default 0,
   recmd                tinyint(1) unsigned not null default 0,
   regTime              timestamp not null default CURRENT_TIMESTAMP,
   face                 enum('Y','N') not null default 'N',
   msgs                 int unsigned not null default 0,
   primary key (cid)
)
type = MYISAM;

/*==============================================================*/
/* Index: idx_uid                                               */
/*==============================================================*/
create index idx_uid on tbl_corp_company
(
   uid
);

/*==============================================================*/
/* Index: idx_industry                                          */
/*==============================================================*/
create index idx_industry on tbl_corp_company
(
   industry
);

/*==============================================================*/
/* Index: idx_status                                            */
/*==============================================================*/
create index idx_status on tbl_corp_company
(
   status
);

/*==============================================================*/
/* Index: idx_recmd                                             */
/*==============================================================*/
create index idx_recmd on tbl_corp_company
(
   recmd
);

/*==============================================================*/
/* Table: tbl_corp_company_biz                                  */
/*==============================================================*/
create table zjuhz_corp.tbl_corp_company_biz
(
   cid                  char(10) not null,
   uid                  int(10) unsigned not null default 0,
   product              text default NULL,
   job                  text default NULL,
   cooperate            text default NULL,
   primary key (cid)
)
type = MYISAM;

/*==============================================================*/
/* Index: idx_uid                                               */
/*==============================================================*/
create index idx_uid on tbl_corp_company_biz
(
   uid
);

/*==============================================================*/
/* Table: tbl_corp_company_contact                              */
/*==============================================================*/
create table zjuhz_corp.tbl_corp_company_contact
(
   cid                  char(10) not null,
   uid                  int(10) unsigned not null default 0,
   phone                varchar(13) not null,
   fax                  varchar(13) default NULL,
   eMail                varchar(50) default NULL,
   url                  varchar(50) default NULL,
   address              varchar(80) default NULL,
   postcode             char(6) default NULL,
   other                varchar(50) default NULL,
   primary key (cid)
)
type = MYISAM;

/*==============================================================*/
/* Index: idx_uid                                               */
/*==============================================================*/
create index idx_uid on tbl_corp_company_contact
(
   uid
);

/*==============================================================*/
/* Table: tbl_corp_company_link                                 */
/*==============================================================*/
create table zjuhz_corp.tbl_corp_company_link
(
   lid                  int unsigned not null auto_increment,
   cid                  char(10) not null,
   uid                  int(10) unsigned not null default 0,
   name                 varchar(20) not null,
   url                  varchar(200) not null,
   orderId              tinyint unsigned not null default 0,
   click                int unsigned not null default 0,
   primary key (lid)
)
type = MYISAM;

/*==============================================================*/
/* Index: idx_id                                                */
/*==============================================================*/
create index idx_id on tbl_corp_company_link
(
   cid,
   uid
);

/*==============================================================*/
/* Index: idx_order                                             */
/*==============================================================*/
create index idx_order on tbl_corp_company_link
(
   orderId
);

/*==============================================================*/
/* Table: tbl_corp_industry                                     */
/*==============================================================*/
create table zjuhz_corp.tbl_corp_industry
(
   iid                  tinyint unsigned not null,
   count                int unsigned not null default 0,
   primary key (iid)
)
type = MYISAM;

/*==============================================================*/
/* Table: tbl_corp_company_msg                                  */
/*==============================================================*/
create table zjuhz_corp.tbl_corp_company_msg
(
   mid                  int unsigned not null auto_increment,
   cid                  char(10) not null,
   uid                  int(10) unsigned not null,
   message              varchar(220) not null,
   reply                varchar(220) default NULL,
   addTime              timestamp not null default CURRENT_TIMESTAMP,
   status               tinyint unsigned not null default 0,
   primary key (mid)
)
type = MYISAM;

/*==============================================================*/
/* Index: idx_cid                                               */
/*==============================================================*/
create index idx_cid on tbl_corp_company_msg
(
   cid
);

/*==============================================================*/
/* Index: idx_uid                                               */
/*==============================================================*/
create index idx_uid on tbl_corp_company_msg
(
   uid
);

/*==============================================================*/
/* Index: idx_status                                            */
/*==============================================================*/
create index idx_status on tbl_corp_company_msg
(
   status
);
