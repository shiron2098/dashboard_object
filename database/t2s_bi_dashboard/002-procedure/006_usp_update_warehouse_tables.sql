-- drop table tmp_visit_dates
-- select * from tmp_visit_dates



drop procedure if exists usp_update_warehouse_tables;

delimiter $$

CREATE PROCEDURE usp_update_warehouse_tables (in param_batch_id varchar(60))
BEGIN
	  call usp_update_average_collections(param_batch_id);
    call usp_update_collection_distribution(param_batch_id);
    call usp_update_missed_stops(param_batch_id);
    call usp_update_stockouts_and_not_picked(param_batch_id);

END;
$$

--  select * from tmp_visit_weeks
delimiter ;

-- call usp_update_warehouse_tables(1)