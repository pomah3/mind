<?php
	namespace Mind\Controls;

	use Mind\Db\{Db, Users, Notifications, Json, Music};
	use Mind\Server\{Control, Utils};
	use Mind\Users\{User, Teacher, Student};

	use Mind\Db\Excel\Reader;

	const READERS = Utils::ROOT."src/Db/Excel/Readers/";

	class Load extends Control {
		public function has_access(array $args): bool {
			return Utils::is_logined() && Utils::get_curr() instanceof Teacher;
		}

		protected function get_data(array $args): array {
			if (Utils::isset_post_fields("excel_type")) {
				$status = $this->post_handle();
			}

			return [
				"readers" => $this->get_readers(),
				"status" => $status ?? null
			];
		}

		private function get_readers(): array {
			$arr = [];

			$files = scandir(READERS);
			$files = $files ? $files : [];

			foreach ($files as $reader_file) {
				if ($reader_file == "." || $reader_file == "..")
					continue;

				require_once READERS.$reader_file;
				$cls_name = Reader::get_reader_name(substr($reader_file, 0, -4));

				$func_name = $cls_name."::get_name";
				if (is_callable($func_name))
					$arr[] = call_user_func($func_name);
			}

			return $arr;
		}

		private function get_readers_assoc(): array {
			$arr = [];

			$files = scandir(READERS);
			$files = $files ? $files : [];

			foreach ($files as $reader_file) {
				if ($reader_file == "." || $reader_file == "..")
					continue;

				require_once READERS.$reader_file;
				$cls_name = Reader::get_reader_name(substr($reader_file, 0, -4));

				$func_name = $cls_name."::get_name";
				if (is_callable($func_name)) {
					$name = call_user_func($func_name);
					$arr[$name] = $cls_name;
				}

			}

			return $arr;
		}

		private function post_handle(): string {
			$type = $_POST["excel_type"];
			$readers = $this->get_readers();

			if (!in_array($type, $readers))
				return "error";

			if (!file_exists($_FILES["excel"]["tmp_name"]))
				return "error";

			$readers = $this->get_readers_assoc();
			$cls_name = $readers[$type];

			$func_name = $cls_name."::load";
			if (is_callable($func_name))
				call_user_func($func_name, $_FILES["excel"]["tmp_name"]);

			return "success";
		}
	}
?>
