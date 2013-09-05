<?php

class LockerTableSeeder extends Seeder {

	public function run()
	{
		DB::table('lockers')->delete();
		DB::table('locker_clusters')->delete();
		DB::table('locker_floors')->delete();

		$levels = [
			['name' => 'Level 3', 'floor' => 3],
			['name' => 'Level 4', 'floor' => 4],
			['name' => 'Level 5', 'floor' => 5],
		];

		$clusters = [
			['name' => 'A', 'blocks' => 6],
			['name' => 'B', 'blocks' => 4],
		];

		foreach ($levels as $level) {
			$floor = new LockerFloor;
			$floor->name = $level['name'];
			$floor->floor = $level['floor'];
			$floor->save();

			$locker_name = $floor->floor * 100; // Generate first locker name

			foreach ($clusters as $position => $__cluster) {
				$cluster = new LockerCluster;
				$cluster->name = $__cluster['name'];
				$cluster->position = $position;
				$cluster->lockerFloor()->associate($floor);
				$cluster->save();

				$actual_col = -1;

				for ($i = 0; $i < $__cluster['blocks']; $i++) {
					$column_offset = $i * 3;
					for ($row = 0; $row < 3; $row++) {
						for ($col = 0; $col < 3; $col++) {
							$locker = new Locker;
							$locker->name = ++$locker_name;
							$locker->row = $row;
							$locker->column = $col + $column_offset;
							$locker->size = 's';
							$locker->lockerCluster()->associate($cluster);
							$locker->status = 'vacant';
							$locker->save();
						}
					}
				}
			}
		}
	}

}