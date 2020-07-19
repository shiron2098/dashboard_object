CREATE TABLE IF NOT EXISTS visits (     -- for this table command: get_vvs_bi   ; SQL: exec t2s_exportVisits
   vvs_record_id INT AUTO_INCREMENT
  ,operator_id       int
  ,pos_id            varchar(32) null
  ,visit_date        datetime null
  ,date_num			int				-- 20191123
  ,week_num			int				-- 201923
  ,month_num			int				-- 201910
  ,vvs_id            varchar(32) null        -- update using vvs_id
  ,rte_id			varchar(32) null
  ,sco_id            varchar(32) null
  ,scheduled         varchar(3)  null  -- yes/no
  ,serviced			varchar(3)	null	-- yes/no
  ,collect			varchar(3)	null  -- yes/no
  ,actual_sales_bills decimal(16,2)   -- total collect, NULL if service
  ,actual_sales_coins decimal(16,2)   -- total collect, NULL if service
  ,total_units_sold  int null        -- total number of units sold form the machine at collect
  ,number_of_columns int null		-- total number of columns in vending machine.
  ,col_sold_out      int null    -- number of sold out columns when driver started service
  ,pro_sold_out      int null    -- number of sold out pro when driver started service
  ,col_empty_after   int null    -- number of sold out columns when driver ended service
  ,pro_empty_after   int null    -- number of sold out pro when driver ended service
  ,not_picked        int null    -- number of items not packed (when added < pre-kit number in service order)
  ,total_picked      int null
  ,created_dt        datetime null
  ,batch_id          varchar(60) null
  ,record_timestamp	timestamp default now()
  ,PRIMARY KEY (vvs_record_id)
)  ENGINE=INNODB;