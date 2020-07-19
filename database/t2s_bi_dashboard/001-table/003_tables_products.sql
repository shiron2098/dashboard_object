CREATE TABLE IF NOT EXISTS products (  -- for this table command: get_pro_bi   ; SQL: exec t2s_exportPRO 'Yes'
   pro_record_id INT AUTO_INCREMENT
  ,operator_id       int
  ,pro_code			varchar(64) null
  ,pro_description   varchar(128) null
  ,pdf_code          varchar(64) null
  ,pdf_description   varchar(128) null
  ,pro_id            varchar(32) null  -- update using pro_id
  ,pdf_id            varchar(32) null
  ,created_dt        datetime null
  ,batch_id          varchar(60) null
  ,record_timestamp	timestamp
  ,PRIMARY KEY (pro_record_id)
)  ENGINE=INNODB;