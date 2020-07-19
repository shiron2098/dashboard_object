CREATE TABLE IF NOT EXISTS not_picked_products (
   npp_record_id INT AUTO_INCREMENT
  ,operator_id       int
  ,visit_datetime        datetime null
  ,date_num			int				-- 20191123
  ,week_num			int				-- 201923
  ,month_num			int				-- 201910
  ,pos_id            varchar(20) null
  ,vvs_id               varchar(20) null
  ,rte_id			varchar(32) null
  ,pro_id			varchar(20) null
  ,not_picked        int null          -- number of items not picked (when added < pre-kit number in service order)
  ,total_picked      int null          -- total number of picked items from sco
  ,created_dt        datetime null
  ,batch_id         varchar(60) null
  ,record_timestamp	timestamp default now()
  ,PRIMARY KEY (npp_record_id)
)  ENGINE=INNODB;