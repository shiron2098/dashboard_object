create table if not exists xml_log (
   log_record_id INT AUTO_INCREMENT
  ,operator_id       int
  ,command_type varchar(32) null  --
  ,xml_value longtext null
  ,batch_id          varchar(60) null
  ,record_timestamp	timestamp
  ,PRIMARY KEY (log_record_id)
)  ENGINE=INNODB;