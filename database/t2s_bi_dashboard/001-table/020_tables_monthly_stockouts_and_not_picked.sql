CREATE TABLE IF NOT EXISTS Monthly_Stockouts_And_Not_Picked
(
    month_num int NULL
  , operator_id	int NULL
  , route_id		varchar(32) NULL
  , before_stockouts int NULL
  , after_stockouts int NULL
  , before_percentage int NULL
  , after_percentage int NULL
  , not_picked		int NULL
  , total_picked		int null
  , created_date timestamp not null    -- date record was created
);-- trend is comapred to the same but for the past week or month