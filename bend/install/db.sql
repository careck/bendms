--
-- Table structure for table `bend_electricity_period`
--

CREATE TABLE IF NOT EXISTS `bend_electricity_period` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `d_provider_invoice` date DEFAULT NULL,
  `d_provider_period_start` date NOT NULL,
  `d_provider_period_end` date NOT NULL,
  `provider_invoice_total_inc_gst` decimal(10,2) DEFAULT NULL,
  `provider_total_consumption_kwh` int(11) DEFAULT NULL,
  `provider_total_production_kwh` int(11) DEFAULT NULL,
  `bend_total_consumption_kwh` int(11) DEFAULT NULL,
  `bend_total_production_kwh` int(11) DEFAULT NULL,
  `bend_total_invoiced` decimal(10,2) DEFAULT NULL,
  `is_deleted` tinyint(4) NOT NULL DEFAULT '0',
  `creator_id` bigint(20) NOT NULL,
  `modifier_id` bigint(20) NOT NULL,
  `dt_created` datetime NOT NULL,
  `dt_modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `bend_household`
--

CREATE TABLE IF NOT EXISTS `bend_household` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `bend_lot_id` bigint(20) NOT NULL,
  `streetnumber` varchar(3) NOT NULL,
  `is_chl` tinyint(4) NOT NULL DEFAULT '0',
  `is_occupied` tinyint(4) NOT NULL DEFAULT '0',
  `is_deleted` tinyint(4) NOT NULL DEFAULT '0',
  `creator_id` bigint(20) NOT NULL,
  `modifier_id` bigint(20) NOT NULL,
  `dt_created` datetime NOT NULL,
  `dt_modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `bend_household_occupant`
--

CREATE TABLE IF NOT EXISTS `bend_household_occupant` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `bend_household_id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `d_start` date DEFAULT NULL,
  `d_end` date DEFAULT NULL,
  `pays_electricity` tinyint(4) NOT NULL DEFAULT '0',
  `does_workhours` tinyint(4) NOT NULL DEFAULT '0',
  `is_deleted` tinyint(4) NOT NULL DEFAULT '0',
  `creator_id` bigint(20) NOT NULL,
  `modifier_id` bigint(20) NOT NULL,
  `dt_created` datetime NOT NULL,
  `dt_modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `bend_lot`
--

CREATE TABLE IF NOT EXISTS `bend_lot` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `lot_number` tinyint(4) NOT NULL,
  `occupancy` tinyint(4) NOT NULL,
  `is_deleted` tinyint(4) NOT NULL DEFAULT '0',
  `creator_id` bigint(20) NOT NULL,
  `modifier_id` bigint(20) NOT NULL,
  `dt_created` datetime NOT NULL,
  `dt_modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `bend_lot_owner`
--

CREATE TABLE IF NOT EXISTS `bend_lot_owner` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `bend_lot_id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `d_start` date DEFAULT NULL,
  `d_end` date DEFAULT NULL,
  `is_deleted` tinyint(4) NOT NULL DEFAULT '0',
  `creator_id` bigint(20) NOT NULL,
  `modifier_id` bigint(20) NOT NULL,
  `dt_created` datetime NOT NULL,
  `dt_modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `bend_meter`
--

CREATE TABLE IF NOT EXISTS `bend_meter` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `is_inverter` tinyint(4) NOT NULL DEFAULT '0',
  `bend_household_id` bigint(20) DEFAULT NULL,
  `bend_lot_id` bigint(20) NOT NULL,
  `meter_number` varchar(255) NOT NULL,
  `start_value` int(11) DEFAULT NULL,
  `last_reading_value` int(11) DEFAULT NULL,
  `d_start` date DEFAULT NULL,
  `d_end` date DEFAULT NULL,
  `is_deleted` tinyint(4) NOT NULL DEFAULT '0',
  `creator_id` bigint(20) NOT NULL,
  `modifier_id` bigint(20) NOT NULL,
  `dt_created` datetime NOT NULL,
  `dt_modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `bend_meter_reading`
--

CREATE TABLE IF NOT EXISTS `bend_meter_reading` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `bend_meter_id` bigint(20) NOT NULL,
  `bend_electricity_period_id` bigint(20) DEFAULT NULL,
  `d_date` date NOT NULL,
  `value` int(11) NOT NULL,
  `notes` text NOT NULL,
  `previous_value` int(11) DEFAULT NULL,
  `is_deleted` tinyint(4) NOT NULL DEFAULT '0',
  `creator_id` bigint(20) NOT NULL,
  `modifier_id` bigint(20) NOT NULL,
  `dt_created` datetime NOT NULL,
  `dt_modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `bend_work_category`
--

CREATE TABLE IF NOT EXISTS `bend_work_category` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `parent_id` bigint(20) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `description` text,
  `is_deleted` tinyint(4) NOT NULL DEFAULT '0',
  `creator_id` bigint(20) NOT NULL,
  `modifier_id` bigint(20) NOT NULL,
  `dt_created` datetime NOT NULL,
  `dt_modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `bend_work_entry`
--

CREATE TABLE IF NOT EXISTS `bend_work_entry` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `bend_workperiod_id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `d_date` date NOT NULL,
  `hours` tinyint(4) NOT NULL,
  `description` text NOT NULL,
  `bend_workcategory_id` bigint(20) DEFAULT NULL,
  `is_deleted` tinyint(4) NOT NULL DEFAULT '0',
  `creator_id` bigint(20) NOT NULL,
  `modifier_id` bigint(20) NOT NULL,
  `dt_created` datetime NOT NULL,
  `dt_modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `bend_work_period`
--

CREATE TABLE IF NOT EXISTS `bend_work_period` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `d_start` date NOT NULL,
  `d_end` date NOT NULL,
  `is_closed` tinyint(4) NOT NULL DEFAULT '0',
  `is_deleted` tinyint(4) NOT NULL DEFAULT '0',
  `creator_id` bigint(20) NOT NULL,
  `modifier_id` bigint(20) NOT NULL,
  `dt_created` datetime NOT NULL,
  `dt_modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;