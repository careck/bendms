<?php

class BendInitialModel extends CmfiveMigration {

	public function up() {
		// Create new tables
		
		if (!$this->hasTable('bend_electricity_period')) {
			$this->tableWithId('bend_electricity_period')
			->addDateColumn('d_provider_invoice')
			->addDateColumn('d_provider_period_start')
			->addDateColumn('d_provider_period_end')
			->addMoneyColumn('provider_invoice_total_inc_gst')
			->addIntegerColumn('provider_total_consumption_kwh')
			->addIntegerColumn('provider_total_production_kwh')
			->addIntegerColumn('bend_total_consumption_kwh')
			->addIntegerColumn('bend_total_production_kwh')
			->addMoneyColumn('bend_total_invoiced')
			->addCmfiveParameters()
			->create();
		}
		
		if (!$this->hasTable('bend_household')) {
			$this->tableWithId('bend_household')
			->addIdColumn('bend_lot_id')
			->addStringColumn('streetnumber',true,3)
			->addBooleanColumn('is_chl')
			->addBooleanColumn('is_occupied')
			->addCmfiveParameters()
			->create();
		}

		if (!$this->hasTable('bend_household_occupant')) {
			$this->tableWithId('bend_household_occupant')
			->addIdColumn('bend_household_id')
			->addIdColumn('user_id')
			->addDateColumn('d_start')
			->addDateColumn('d_end')
			->addBooleanColumn('pays_electricity',true,false,true)
			->addBooleanColumn('does_workhours',true,false,true)
			->addCmfiveParameters()
			->create();
		}
		
		if (!$this->hasTable('bend_lot')) {
			$this->tableWithId('bend_lot')
			->addIntegerColumn('lot_number')
			->addStringColumn('occupancy')
			->addCmfiveParameters()
			->create();
		}
		
		
		if (!$this->hasTable('bend_lot_owner')) {
			$this->tableWithId('bend_lot_owner')
			->addIdColumn('bend_lot_id')
			->addIdColumn('user_id')
			->addStringColumn('address1')
			->addStringColumn('address2')
			->addStringColumn('town')
			->addStringColumn('postcode')
			->addStringColumn('state')
			->addStringColumn('country')
			->addDateColumn('d_start')
			->addDateColumn('d_end')
			->addCmfiveParameters()
			->create();
		}

		if (!$this->hasTable('bend_meter')) {
			$this->tableWithId('bend_meter')
			->addBooleanColumn('is_inverter')
			->addIdColumn('bend_household_id')
			->addIdColumn('bend_lot_id')
			->addStringColumn('meter_number')
			->addBigIntegerColumn('start_value')
			->addBigIntegerColumn('las_reading_value')
			->addDateColumn('d_start')
			->addDateColumn('d_end')
			->addCmfiveParameters()
			->create();
		}

		if (!$this->hasTable('bend_meter_reading')) {
			$this->tableWithId('bend_meter_reading')
			->addIdColumn('bend_meter_id')
			->addIdColumn('bend_electricity_period_id')
			->addDateColumn('d_date')
			->addBigIntegerColumn('value')
			->addTextColumn('notes')
			->addBigIntegerColumn('previous_value')
			->addCmfiveParameters()
			->create();
		}
		
		if (!$this->hasTable('bend_work_category')) {
			$this->tableWithId('bend_work_category')
			->addStringColumn('title')
			->addTextColumn('description')
			->addCmfiveParameters()
			->create();
		}
		
		if (!$this->hasTable('bend_work_entry')) {
			$this->tableWithId('bend_work_entry')
			->addIdColumn('bend_workperiod_id')
			->addIdColumn('user_id')
			->addDateColumn('d_date')
			->addDecimalColumn('hours')
			->addIdColumn('bend_work_category_id')
			->addCmfiveParameters()
			->create();
		}

		if (!$this->hasTable('bend_work_period')) {
			$this->tableWithId('bend_work_period')
			->addDateColumn('d_start')
			->addDateColumn('d_end')
			->addBooleanColumn('is_closed')
			->addCmfiveParameters()
			->create();
		}
		
	}

	public function down() {
		// Remove all tables
		$this->dropTable('bend_electricity_period');
		$this->dropTable('bend_household');
		$this->dropTable('bend_household_occupant');
		$this->dropTable('bend_lot');
		$this->dropTable('bend_lot_owner');
		$this->dropTable('bend_meter');
		$this->dropTable('bend_meter_reading');
		$this->dropTable('bend_work_category');
		$this->dropTable('bend_work_entry');
		$this->dropTable('bend_work_period');
		
	}

}
