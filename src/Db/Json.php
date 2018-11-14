<?php
	namespace Mind\Db;

	use Mind\Db\Db;
	use Mind\Users\{User, Teacher, Student};

	class Json {
		public static function get_classes(): string {
			$query = Db::query(
				"SELECT
					CONCAT(CLASS_NUM, '-', CLASS_LIT) AS CLASS,
					GIVEN_NAME,
					FATHER_NAME,
					FAMILY_NAME,
					LOGIN
				FROM students
				ORDER BY
					CLASS_NUM, CLASS_LIT,
					FAMILY_NAME, GIVEN_NAME
				"
			);
			$classes = [];

			foreach ($query as $student) {
				$class = $student["CLASS"];
				$classes[$class] = $classes[$class] ?? [];

				$classes[$class][] = $student;
			}

			return json_encode($classes);
		}

		public static function get_events(): string {
			$query = Db::query("SELECT * FROM `calendar`");

			$events = [];
			foreach ($query as $event) {
				$events[] = $event;
			}

			return json_encode($events);
		}
	}
?>
