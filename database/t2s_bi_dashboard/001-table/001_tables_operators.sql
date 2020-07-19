CREATE TABLE IF NOT EXISTS operators (
   ops_record_id         INT AUTO_INCREMENT
  ,operator_name     varchar(64) UNIQUE -- Unique
  ,operator_id       int      UNIQUE    -- unique
  ,operator_software varchar(64)
  ,record_timestamp	timestamp
  ,PRIMARY KEY (ops_record_id)
)  ENGINE=INNODB;