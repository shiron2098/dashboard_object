drop procedure if exists usp_update_collection_distribution;

delimiter $$

CREATE PROCEDURE usp_update_collection_distribution (in param_batch_id varchar(60))
BEGIN

 
	create temporary table tmp_visit_dates
    select    v.date_num
            , v.operator_id       
    from visits v
    where v.batch_id = param_batch_id and v.collect = 'Yes'
    group by v.date_num, v.operator_id;
 /*
	create temporary table tmp_visit_weeks
    select 
              v.week_num  -- 201926
              , v.operator_id       
    from visits v
    where v.batch_id = param_batch_id and v.collect = 'Yes'
    group by v.week_num, v.operator_id;

	create temporary table tmp_visit_months
    select 
			  v.month_num
            , v.operator_id       
    from visits v
    where v.batch_id = param_batch_id and v.collect = 'Yes'
    group by v.month_num, v.operator_id;
 
    delete d 
    from Monthly_Collection_Distribution  d
		join tmp_visit_months t
			on d.month_num = t.month_num and d.operator_id = t.operator_id;

	delete d 
    from Weekly_Collection_Distribution  d
		join tmp_visit_weeks t
			on d.week_num = t.week_num and d.operator_id = t.operator_id;
 */
	delete d 
    from Daily_Collection_Distribution  d
		join tmp_visit_dates t
			on d.date_num = t.date_num and d.operator_id = t.operator_id;
 /*
 	insert into Monthly_Collection_Distribution 
    select 
			  t.month_num																	-- date_num int(8) NULL
              , t.operator_id  
              , v.rte_id
              , sum(case when coalesce(v.actual_Sales_Bills, 0) + coalesce(v.actual_Sales_Coins, 0) <= 50
							then 1
                            else 0
						end) 																-- , less_50					int(2) NULL
              , sum(case when coalesce(v.actual_Sales_Bills, 0) + coalesce(v.actual_Sales_Coins, 0) > 50
								and coalesce(v.actual_Sales_Bills, 0) + coalesce(v.actual_Sales_Coins, 0) <= 75
							then 1
                            else 0
						end)																-- , more_50_less_75 			int(2) NULL
              , sum(case when coalesce(v.actual_Sales_Bills, 0) + coalesce(v.actual_Sales_Coins, 0) > 75
								and coalesce(v.actual_Sales_Bills, 0) + coalesce(v.actual_Sales_Coins, 0) <= 100
							then 1
                            else 0
						end)																-- , more_75_less_100 			int(2) NULL
              , sum(case when coalesce(v.actual_Sales_Bills, 0) + coalesce(v.actual_Sales_Coins, 0) > 100
								and coalesce(v.actual_Sales_Bills, 0) + coalesce(v.actual_Sales_Coins, 0) <= 150
							then 1
                            else 0
						end)																-- , more_100_less_150			int(2) NULL
				, sum(case when coalesce(v.actual_Sales_Bills, 0) + coalesce(v.actual_Sales_Coins, 0) > 150
							then 1
                            else 0
						end) 																-- , more_150		 			int(2) NULL                
-- -------------------------
              , sum(case when coalesce(v.total_units_sold, 0) <= 50
							then 1
                            else 0
						end) 																-- , less_50					int(2) NULL
              , sum(case when coalesce(v.total_units_sold, 0)  > 50
								and coalesce(v.total_units_sold, 0)  <= 75
							then 1
                            else 0
						end)																-- , more_50_less_75 			int(2) NULL
              , sum(case when coalesce(v.total_units_sold, 0)  > 75
								and coalesce(v.total_units_sold, 0)  <= 100
							then 1
                            else 0
						end)																-- , more_75_less_100 			int(2) NULL
              , sum(case when coalesce(v.total_units_sold, 0)  > 100
								and coalesce(v.total_units_sold, 0)  <= 150
							then 1
                            else 0
						end)																-- , more_100_less_150			int(2) NULL
				, sum(case when coalesce(v.total_units_sold, 0)  > 150
							then 1
                            else 0
						end) 																-- , more_150		 			int(2) NULL                

-- -------------------------                        
                , now()        
		from visits v
			join tmp_visit_months t
				on v.month_num = t.month_num and v.operator_id = t.operator_id
		where v.collect = 'Yes'
		group by t.month_num, t.operator_id, v.rte_id;

	insert into Weekly_Collection_Distribution 
    select 
			  t.week_num																	-- date_num int(8) NULL
			, t.operator_id  
            , v.rte_id
			, sum(case when coalesce(v.actual_Sales_Bills, 0) + coalesce(v.actual_Sales_Coins, 0) <= 50
							then 1
                            else 0
						end) 																-- , less_50					int(2) NULL
              , sum(case when coalesce(v.actual_Sales_Bills, 0) + coalesce(v.actual_Sales_Coins, 0) > 50
								and coalesce(v.actual_Sales_Bills, 0) + coalesce(v.actual_Sales_Coins, 0) <= 75
							then 1
                            else 0
						end)																-- , more_50_less_75 			int(2) NULL
              , sum(case when coalesce(v.actual_Sales_Bills, 0) + coalesce(v.actual_Sales_Coins, 0) > 75
								and coalesce(v.actual_Sales_Bills, 0) + coalesce(v.actual_Sales_Coins, 0) <= 100
							then 1
                            else 0
						end)																-- , more_75_less_100 			int(2) NULL
              , sum(case when coalesce(v.actual_Sales_Bills, 0) + coalesce(v.actual_Sales_Coins, 0) > 100
								and coalesce(v.actual_Sales_Bills, 0) + coalesce(v.actual_Sales_Coins, 0) <= 150
							then 1
                            else 0
						end)																-- , more_100_less_150			int(2) NULL
				, sum(case when coalesce(v.actual_Sales_Bills, 0) + coalesce(v.actual_Sales_Coins, 0) > 150
							then 1
                            else 0
						end) 																-- , more_150		 			int(2) NULL                															-- , more_150		 			int(2) NULL                
-- -------------------------
              , sum(case when coalesce(v.total_units_sold, 0) <= 50
							then 1
                            else 0
						end) 																-- , less_50					int(2) NULL
              , sum(case when coalesce(v.total_units_sold, 0)  > 50
								and coalesce(v.total_units_sold, 0)  <= 75
							then 1
                            else 0
						end)																-- , more_50_less_75 			int(2) NULL
              , sum(case when coalesce(v.total_units_sold, 0)  > 75
								and coalesce(v.total_units_sold, 0)  <= 100
							then 1
                            else 0
						end)																-- , more_75_less_100 			int(2) NULL
              , sum(case when coalesce(v.total_units_sold, 0)  > 100
								and coalesce(v.total_units_sold, 0)  <= 150
							then 1
                            else 0
						end)																-- , more_100_less_150			int(2) NULL
				, sum(case when coalesce(v.total_units_sold, 0)  > 150
							then 1
                            else 0
						end) 																-- , more_150		 			int(2) NULL                

-- -------------------------                        
			, now()				
		from visits v
		join tmp_visit_weeks t
			on v.week_num = t.week_num and v.operator_id = t.operator_id
        where v.collect = 'Yes'
		group by t.week_num, t.operator_id, v.rte_id;
*/    
	insert into Daily_Collection_Distribution 
    select 
			  t.date_num																	-- date_num int(8) NULL
            , t.operator_id    
            , v.rte_id
			, sum(case when coalesce(v.actual_Sales_Bills, 0) + coalesce(v.actual_Sales_Coins, 0) <= 50
							then 1
                            else 0
						end) 																-- , less_50					int(2) NULL
              , sum(case when coalesce(v.actual_Sales_Bills, 0) + coalesce(v.actual_Sales_Coins, 0) > 50
								and coalesce(v.actual_Sales_Bills, 0) + coalesce(v.actual_Sales_Coins, 0) <= 75
							then 1
                            else 0
						end)																-- , more_50_less_75 			int(2) NULL
              , sum(case when coalesce(v.actual_Sales_Bills, 0) + coalesce(v.actual_Sales_Coins, 0) > 75
								and coalesce(v.actual_Sales_Bills, 0) + coalesce(v.actual_Sales_Coins, 0) <= 100
							then 1
                            else 0
						end)																-- , more_75_less_100 			int(2) NULL
              , sum(case when coalesce(v.actual_Sales_Bills, 0) + coalesce(v.actual_Sales_Coins, 0) > 100
								and coalesce(v.actual_Sales_Bills, 0) + coalesce(v.actual_Sales_Coins, 0) <= 150
							then 1
                            else 0
						end)																-- , more_100_less_150			int(2) NULL
				, sum(case when coalesce(v.actual_Sales_Bills, 0) + coalesce(v.actual_Sales_Coins, 0) > 150
							then 1
                            else 0
						end) 																-- , more_150		 			int(2) NULL                															-- , more_150		 			int(2) NULL                
-- -------------------------
              , sum(case when coalesce(v.total_units_sold, 0) <= 50
							then 1
                            else 0
						end) 																-- , less_50					int(2) NULL
              , sum(case when coalesce(v.total_units_sold, 0)  > 50
								and coalesce(v.total_units_sold, 0)  <= 75
							then 1
                            else 0
						end)																-- , more_50_less_75 			int(2) NULL
              , sum(case when coalesce(v.total_units_sold, 0)  > 75
								and coalesce(v.total_units_sold, 0)  <= 100
							then 1
                            else 0
						end)																-- , more_75_less_100 			int(2) NULL
              , sum(case when coalesce(v.total_units_sold, 0)  > 100
								and coalesce(v.total_units_sold, 0)  <= 150
							then 1
                            else 0
						end)																-- , more_100_less_150			int(2) NULL
				, sum(case when coalesce(v.total_units_sold, 0)  > 150
							then 1
                            else 0
						end) 																-- , more_150		 			int(2) NULL                

-- -------------------------                        
                , now()        
		from visits v
		join tmp_visit_dates t
			on v.date_num = t.date_num and v.operator_id = t.operator_id
        where v.collect = 'Yes'
		group by t.date_num, t.operator_id, v.rte_id;

 drop temporary table tmp_visit_dates;
-- drop temporary table tmp_visit_weeks;
-- drop temporary table tmp_visit_months;


END;
$$

delimiter ;
-- CALL usp_update_collection_distribution (1)