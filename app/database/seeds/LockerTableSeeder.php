<?php

class LockerTableSeeder extends Seeder {

	public function run()
	{
		DB::table('lockers')->delete();
		DB::table('locker_clusters')->delete();
		DB::table('locker_floors')->delete();

		$levels = [];
		$levels[] = LockerFloor::create(['name' => 'Level 3', 'floor' => 3, 'position' => 1]);
		$levels[] = LockerFloor::create(['name' => 'Level 4', 'floor' => 4, 'position' => 2]);
		$levels[] = LockerFloor::create(['name' => 'Level 5', 'floor' => 5, 'position' => 3]);

		foreach ($levels as $level) {
			foreach (['West', 'North'] as $position => $cluster_name) {
				$cluster = new LockerCluster;
				$cluster->name = $cluster_name;
				$cluster->position = $position;
				$cluster->lockerFloor()->associate($level);
				$cluster->save();

				for ($row = 0; $row < 3; ++$row) {
					for ($col = 0; $col < 20; ++$col) {
						$locker = new Locker;
						$locker->name = "{$level->floor}{$row}{$col}";
						$locker->row = $row;
						$locker->column = $col;
						$sizes = ['s', 'm', 'l'];
						$locker->size = $sizes[$row];
						$locker->lockerCluster()->associate($cluster);
						$statuses = ['vacant', 'vacant', 'vacant', 'vacant', 'taken', 'reserved']; // Biased towards vacant
						$locker->status = $statuses[rand(0, 5)];
						$locker->save();
					}
				}
			}
		}
	}

}