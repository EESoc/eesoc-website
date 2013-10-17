<?php

use Illuminate\Database\Migrations\Migration;

class AddNotifiedEmailFieldToSale extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('sales', function($table) {
			$table->boolean('notified')->default(false);
		});

		// Send locker sales notifications
		foreach (User::all() as $user) {
			if ($user->unclaimed_lockers_count > 0) {
				if (Notification::sendLockerInformation($user)) {
					foreach ($user->sales as $sale) {
						$sale->notified = true;
						$sale->save();
					}
				}
			} else {
				foreach ($user->sales as $sale) {
					$sale->notified = true;
					$sale->save();
				}
			}
		}
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('sales', function($table) {
			$table->dropColumn('notified');
		});
	}

}