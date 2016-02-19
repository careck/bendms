<?php

class BendCategoryParentAndWorkEntryDescription extends CmfiveMigration {

	public function up() {
		$table = $this->table('bend_work_category');
		if (!empty($table)) {
			$column = $table->hasColumn('parent_id');
		}
		if (empty($column)) {
			$table->addIdColumn('parent_id',true)->update();
		}
		
		$table = $this->table('bend_work_entry');
		if (!empty($table)) {
			$column = $table->hasColumn('description');
		}
		if (empty($column)) {
			$table->addStringColumn('description',true)->update();
		}
		
	}

	public function down() {
		$table = $this->table('bend_work_category');
		if (!empty($table)) {
			$table->removeColumn('parent_id')->update();
		}
		$table = $this->table('bend_work_entry');
		if (!empty($table)) {
			$table->removeColumn('description')->update();
		}
		
	}
}
