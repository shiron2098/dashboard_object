


drop procedure if exists usp_update_average_collections;
delimiter $$
CREATE PROCEDURE usp_update_average_collections (in param_batch_id varchar(60))
	BEGIN
		create temporary table tmp_visit_dates
				select
							 v.date_num
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
		from Monthly_Avg_Collect d
					 join tmp_visit_months t
						 on d.month_num = t.month_num and d.operator_id = t.operator_id;
		delete d
		from Weekly_Avg_Collect d
					 join tmp_visit_weeks t
						 on d.week_num = t.week_num and d.operator_id = t.operator_id;
*/
		delete d
		from Daily_Avg_Collect d
					 join tmp_visit_dates t
						 on d.date_num = t.date_num and d.operator_id = t.operator_id;
/*
		insert into Monthly_Avg_Collect
		select
					 t.month_num																	-- date_num int(8) NULL
				, t.operator_id
                                , v.rte_id
				, count(pos_id)
				, avg(coalesce(v.actual_Sales_Bills, 0) + coalesce(v.actual_Sales_Coins, 0) )		-- average_collect decimal(16,2) NULL
				, min(coalesce(v.actual_Sales_Bills, 0) + coalesce(v.actual_Sales_Coins, 0) )		-- min_collect decimal(16,2) NULL
				, max(coalesce(v.actual_Sales_Bills, 0) + coalesce(v.actual_Sales_Coins, 0) )		-- max_collect decimal(16,2) NULL
			, avg(coalesce(v.total_units_sold, 0)  )		-- average_collect decimal(16,2) NULL
			, min(coalesce(v.total_units_sold, 0)  )		-- min_units decimal(16,2) NULL
			, max(coalesce(v.total_units_sold, 0)  )		-- max_units decimal(16,2) NULL
				, now()
		from visits v
					 join tmp_visit_months t
						 on v.month_num = t.month_num and v.operator_id = t.operator_id
        where v.collect = 'Yes'        
		group by t.month_num, t.operator_id, v.rte_id;

		insert into Weekly_Avg_Collect
		select
					 t.week_num																	-- date_num int(8) NULL
				, t.operator_id
				, v.rte_id
				, count(pos_id)
				, avg(coalesce(v.actual_Sales_Bills, 0) + coalesce(v.actual_Sales_Coins, 0) )		-- average_collect decimal(16,2) NULL
				, min(coalesce(v.actual_Sales_Bills, 0) + coalesce(v.actual_Sales_Coins, 0) )		-- min_collect decimal(16,2) NULL
				, max(coalesce(v.actual_Sales_Bills, 0) + coalesce(v.actual_Sales_Coins, 0) )		-- max_collect decimal(16,2) NULL
				, avg(coalesce(v.total_units_sold, 0)  )		-- average_collect decimal(16,2) NULL
				, min(coalesce(v.total_units_sold, 0)  )		-- min_units decimal(16,2) NULL
				, max(coalesce(v.total_units_sold, 0)  )		-- max_units decimal(16,2) NULL         
				, now()
		from visits v
					 join tmp_visit_weeks t
						 on v.week_num = t.week_num and v.operator_id = t.operator_id
		where v.collect = 'Yes'
		group by t.week_num, t.operator_id, v.rte_id;
*/
		insert into Daily_Avg_Collect
		select
					 t.date_num																	-- date_num int(8) NULL
				, t.operator_id
				, v.rte_id
				, count(pos_id) 
				, avg(coalesce(v.actual_Sales_Bills, 0) + coalesce(v.actual_Sales_Coins, 0) )		-- average_collect decimal(16,2) NULL
				, min(coalesce(v.actual_Sales_Bills, 0) + coalesce(v.actual_Sales_Coins, 0) )		-- min_collect decimal(16,2) NULL
				, max(coalesce(v.actual_Sales_Bills, 0) + coalesce(v.actual_Sales_Coins, 0) )		-- max_collect decimal(16,2) NULL
				, avg(coalesce(v.total_units_sold, 0)  )		-- average_collect decimal(16,2) NULL
				, min(coalesce(v.total_units_sold, 0)  )		-- min_units decimal(16,2) NULL
				, max(coalesce(v.total_units_sold, 0)  )		-- max_units decimal(16,2) NULL            
				, now()
		from visits v
					 join tmp_visit_dates t
						 on v.date_num = t.date_num and v.operator_id = t.operator_id
		where v.collect = 'Yes'
		group by t.date_num, t.operator_id, v.rte_id;

		drop temporary table tmp_visit_dates;
--		drop temporary table tmp_visit_weeks;
--		drop temporary table tmp_visit_months;
	END;
$$
--  select * from tmp_visit_weeks
delimiter ;
--  CALL usp_update_average_collections(0); 