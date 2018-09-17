<?php
	declare(strict_types=1);

	class Control {
		protected $template;
		protected $name;

		public function __construct(string $name) {
			$this->name = $name;
			$this->template = get_twig()->load("$name.html");
		}

		public function has_access(array $args): bool {
			return true;
		}

		protected function get_data(array $args): array {
			return [];
		}

		protected function get_default_data(): array {
			return [
				"control_name" => $this->name
			];
		}

		public function get_html(array $args): string {
			$data = $this->get_default_data();
			add_to_arr($data, $this->get_data($args));

			$html = $this->template->render($data);
			return $html;
		}

	}
?>