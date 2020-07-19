-- drop table tmp_visit_dates
-- select * from tmp_visit_dates

drop procedure if exists usp_update_missed_stops ;

delimiter $$

CREATE PROCEDURE usp_update_missed_stops  (in param_batch_id varchar(60))
BEGIN


	create temporary table tmp_visit_dates
    select    v.date_num
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
    from Monthly_Missed_Stops d
		join tmp_visit_months t
			on d.month_num = t.month_num and d.operator_id = t.operator_id;

	delete d 
    from Weekly_Missed_Stops d
		join tmp_visit_weeks t
			on d.week_num = t.week_num and d.operator_id = t.operator_id;
*/ 
	delete d 
    from Daily_Missed_Stops d
		join tmp_visit_dates t
			on d.date_num = t.date_num and d.operator_id = t.operator_id;
/* 
 	insert into Monthly_Missed_Stops 
    select 
			  t.month_num																	-- date_num int(8) NULL
            , t.operator_id  
            , v.rte_id
			, sum(case when v.scheduled = 'Yes' 
							then 1
                            else 0
						end
                     )																		-- as Scheduled   
			, sum(case when v.scheduled = 'Yes' and v.serviced = 'No'
							then 1
                            else 0
						end
                     )																		-- as Missed
			, sum(case when v.scheduled = 'No' and v.serviced = 'Yes'
							then 1
                            else 0
						end
                     )																		-- as Out_of_schedule_Stop
            , now()
		from visits v
			join tmp_visit_months t
				on v.month_num = t.month_num and v.operator_id = t.operator_id
		group by t.month_num, t.operator_id, v.rte_id;

	insert into Weekly_Missed_Stops 
    select 
			  t.week_num																	-- date_num int(8) NULL
			, t.operator_id  
            , v.rte_id
			, sum(case when v.scheduled = 'Yes' 
							then 1
                            else 0
						end
                     )																		-- as Scheduled   
			, sum(case when v.scheduled = 'Yes' and v.serviced = 'No'
							then 1
                            else 0
						end
                     )																		-- as Missed
			, sum(case when v.scheduled = 'No' and v.serviced = 'Yes'
							then 1
                            else 0
						end
                     )																		-- as Out_of_schedule_Stop
            , now()
		from visits v
		join tmp_visit_weeks t
			on v.week_num = t.week_num and v.operator_id = t.operator_id
		group by t.week_num, t.operator_id, v.rte_id;
*/    
	insert into Daily_Missed_Stops 
    select 
			  t.date_num																	-- date_num int(8) NULL
            , t.operator_id    
            , v.rte_id
			, sum(case when v.scheduled = 'Yes' 
							then 1
                            else 0
						end
                     )																		-- as Scheduled   
			, sum(case when v.scheduled = 'Yes' and v.serviced = 'No'
							then 1
                            else 0
						end
                     )																		-- as Missed
			, sum(case when v.scheduled = 'No' and v.serviced = 'Yes'
							then 1
                            else 0
						end
                     )																		-- as Out_of_schedule_Stop
            , now()
		from visits v
		join tmp_visit_dates t
			on v.date_num = t.date_num and v.operator_id = t.operator_id
		group by t.date_num, t.operator_id, v.rte_id;
  
 drop temporary table tmp_visit_dates;
 -- drop temporary table tmp_visit_weeks;
 -- drop temporary table tmp_visit_months;

END;
$$

--  select * from tmp_visit_weeks
delimiter ;
--  CALL usp_update_missed_stops(0); 