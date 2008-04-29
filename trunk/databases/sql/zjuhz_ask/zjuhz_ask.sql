/*==============================================================*/
/* DBMS name:      MySQL 5.0                                    */
/* Created on:     2008-4-28 22:21:54                           */
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
   realName             char(6) not null,
   point                int unsigned not null default 0,
   question             int unsigned not null default 0,
   unsolved             int unsigned not null default 0,
   solved               int unsigned not null default 0,
   closed               int unsigned not null default 0,
   overtime             int unsigned not null default 0,
   reply                int unsigned not null default 0,
   answer               int unsigned not null default 0,
   collection           int unsigned not null default 0,
   primary key (uid)
)
type = MYISAM;

/*==============================================================*/
/* Index: idx_point                                             */
/*==============================================================*/
create index idx_point on tbl_ask
(
   point
);

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
   sid                  smallint unsigned not null default 0,
   title                char(25) not null,
   content              text not null,
   append               varchar(100) default NULL,
   tags                 char(30) default NULL,
   offer                smallint unsigned not null default 0,
   anonym               enum('Y','N') not null default 'N',
   addTime              datetime not null,
   closedTime           timestamp not null default CURRENT_TIMESTAMP,
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
/* Index: idx_sid                                               */
/*==============================================================*/
create index idx_sid on tbl_ask_closed
(
   sid
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
/* Index: idx_collect                                           */
/*==============================================================*/
create unique index idx_collect on tbl_ask_collection
(
   qid,
   uid
);

/*==============================================================*/
/* Index: idx_addTime                                           */
/*==============================================================*/
create index idx_addTime on tbl_ask_collection
(
   addTime
);

/*==============================================================*/
/* Table: tbl_ask_overtime                                      */
/*==============================================================*/
create table zjuhz_ask.tbl_ask_overtime
(
   qid                  int(10) unsigned not null auto_increment,
   uid                  int(10) unsigned not null,
   sid                  smallint unsigned not null default 0,
   title                char(25) not null,
   content              text not null,
   append               varchar(100) default NULL,
   tags                 char(30) default NULL,
   offer                smallint unsigned not null default 0,
   anonym               enum('Y','N') not null default 'N',
   addTime              datetime not null,
   overTime             timestamp not null default CURRENT_TIMESTAMP,
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
/* Index: idx_sid                                               */
/*==============================================================*/
create index idx_sid on tbl_ask_overtime
(
   sid
);

/*==============================================================*/
/* Table: tbl_ask_point_log                                     */
/*==============================================================*/
create table zjuhz_ask.tbl_ask_point_log
(
   id                   int unsigned not null auto_increment,
   uid                  int(10) unsigned not null,
   point                smallint not null default 0,
   time                 timestamp not null default CURRENT_TIMESTAMP,
   type                 tinyint unsigned not null default 0,
   primary key (id)
)
type = MYISAM;

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
   sid                  smallint unsigned not null default 0,
   title                char(25) not null,
   content              text not null,
   append               varchar(100) default NULL,
   tags                 char(30) default NULL,
   offer                smallint unsigned not null default 0,
   anonym               enum('Y','N') not null default 'N',
   addTime              timestamp not null default CURRENT_TIMESTAMP,
   replyTime            int(10) not null default 0,
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
/* Index: idx_sid                                               */
/*==============================================================*/
create index idx_sid on tbl_ask_question
(
   sid
);

/*==============================================================*/
/* Index: idx_status                                            */
/*==============================================================*/
create index idx_status on tbl_ask_question
(
   status
);

/*==============================================================*/
/* Index: idx_offer                                             */
/*==============================================================*/
create index idx_offer on tbl_ask_question
(
   offer
);

/*==============================================================*/
/* Index: idx_reply                                             */
/*==============================================================*/
create index idx_reply on tbl_ask_question
(
   reply
);

/*==============================================================*/
/* Index: idx_replyTime                                         */
/*==============================================================*/
create index idx_replyTime on tbl_ask_question
(
   replyTime
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
/* Index: idx_status                                            */
/*==============================================================*/
create index idx_status on tbl_ask_reply
(
   status
);

/*==============================================================*/
/* Table: tbl_ask_solved                                        */
/*==============================================================*/
create table zjuhz_ask.tbl_ask_solved
(
   qid                  int(10) unsigned not null auto_increment,
   uid                  int(10) unsigned not null,
   sid                  smallint unsigned not null default 0,
   title                char(25) not null,
   content              text not null,
   append               varchar(100) default NULL,
   tags                 char(30) default NULL,
   offer                smallint unsigned not null default 0,
   anonym               enum('Y','N') not null default 'N',
   addTime              datetime not null,
   solvedTime           timestamp not null default CURRENT_TIMESTAMP,
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
/* Index: idx_sid                                               */
/*==============================================================*/
create index idx_sid on tbl_ask_solved
(
   sid
);

/*==============================================================*/
/* Table: tbl_ask_sort                                          */
/*==============================================================*/
create table zjuhz_ask.tbl_ask_sort
(
   sid                  smallint unsigned not null auto_increment,
   name                 char(20) not null,
   parent               smallint unsigned not null default 0,
   pid                  smallint unsigned not null default 0,
   pName                char(20) not null,
   child                tinyint not null default 0,
   question             int unsigned not null default 0,
   solved               int unsigned not null default 0,
   closed               int unsigned not null default 0,
   overtime             int unsigned not null default 0,
   primary key (sid)
)
type = MYISAM;

/*==============================================================*/
/* Index: idx_parent                                            */
/*==============================================================*/
create index idx_parent on tbl_ask_sort
(
   parent
);
