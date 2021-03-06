<?php
	declare(strict_types=1);

	namespace Mind\Users;

	use Mind\Db\Db;

	abstract class User {
		/**
		 * @var string $login
		 */
		protected $login;
		/**
		 * @var string $enter_login
		 */
		protected $enter_login;
		/**
		 * @var string $given_name
		 * @var string $family_name
		 * @var string $father_name
		 * @var string $email
		 */
		protected $given_name, $family_name, $father_name, $email;

		/**
		 * @var array<int, string>
		 */
		protected $roles;

		/**
		 * @var array<string, string>
		 */
		protected $role_args;

		public function get_email(): ?string {
			return $this->email;
		}

		public function get_given_name(): string {
			return $this->given_name;
		}

		public function get_family_name(): string {
			return $this->family_name;
		}

		public function get_father_name(): string {
			return $this->father_name;
		}

		public function get_full_name(string $format = "fm gi ft"): string {
			return $this->get_name($format);
		}

		public function get_name(string $format = "fm gi ft"): string {
			$search = ["gi", "ft", "fm"];
			$replace = [$this->given_name, $this->father_name, $this->family_name];

			return str_replace($search, $replace, $format);
		}

		public function get_names(): array {
			return [
				"given" => $this->get_given_name(),
				"father" => $this->get_father_name(),
				"family" => $this->get_family_name(),
			];
		}

		public function get_login(): string {
			return $this->login;
		}

		public function get_enter_login(): string {
			return $this->enter_login;
		}

		public function has_role(string $role): bool {
			return in_array($role, $this->roles); 
		}

		public function get_role_arg(string $role): ?string {
			if (isset($this->role_args[$role]))
				return $this->role_args[$role];
			return null;
		}

		/**
		 * @return array<int, string>
		 */
		public function get_roles(): array {
			return $this->roles;
		}

		protected function from_assoc(array $assoc) {
			$this->given_name = $assoc["GIVEN_NAME"];
			$this->family_name = $assoc["FAMILY_NAME"];
			$this->father_name = $assoc["FATHER_NAME"];

			$this->email = $assoc["EMAIL"];

			$this->enter_login = $assoc["ENTER_LOGIN"];

			$r = Db::query("
				SELECT ROLE, ARG
				FROM teacher_roles
				WHERE LOGIN = ?s
			", $this->login);

			$this->roles = [];
			$this->role_args = [];

			foreach ($r as $role) {
				$this->roles[] = $role["ROLE"];
				$this->role_args[$role["ROLE"]] = $role["ARG"];
			}
		}
	}
?>
