drop procedure if exists product_MYSQL;
delimiter $$
CREATE PROCEDURE product_MYSQL(pro_record_id int,operator_id int,pro_code varchar(64),pro_description varchar(128),pdf_code varchar(64),pdf_description varchar(128),pro_id varchar(32),pdf_id varchar(32),created_dt datetime,batch_id varchar(60))
  BEGIN
  replace into products (pro_record_id,operator_id,pro_code,pro_description,pdf_code,pdf_description,pro_id,pdf_id,created_dt,batch_id) values (pro_record_id,operator_id,pro_code,pro_description,pdf_code,pdf_description,pro_id,pdf_id,created_dt,batch_id);
  END
 $$
delimiter ;

drop procedure if exists points_of_sale_MYSQL;
delimiter $$
CREATE PROCEDURE points_of_sale_MYSQL(pos_record_id int,operator_id int,pos_code varchar(64),pos_description varchar (128),veq_code varchar(64),veq_description varchar(128),loc_code varchar(64),loc_description varchar(128),
  cus_code varchar(64),cus_description varchar(128),address_1 varchar(128),address_2 varchar (128),city varchar(128),state varchar(128),zip varchar (128),pos_id varchar (32),veq_id varchar (32),loc_id varchar (32),cus_id varchar (32),created_dt datetime,batch_id varchar(60))
  BEGIN
    replace into points_of_sale (pos_record_id,operator_id,pos_code,pos_description,veq_code,veq_description,loc_code,loc_description,cus_code,cus_description,address_1,address_2,city,state,zip,pos_id,veq_id,loc_id,cus_id,created_dt,batch_id)
    values (pos_record_id,operator_id,pos_code,pos_description,veq_code,veq_description,loc_code,loc_description,cus_code,cus_description,address_1,address_2,city,state,zip,pos_id,veq_id,loc_id,cus_id,created_dt,batch_id);
  END
$$
delimiter ;

drop procedure if exists sold_out_product_MYSQL;
delimiter $$
CREATE PROCEDURE sold_out_product_MYSQL(sop_record_id int ,operator_id int ,visit_date datetime,date_num int,pos_id varchar(32),vvs_id varchar (32),rte_id varchar (32),pro_id varchar(32),created_dt datetime,batch_id varchar(60))
  BEGIN
    replace into sold_out_products (sop_record_id,operator_id,visit_date,date_num,pos_id,vvs_id,rte_id,pro_id,created_dt,batch_id)
    values (sop_record_id,operator_id,visit_date,date_num,pos_id,vvs_id,rte_id,pro_id,created_dt,batch_id);
  END
$$
delimiter ;

drop procedure if exists not_picked_product_MYSQL;
delimiter $$
CREATE PROCEDURE not_picked_product_MYSQL(npp_record_id int ,operator_id int ,visit_datetime datetime,date_num int,pos_id varchar(32),vvs_id varchar (32),rte_id varchar (32),pro_id varchar(32),not_picked int,total_picked int,created_dt datetime,batch_id varchar(60))
  BEGIN
    replace into not_picked_products (npp_record_id,operator_id,visit_datetime,date_num,pos_id,vvs_id,rte_id,pro_id,not_picked,total_picked,created_dt,batch_id)
    values (npp_record_id,operator_id,visit_datetime,date_num,pos_id,vvs_id,rte_id,pro_id,not_picked,total_picked,created_dt,batch_id);
  END
$$
delimiter ;

drop procedure if exists visits_MYSQL;
delimiter $$
CREATE PROCEDURE visits_MYSQL(vvs_record_id int,operator_id int,pos_id varchar(32),visit_date datetime,date_num integer,week_num integer,month_num int,vvs_id varchar(32),rte_id varchar(32),scheduled varchar(32),serviced varchar(32),collect varchar(32),
                           actual_Sales_Bills decimal(16,2)	,actual_Sales_Coins decimal(16,2)		,total_units_sold int,number_of_columns int,col_sold_out int,pro_sold_out int,col_empty_after int,pro_empty_after int,total_picked int,not_picked int,
                           created_dt datetime,batch_id varchar(60))
  BEGIN
    replace into visits (vvs_record_id,operator_id,pos_id,visit_date,date_num,week_num,month_num,vvs_id,rte_id,scheduled,serviced,collect,actual_Sales_Bills,actual_Sales_Coins,
                         total_units_sold,number_of_columns,col_sold_out,pro_sold_out,col_empty_after,pro_empty_after,total_picked,not_picked,created_dt,batch_id)
    values (vvs_record_id,operator_id,pos_id,visit_date,date_num,week_num,month_num,vvs_id,rte_id,scheduled,serviced,collect,
            actual_Sales_Bills,actual_Sales_Coins,total_units_sold,number_of_columns,col_sold_out,pro_sold_out,col_empty_after,pro_empty_after,total_picked,not_picked,created_dt,batch_id);
  END
$$
delimiter ;

drop procedure if exists route_MYSQL;
delimiter $$
CREATE PROCEDURE route_MYSQL(id_string_route int,operator_id int,rte_code varchar(64),rte_description varchar(128),rte_id varchar(20),changed_or_new varchar(60),
                              created_dt datetime,batch_id varchar(60),record_id int)
  BEGIN
    replace into routes (id_string_route,operator_id,rte_code,rte_description,rte_id,changed_or_new,created_dt,batch_id,record_id)
    values (id_string_route,operator_id,rte_code,rte_description,rte_id,changed_or_new,created_dt,batch_id,record_id);
  END
$$
delimiter ;

drop procedure if exists group_MYSQL;
delimiter $$
CREATE PROCEDURE group_MYSQL(group_string_id int,operator_id int,rte_grp_description varchar(64),rte_grp_id varchar(128),rte_list varchar(20),changed_or_new varchar(60),
                             created_dt datetime,batch_id varchar(60),record_id int)
  BEGIN
    replace into groups (id_string_group,operator_id,rte_grp_description,rte_grp_id,rte_list,changed_or_new,created_dt,batch_id,record_id)
    values (group_string_id,operator_id,rte_grp_description,rte_grp_id,rte_list,changed_or_new,created_dt,batch_id,record_id);
  END
$$
delimiter ;

drop procedure if exists check_vvs;
delimiter $$
CREATE PROCEDURE check_vvs(vvs_id_xml varchar (100),pos_id_xml varchar(100),visit_date_xml varchar(100),rte_id_xml varchar(100))
  BEGIN
    select vvs_record_id from visits where vvs_id = vvs_id_xml and pos_id =  pos_id_xml and visit_date = visit_date_xml and rte_id = rte_id_xml;
  END
$$
delimiter ;

drop procedure if exists check_sold_out_product;
delimiter $$
CREATE PROCEDURE check_sold_out_product(vvs_id_xml varchar (100),pos_id_xml varchar(100),pro_id_xml varchar(100),rte_id_xml varchar(100))
  BEGIN
    select sop_record_id from sold_out_products where vvs_id = vvs_id_xml and pos_id = pos_id_xml and pro_id = pro_id_xml and rte_id = rte_id_xml;
  END
$$
delimiter ;

drop procedure if exists check_not_picked_product;
delimiter $$
CREATE PROCEDURE check_not_picked_product(vvs_id_xml varchar (100),pos_id_xml varchar(100),pro_id_xml varchar(100),rte_id_xml varchar(100))
  BEGIN
    select npp_record_id from not_picked_products where vvs_id = vvs_id_xml and pos_id = pos_id_xml and pro_id = pro_id_xml and rte_id = rte_id_xml;
  END
$$
delimiter ;

drop procedure if exists check_product;
delimiter $$
CREATE PROCEDURE check_product(pro_id_xml varchar(100))
  BEGIN
    select pro_record_id from products where pro_id = pro_id_xml;
  END
$$
delimiter ;

drop procedure if exists check_points_of_sale;
delimiter $$
CREATE PROCEDURE check_points_of_sale(pos_id_xml varchar(100))
  BEGIN
    select pos_record_id from points_of_sale where pos_id = pos_id_xml;
  END
$$
delimiter ;

drop procedure if exists check_visits_time;
delimiter $$
CREATE PROCEDURE check_visits_time(visits_start_xml varchar(50),visits_finish varchar(60))
  BEGIN
    select max(date_num) as max_date_num,min(date_num) as min_date_num from visits where date_num >= visits_start_xml and date_num <= visits_finish order by date_num DESC limit 1;
  END
$$
delimiter ;

drop procedure if exists check_group;
delimiter $$
CREATE PROCEDURE check_group(rte_grp_description_xml varchar(100),rte_list_xml varchar(70),rte_grp_id_xml varchar(70))
  BEGIN
    select id_string_group from groups where rte_grp_description = rte_grp_description_xml and  rte_list = rte_list_xml and rte_grp_id = rte_grp_id_xml
                 group by rte_grp_id;
  END
$$
delimiter ;

drop procedure if exists check_route;
delimiter $$
CREATE PROCEDURE check_route(rte_id_xml varchar(100))
  BEGIN
    select id_string_route from routes where rte_id = rte_id_xml;
  END
$$
delimiter ;

drop procedure if exists t2s_operators_insert;
delimiter $$
CREATE PROCEDURE t2s_operators_insert($operators_name varchar(64),operator_id int,operator_software varchar(64))
  BEGIN
    REPLACE into operators (operator_name,operator_id,operator_software)
                       values ($operators_name,operator_id,operator_software);
  END
$$
delimiter ;

drop procedure if exists xml_insert_string_mysql;
delimiter $$
CREATE PROCEDURE xml_insert_string_mysql(operator_id int,command_type varchar(32),xml_value longtext,id_batch varchar (60))
  BEGIN
    insert into xml_log (operator_id,command_type,xml_value,batch_id)
                       values (operator_id,command_type,xml_value,id_batch);
  END
$$
delimiter ;