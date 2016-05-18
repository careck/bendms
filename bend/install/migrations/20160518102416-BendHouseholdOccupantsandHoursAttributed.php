<?php

class BendHouseholdOccupantsandHoursAttributed extends CmfiveMigration {

	public function up() {
		$table = $this->table('bend_household');
		if (!empty($table)) {
			$column = $table->hasColumn('num_occupants');
		}
		if (empty($column)) {
			$table->addIdColumn('num_occupants',true)->update();
		}
		
		$table = $this->table('bend_work_entry');
		if (!empty($table)) {
			$column = $table->hasColumn('attributed_user_id');
		}
		if (empty($column)) {
			$table->addIdColumn('attributed_user_id',true)->update();
		}
		
	}

	public function down() {
		$table = $this->table('bend_household');
		if (!empty($table)) {
			$table->removeColumn('num_occupants')->update();
		}
		$table = $this->table('bend_work_entry');
		if (!empty($table)) {
			$table->removeColumn('attributed_user_id')->update();
		}
	}

}
