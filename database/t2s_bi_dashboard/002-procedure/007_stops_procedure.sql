drop procedure if exists chart_stops;
delimiter $$
CREATE PROCEDURE chart_stops (date_number varchar(60),route text)
	BEGIN

		set @sql = concat("SELECT  sum(missed_stops) as missed_stops,sum(scheduled_stops) as scheduled_stops,sum(out_of_schedule_stops) as out_of_schedule_stops FROM Daily_Missed_Stops dms
     JOIN routes r on r.rte_id = dms.route_id
     WHERE date_num = " , date_number , " and dms.route_id in (" , route , ")");

		PREPARE stmt FROM @sql;
		EXECUTE stmt;
	END
$$
delimiter ;

drop procedure if exists chart_stops_avg_week_and_month;
delimiter $$
CREATE PROCEDURE chart_stops_avg_week_and_month (datetimeavg varchar(32),datetimenow varchar(32),route text)
	BEGIN
		set @sql = concat("select avg(missed_stops)as missedstops,avg(out_of_schedule_stops)as outstops
		from Daily_Missed_Stops dms
					 JOIN routes r on r.rte_id = dms.route_id
		WHERE date_num  >= " , datetimeavg , " and date_num <= " , datetimenow , " and  dms.route_id in (" , route , ")");

		PREPARE stmt FROM @sql;
		EXECUTE stmt;
	END
$$
delimiter ;

drop procedure if exists chart_stops_avg_day;
delimiter $$
CREATE PROCEDURE chart_stops_avg_day (datetimenow varchar(32),route text)
	BEGIN
		set @sql = concat("select avg(missed_stops)as missed_stops,avg(out_of_schedule_stops)as out_of_schedule_stops
		from Daily_Missed_Stops dms
					 JOIN routes r on r.rte_id = dms.route_id
		 WHERE date_num = " , datetimenow , " and  dms.route_id in (" , route , ")");

		PREPARE stmt FROM @sql;
		EXECUTE stmt;
	END
$$
delimiter ;

drop procedure if exists chart_stops_avg_calendar;
delimiter $$
CREATE PROCEDURE chart_stops_avg_calendar (datetimenow varchar(32),route text)
	BEGIN
		set @sql = concat("SELECT  sum(missed_stops) as missed_stops FROM Daily_Missed_Stops dms
											 JOIN routes r on r.rte_id = dms.route_id
                       WHERE date_num = " , datetimenow , " and dms.route_id
                       in (" , route , ")");

		PREPARE stmt FROM @sql;
		EXECUTE stmt;
	END
$$
delimiter ;

drop procedure if exists chart_stops_per_collection;
delimiter $$
CREATE PROCEDURE chart_stops_per_collection (datetimenow varchar(32),columnsorting varchar(32),sort varchar(10),offset integer,count integer,route text)
	BEGIN
		set @sql = concat("SELECT  pos.pos_id as posGlobalKey,pos.cus_code as customerCode,pos.cus_description as customerDescription,pos.pos_code as posCode,pos.pos_description as posDescription,
                    pos.address_1,pos.address_2,pos.city,pos.state,CAST(pos.zip AS SIGNED) as zip,r.rte_code as codeRoute,r.rte_description as routeDescription FROM visits v
                    left join points_of_sale pos on pos.pos_id = v.pos_id
                    join routes r on r.rte_id  = v.rte_id
                    where CONVERT (v.visit_date,date) = " , datetimenow , " and v.rte_id IN(" , route , ")and v.scheduled = 'Yes' and v.serviced = 'No'
                    ORDER BY " , columnsorting , " " , sort , " limit " , offset , "," , count , " ");

		PREPARE stmt FROM @sql;
		EXECUTE stmt;
	END
$$
delimiter ;

drop procedure if exists chart_stops_per_collection_count;
delimiter $$
CREATE PROCEDURE chart_stops_per_collection_count (datetimenow varchar(32),route text)
	BEGIN
		set @sql = concat("SELECT count(pos.pos_id) FROM visits v
                    left join points_of_sale pos on pos.pos_id = v.pos_id
                    left join routes r on r.rte_id  = v.rte_id
                    where CONVERT (v.visit_date,date) = " , datetimenow , " and v.rte_id IN (" , route , ") and v.scheduled = 'Yes' and v.serviced = 'No'");

		PREPARE stmt FROM @sql;
		EXECUTE stmt;
	END
$$
delimiter ;
