CREATE TABLE IF NOT EXISTS Monthly_Missed_Stops
(
    month_num int NULL
  , operator_id	int NULL
  , route_id		varchar(32) NULL
  , scheduled_stops int NULL
  , missed_stops int NULL
  , out_of_schedule_stops int NULL
  , created_date timestamp not null    -- date record was created
);-- trend is comapred to the same but for the past week or month