
drop procedure if exists chart_stockouts_after;
delimiter $$
CREATE PROCEDURE chart_stockouts_after (datetimenow varchar(32),route text)
	BEGIN
		set @sql = concat("select sum(pro_empty_after) as pro_empty_after from visits v
                 left JOIN routes r on r.rte_id = v.rte_id
                  WHERE date_num = " , datetimenow , " and v.rte_id IN (" , route , ")");
		PREPARE stmt FROM @sql;
		EXECUTE stmt;
	END
$$
delimiter ;

drop procedure if exists chart_stockouts_before;
delimiter $$
CREATE PROCEDURE chart_stockouts_before (datetimenow varchar(32),route text)
	BEGIN
		set @sql = concat("select count(*) as count
                        from sold_out_products s
                        left join visits v on s.vvs_id = v.vvs_id
                        left JOIN routes r on r.rte_id = s.rte_id
                        WHERE convert(s.visit_date,date) = " , datetimenow , " and v.rte_id IN (" , route , ")");
		PREPARE stmt FROM @sql;
		EXECUTE stmt;
	END
$$
delimiter ;

drop procedure if exists chart_stockouts_avg_week_and_month;
delimiter $$
CREATE PROCEDURE chart_stockouts_avg_week_and_month (datetimeavg varchar(32),datetimenow varchar(32),route text)
	BEGIN
		set @sql = concat("select avg(before_stockouts)as beforestockouts
                      from Daily_Stockouts_And_Not_Picked d
                      JOIN routes r on r.rte_id = d.route_id
                    WHERE date_num  >= " , datetimeavg , " and date_num <= " , datetimenow , " and  d.route_id IN (" , route , ")");
		PREPARE stmt FROM @sql;
		EXECUTE stmt;
	END
$$
delimiter ;

drop procedure if exists chart_stockouts_avg_day;
delimiter $$
CREATE PROCEDURE chart_stockouts_avg_day (datetimenow varchar(32),route text)
	BEGIN
		set @sql = concat("select avg(before_stockouts) as before_stockouts
                      from Daily_Stockouts_And_Not_Picked d
                    WHERE date_num = " , datetimenow , " and  d.route_id IN (" , route , ")");
		PREPARE stmt FROM @sql;
		EXECUTE stmt;
	END
$$
delimiter ;

drop procedure if exists chart_stockouts_calendar;
delimiter $$
CREATE PROCEDURE chart_stockouts_calendar (datetimenow varchar(32),route text)
	BEGIN
		set @sql = concat("SELECT  before_percentage FROM Daily_Stockouts_And_Not_Picked dac
                      JOIN routes r on r.rte_id = dac.route_id
                      WHERE date_num  = " , datetimenow , " and dac.route_id IN (" , route , ")");
		PREPARE stmt FROM @sql;
		EXECUTE stmt;
	END
$$
delimiter ;

drop procedure if exists chart_stockouts_per_collection;
delimiter $$
CREATE PROCEDURE chart_stockouts_per_collection (datetimenow varchar(32),columnsorting varchar(32),sort varchar(10),offset integer,count integer,route text)
	BEGIN
		set @sql = concat("SELECT p.pro_code as productCode,p.pro_description as productDescription,pos.pos_code as posCode
                              ,pos.pos_description as posDescription,pos.cus_code as customerCode
                              ,pos.cus_description as customerDescription,r.rte_code as codeRoute,r.rte_description as routeDescription from sold_out_products s
	                           join products p on s.pro_id = p.pro_id
                              left join points_of_sale  pos on s.pos_id = pos.pos_id
                              join routes r on r.rte_id  = s.rte_id
                              where CONVERT (s.visit_date,date) = " , datetimenow , " and s.rte_id IN (" , route , ")
                              ORDER BY " , columnsorting , " " , sort , " limit " , offset , "," , count , "");

		PREPARE stmt FROM @sql;
		EXECUTE stmt;
	END
$$
delimiter ;

drop procedure if exists chart_stockouts_per_collection_count;
delimiter $$
CREATE PROCEDURE chart_stockouts_per_collection_count (datetimenow varchar(32),route text)
	BEGIN
		set @sql = concat("SELECT count(s.pro_id) as countPosid
                         from sold_out_products s
	                       join products p on s.pro_id = p.pro_id
                         left join points_of_sale  pos on s.pos_id = pos.pos_id
		                     join routes r on r.rte_id  = s.rte_id
                         where CONVERT (s.visit_date,date) = " , datetimenow , " and s.rte_id IN(" , route , ")");

		PREPARE stmt FROM @sql;
		EXECUTE stmt;
	END
$$
delimiter ;
