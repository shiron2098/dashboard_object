CREATE TABLE IF NOT EXISTS Monthly_Avg_Collect
(
    month_num int NULL
  , operator_id	int NULL
  , route_id		varchar(32) NULL
  , pos_count		int NULL
  , average_collect decimal(16,2) NULL
  , min_collect decimal(16,2) NULL
  , max_collect decimal(16,2) NULL
  , average_units int NULL
  , min_units int NULL
  , max_units int NULL
  , created_date timestamp not null    -- date record was created
);-- trend is comapred to the same but for the past week or month