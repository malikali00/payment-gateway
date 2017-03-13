
--
-- Database: `spg`
--

-- --------------------------------------------------------

--
-- Table structure for table `authority`
--

CREATE TABLE `authority` (
  `id` bigint(20) NOT NULL,
  `uid` varchar(255) DEFAULT NULL,
  `version` int(11) DEFAULT NULL,
  `authority` varchar(64) DEFAULT NULL,
  `authority_name` varchar(64) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `authority`
--

INSERT INTO `authority` (`id`, `uid`, `version`, `authority`, `authority_name`) VALUES
  (1, 'role_01', 2, 'ROLE_ADMIN', 'Super Admin'),
  (2, 'role_02', 11, 'ROLE_POST_CHARGE', 'Post Charge'),
  (3, 'role_03', 9, 'ROLE_VOID_CHARGE', 'Void Charge'),
  (4, 'role_04', 10, 'ROLE_RUN_REPORTS', 'Run Reports'),
  (5, 'role_05', 1, 'ROLE_RETURN_CHARGE', 'Return Charge'),
  (6, 'role_06', 12, 'ROLE_SUB_ADMIN', 'Admin'),
  (7, 'role_07', 13, 'ROLE_DEBUG', 'Debugger');

-- --------------------------------------------------------

--
-- Table structure for table `email_template`
--

CREATE TABLE `email_template` (
  `id` int(11) NOT NULL,
  `class` varchar(128) NOT NULL,
  `subject` varchar(256) NOT NULL,
  `body` text NOT NULL,
  `updated` datetime NOT NULL,
  `merchant_id` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `fee`
--

CREATE TABLE `fee` (
  `amount` decimal(9,2) NOT NULL,
  `type` varchar(32) NOT NULL,
  `date` datetime NOT NULL,
  `order_item_id` bigint(20) NOT NULL,
  `merchant_id` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Merchant Fees';

-- --------------------------------------------------------

--
-- Table structure for table `integration`
--

CREATE TABLE `integration` (
  `id` int(11) NOT NULL,
  `uid` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `class_path` varchar(255) NOT NULL,
  `api_url_base` varchar(255) NOT NULL,
  `api_username` varchar(255) DEFAULT NULL,
  `api_password` varchar(255) DEFAULT NULL,
  `api_app_id` varchar(255) DEFAULT NULL,
  `api_type` enum('testing','production','disabled') NOT NULL DEFAULT 'testing',
  `api_credentials` text,
  `notes` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `integration`
--

INSERT INTO `integration` (`id`, `uid`, `name`, `class_path`, `api_url_base`, `api_username`, `api_password`, `api_app_id`, `api_type`, `api_credentials`, `notes`) VALUES
  (5, 'MOCK_INTEGRATION', 'Mock Only', 'Integration\\Mock\\MockIntegration', 'http://localhost', NULL, NULL, NULL, 'disabled', NULL, 'Mock-Only (No integration)');

-- --------------------------------------------------------

--
-- Table structure for table `integration_request`
--

CREATE TABLE `integration_request` (
  `id` bigint(20) NOT NULL,
  `integration_id` int(11) NOT NULL,
  `type` enum('transaction','transaction-reversal','transaction-void','transaction-return','transaction-search','merchant','merchant-identity','merchant-provision','merchant-payment','health-check','other') NOT NULL,
  `type_id` bigint(20) NOT NULL,
  `url` varchar(145) DEFAULT NULL,
  `request` text NOT NULL,
  `response` text NOT NULL,
  `response_code` int(11) DEFAULT NULL,
  `response_message` varchar(255) DEFAULT NULL,
  `result` enum('success','fail','error') NOT NULL,
  `date` datetime NOT NULL,
  `duration` double(13,8) DEFAULT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `merchant_id` bigint(20) DEFAULT NULL,
  `order_item_id` bigint(20) DEFAULT NULL,
  `transaction_id` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `merchant`
--

CREATE TABLE `merchant` (
  `id` bigint(20) NOT NULL,
  `uid` varchar(255) DEFAULT NULL,
  `branch` varchar(64) NOT NULL,
  `description` varchar(128) NOT NULL,
  `address1` varchar(23) NOT NULL,
  `address2` varchar(23) DEFAULT NULL,
  `agent_chain` varchar(6) DEFAULT NULL,
  `amex_external` varchar(30) DEFAULT NULL,
  `city` varchar(64) NOT NULL,
  `convenience_fee_flat` decimal(19,2) DEFAULT NULL,
  `convenience_fee_limit` decimal(19,2) DEFAULT NULL,
  `convenience_fee_variable_rate` decimal(19,2) DEFAULT NULL,
  `convenience_fee_merchant_id` bigint(20) NOT NULL,
  `discover_external` varchar(30) DEFAULT NULL,
  `main_contact` varchar(100) NOT NULL,
  `main_email_id` varchar(64) NOT NULL,
  `name` varchar(100) NOT NULL,
  `title` varchar(45) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `notes` longtext,
  `open_date` datetime DEFAULT NULL,
  `sale_rep` varchar(64) DEFAULT NULL,
  `short_name` varchar(15) DEFAULT NULL,
  `sic` varchar(4) DEFAULT NULL,
  `mcc` varchar(4) DEFAULT NULL,
  `store_id` varchar(4) DEFAULT NULL,
  `telephone` varchar(25) DEFAULT NULL,
  `zipcode` varchar(255) DEFAULT NULL,
  `country` varchar(3) DEFAULT 'USA',
  `state_id` bigint(20) DEFAULT NULL,
  `status_id` bigint(20) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `logo_path` varchar(256) DEFAULT NULL,
  `tax_id` varchar(45) DEFAULT NULL,
  `business_tax_id` varchar(45) DEFAULT NULL,
  `business_type` enum('INDIVIDUAL_SOLE_PROPRIETORSHIP','CORPORATION','LIMITED_LIABILITY_COMPANY','PARTNERSHIP','ASSOCIATION_ESTATE_TRUST','TAX_EXEMPT_ORGANIZATION','INTERNATIONAL_ORGANIZATION','GOVERNMENT_AGENCY') NOT NULL DEFAULT 'INDIVIDUAL_SOLE_PROPRIETORSHIP',
  `payout_type` enum('BANK_ACCOUNT') DEFAULT 'BANK_ACCOUNT',
  `payout_account_name` varchar(45) DEFAULT NULL,
  `payout_account_type` enum('CHECKING','SAVINGS') DEFAULT NULL,
  `payout_account_number` varchar(45) DEFAULT NULL,
  `payout_bank_code` varchar(45) DEFAULT NULL,
  `fraud_high_limit` int(11) NOT NULL DEFAULT '9999',
  `fraud_low_limit` int(11) NOT NULL DEFAULT '3',
  `fraud_high_monthly_limit` int(11) DEFAULT NULL,
  `fraud_flags` int(11) NOT NULL DEFAULT '0',
  `label_item` varchar(64) NOT NULL,
  `label_contact` varchar(64) NOT NULL,
  `integration_default_id` int(11) DEFAULT '3'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `merchant`
--

INSERT INTO `merchant` (`id`, `uid`, `branch`, `description`, `address1`, `address2`, `agent_chain`, `amex_external`, `city`, `convenience_fee_flat`, `convenience_fee_limit`, `convenience_fee_variable_rate`, `convenience_fee_merchant_id`, `discover_external`, `main_contact`, `main_email_id`, `name`, `title`, `dob`, `notes`, `open_date`, `sale_rep`, `short_name`, `sic`, `mcc`, `store_id`, `telephone`, `zipcode`, `country`, `state_id`, `status_id`, `url`, `logo_path`, `tax_id`, `business_tax_id`, `business_type`, `payout_type`, `payout_account_name`, `payout_account_type`, `payout_account_number`, `payout_bank_code`, `fraud_high_limit`, `fraud_low_limit`, `fraud_high_monthly_limit`, `fraud_flags`, `label_item`, `label_contact`, `integration_default_id`) VALUES
  (1, 'TEST_MERCHANT', '', '', '1234 Test St.', NULL, '654321', '654321', 'Testerton', '0.00', '0.00', '4.00', 0, NULL, 'Tony G', 'test@sample.com', 'Dr. Who', NULL, NULL, NULL, '2013-05-03 00:00:00', 'Tony G', 'Dr. Who', '4900', '4900', '4', '666 5554444', '66554', 'USA', 1, 4, 'http://PAYLOGICNETWORK.COM', NULL, NULL, NULL, 'INDIVIDUAL_SOLE_PROPRIETORSHIP', 'BANK_ACCOUNT', NULL, NULL, NULL, NULL, 9999, 1, NULL, 10, '', '', 3);

-- --------------------------------------------------------

--
-- Table structure for table `merchant_fee`
--

CREATE TABLE `merchant_fee` (
  `id` bigint(20) NOT NULL,
  `type` varchar(32) NOT NULL,
  `amount_flat` decimal(9,2) DEFAULT NULL,
  `amount_variable` decimal(9,2) DEFAULT NULL,
  `amount_limit` decimal(9,2) DEFAULT NULL,
  `entry_mode` enum('Keyed','Swipe','Check') DEFAULT NULL,
  `comment` varchar(128) DEFAULT NULL,
  `merchant_id` bigint(20) DEFAULT NULL,
  `merchant_fee_account_id` bigint(20) DEFAULT NULL,
  `integration_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Merchant Fee Schedule';

-- --------------------------------------------------------

--
-- Table structure for table `merchant_form`
--

CREATE TABLE `merchant_form` (
  `id` int(11) NOT NULL,
  `uid` varchar(255) DEFAULT NULL,
  `merchant_id` bigint(20) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `template` varchar(255) DEFAULT NULL,
  `classes` varchar(255) DEFAULT NULL,
  `fields` text,
  `flags` text,
  `created` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='Customized Merchant Form';

--
-- Dumping data for table `merchant_form`
--

INSERT INTO `merchant_form` (`id`, `uid`, `merchant_id`, `title`, `template`, `classes`, `fields`, `flags`, `created`) VALUES
  (101, 'DEFAULT', NULL, 'Default Charge Form', 'Order\\Forms\\DefaultOrderForm', '', '{\n    "customer_id": [],\n    "invoice_number": [],\n    "payee_receipt_email": [],\n    "payee_phone_number": [],\n    "notes_text": []\n}', '', NULL),
  (99, 'SIMPLE', NULL, 'Simple Form', 'Order\\Forms\\SimpleOrderForm', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `merchant_integration`
--

CREATE TABLE `merchant_integration` (
  `merchant_id` bigint(20) NOT NULL,
  `integration_id` int(11) NOT NULL,
  `created` datetime DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `credentials` text
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `merchant_integration`
--

INSERT INTO `merchant_integration` (`merchant_id`, `integration_id`, `created`, `updated`, `credentials`) VALUES
  (1, 4, '2017-02-11 22:59:24', '2017-02-11 22:59:24', '{"AccountID": "1021216","AccountToken": "1EE26842EF89991F28394739F68E808196676F497FD9CF275D9235A9CF18C9F768D48B01" ,"ApplicationID": "7731","AcceptorID": "3928907"}'),
  (1, 3, '2017-02-11 22:59:24', '2017-02-11 22:59:24', '{"AccountID": "1037664","AccountToken": "D82F25FEA4656D6C37F618CE003E52D12A6FA4D290EC45E69C2F8BC1FE420DE094505801" ,"ApplicationID": "7731","AcceptorID": "024068924", "DefaultTerminalID": "S5174000101"}'),
  (1, 6, '2017-02-11 22:59:24', '2017-02-11 22:59:24', '{\n    "MerchantProfileId": 2095677,\n    "propayAccountNum": "123456",\n    "propayPassword": null\n}');

-- --------------------------------------------------------

--
-- Table structure for table `merchant_status`
--

CREATE TABLE `merchant_status` (
  `id` bigint(20) NOT NULL,
  `uid` varchar(255) DEFAULT NULL,
  `version` int(11) DEFAULT NULL,
  `name` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `merchant_status`
--

INSERT INTO `merchant_status` (`id`, `uid`, `version`, `name`) VALUES
  (1, 'MERCH_STATUS_1', 1, 'Live'),
  (2, 'MERCH_STATUS_2', 1, 'In Progress'),
  (3, 'MERCH_STATUS_3', 1, 'Cancelled'),
  (4, 'MERCH_STATUS_4', 1, 'Deleted');

-- --------------------------------------------------------

--
-- Table structure for table `order_field`
--

CREATE TABLE `order_field` (
  `order_id` bigint(20) NOT NULL,
  `field_name` varchar(45) NOT NULL,
  `field_value` varchar(1024) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='custom field values for an order';

--
-- Dumping data for table `order_field`
--

INSERT INTO `order_field` (`order_id`, `field_name`, `field_value`) VALUES
  (9092, 'notes_text', 'custom notes'),
  (9493, 'notes_text', '67794');

-- --------------------------------------------------------

--
-- Table structure for table `order_item`
--

CREATE TABLE `order_item` (
  `id` bigint(20) NOT NULL,
  `uid` varchar(255) DEFAULT NULL,
  `version` int(11) DEFAULT NULL,
  `amount` decimal(9,2) NOT NULL,
  `card_exp_month` varchar(255) DEFAULT NULL,
  `card_exp_year` varchar(255) DEFAULT NULL,
  `card_number` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `date` datetime NOT NULL,
  `invoice_number` varchar(255) DEFAULT NULL,
  `entry_mode` varchar(255) NOT NULL,
  `card_type` varchar(255) DEFAULT NULL,
  `check_account_name` varchar(45) DEFAULT NULL,
  `check_account_bank_name` varchar(45) DEFAULT NULL,
  `check_account_type` enum('Checking','Savings') DEFAULT NULL,
  `check_account_number` varchar(45) DEFAULT NULL,
  `check_routing_number` varchar(45) DEFAULT NULL,
  `check_type` enum('Personal','Business') DEFAULT NULL,
  `check_number` int(11) DEFAULT NULL,
  `customer_first_name` varchar(255) DEFAULT NULL,
  `customer_id` varchar(255) DEFAULT NULL,
  `customer_last_name` varchar(255) DEFAULT NULL,
  `customermi` varchar(255) DEFAULT NULL,
  `order_item_id` varchar(255) DEFAULT NULL,
  `payee_first_name` varchar(255) DEFAULT NULL,
  `payee_last_name` varchar(255) DEFAULT NULL,
  `payee_phone_number` varchar(255) DEFAULT NULL,
  `payee_reciept_email` varchar(255) DEFAULT NULL,
  `payee_address` varchar(45) DEFAULT NULL,
  `payee_address2` varchar(45) DEFAULT NULL,
  `payee_zipcode` varchar(255) DEFAULT NULL,
  `payee_city` varchar(45) DEFAULT NULL,
  `payee_state` varchar(3) DEFAULT NULL,
  `total_returned_amount` decimal(9,2) NOT NULL DEFAULT '0.00',
  `total_returned_service_fee` decimal(9,2) DEFAULT NULL,
  `convenience_fee` decimal(5,2) DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  `merchant_id` bigint(20) DEFAULT NULL,
  `integration_id` int(11) DEFAULT NULL,
  `subscription_id` bigint(20) DEFAULT NULL,
  `form_id` int(11) DEFAULT NULL,
  `integration_remote_id` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='										';

-- --------------------------------------------------------

--
-- Table structure for table `payee`
--

CREATE TABLE `payee` (
  `id` bigint(20) NOT NULL,
  `uid` varchar(255) DEFAULT NULL,
  `created` datetime NOT NULL,
  `status` enum('Enabled','Disabled') DEFAULT 'Enabled',
  `payee_first_name` varchar(255) DEFAULT NULL,
  `payee_last_name` varchar(255) DEFAULT NULL,
  `payee_phone_number` varchar(255) DEFAULT NULL,
  `payee_reciept_email` varchar(255) DEFAULT NULL,
  `payee_address` varchar(45) DEFAULT NULL,
  `payee_address2` varchar(45) DEFAULT NULL,
  `payee_zipcode` varchar(255) DEFAULT NULL,
  `payee_city` varchar(45) DEFAULT NULL,
  `payee_state` varchar(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `id` bigint(20) NOT NULL,
  `uid` varchar(255) DEFAULT NULL,
  `created` datetime NOT NULL,
  `status` enum('Enabled','Disabled') DEFAULT 'Enabled',
  `card_number` varchar(255) DEFAULT NULL,
  `card_type` varchar(255) DEFAULT NULL,
  `card_exp_month` varchar(255) DEFAULT NULL,
  `card_exp_year` varchar(255) DEFAULT NULL,
  `check_account_number` varchar(45) DEFAULT NULL,
  `check_routing_number` varchar(45) DEFAULT NULL,
  `check_account_name` varchar(45) DEFAULT NULL,
  `check_account_bank_name` varchar(45) DEFAULT NULL,
  `check_account_type` enum('Checking','Savings') DEFAULT NULL,
  `check_type` enum('Personal','Business') DEFAULT NULL,
  `check_number` int(11) DEFAULT NULL,
  `payee_id` bigint(20) DEFAULT NULL,
  `integration_id` int(11) DEFAULT NULL,
  `integration_remote_id` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='										';

-- --------------------------------------------------------

--
-- Table structure for table `state`
--

CREATE TABLE `state` (
  `id` bigint(20) NOT NULL,
  `uid` varchar(255) DEFAULT NULL,
  `version` int(11) DEFAULT NULL,
  `name` varchar(50) NOT NULL,
  `short_code` varchar(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `state`
--

INSERT INTO `state` (`id`, `uid`, `version`, `name`, `short_code`) VALUES
  (1, 'STATE_01', 0, 'Alaska', 'AK'),
  (2, 'STATE_02', 0, 'Alabama', 'AL'),
  (3, 'STATE_03', 0, 'Arkansas', 'AR'),
  (4, 'STATE_04', 0, 'Arizona', 'AZ'),
  (5, 'STATE_05', 0, 'California', 'CA'),
  (6, 'STATE_06', 0, 'Colorado', 'CO'),
  (7, 'STATE_07', 0, 'Connecticut', 'CT'),
  (8, 'STATE_08', 0, 'District of Columbia', 'DC'),
  (9, 'STATE_09', 0, 'Delaware', 'DE'),
  (10, 'STATE_10', 0, 'Florida', 'FL'),
  (11, 'STATE_11', 0, 'Georgia', 'GA'),
  (12, 'STATE_12', 0, 'Hawaii', 'HI'),
  (13, 'STATE_13', 0, 'Iowa', 'IA'),
  (14, 'STATE_14', 0, 'Idaho', 'ID'),
  (15, 'STATE_15', 0, 'Illinois', 'IL'),
  (16, 'STATE_16', 0, 'Indiana', 'IN'),
  (17, 'STATE_17', 0, 'Kansas', 'KS'),
  (18, 'STATE_18', 0, 'Kentucky', 'KY'),
  (19, 'STATE_19', 0, 'Louisiana', 'LA'),
  (20, 'STATE_20', 0, 'Massachusetts', 'MA'),
  (21, 'STATE_21', 0, 'Maryland', 'MD'),
  (22, 'STATE_22', 0, 'Maine', 'ME'),
  (23, 'STATE_23', 0, 'Michigan', 'MI'),
  (24, 'STATE_24', 0, 'Minnesota', 'MN'),
  (25, 'STATE_25', 0, 'Missouri', 'MO'),
  (26, 'STATE_26', 0, 'Mississippi', 'MS'),
  (27, 'STATE_27', 0, 'Montana', 'MT'),
  (28, 'STATE_28', 0, 'North Carolina', 'NC'),
  (29, 'STATE_29', 0, 'North Dakota', 'ND'),
  (30, 'STATE_30', 0, 'Nebraska', 'NE'),
  (31, 'STATE_31', 0, 'New Hampshire', 'NH'),
  (32, 'STATE_32', 0, 'New Jersey', 'NJ'),
  (33, 'STATE_33', 0, 'New Mexico', 'NM'),
  (34, 'STATE_34', 0, 'Nevada', 'NV'),
  (35, 'STATE_35', 0, 'New York', 'NY'),
  (36, 'STATE_36', 0, 'Ohio', 'OH'),
  (37, 'STATE_37', 0, 'Oklahoma', 'OK'),
  (38, 'STATE_38', 0, 'Oregon', 'OR'),
  (39, 'STATE_39', 0, 'Pennsylvania', 'PA'),
  (40, 'STATE_40', 0, 'Rhode Island', 'RI'),
  (41, 'STATE_41', 0, 'South Carolina', 'SC'),
  (42, 'STATE_42', 0, 'South Dakota', 'SD'),
  (43, 'STATE_43', 0, 'Tennessee', 'TN'),
  (44, 'STATE_44', 0, 'Texas', 'TX'),
  (45, 'STATE_45', 0, 'Utah', 'UT'),
  (46, 'STATE_46', 0, 'Virginia', 'VA'),
  (47, 'STATE_47', 0, 'Vermont', 'VT'),
  (48, 'STATE_48', 0, 'Washington', 'WA'),
  (49, 'STATE_49', 0, 'Wisconsin', 'WI'),
  (50, 'STATE_50', 0, 'West Virginia', 'WV'),
  (51, 'STATE_51', 0, 'Wyoming', 'WY');

-- --------------------------------------------------------

--
-- Table structure for table `subscription`
--

CREATE TABLE `subscription` (
  `id` bigint(20) NOT NULL,
  `order_item_id` bigint(20) NOT NULL,
  `uid` varchar(255) NOT NULL,
  `status` enum('Active','Inactive') NOT NULL DEFAULT 'Inactive',
  `status_message` varchar(255) DEFAULT NULL,
  `recur_amount` decimal(9,2) NOT NULL,
  `recur_count` int(11) NOT NULL,
  `recur_next_date` datetime NOT NULL,
  `recur_frequency` enum('OneTimeFuture','Daily','Weekly','BiWeekly','Monthly','BiMonthly','Quarterly','SemiAnnually','Yearly') NOT NULL,
  `recur_cancel_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `transaction`
--

CREATE TABLE `transaction` (
  `id` bigint(20) NOT NULL,
  `uid` varchar(255) DEFAULT NULL,
  `version` int(11) DEFAULT NULL,
  `action` varchar(255) NOT NULL,
  `amount` decimal(9,2) NOT NULL,
  `capture_to` tinyint(1) NOT NULL,
  `date` datetime DEFAULT NULL,
  `entry_method` varchar(255) DEFAULT NULL,
  `is_reviewed` tinyint(1) NOT NULL,
  `return_type` varchar(255) NOT NULL,
  `returned_amount` decimal(9,2) DEFAULT NULL,
  `reviewed_by` varchar(255) DEFAULT NULL,
  `reviewed_date_time` datetime DEFAULT NULL,
  `service_fee` decimal(9,2) NOT NULL,
  `status_code` varchar(255) NOT NULL,
  `status_message` varchar(255) NOT NULL,
  `transaction_id` varchar(255) NOT NULL,
  `order_item_id` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` bigint(20) NOT NULL,
  `uid` varchar(255) DEFAULT NULL,
  `email` varchar(50) NOT NULL,
  `fname` varchar(50) NOT NULL,
  `lname` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `date` datetime DEFAULT NULL,
  `timezone` varchar(95) DEFAULT NULL,
  `authority` set('admin','sub_admin','debug','post_charge','void_charge','return_charge','run_reports') DEFAULT NULL,
  `admin_id` bigint(20) DEFAULT NULL,
  `merchant_id` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='		';

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `uid`, `email`, `fname`, `lname`, `password`, `username`, `date`, `timezone`, `authority`, `admin_id`, `merchant_id`) VALUES
  (1, 'TEST_ADMIN', 'support@simonpayments.com', 'Test', 'Admin', 'TestAdmin', 'TestAdmin', '2016-10-18 12:51:33', 'America/New_York', 'admin,debug', NULL, 1),
  (2, 'TEST_MERCHANT', 'TestMerchant@simonpayments.com', 'Test', 'Merchant', 'TestMerchant', 'TestMerchant', '2016-10-18 12:51:33', 'America/New_York', 'debug,post_charge,void_charge,return_charge,run_reports', 1, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `authority`
--
ALTER TABLE `authority`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `email_template`
--
ALTER TABLE `email_template`
  ADD PRIMARY KEY (`id`),
  ADD KEY `merchant_id` (`merchant_id`);

--
-- Indexes for table `fee`
--
ALTER TABLE `fee`
  ADD KEY `idx_fee_merchant_id` (`merchant_id`),
  ADD KEY `idx_fee_order_item_id` (`order_item_id`),
  ADD KEY `idx_fee_date` (`date`);

--
-- Indexes for table `integration`
--
ALTER TABLE `integration`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `integration_uid_unique` (`uid`);

--
-- Indexes for table `integration_request`
--
ALTER TABLE `integration_request`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_integration_request_type_type_id` (`type`,`type_id`),
  ADD KEY `idx_integration_request_date` (`date`),
  ADD KEY `idx_integration_request_result` (`result`),
  ADD KEY `integration_id_fk` (`integration_id`);

--
-- Indexes for table `merchant`
--
ALTER TABLE `merchant`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FKE1E1C9C8155B08DB` (`state_id`),
  ADD KEY `FKE1E1C9C8D6A4F0C1` (`status_id`);

--
-- Indexes for table `merchant_fee`
--
ALTER TABLE `merchant_fee`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_merchant_fee_merchant_id` (`merchant_id`);

--
-- Indexes for table `merchant_form`
--
ALTER TABLE `merchant_form`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uid_UNIQUE` (`uid`),
  ADD KEY `fk_merchant_form_merchant_id_idx` (`merchant_id`);

--
-- Indexes for table `merchant_integration`
--
ALTER TABLE `merchant_integration`
  ADD UNIQUE KEY `u_merchant_integration_id` (`merchant_id`,`integration_id`),
  ADD KEY `fk_merchant_integration_id_idx` (`merchant_id`),
  ADD KEY `fk_merchant_integration_integration_id_idx` (`integration_id`);

--
-- Indexes for table `merchant_status`
--
ALTER TABLE `merchant_status`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_field`
--
ALTER TABLE `order_field`
  ADD UNIQUE KEY `unique_order_fields_id_name` (`order_id`,`field_name`);

--
-- Indexes for table `order_item`
--
ALTER TABLE `order_item`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK2D110D648775CC59` (`merchant_id`),
  ADD KEY `index_status` (`status`),
  ADD KEY `index_date` (`date`);

--
-- Indexes for table `payee`
--
ALTER TABLE `payee`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `payer_uid` (`uid`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `payment_uid` (`uid`),
  ADD KEY `payment_integration_id_idx` (`integration_id`),
  ADD KEY `payment_payer_id_idx` (`payee_id`);

--
-- Indexes for table `state`
--
ALTER TABLE `state`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `short_code` (`short_code`);

--
-- Indexes for table `subscription`
--
ALTER TABLE `subscription`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `order_item_id_UNIQUE` (`order_item_id`),
  ADD KEY `in_subscription_status` (`status`),
  ADD KEY `fk_subscription_order_item_id_idx` (`order_item_id`);

--
-- Indexes for table `transaction`
--
ALTER TABLE `transaction`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK7FA0D2DE43F2BD98` (`order_item_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `authority`
--
ALTER TABLE `authority`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `email_template`
--
ALTER TABLE `email_template`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `integration`
--
ALTER TABLE `integration`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `integration_request`
--
ALTER TABLE `integration_request`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `merchant`
--
ALTER TABLE `merchant`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;
--
-- AUTO_INCREMENT for table `merchant_form`
--
ALTER TABLE `merchant_form`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=104;
--
-- AUTO_INCREMENT for table `merchant_status`
--
ALTER TABLE `merchant_status`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `order_item`
--
ALTER TABLE `order_item`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `payee`
--
ALTER TABLE `payee`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9146;
--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9124;
--
-- AUTO_INCREMENT for table `state`
--
ALTER TABLE `state`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;
--
-- AUTO_INCREMENT for table `subscription`
--
ALTER TABLE `subscription`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `transaction`
--
ALTER TABLE `transaction`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `email_template`
--
ALTER TABLE `email_template`
  ADD CONSTRAINT `fk_email_template_merchant_id` FOREIGN KEY (`merchant_id`) REFERENCES `merchant` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `fee`
--
ALTER TABLE `fee`
  ADD CONSTRAINT `fk_fee_merchant_id` FOREIGN KEY (`merchant_id`) REFERENCES `merchant` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_fee_order_item_id` FOREIGN KEY (`order_item_id`) REFERENCES `order_item` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `integration_request`
--
ALTER TABLE `integration_request`
  ADD CONSTRAINT `integration_id_fk` FOREIGN KEY (`integration_id`) REFERENCES `integration` (`id`);

--
-- Constraints for table `merchant`
--
ALTER TABLE `merchant`
  ADD CONSTRAINT `FKE1E1C9C8155B08DB` FOREIGN KEY (`state_id`) REFERENCES `state` (`id`),
  ADD CONSTRAINT `FKE1E1C9C8D6A4F0C1` FOREIGN KEY (`status_id`) REFERENCES `merchant_status` (`id`);

--
-- Constraints for table `order_item`
--
ALTER TABLE `order_item`
  ADD CONSTRAINT `FK2D110D648775CC59` FOREIGN KEY (`merchant_id`) REFERENCES `merchant` (`id`);

--
-- Constraints for table `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `payment_integration_id` FOREIGN KEY (`integration_id`) REFERENCES `integration` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `subscription`
--
ALTER TABLE `subscription`
  ADD CONSTRAINT `fk_subscription_order_item_id` FOREIGN KEY (`order_item_id`) REFERENCES `order_item` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `transaction`
--
ALTER TABLE `transaction`
  ADD CONSTRAINT `FK7FA0D2DE43F2BD98` FOREIGN KEY (`order_item_id`) REFERENCES `order_item` (`id`) ON DELETE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
