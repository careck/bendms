<?php

class BendAddWorkperiodBudgetHours extends CmfiveMigration {

	public function up() {
		$table = $this->table('bend_work_period');
		if (!empty($table)) {
			$column = $table->hasColumn('monthly_person_hours');
		}
		if (empty($column)) {
			$table->addIntegerColumn('monthly_person_hours',true,"4")->update();
		}
	}

	public function down() {
		$table = $this->table('bend_work_period');
		if (!empty($table)) {
			$table->removeColumn('monthly_person_hours')->update();
		}
		
	}
	
}
