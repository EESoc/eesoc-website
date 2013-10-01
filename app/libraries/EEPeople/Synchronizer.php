<?php
namespace EEPeople;

use \ImperialCollegeUser;
use \Loggable;
use \StudentGroup;
use \User;

class Synchronizer {

	private $client;
	private $logger;
	private $skip;

	public function __construct(Client $client, $no_time_limit = true, $skip = true, Loggable $logger = null)
	{
		$this->client = $client;
		$this->logger = $logger;
		$this->skip   = $skip;

		if ($no_time_limit) {
			set_time_limit(0);
		}
	}

	public function perform()
	{
		$this->log('info', 'Synchronizing users from EEPeople...');
		$this->synchronizeStudentGroups();
		$this->synchronizeStudents(); // This will take a while...
		$this->log('info', 'Successfully synchronized all users!');
	}

	public function synchronizeStudentGroups()
	{
		$this->log('info', 'Synchronizing student groups');

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

			$this->log('info', sprintf('Updated group `%s`', $student_group->name));
		}
	}

	public function synchronizeStudents()
	{
		$this->log('info', 'Synchronizing students');

		$official_groups = StudentGroup::official()->get();

		foreach ($official_groups as $group) {
			$this->log('info', sprintf('Synchronizing students in group `%s`', $group->name));

			$student_ids = $this->client->getStudentIdsInGroup($group->group_id);

			foreach ($student_ids as $student_id) {
				$this->synchronizeStudent($student_id, $group);
			}
		}
	}

	public function synchronizeStudent($student_id, StudentGroup $group = null)
	{
		if ($this->skip && User::where('eepeople_id', '=', $student_id)->exists()) {
			$this->log('info', sprintf('Skipping EEPeople ID `%s`', $student_id));
			return true;
		}

		$person = $this->client->getPerson($student_id);

		// Skip this person has missing information
		if ( ! $person || ! $person['username']) {
			$this->log('error', sprintf('EEPeople ID `%d` has missing information', $student_id));
			return false;
		}

		// Skip this person if no email
		if ( ! $person['email']) {
			$this->log('error', sprintf('`%s` has no email', $person['name']));
			return false;
		}

		$user = User::where('username', '=', $person['username'])->first();

		if ( ! $user) {
			$user = new User;
			$user->username = $person['username'];
		}

		$user->studentGroup()->associate($group);
		$user->eepeople_id = $person['id'];
		$user->tutor_name  = $person['tutor_name'];
		$user->name        = isset($person['name']) ? $person['name'] : $person['username'];
		$user->email       = $person['email'];

		if ( ! $user->image_content_type || ! $user->image_blob) {
			$image = $this->client->getImageOfPerson($person);
			$user->image_content_type = $image['content_type'];
			$user->image_blob         = $image['blob'];
		}

		if ($user->save()) {
			$this->log('info', sprintf('Updated user `%s`', $user->name));
			return true;
		} else {
			$this->log('error', sprintf('Error saving `%s`', $user->name));
			return false;
		}
	}

	private function log($type, $message)
	{
		if ($this->logger) {
			$this->logger->{$type}($message);
		}
	}

}