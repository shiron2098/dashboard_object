ALTER TABLE visits ADD INDEX (visit_date,date_num);
ALTER TABLE visits ADD INDEX (rte_id);
ALTER TABLE visits ADD INDEX (vvs_id);
ALTER TABLE visits ADD INDEX (collect,scheduled,serviced);
ALTER TABLE visits ADD INDEX (pos_id);
ALTER TABLE visits ADD INDEX (batch_id);