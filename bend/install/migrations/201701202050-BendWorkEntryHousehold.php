<?php

class BendWorkEntryHousehold extends CmfiveMigration {

	public function up() {
		
		$table = $this->table('bend_work_entry');
		if (!empty($table)) {
			$column = $table->hasColumn('bend_household_id');
		}
		if (empty($column)) {
			$table->addIdColumn('bend_household_id',true)->update();
		}
		
	}

	public function down() {
		$table = $this->table('bend_work_entry');
		if (!empty($table)) {
			$table->removeColumn('bend_household_id')->update();
		}
	}

}
