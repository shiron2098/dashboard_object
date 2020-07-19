CREATE TABLE IF NOT EXISTS Dim_Date
(
				 date_num int,    -- numeric value, in YYYYMMDD, 20080818  -- primay key.
				 date datetime ,     -- date: 2008-08-18 00:00:00
				 day_num int ,    -- numeric value, 18
				 day_of_year int, -- the day of the year
				 day_of_week int, -- the day of the week
				 day_of_week_name varchar(20), -- day of week name (Monday, Tuesday,etc)

				 week_num int , --  week of the year
				 week_begin_date datetime,  -- week begin date
				 week_end_date datetime, -- week end date

				 prev_week_num int,
				 prev_week_begin_date datetime,  -- week begin date
				 prev_week_end_date datetime, -- week end date

				 prev_2_week_num int,
				 prev_2_week_begin_date datetime,  -- week begin date
				 prev_2_week_end_date datetime, -- week end date

				 month_num int  ,  -- month in number, ie. 12
				 month_name varchar(20),  -- month in name, ie. December

				 prev_month_num int  ,  -- month in number, ie. 12
				 prev_month_name varchar(20),  -- month in name, ie. December
				 prev_month_year_num int,			-- year of the prev. months

				 quarter_num int ,  -- quarter in number, ie 4
				 year_num int , -- year in number, ie, 2012
				 created_date timestamp  default current_timestamp,  -- date record was created
				 updated_date timestamp  default current_timestamp, -- date record was updated
				 primary key (date_num)
 );
CREATE INDEX Dim_Date_index_date_num on Dim_Date(date);