ALTER TABLE not_picked_products ADD INDEX (visit_datetime,date_num);
ALTER TABLE not_picked_products ADD INDEX (pos_id);
ALTER TABLE not_picked_products ADD INDEX (pro_id);
ALTER TABLE not_picked_products ADD INDEX (vvs_id);
ALTER TABLE not_picked_products ADD INDEX (rte_id);
ALTER TABLE not_picked_products ADD INDEX (batch_id);