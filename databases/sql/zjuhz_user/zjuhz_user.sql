/*==============================================================*/
/* DBMS name:      MySQL 5.0                                    */
/* Created on:     2008-3-23 21:42:05                           */
/*==============================================================*/


drop database if exists zjuhz_ask;

/*==============================================================*/
/* Database: zjuhz_ask                                          */
/*==============================================================*/
create database zjuhz_ask;

use zjuhz_ask;

/*==============================================================*/
/* Table: tbl_ask                                               */
/*==============================================================*/
create table zjuhz_ask.tbl_ask
(
   uid                  int(10) unsigned not null,
   realName             char(16) not null,
   point                int unsigned not null default 0,
   question             int unsigned not null default 0,
   unsolved             int unsigned not null default 0,
   solved               int unsigned not null default 0,
   closed               int unsigned not null default 0,
   overTime             int unsigned not null default 0,
   reply                int unsigned not null default 0,
   answer               int unsigned not null default 0,
   collection           int unsigned not null default 0,
   primary key (uid)
)
type = MYISAM;

/*==============================================================*/
/* Table: tbl_ask_answer                                        */
/*==============================================================*/
create table zjuhz_ask.tbl_ask_answer
(
   rid                  int unsigned not null auto_increment,
   qid                  int(10) unsigned not null,
   uid                  int(10) unsigned not null,
   content              text not null,
   anonym               enum('Y','N') not null default 'N',
   addTime              timestamp not null default CURRENT_TIMESTAMP,
   support              smallint unsigned not null default 0,
   opposition           smallint unsigned not null default 0,
   primary key (rid)
)
type = MYISAM;

/*==============================================================*/
/* Index: idx_qid                                               */
/*==============================================================*/
create index idx_qid on tbl_ask_answer
(
   qid
);

/*==============================================================*/
/* Index: idx_uid                                               */
/*==============================================================*/
create index idx_uid on tbl_ask_answer
(
   uid
);

/*==============================================================*/
/* Table: tbl_ask_closed                                        */
/*==============================================================*/
create table zjuhz_ask.tbl_ask_closed
(
   qid                  int(10) unsigned not null auto_increment,
   uid                  int(10) unsigned not null,
   title                char(30) not null,
   content              text not null,
   append               varchar(101) default NULL,
   sortId               smallint unsigned not null default 0,
   offer                smallint unsigned not null default 0,
   anonym               enum('Y','N') not null default 'N',
   addTime              timestamp not null default CURRENT_TIMESTAMP,
   passTime             int(10) unsigned not null default 0,
   reply                smallint unsigned not null default 0,
   primary key (qid)
)
type = MYISAM;

/*==============================================================*/
/* Index: idx_uid                                               */
/*==============================================================*/
create index idx_uid on tbl_ask_closed
(
   uid
);

/*==============================================================*/
/* Index: idx_sortid                                            */
/*==============================================================*/
create index idx_sortid on tbl_ask_closed
(
   sortId
);

/*==============================================================*/
/* Table: tbl_ask_collection                                    */
/*==============================================================*/
create table zjuhz_ask.tbl_ask_collection
(
   cid                  int unsigned not null auto_increment,
   qid                  int unsigned not null default 0,
   uid                  int unsigned not null default 0,
   addTime              timestamp not null default CURRENT_TIMESTAMP,
   primary key (cid)
)
type = MYISAM;

/*==============================================================*/
/* Table: tbl_ask_overtime                                      */
/*==============================================================*/
create table zjuhz_ask.tbl_ask_overtime
(
   qid                  int(10) unsigned not null auto_increment,
   uid                  int(10) unsigned not null,
   title                char(30) not null,
   content              text not null,
   append               varchar(101) default NULL,
   sortId               smallint unsigned not null default 0,
   offer                smallint unsigned not null default 0,
   anonym               enum('Y','N') not null default 'N',
   addTime              timestamp not null default CURRENT_TIMESTAMP,
   passTime             int(10) unsigned not null default 0,
   reply                smallint unsigned not null default 0,
   primary key (qid)
)
type = MYISAM;

/*==============================================================*/
/* Index: idx_uid                                               */
/*==============================================================*/
create index idx_uid on tbl_ask_overtime
(
   uid
);

/*==============================================================*/
/* Index: idx_sortid                                            */
/*==============================================================*/
create index idx_sortid on tbl_ask_overtime
(
   sortId
);

/*==============================================================*/
/* Table: tbl_ask_point_log                                     */
/*==============================================================*/
create table zjuhz_ask.tbl_ask_point_log
(
   uid                  int(10) unsigned not null,
   point                int unsigned not null default 0,
   addTime              timestamp not null default CURRENT_TIMESTAMP,
   primary key (uid)
)
type = MYISAM;

/*==============================================================*/
/* Index: idx_addtime                                           */
/*==============================================================*/
create index idx_addtime on tbl_ask_point_log
(
   addTime
);

/*==============================================================*/
/* Table: tbl_ask_point_week                                    */
/*==============================================================*/
create table zjuhz_ask.tbl_ask_point_week
(
   uid                  int(10) unsigned not null,
   point                int unsigned not null default 0,
   primary key (uid)
)
type = MYISAM;

/*==============================================================*/
/* Index: idx_point                                             */
/*==============================================================*/
create index idx_point on tbl_ask_point_week
(
   point
);

/*==============================================================*/
/* Table: tbl_ask_question                                      */
/*==============================================================*/
create table zjuhz_ask.tbl_ask_question
(
   qid                  int(10) unsigned not null auto_increment,
   uid                  int(10) unsigned not null,
   title                char(30) not null,
   content              text not null,
   append               varchar(101) default NULL,
   sortId               smallint unsigned not null default 0,
   offer                smallint unsigned not null default 0,
   anonym               enum('Y','N') not null default 'N',
   addTime              timestamp not null default CURRENT_TIMESTAMP,
   passTime             int(10) unsigned not null default 0,
   status               tinyint(1) unsigned not null default 0,
   reply                smallint unsigned not null default 0,
   primary key (qid)
)
type = MYISAM;

/*==============================================================*/
/* Index: idx_uid                                               */
/*==============================================================*/
create index idx_uid on tbl_ask_question
(
   uid
);

/*==============================================================*/
/* Index: idx_sortid                                            */
/*==============================================================*/
create index idx_sortid on tbl_ask_question
(
   sortId
);

/*==============================================================*/
/* Index: idx_status                                            */
/*==============================================================*/
create index idx_status on tbl_ask_question
(
   status
);

/*==============================================================*/
/* Table: tbl_ask_reply                                         */
/*==============================================================*/
create table zjuhz_ask.tbl_ask_reply
(
   rid                  int unsigned not null auto_increment,
   qid                  int(10) unsigned not null,
   uid                  int(10) unsigned not null,
   content              text not null,
   anonym               enum('Y','N') not null default 'N',
   addTime              timestamp not null default CURRENT_TIMESTAMP,
   status               tinyint(1) unsigned not null default 0,
   support              smallint unsigned not null default 0,
   opposition           smallint unsigned not null default 0,
   primary key (rid)
)
type = MYISAM;

/*==============================================================*/
/* Index: idx_qid                                               */
/*==============================================================*/
create index idx_qid on tbl_ask_reply
(
   qid
);

/*==============================================================*/
/* Index: idx_uid                                               */
/*==============================================================*/
create index idx_uid on tbl_ask_reply
(
   uid
);

/*==============================================================*/
/* Table: tbl_ask_solved                                        */
/*==============================================================*/
create table zjuhz_ask.tbl_ask_solved
(
   qid                  int(10) unsigned not null auto_increment,
   uid                  int(10) unsigned not null,
   title                char(30) not null,
   content              text not null,
   append               varchar(101) default NULL,
   sortId               smallint unsigned not null default 0,
   offer                smallint unsigned not null default 0,
   anonym               enum('Y','N') not null default 'N',
   addTime              timestamp not null default CURRENT_TIMESTAMP,
   passTime             int(10) unsigned not null default 0,
   reply                smallint unsigned not null default 0,
   primary key (qid)
)
type = MYISAM;

/*==============================================================*/
/* Index: idx_uid                                               */
/*==============================================================*/
create index idx_uid on tbl_ask_solved
(
   uid
);

/*==============================================================*/
/* Index: idx_sortid                                            */
/*==============================================================*/
create index idx_sortid on tbl_ask_solved
(
   sortId
);
