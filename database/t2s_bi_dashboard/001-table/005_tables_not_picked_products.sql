CREATE TABLE IF NOT EXISTS sold_out_products (
   sop_record_id INT AUTO_INCREMENT
  ,operator_id       int
  ,visit_date        datetime null
  ,date_num			int				-- 20191123
  ,week_num			int				-- 201923
  ,month_num			int				-- 201910
  ,pos_id            varchar(32) null
  ,vvs_id			varchar(20) null
  ,rte_id			varchar(32) null
  ,pro_id			varchar(20) null
  ,created_dt        datetime null
  ,batch_id          varchar(60) null
  ,record_timestamp	timestamp default now()
  ,PRIMARY KEY (sop_record_id)
)  ENGINE=INNODB;