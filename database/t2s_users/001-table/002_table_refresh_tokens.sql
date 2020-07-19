CREATE TABLE IF NOT EXISTS refresh_tokens
(
id INT PRIMARY KEY  AUTO_INCREMENT,
user_token_id int null ,
token_key               VARCHAR(100)     NOT NULL unique ,
create_datetime_utc    TIMESTAMP    default now()
)ENGINE=INNODB;