drop procedure if exists chart_revenue_collection;
delimiter $$
CREATE PROCEDURE chart_revenue_collection (datetimenow varchar(60),revenue_params_select VARCHAR (32),min_params VARCHAR (32),max_params varchar(32),route text)
	BEGIN

		set @sql = concat("SELECT  round(sum(" , revenue_params_select , " * pos_count)/coalesce(sum(pos_count), 1),2) as average_collect,min(" , min_params , ") as min_collect,max(" , max_params , ") as max_collect FROM Daily_Avg_Collect dac
                                  JOIN routes r on r.rte_id = dac.route_id
                  WHERE date_num = " , datetimenow , " and dac.route_id IN (" , route , ")");

		PREPARE stmt FROM @sql;
		EXECUTE stmt;
	END
$$
delimiter ;

drop procedure if exists chart_revenue_collection_avg_week_and_month;
delimiter $$
CREATE PROCEDURE chart_revenue_collection_avg_week_and_month (datetimeavg varchar(32),datetimenow varchar(32),revenue_params_select VARCHAR (32),min_params VARCHAR (32),max_params varchar(32),route text)
	BEGIN
		set @sql = concat("select sum(" , revenue_params_select , " * pos_count) / sum(pos_count) as averagecollect, min(" , min_params , ") as mincollect, max(" , max_params , ") as maxcollect
                      from Daily_Avg_Collect dac
                     JOIN routes r on r.rte_id = dac.route_id
                    WHERE date_num  >= " , datetimeavg , " and date_num <= " , datetimenow , " and  dac.route_id IN (" , route , ")");

		PREPARE stmt FROM @sql;
		EXECUTE stmt;
	END
$$
delimiter ;

drop procedure if exists chart_revenue_collection_avg_day;
delimiter $$
CREATE PROCEDURE chart_revenue_collection_avg_day (datetimenow varchar(32),revenue_params_select VARCHAR (32),min_params VARCHAR (32),max_params varchar(32),route text)
	BEGIN
		set @sql = concat("select sum(" , revenue_params_select , " * pos_count) / sum(pos_count) as average_collect, min(" , min_params , ") as min_collect, max(" , max_params , ") as max_collect
                      from Daily_Avg_Collect dac
                    WHERE date_num = " , datetimenow , " and dac.route_id IN (" , route , ")");

		PREPARE stmt FROM @sql;
		EXECUTE stmt;
	END
$$
delimiter ;

drop procedure if exists chart_revenue_collection_calendar;
delimiter $$
CREATE PROCEDURE chart_revenue_collection_calendar (datetimenow varchar(32),revenue_params_select VARCHAR (32),route text)
	BEGIN
		set @sql = concat("SELECT round(sum(" , revenue_params_select , " * pos_count)/coalesce(sum(pos_count), 1),2) as average_collect FROM Daily_Avg_Collect dac
                     JOIN routes r on r.rte_id = dac.route_id
                WHERE date_num = " , datetimenow , " and dac.route_id IN (" , route , ")");
		PREPARE stmt FROM @sql;
		EXECUTE stmt;
	END
$$
delimiter ;

drop procedure if exists chart_revenue_collection_array;
delimiter $$
CREATE PROCEDURE chart_revenue_collection_array (datetimenow varchar(32),revenue_params_select VARCHAR (32),route text)
	BEGIN
		set @sql = concat("SELECT date_num as date,round(sum(" , revenue_params_select , " * pos_count)/coalesce(sum(pos_count), 1),2) as averageCollect,sum(pos_count) as numberOfPos
                              FROM Daily_Avg_Collect dac
                          JOIN routes r on r.rte_id = dac.route_id
                          WHERE date_num <= " , datetimenow , " and  dac.route_id IN (" , route , ")
                          GROUP BY date_num
                          ORDER BY date_num DESC limit 7");
		PREPARE stmt FROM @sql;
		EXECUTE stmt;
	END
$$
delimiter ;

drop procedure if exists chart_revenue_collection_per_collection;
delimiter $$
CREATE PROCEDURE chart_revenue_collection_per_collection (datetimenow varchar(32),columnsorting varchar(32),sort varchar(10),offset integer,count integer,selectcolumn varchar(150),route text)
	BEGIN
		set @sql = concat("select " , selectcolumn , " as collection,p.pos_code as posCode,p.pos_id as posGlobalKey
                         ,p.pos_description as posDescription,p.cus_code as customerCode,p.cus_description as customerDescription
                         ,p.address_1,p.address_2,p.city,p.state,cast(p.zip as signed) as zip,r.rte_code as codeRoute,r.rte_description as routeDescription
                              from visits v
                          left join points_of_sale  p on v.pos_id = p.pos_id
	                       join routes r on r.rte_id  = v.rte_id
                            where v.collect = 'Yes'
                    and CONVERT (v.visit_date,date) = " , datetimenow , " and v.rte_id IN(" , route , ")
                    ORDER BY " , columnsorting , " " , sort , " limit " , offset , "," , count , "");

		PREPARE stmt FROM @sql;
		EXECUTE stmt;
	END
$$
delimiter ;

drop procedure if exists chart_revenue_collection_per_collection_count;
delimiter $$
CREATE PROCEDURE chart_revenue_collection_per_collection_count (datetimenow varchar(32),route text)
	BEGIN
		set @sql = concat("select count(v.pos_id) count from visits v
                        left join points_of_sale  p on v.pos_id = p.pos_id
	                      join routes r on r.rte_id  = v.rte_id
                        where v.collect = 'Yes'
                        and CONVERT (v.visit_date,date) = " , datetimenow , " and v.rte_id IN (" , route , ")");

		PREPARE stmt FROM @sql;
		EXECUTE stmt;
	END
$$
delimiter ;

