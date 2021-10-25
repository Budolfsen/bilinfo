<?php

class bilinfo_Media_Queue
{

	private $id;
	private $type;
	private $url;
	private $post_id;
	private $time;
	private $priority;

	/**
	 * bilinfo_Media_Queue constructor.
	 *
	 * @param $id
	 */
	public function __construct($id = null)
	{
		if ($id && is_int($id)) {
			$this->setId($id);
			$this->fetch();
		} else {
			$this->setId('NULL');
		}
	}

	public static function add($type, $url, $post_id, $time = null, $priority = null)
	{
		$item = new bilinfo_Media_Queue();
		$item->setType($type);
		$item->setUrl($url);
		$item->setPostId($post_id);
		$item->setTime($time);
		$item->setPriority($priority);
		return $item->save();
	}

	public function fetch()
	{
		if (!$this->getId()) {
			return false;
		}

		global $wpdb;

		$sql = "SELECT * FROM " . $wpdb->prefix . "bilinfo_media_queue WHERE id = %d;";
		$query = $wpdb->get_row(
			$wpdb->prepare($sql, $this->getId())
		);

		if ($query) {
			$this->setType($query->type);
			$this->setUrl($query->url);
			$this->setTime($query->time);
			$this->setPostId($query->post_id);
			$this->setPriority($query->priority);
		}

		return $query;
	}

	public function save()
	{
		global $wpdb;

		$sql = "INSERT INTO " . $wpdb->prefix . "bilinfo_media_queue (id, type, url, post_id, time, priority) VALUES (" . $this->getId() . ", '" . $this->getType() . "', '" . $this->getUrl() . "', " . $this->getPostId() . ", '" . $this->getTime() . "', " . $this->getPriority() . ") ON DUPLICATE KEY UPDATE type = '" . $this->getType() . "', url = '" . $this->getUrl() . "', post_id = " . $this->getPostId() . ", time = '" . $this->getTime() . "', priority = " . $this->getPriority() . ";";
		$query = $wpdb->query($sql);

		if ($query) {
			$this->setId($wpdb->insert_id);
		}

		return $query;
	}

	public function delete()
	{

		if (!$this->getId()) {
			return false;
		}

		global $wpdb;

		$sql = "DELETE FROM " . $wpdb->prefix . "bilinfo_media_queue WHERE id = " . $this->getId() . ";";
		$query = $wpdb->query($sql);

		return $query;
	}

	public function fetch_next_item()
	{
		global $wpdb;

		$sql = "SELECT * FROM " . $wpdb->prefix . "bilinfo_media_queue ORDER BY priority DESC, id ASC LIMIT 1";
		$query = $wpdb->get_row($sql);

		if ($query) {
			$this->setId($query->id);
			$this->setType($query->type);
			$this->setUrl($query->url);
			$this->setTime($query->time);
			$this->setPostId($query->post_id);
			$this->setPriority($query->priority);
		}

		return $query;
	}

	public function run()
	{

		if (!$this->getPostId()) {
			$fetched = $this->fetch_next_item();
		} else {
			$fetched = true;
		}


		if (!$fetched || !$this->getPostId() || !$this->getUrl() || !$this->getType()) {
			new bilinfo_Log('stopping_queue');
			die('no more');
		}

		$case = new bilinfo_Case($this->getPostId());
		$case->fetch();
		$drawing = ($this->getType() == 'drawing') ?: false;
		$download = bilinfo_Import::check_and_download_single_image($case, $this->getUrl(), $drawing);
		new bilinfo_Log('running_queue', array($this->getId(), $this->getType(), $this->getUrl(), $this->getPostId(), 'download: ' . $download));
		$this->delete();
		bilinfo_Helpers::post_without_wait(get_home_url() . '?run_queue=1');
	}


	/**
	 * @return bool
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * @param bool $id
	 */
	public function setId($id)
	{
		$this->id = $id;
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
		$this->type = $type;
	}

	/**
	 * @return mixed
	 */
	public function getUrl()
	{
		return $this->url;
	}

	/**
	 * @param mixed $url
	 */
	public function setUrl($url)
	{
		$this->url = $url;
	}

	/**
	 * @return mixed
	 */
	public function getPostId()
	{
		return $this->post_id;
	}

	/**
	 * @param mixed $post_id
	 */
	public function setPostId($post_id)
	{
		$this->post_id = $post_id;
	}

	/**
	 * @return mixed
	 */
	public function getTime()
	{
		return $this->time;
	}

	/**
	 * @param mixed $time
	 */
	public function setTime($time = false)
	{
		$time = ($time) ?: time();
		$this->time = date('Y-m-d H:i:s', $time);
	}

	/**
	 * @return mixed
	 */
	public function getPriority()
	{
		if (!$this->priority) {
			return 50;
		}
		return $this->priority;
	}

	/**
	 * @param mixed $priority
	 */
	public function setPriority($priority)
	{
		$this->priority = $priority;
	}
}
