<?php

class bilinfo_API
{

	private $base_url;
	private $password;
	private $username;

	public function __construct()
	{

		$this->base_url = $this->get_api_url();
		$this->password = $this->get_password();
		$this->username = $this->get_username();
		$this->header = $this->get_header_args();
	}


	/* Get cases from bilinfo. Returns array if data is given, false otherwise */
	public function get_cases()
	{
		$resp = wp_remote_get($this->base_url, array(
			'headers' => array(
				'Authorization' => 'Basic ' . base64_encode($this->username . ':' . $this->password),
				"content-type" => "application/json",
				"sslverify" => true
			)
		));

		var_dump($resp);


		if (!is_wp_error($resp)) {

			$cases = json_decode($resp['body']);

			if (is_array($cases) && count($cases) > 0) {
				return $cases;
			} else {
				return array();
			}
		} else {

			return $resp;
		}
	}

	/* Get the base-url from the settings */
	private function get_api_url()
	{

		return trailingslashit(get_option('bilinfo')['base-url']);
	}

	/* Get the org-key from the settings */
	private function get_password()
	{

		return get_option('bilinfo')['password'];
	}

	/* Get the username from the settings */
	private function get_username()
	{

		return get_option('bilinfo')['username'];
	}

	private function get_header_args()
	{

		$args = array(
			'headers' => array(
				'Authorization' => 'Basic ' . base64_encode($this->username . ':' . $this->password),
				"content-type" => "application/json",
				"sslverify" => true
			)
		);

		return $args;
	}
}
