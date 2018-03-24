<?php
class BendWorkPeriodSapphs extends CmfiveMigration {
	
	public function up() {
		
		$table = $this->table('bend_work_period');
		if (!empty($table)) {
			$column = $table->hasColumn('sapphs_per_hour');
		}
		if (empty($column)) {
			$table->addMoneyColumn('sapphs_per_hour',true)->update();
		}
		
	}
	
	public function down() {
		$table = $this->table('bend_work_entry');
		if (!empty($table)) {
			$table->removeColumn('sapphs_per_hour')->update();
		}
	}
	
}
