ALTER TABLE points_of_sale ADD INDEX (pos_id);
ALTER TABLE points_of_sale ADD INDEX (pos_code,batch_id);
ALTER TABLE points_of_sale ADD INDEX (batch_id);