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
		$resp = wp_remote_get($this->base_url, $this->header);

		if (!is_wp_error($resp)) {

			$JSON = json_decode($resp['body']);

			$vehicles = (array) $JSON;
			$cases = $vehicles['Vehicles'];

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
			),
			'user-agent'  =>  'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_8) AppleWebKit/535.6.2 (KHTML, like Gecko) Version/5.2 Safari/535.6.2',
		);

		return $args;
	}

	public function set_property_url($property_url, $bilID)
	{

		if (BILINFO_DEV) {
			echo 'Is dev.. Skipping';
			return false;
		}

		$url = $this->base_url;


		$args = $this->get_header_args();

		$args['method'] = 'GET';
		$args['body'] = array(
			'bilID' => $bilID,
			'uri' => $property_url
		);

		//return false;
		$response = wp_remote_post($url, $args);

		return $response;
	}

	public function enqueue_case_scripts()
	{
		if (BILINFO_DEV == true) {
			return false;
		}
		echo '<script>var bilinfoId = "' . get_post_meta(get_the_ID(), 'Id', true) . '";</script>';
		echo '<script src="' . $this->base_url . 'getStatsScript.js" defer></script>';
	}
}
