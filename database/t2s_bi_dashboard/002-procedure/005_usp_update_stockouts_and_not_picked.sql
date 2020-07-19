-- drop table tmp_visit_dates
-- select * from tmp_visit_dates

drop procedure if exists usp_update_stockouts_and_not_picked ;

delimiter $$

CREATE PROCEDURE usp_update_stockouts_and_not_picked  (in param_batch_id varchar(60))
BEGIN

 
 
	create temporary table tmp_visit_dates
    select    
			  v.date_num
            , v.operator_id       
    from visits v
    where v.batch_id = param_batch_id
    group by v.date_num, v.operator_id;
 /*
	create temporary table tmp_visit_weeks
    select 
			  v.week_num  -- 201926
            , v.operator_id       
    from visits v
    where v.batch_id = param_batch_id
    group by v.week_num, v.operator_id;

	create temporary table tmp_visit_months
    select 
              v.month_num
            , v.operator_id       
    from visits v
    where v.batch_id = param_batch_id
    group by v.month_num, v.operator_id;
 
    delete d 
    from Monthly_Stockouts_And_Not_Picked d
		join tmp_visit_months t
			on d.month_num = t.month_num and d.operator_id = t.operator_id;

	delete d 
    from Weekly_Stockouts_And_Not_Picked d
		join tmp_visit_weeks t
			on d.week_num = t.week_num and d.operator_id = t.operator_id;
 
	delete d 
    from Daily_Stockouts_And_Not_Picked d
		join tmp_visit_dates t
			on d.date_num = t.date_num and d.operator_id = t.operator_id;
 
 	insert into Monthly_Stockouts_And_Not_Picked
    select 
			  t.month_num																	-- date_num int(8) NULL
            , t.operator_id  
            , v.rte_id
			, sum(coalesce(v.pro_sold_out, 0))																		-- as before_stockouts   
			, sum(coalesce(v.pro_empty_after, 0))																		-- as after_stockouts
			, sum(coalesce(v.pro_sold_out, 0))	/ coalesce((case 
																when sum(coalesce(v.number_of_columns,0)) = 0 and sum(coalesce(v.pro_sold_out, 0)) = 0
																	then 1
																when sum(coalesce(v.number_of_columns,0)) = 0 
																	then sum(coalesce(v.pro_sold_out, 0))
																else sum(coalesce(v.number_of_columns,0)) 
                                                        end), 1)											-- as before_percentage            
			, sum(coalesce(v.pro_empty_after, 0))	/ coalesce((case 
																	when sum(coalesce(v.number_of_columns,0)) = 0 and sum(coalesce(v.pro_empty_after, 0)) = 0
																		then 1
                                                                    when sum(coalesce(v.number_of_columns,0)) = 0     
																		then sum(coalesce(v.pro_empty_after, 0))
																	else sum(coalesce(v.number_of_columns,0)) 
                                                        end),1)											-- as after_percentage 
            , sum(coalesce(p.not_picked, 0))																			-- as not_picked
			, sum(coalesce(v.total_picked,0)) 																					-- total_picked		int null
            , now()
		from visits v
			join tmp_visit_months t
				on v.month_num = t.month_num and v.operator_id = t.operator_id
            left outer join not_picked_products p
				on v.vvs_id = p.vvs_id 
		group by t.month_num, t.operator_id, v.rte_id;

	insert into Weekly_Stockouts_And_Not_Picked
    select 
			  t.week_num																	-- date_num int(8) NULL
			, t.operator_id  
            , v.rte_id
			, sum(coalesce(v.pro_sold_out, 0))																		-- as before_stockouts   
			, sum(coalesce(v.pro_empty_after, 0))																		-- as after_stockouts
			, sum(coalesce(v.pro_sold_out, 0))	/ coalesce((case 
																when sum(coalesce(v.number_of_columns,0)) = 0 and sum(coalesce(v.pro_sold_out, 0)) = 0
																	then 1
																when sum(coalesce(v.number_of_columns,0)) = 0 
																	then sum(coalesce(v.pro_sold_out, 0))
																else sum(coalesce(v.number_of_columns,0)) 
                                                        end), 1)											-- as before_percentage            
			, sum(coalesce(v.pro_empty_after, 0))	/ coalesce((case 
																	when sum(coalesce(v.number_of_columns,0)) = 0 and sum(coalesce(v.pro_empty_after, 0)) = 0
																		then 1
                                                                    when sum(coalesce(v.number_of_columns,0)) = 0     
																		then sum(coalesce(v.pro_empty_after, 0))
																	else sum(coalesce(v.number_of_columns,0)) 
                                                        end),1)											-- as after_percentage 
            , sum(coalesce(p.not_picked, 0))																			-- as not_picked
            , sum(coalesce(v.total_picked,0)) 																					-- total_picked		int null
            , now()
		from visits v
			join tmp_visit_weeks t
				on v.week_num = t.week_num and v.operator_id = t.operator_id
            left outer join not_picked_products p
				on v.vvs_id = p.vvs_id
		group by t.week_num, t.operator_id, v.rte_id;
*/    
	insert into Daily_Stockouts_And_Not_Picked
    select 
			  t.date_num																	-- date_num int(8) NULL
            , t.operator_id    
            , v.rte_id
			, sum(coalesce(v.pro_sold_out, 0))																		-- as before_stockouts   
			, sum(coalesce(v.pro_empty_after, 0))																		-- as after_stockouts
			, sum(coalesce(v.pro_sold_out, 0))	/ coalesce((case 
																when sum(coalesce(v.number_of_columns,0)) = 0 and sum(coalesce(v.pro_sold_out, 0)) = 0
																	then 1
																when sum(coalesce(v.number_of_columns,0)) = 0 
																	then sum(coalesce(v.pro_sold_out, 0))
																else sum(coalesce(v.number_of_columns,0)) 
                                                        end), 1)											-- as before_percentage            
			, sum(coalesce(v.pro_empty_after, 0))	/ coalesce((case 
																	when sum(coalesce(v.number_of_columns,0)) = 0 and sum(coalesce(v.pro_empty_after, 0)) = 0
																		then 1
                                                                    when sum(coalesce(v.number_of_columns,0)) = 0     
																		then sum(coalesce(v.pro_empty_after, 0))
																	else sum(coalesce(v.number_of_columns,0)) 
                                                        end),1)											-- as after_percentage     
            , sum(coalesce(p.not_picked, 0))																			-- as not_picked
            , sum(coalesce(v.total_picked,0)) 																					-- total_picked		int null
            , now()
		from visits v
			join tmp_visit_dates t
				on v.date_num = t.date_num and v.operator_id = t.operator_id
            left outer join not_picked_products p
				on v.vvs_id = p.vvs_id
		group by t.date_num, t.operator_id, v.rte_id;
  
 drop temporary table tmp_visit_dates;
-- drop temporary table tmp_visit_weeks;
-- drop temporary table tmp_visit_months;

END;
$$

--  select * from tmp_visit_weeks
delimiter ;
  -- CALL usp_update_stockouts_and_not_picked(0); 
  
  /*
 drop temporary table tmp_visit_dates;
 drop temporary table tmp_visit_weeks;
 drop temporary table tmp_visit_months;
*/

-- select * from visits