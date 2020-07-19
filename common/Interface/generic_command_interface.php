<?php
namespace app;


/**
 * interface command for jobs
 * Interface generic_command_interface
 * @package app
 */
interface generic_command_interface{

    public function get_products();
    public function get_customers();
    public function get_pointsofsale();
    public function get_locations();
    public function get_non_vending_equipment();
    public function get_equipment();
    public function get_items();
    public function get_t2s_exportPos();
    public function get_t2s_exportPRO();
    public function get_t2s_exportVisits();
    public function get_t2s_export_pro_wbstore();
    public function get_t2s_export_pos_wbstore();
    public function get_t2s_exportRoute();
    public function get_t2s_exportPrices();

}