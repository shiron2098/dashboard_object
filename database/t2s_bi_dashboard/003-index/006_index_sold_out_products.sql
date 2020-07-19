ALTER TABLE sold_out_products ADD INDEX (visit_date,date_num);
ALTER TABLE sold_out_products ADD INDEX (pos_id);
ALTER TABLE sold_out_products ADD INDEX (pro_id);
ALTER TABLE sold_out_products ADD INDEX (vvs_id);
ALTER TABLE sold_out_products ADD INDEX (rte_id);
ALTER TABLE sold_out_products ADD INDEX (batch_id);