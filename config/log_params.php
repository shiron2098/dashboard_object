<?php



define('day_45', '45');
define('day_180','180');

define ('path_folder',__DIR__  . '/../common/file_log/');
define ('log_file_stops_name_and_folder',path_folder . 'stops');
define ('log_file_stockouts_name_and_folder',path_folder . 'stockouts');
define ('log_file_items_name_and_folder',path_folder . 'items');
define ('log_file_revenue_name_and_folder',path_folder . 'revenue');
define ('log_file_distribution_name_and_folder',path_folder . 'distribution');
define ('log_file_route_name_and_folder',path_folder . 'route');
define ('log_file_group_name_and_folder',path_folder . 'group');
define ('log_file_calendar_name_and_folder',path_folder . 'calendar');


define ('log_file_authentication' ,path_folder . 'authentication');
define ('log_file_users' ,path_folder . 'users');



/** sizemax file
 * unit of measurement Bait
 */
define ('sizemax','10485760');
/**
 * max files log
 */
define('maxfiles','50');


/**
 * date format log file
 * name fil
 */
define ('dateFormat','[Y-m-d,H:i:s]');
define ('output',"%datetime% > %level_name% > %message%\n");

/**
 * Interesting events
 *
 * Examples: User logs in, SQL logs.
 */

define ('INFO','200');

/**
 * Uncommon events
 */
define('NOTICE','250');

/**
 * Exceptional occurrences that are not errors
 *
 * Examples: Use of deprecated APIs, poor use of an API,
 * undesirable things that are not necessarily wrong.
 */
define('WARNING','300');

/**
 * Runtime errors
 */
define('ERROR','400');

/**
 * Critical conditions
 *
 * Example: Application component unavailable, unexpected exception.
 */
define('CRITICAL','500');

/**
 * Action must be taken immediately
 *
 * Example: Entire website down, database unavailable, etc.
 * This should trigger the SMS alerts and wake you up.
 */
define('ALERT','550');

/**
 * Urgent alert.
 */
define('EMERGENCY','600');