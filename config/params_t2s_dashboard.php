<?php

/**
 * table name
 */
define ('table_daily_revenue_avg', 'Daily_Avg_Collect');
define ('table_daily_stops','Daily_Missed_Stops');
define ('table_items_and_stockouts','Daily_Stockouts_And_Not_Picked');
define ('table_collection_distribution','Daily_Collection_Distribution');

/**
 * distribution  select column name dolars
 */
define ('currency_less_50','less_50');
define ('currency_more_50_less_75','more_50_less_75');
define ('currency_more_75_less_100','more_75_less_100');
define ('currency_more_100_less_150','more_100_less_150');
define ('currency_more_150','more_150');

/**
 * distribution select column name units
 */
define ('units_less_50','units_less_50');
define ('units_more_50_less_75','units_more_50_less_75');
define ('units_more_75_less_100','units_more_75_less_100');
define ('units_more_100_less_150','units_more_100_less_150');
define ('units_more_150','units_more_150');

/**
 * distiribution column name and where collection
 */
define ('currency_collection_value','coalesce(actual_Sales_Bills, 0.00) + coalesce(actual_Sales_Coins, 0.00) as collection');
define ('currency_per_collection_50','and coalesce(actual_Sales_Bills, 0.00) + coalesce(actual_Sales_Coins, 0.00) <=');
define ('currency_per_collection_more_150','and coalesce(actual_Sales_Bills, 0.00) + coalesce(actual_Sales_Coins, 0.00) >');
define ('units_per_collection_50','and total_units_sold <=');
define ('units_per_collection_more_150','and total_units_sold >');
define ('units_collection_value','total_units_sold as collection');

/**
 * revenue select column name dolars and units
 */
define ('currency_revenue','average_collect');
define ('currency_min_params','min_collect');
define ('currency_max_params','max_collect');
define ('units_revenue','average_units');
define ('units_min_params','min_units');
define ('units_max_params','max_units');

/**
 * revenue select column dolars and units
 */
define ('currency_revenue_per_collection_select','coalesce(actual_Sales_Bills, 0.00) + coalesce(actual_Sales_Coins, 0.00)');
define ('units_revenue_per_collection_select','total_units_sold');

define ('currency','currency');
define ('units','unit');

/**
 *  column name sotring
 */
define ('productGlobalKey','pro_id');
define ('productCode','pro_code');
define ('productDescription','pro_description');
define ('address','zip');
define ('quantity','not_picked');
define ('route_code','codeRoute');
define ('route_description','rte_description');
define ('posGlobalKey','pos_id');
define ('customerCode','customerCode');
define ('customerDescription','cus_description');
define ('poscode','posCode');
define ('posdescription','pos_description');
define ('distcollection','collection');
/**
 * sort name up and down
 */
define('ASC','ASC');
define('DESC','DESC');

/**
 * calendar value params
 */
define ('missed','2');
define ('stockout','15');
define ('avg','75');
define ('items','5');
define ('distribution','5');

define('array_data','array');
define('object','object');
