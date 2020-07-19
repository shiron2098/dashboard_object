drop procedure if exists chart_distribution_collection_avg_week_and_month;
delimiter $$
CREATE PROCEDURE chart_distribution_collection_avg_week_and_month (datetimeavg varchar(32),datetimenow varchar(60),less50 VARCHAR (32),more50_less75 VARCHAR (32),more75_less100 VARCHAR (32),more100_less150 VARCHAR(32),more150 VARCHAR(32),route text)
BEGIN

set @sql = concat("select  round(avg(" , less50 , "),4) as less50,round(avg(" , more50_less75 , "),4) as more50less75,
                      round(avg(" , more75_less100 , "),4) as more75less100,round(avg(" , more100_less150 , "),4) as more100less150,round(avg(" , more150 , "),4) as more150
                      from Daily_Collection_Distribution dcd
                      JOIN routes r on r.rte_id = dcd.route_id
                    WHERE date_num  >= " , datetimeavg , " and date_num <= " , datetimenow , " and dcd.route_id IN (" , route , ")");

PREPARE stmt FROM @sql;
EXECUTE stmt;
END
$$
delimiter ;

drop procedure if exists chart_distribution_collection_avg_day;
delimiter $$
CREATE PROCEDURE chart_distribution_collection_avg_day (datetimenow varchar(60),less50 VARCHAR (32),more50_less75 VARCHAR (32),more75_less100 VARCHAR (32),more100_less150 VARCHAR(32),more150 VARCHAR(32),route text)
  BEGIN

    set @sql = concat("select  round(avg(" , less50 , "),4) as less_50,round(avg(" , more50_less75 , "),4) as more50_less75,
                      round(avg(" , more75_less100 , "),4) as more75_less100,round(avg(" , more100_less150 , "),4) as more100_less150,round(avg(" , more150 , "),4) as more_150
                      from Daily_Collection_Distribution dcd
                      JOIN routes r on r.rte_id = dcd.route_id
                               WHERE date_num = " , datetimenow , " and  dcd.route_id IN (" , route , ")");

    PREPARE stmt FROM @sql;
    EXECUTE stmt;
  END
$$
delimiter ;

drop procedure if exists chart_distribution_collection_avg_sum;
delimiter $$
CREATE PROCEDURE chart_distribution_collection_avg_sum (datetimenow varchar(60),less50 VARCHAR (32),more50_less75 VARCHAR (32),more75_less100 VARCHAR (32),more100_less150 VARCHAR(32),more150 VARCHAR(32),route text)
  BEGIN

    set @sql = concat("SELECT  sum(" , less50 , ") as less_50,sum(" , more50_less75 , ") as more_50_less_75,
                               sum(" , more75_less100 , ") as more_75_less_100,sum(" , more100_less150 , ") as more_100_less_150,sum(" , more150 , ") as more_150
                               FROM Daily_Collection_Distribution dcd
                               JOIN routes r on r.rte_id = dcd.route_id
                               WHERE date_num = " , datetimenow , " and  dcd.route_id IN (" , route , ")");

    PREPARE stmt FROM @sql;
    EXECUTE stmt;
  END
$$
delimiter ;

drop procedure if exists chart_distribution_collection_calendar;
delimiter $$
CREATE PROCEDURE chart_distribution_collection_calendar (datetimenow varchar(60),route text)
  BEGIN

    set @sql = concat("SELECT sum(less_50) as less_50,sum(more_50_less_75) as more_50_less_75,sum(more_75_less_100) as more_75_less_100,sum(more_100_less_150) as more_100_less_150 ,sum(more_150) as more_150 FROM Daily_Collection_Distribution dcd
                         JOIN routes r on r.rte_id = dcd.route_id
                         WHERE date_num = " , datetimenow , " and dcd.route_id IN (" , route , ")");

    PREPARE stmt FROM @sql;
    EXECUTE stmt;
  END
$$
delimiter ;

drop procedure if exists chart_distribution_collection_per_collection;
delimiter $$
CREATE PROCEDURE chart_distribution_collection_per_collection(datetimenow varchar(60),$collection_value varchar(255),columnsorting varchar(32),sort varchar(10),offset integer,count integer,collection varchar(255),route text)
  BEGIN

    set @sql = concat("select " , $collection_value , ",p.pos_code as posCode,p.pos_id as posGlobalKey
                                    ,p.pos_description as posDescription,p.cus_code as customerCode,p.cus_description as customerDescription
                                    ,p.address_1,p.address_2,p.city,p.state,cast(p.zip as signed) as zip,r.rte_code as codeRoute,r.rte_description as routeDescription
                                    from visits v
                                    left join points_of_sale  p on v.pos_id = p.pos_id
		                                join routes r on r.rte_id  = v.rte_id
                                    where v.collect = 'Yes'
                                    and CONVERT (v.visit_date,date) = " , datetimenow , " and v.rte_id IN(" , route , ") " , collection , "
                                    ORDER BY " , columnsorting , " " , sort , " limit " , offset , "," , count , "");

    PREPARE stmt FROM @sql;
    EXECUTE stmt;
  END
$$
delimiter ;

drop procedure if exists chart_distribution_collection_per_collection_count;
delimiter $$
CREATE PROCEDURE chart_distribution_collection_per_collection_count(datetimenow varchar(60),collection varchar(255),route text)
  BEGIN

    set @sql = concat("select  count(v.pos_id) as count
                                    from visits v
                                    left join points_of_sale  p on v.pos_id = p.pos_id
		                            join routes r on r.rte_id  = v.rte_id
                                    where v.collect = 'Yes'
                    and CONVERT (v.visit_date,date) = " , datetimenow , " and v.rte_id IN(" , route , ") " , collection , "");

    PREPARE stmt FROM @sql;
    EXECUTE stmt;
  END
$$
delimiter ;