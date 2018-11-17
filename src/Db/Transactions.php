<?php
	namespace Mind\Db;

	use Mind\Users\{User, Student, Teacher};
	use Mind\Server\Utils;

	class Transactions {
		public static function add(User $from, User $to, int $points, string $cause): bool {
			if (!$to->has_role("student"))
				return false;

			if ($from instanceof Student) {
				if ($from->get_points() < $points)
					return false;
			}

			Db::query(
				"INSERT INTO `transactions` (
					FROM_LOGIN,
					TO_LOGIN,
					POINTS,
					CAUSE
				) VALUES (
					?s, ?s, ?i, ?s
				)", $from->get_login(), $to->get_login(), $points, $cause
			);

			$points = Utils::get_points_in_case(intval($points));

			Notifications::add(
				$to,
				$from,
				"Вам перечислили баллы",
				intval($points)
			);

			return true;
		}

		public static function of_student(Student $user): array {
			$ret = [];
			$query = Db::query(
				"SELECT
					DATE_FORMAT(TIME, '%H:%i %d.%m.%y') AS NORM_TIME,
					FROM_LOGIN,
					TO_LOGIN,
					CAUSE,
					POINTS,
					TIME
				FROM `transactions`
				WHERE
					FROM_LOGIN=?s OR
					TO_LOGIN=?s
				ORDER BY TIME DESC
				", $user->get_login(), $user->get_login()
			);

			foreach ($query as $q) {
				if ($q["FROM_LOGIN"] == $user->get_login())
					$q["POINTS"] = -$q["POINTS"];
				
				$ret[] = $q;
			}

			return $ret;
		}
	}
?>