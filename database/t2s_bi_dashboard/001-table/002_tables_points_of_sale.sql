CREATE TABLE IF NOT EXISTS points_of_sale (  -- for this table command: get_pos_bi   ; SQL: exec t2s_exportPos 'Yes'
   pos_record_id INT AUTO_INCREMENT
  ,operator_id       int
  ,pos_code			varchar(64) null  --
  ,pos_description   varchar(128)    null
  ,veq_code          varchar(64) null
  ,veq_description   varchar(128)    null
  ,loc_code          varchar(64) null
  ,loc_description   varchar(128)    null
  ,cus_code          varchar(164)     null
  ,cus_description   varchar(128)     null
  ,address_1			varchar(128)     null
  ,address_2			varchar(128)     null
  ,city				varchar(128)     null
  ,state				varchar(128)     null
  ,zip				varchar(128)     null
  ,pos_id            varchar(32) null        -- update pos_id
  ,veq_id            varchar(32) null
  ,loc_id            varchar(32) null
  ,cus_id            varchar(32)null
  ,created_dt        datetime null
  ,batch_id         varchar(60) null
  ,record_timestamp	timestamp
  ,PRIMARY KEY (pos_record_id)
)  ENGINE=INNODB;