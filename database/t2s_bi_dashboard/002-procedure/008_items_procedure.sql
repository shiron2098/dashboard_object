------------------------------------------------------

drop procedure if exists chart_items;
delimiter $$
CREATE PROCEDURE chart_items (datetimenow varchar(32),route text)
	BEGIN
		set @sql = concat("SELECT SUM(not_picked) as not_picked,round(SUM(not_picked) / SUM(total_picked) *100,1) as total_picked FROM Daily_Stockouts_And_Not_Picked d
                 JOIN routes r on r.rte_id = d.route_id
                  WHERE d.date_num = " , datetimenow , " and d.route_id IN (" , route , ")");
		PREPARE stmt FROM @sql;
		EXECUTE stmt;
	END
$$
delimiter ;

drop procedure if exists chart_items_avg_week_and_month;
delimiter $$
CREATE PROCEDURE chart_items_avg_week_and_month (datetimeavg varchar(32),datetimenow varchar(32),route text)
	BEGIN
		set @sql = concat("select avg(not_picked)as notpicked
				 from Daily_Stockouts_And_Not_Picked d
         JOIN routes r on r.rte_id = d.route_id
				 WHERE date_num  >= " , datetimeavg , " and date_num <= " , datetimenow , " and  d.route_id IN (" , route , ")");
		PREPARE stmt FROM @sql;
		EXECUTE stmt;
	END
$$
delimiter ;

drop procedure if exists chart_items_avg_day;
delimiter $$
CREATE PROCEDURE chart_items_avg_day (datetimenow varchar(32),route text)
	BEGIN
		set @sql = concat("select avg(not_picked) as not_picked
								from Daily_Stockouts_And_Not_Picked d
								WHERE date_num = " , datetimenow , " and  d.route_id IN (" , route , ")");
		PREPARE stmt FROM @sql;
		EXECUTE stmt;
	END
$$
delimiter ;

drop procedure if exists chart_items_calendar;
delimiter $$
CREATE PROCEDURE chart_items_calendar (datetimenow varchar(32),route text)
	BEGIN
		set @sql = concat("SELECT  round(SUM(not_picked) / SUM(total_picked) *100,1) as total_picked FROM Daily_Stockouts_And_Not_Picked d
                        JOIN routes r on r.rte_id = d.route_id
                         WHERE date_num  = " , datetimenow , " and d.route_id IN (" , route , ")");
		PREPARE stmt FROM @sql;
		EXECUTE stmt;
	END
$$
delimiter ;

drop procedure if exists chart_items_per_collection;
delimiter $$
CREATE PROCEDURE chart_items_per_collection (datetimenow varchar(32),columnsorting varchar(32),sort varchar(10),offset integer,count integer,route text)
	BEGIN
		set @sql = concat("SELECT p.pro_code as productCode,p.pro_description as productDescription,p.pro_id as productGlobalKey,cast(n.not_picked  as SIGNED) as quantity,r.rte_code as codeRoute,r.rte_description as routeDescription
                               ,pos.pos_code as posCode,pos.pos_description as posDescription
                               from not_picked_products n
	                           join products p on n.pro_id = p.pro_id
                               join points_of_sale  pos on n.pos_id = pos.pos_id
		                          join routes r on r.rte_id  = n.rte_id
                                where CONVERT (n.visit_datetime,date)  = " , datetimenow , " and n.rte_id IN(" , route , ")
                              ORDER BY " , columnsorting , " " , sort , " limit " , offset , "," , count , "");
		PREPARE stmt FROM @sql;
		EXECUTE stmt;
	END
$$
delimiter ;

drop procedure if exists chart_items_per_collection_count;
delimiter $$
CREATE PROCEDURE chart_items_per_collection_count (datetimenow varchar(32),route text)
	BEGIN
		set @sql = concat("SELECT count(n.pos_id) as count from not_picked_products n
	                           left join products p
		                         on n.pro_id = p.pro_id
                             left join points_of_sale  pos
		                         on n.pos_id = pos.pos_id
		                         join routes r on r.rte_id  = n.rte_id
                             where CONVERT (n.visit_datetime,date) = " , datetimenow , " and n.rte_id IN(" , route , ")");
		PREPARE stmt FROM @sql;
		EXECUTE stmt;
	END
$$
delimiter ;
