CREATE TABLE IF NOT EXISTS groups (
  id_string_group           int auto_increment primary key
 ,operator_id       int         not null
 ,rte_grp_description   varchar(128)    null
 ,rte_grp_source_id            int null
 ,rte_grp_id            varchar(20) null
 ,rte_list      varchar(20) null
 ,changed_or_new    varchar(30)  null
 ,created_dt        datetime null
 ,batch_id          varchar(60) null
 ,record_id         int null
)   ENGINE=INNODB;