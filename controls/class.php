<?php
	class Class_control extends Control {
		private $row_view;

		public function has_access(array $args): bool {
			if (isset($args[1]) && $args[1] != "")
				return true;
			else
				return get_curr()->has_role("classruk");
		}

		protected function get_data(array $args): array {
			$class_name = $args[1] ?? get_curr()->get_role_arg("classruk");
		
			list($class, $sum) = $this->get_sum_class($class_name);

			return [
				"class" => $class_name,
				"sum" => $sum,
				"students" => $class,
			];
		}

		private function get_sum_class(string $class_name) {
			$class = [];
			$sum = 0;

			foreach ($this->get_students_in_class($class_name) as $student) {
				$class[] = [
					"points" => $student->get_points(),
					"login" => $student->get_login(),
					"names" => $student->get_names()
				];

				$sum += $student->get_points();
			}

			return [$class, $sum];
		}
		
		private function get_students_in_class(string $class_name): array {
			$class = [];

			list($class_num, $class_lit) = explode("-", $class_name);

			$r = sql_query("
				SELECT LOGIN
				FROM `students`
				WHERE
					CLASS_LIT = '$class_lit' AND
					CLASS_NUM = '$class_num'
			");

			foreach ($r as $st) {
				$student = get_user($st["LOGIN"], "student");
				$class[] = $student;
			}

			return $class;
		}
	}
?>
