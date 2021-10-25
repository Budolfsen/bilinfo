<?php

class bilinfo_Log
{

	private $type;
	private $input;
	private $response;
	private $time;

	/**
	 * bilinfo_Log constructor.
	 *
	 * @param $type
	 * @param $input
	 * @param $response
	 * @param $time
	 */
	public function __construct($type, $input = null, $response = null, $time = false)
	{
		$this->setType($type);
		$this->setInput($input);
		$this->setResponse($response);
		$this->setTime($time);
		$this->save();
	}

	public function save()
	{
		global $wpdb;

		$sql = "INSERT INTO " . $wpdb->prefix . "bilinfo_log (type, input, response, time) VALUES ('" . $this->getType() . "', '" . $this->getInput() . "', '" . $this->getResponse() . "', '" . $this->getTime() . "')";
		$query = $wpdb->query($sql);
		return $query;
	}

	/**
	 * @return mixed
	 */
	public function getType()
	{
		return $this->type;
	}

	/**
	 * @param mixed $type
	 */
	public function setType($type)
	{
		$this->type = filter_var($type, FILTER_SANITIZE_STRING);
	}

	/**
	 * @return mixed
	 */
	public function getInput()
	{
		return $this->input;
	}

	/**
	 * @param mixed $input
	 */
	public function setInput($input)
	{
		if ($input && is_array($input)) {
			$this->input = json_encode($input);
		} else {
			$this->input = $input;
		}
	}

	/**
	 * @return mixed
	 */
	public function getResponse()
	{
		return $this->response;
	}

	/**
	 * @param mixed $response
	 */
	public function setResponse($response)
	{
		if ($response && is_array($response)) {
			$this->response = json_encode($response);
		} else {
			$this->response = $response;
		}
	}

	/**
	 * @return bool|int
	 */
	public function getTime()
	{
		return $this->time;
	}

	/**
	 * @param bool|int $time
	 */
	public function setTime($time)
	{

		$time = ($time) ?: time();
		$this->time = date('Y-m-d H:i:s', $time);
	}
}
