<?php
	class Menu_control extends Control {
		private $button_view;
		private $buttons = [
			// [role, title, url, class (maybe)]
			["all", "Профиль", "/"],
			["student", "Выписка по баллам", "/points"],
			["student", "Передать баллы", "/give"],
			["teacher", "Изменить баллы", "/award"],
			["classruk", "Выписка по классу", "/class"],
			["student", "Расписание", "/timetable"],
			["student", "Аукцион", "/auction"],
			["student", "Интернат", "/internat"],
			["vospit", "Интернат", "/internat"],
			["teacher", "Оповестить учеников", "/broadcast"]

		];

		protected function get_data(array $args): array {
			$buttons = [];

			foreach ($this->buttons as $button) {
				if ($button[0]=="all" || get_curr()->has_role($button[0])) {
					$buttons[] = [
						"title" => $button[1],
						"url" => $button[2]
					];
				}
			}

			return [
				"buttons" => $buttons
			];
		}		
	}
?>
