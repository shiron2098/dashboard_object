CREATE TABLE IF NOT EXISTS users
(
    id INT PRIMARY KEY  AUTO_INCREMENT,
    userGlobalKey INT null,
    email                   VARCHAR(100)     NOT NULL unique ,
    password_hash           VARCHAR(100)     NOT NULL,
    operatorName varchar(200) NULL,
    firstName              VARCHAR(64)         NULL,
    lastName               VARCHAR(64)         NULL,
    workPhone            VARCHAR(50)         NULL ,
    operatorGlobalKey  integer  null,
    mobilePhone            VARCHAR(50)         NULL,
    enabled                 BOOLEAN NULL,
    role                    VARCHAR(10) NULL,
    requireToChangePassword  BOOLEAN,
    createDatetimeUtc     TIMESTAMP    default now(),
    updateDatetimeUtc     TIMESTAMP    default now()
)  ENGINE=INNODB;