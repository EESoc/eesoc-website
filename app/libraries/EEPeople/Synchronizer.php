<?php
namespace EEPeople;

use StudentGroup;
use User;

class Synchronizer {

	private $client;

	public function __construct(Client $client, $no_time_limit = true)
	{
		$this->client = $client;

		if ($no_time_limit) {
			set_time_limit(0);
		}
	}

	public function perform()
	{
		$this->synchronizeStudentGroups();
		$this->synchronizeStudents(); // This will take a while...
	}

	public function synchronizeStudentGroups()
	{
		$groups = $this->client->getStudentGroups();

		foreach ($groups as $group) {
			$student_group = StudentGroup::where('group_id', '=', $group['id'])->first();
			if ( ! $student_group) {
				$student_group = new StudentGroup;
				$student_group->group_id = $group['id'];
			}

			$student_group->name = $group['name'];
			$student_group->is_official = true;
			$student_group->save();
		}
	}

	public function synchronizeStudents()
	{
		$official_groups = StudentGroup::official()->get();
		
		foreach ($official_groups as $group) {
			$student_ids = $this->client->getStudentIdsInGroup($group->group_id);

			foreach ($student_ids as $student_id) {
				$this->synchronizeStudent($student_id, $group);
			}
		}
	}

	public function synchronizeStudent($student_id, StudentGroup $group = null)
	{
		$person = $this->client->getPerson($student_id);

		$user = User::where('username', '=', $person['username'])->first();
		if ( ! $user) {
			$user = new User;
			$user->username = $person['username'];
		}

		$user->studentGroup()->associate($group);
		$user->eepeople_id        = $person['id'];
		$user->name               = $person['name'];
		$user->tutor_name         = $person['tutor_name'];
		$user->email              = $person['email'];

		if ( ! $user->image_content_type || ! $user->image_blob) {
			$image = $this->client->getImageOfPerson($person);
			$user->image_content_type = $image['content_type'];
			$user->image_blob         = $image['blob'];
		}

		return $user->save();
	}

}