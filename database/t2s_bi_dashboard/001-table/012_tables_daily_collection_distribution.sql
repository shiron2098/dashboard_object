CREATE TABLE IF NOT EXISTS Daily_Collection_Distribution
(
    date_num 				int NULL
  , operator_id	int NULL
  , route_id		varchar(32) NULL
  , less_50				int NULL
  , more_50_less_75 			int NULL
  , more_75_less_100 			int NULL
  , more_100_less_150			int NULL
  , more_150		 		int NULL
  , units_less_50			int NULL
  , units_more_50_less_75 		int NULL
  , units_more_75_less_100 		int NULL
  , units_more_100_less_150		int NULL
  , units_more_150		 	int NULL
  , created_date timestamp not null   -- date record was created
); -- trend is comapred to the same but for the past week or month