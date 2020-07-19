CREATE TABLE IF NOT EXISTS routes(
  id_string_route        int auto_increment primary key
 ,operator_id       int         not null
 ,rte_code			varchar(64) null
 ,rte_description   varchar(128)    null
 ,rte_source_id            int null
 ,rte_id            varchar(20) null
 ,changed_or_new    varchar(40)  null
 ,created_dt        datetime null
 ,batch_id      varchar(60)     null
 ,record_id         int null
)  ENGINE=INNODB;