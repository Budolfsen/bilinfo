<?php

class bilinfo_Import
{

	private $api;

	/**
	 * bilinfo_Import constructor.
	 *
	 * @param $api
	 */
	public function __construct()
	{
		$this->setAPI(new bilinfo_API());
	}

	public function import_cases($force = false, $debug = true)
	{
		ob_start();
		$existing_cases = $this->get_existing_cases_ID();
		$api_cases = $this->getAPI()->get_cases();
		$limit = -1;
		$count = 1;

		if ($api_cases && count($api_cases) > 0) {
			new bilinfo_Log('import_started', $api_cases);
			foreach ($api_cases as $case) {
				echo "updating case: $case->caseKey<br>";
				$this->import_case($case, $force);
				$ex_case = array_search($case->caseKey, $existing_cases);
				if ($ex_case) {
					unset($existing_cases[$ex_case]);
				}
				$count++;
				if ($count === $limit) {
					echo 'Reached Limit.. Terminating';
					die();
				}
				echo '<br>';
			}

			if (count($existing_cases)) {

				foreach ($existing_cases as $id => $val) {
					$this->deleteCase($id);
				}
			}


			//$queue = new bilinfo_Media_Queue();
			//$queue->run();


			$c = ob_get_clean();

			if ($debug) {
				echo "<pre>$c</pre>";
			}
			echo 'done';
		} else {

			die('No cars to import!');
		}
	}


	private function deleteCase($id)
	{
		$case = new bilinfo_Case($id);
		return $case->delete();
	}

	private function import_case($c, $force)
	{
		$case = new bilinfo_Case($c->caseKey);

		$hash = hash('md5', json_encode($c));

		if ($case->getPostID() && $force == false) {

			if ($hash === $case->getOldHash()) {
				echo 'Hash values are the same, skipping<br>';
				return;
			}
		}

		foreach ($c as $key => $value) {
			//Go through the easy properties fast
			if (property_exists($case, $key)) {
				$funcName = "set" . ucfirst($key);
				$case->$funcName($value);
			}
		}

		$case->setDescription($c->description);
		$case->setRoadname($c->address->roadname);
		$case->setRoadnumber($c->address->roadnumber);
		$case->setFloor($c->address->floor);
		$case->setDoor($c->address->door);
		$case->setZipcode($c->address->zipcode);
		$case->setCity($c->address->city);
		$case->setAddress(bilinfo_Helpers::create_address($case->getRoadname(), $case->getRoadnumber(), $case->getFloor(), $case->getDoor()));
		$case->setPlacename($c->address->placename);
		$case->setPrimaryPhoto1000($c->primaryPhoto);
		$case->setRealtor(bilinfo_Realtor::findIdByEmail($c->realtorEmail));
		$case->setPhotos($c->urlPhotos);
		$case->setThumbnails($c->urlThumbnails);
		$case->setDrawings($c->urlDrawings);
		$case->setVideos($c->urlMovies);
		$case->setHash($hash);
		if (isset($c->documents)) {
			$case->setDocuments($c->documents);
		}

		$saved_id = $case->save();

		new bilinfo_Log('import_case', array('caseKey' => $c->caseKey, 'saved' => $saved_id));


		if ($saved_id) {
			$case->setPostID($saved_id);
			$this->set_taxonomies($case);
			echo 'Setting Url: ';
			$this->getAPI()->set_property_url(get_the_permalink($saved_id), $case->getCaseKey());
			//$this->setCaseThumbnail($case, $c->primaryPhoto);
			//self::check_and_download_images($case, $c->urlPhotos, null, $force);
			//self::check_and_download_images($case, $c->urlDrawings, true, $force);
		}
	}

	/*
	public function setCaseThumbnail($case, $photo_url){
		$thumb = get_the_post_thumbnail_url( $case->getPostID(), 'full');

		if(!$thumb || $this->thumb_has_changed($thumb, $photo_url)){
			echo 'change in thumb';
			if(strpos($photo_url, 'maps.googleapis.com') === false || str()){

				$img_id = $this->save_remote_image($photo_url, $case->getPostID());

				if($img_id){
					$old_thumb = get_post_thumbnail_id( $case->getPostID() );
					set_post_thumbnail( $case->getPostID(), $img_id );
				}else{

				}

			}

		}else{
			echo 'no change in thumb';
		}

	}*/

	public function setAPI($api)
	{
		$this->api = $api;
	}

	public function getAPI()
	{
		return $this->api;
	}

	/* Get all existing cases as post_id. Returns array */
	private function get_existing_cases_ID()
	{

		if (!isset($this->ex_cases_id)) {
			$args = array(

				'post_type' => 'sag',
				'posts_per_page' => -1,

			);

			// The Query
			$the_query = new WP_Query($args);
			$ex_cases = $the_query->posts;
			$ids = array();

			foreach ($ex_cases as $case) {

				$ids[$case->ID] = get_field('caseKey', $case->ID);
			}

			$this->ex_cases_id = $ids;
		}

		return $this->ex_cases_id;
	}

	private function set_taxonomies($case)
	{
		// Set taxonomies. Tax_input can't be used because of permissions.
		$saletype_ids = $this->set_sale_type_taxonomy($case->getStatus(), $case->getSaleType());
		wp_set_object_terms($case->getPostID(), $saletype_ids, 'saletype');
		$zipcodes = $this->set_zipcode_taxonomy($case->getZipcode());
		wp_set_object_terms($case->getPostID(), $zipcodes, 'zipcode');
		$types = $this->set_property_type_taxonomy($case->getPropertyType());
		wp_set_object_terms($case->getPostID(), $types, 'type');
		$offices = $this->set_office_taxonomy($case->getOfficeId());
		wp_set_object_terms($case->getPostID(), $offices, 'office');
	}

	private function set_property_type_taxonomy($type)
	{
		$title = $type;
		$id = term_exists($title, 'type');

		if (!$id) {
			$id = wp_insert_term($title, 'type');
		}

		$tax = array($title);

		return $tax;
	}

	private function set_office_taxonomy($officeId)
	{
		$title = (string) 'office-' . $officeId;
		$id = term_exists($title, 'office');
		if (!$id) {
			$id = wp_insert_term($title, 'office');
		}

		$tax = array($title);

		return $tax;
	}

	private function set_zipcode_taxonomy($zipcode)
	{
		$zip = (string) $zipcode;

		$id = term_exists($zip, 'zipcode');

		if (!$id) {
			$id = wp_insert_term($zip, 'zipcode');
		}

		$tax = array($zip);

		return $tax;
	}

	private function set_sale_type_taxonomy($status, $type)
	{

		if ($status === 'ACTIVE') {

			switch ($type) {

				case 'PRIVATESALE':

					$parent = term_exists('Privat', 'saletype', 0);

					if (!$parent) {
						$parent = wp_insert_term('Privat', 'saletype', 0);
					}

					$id = term_exists('Salg', 'saletype', $parent['term_taxonomy_id']);

					if (!$id) {
						$id = wp_insert_term('Salg', 'saletype', array('parent' => $parent['term_taxonomy_id']));
					}

					$tax = array(
						(int) $parent['term_taxonomy_id'],
						(int) $id['term_taxonomy_id'],
					);

					return $tax;

					break;
				case 'PRIVATERENTAL':

					$parent = term_exists('Privat', 'saletype', 0);

					if (!$parent) {
						$parent = wp_insert_term('Privat', 'saletype', 0);
					}

					$id = term_exists('Leje', 'saletype', $parent['term_taxonomy_id']);

					if (!$id) {
						$id = wp_insert_term('Leje', 'saletype', array('parent' => $parent['term_taxonomy_id']));
					}

					$tax = array(
						(int) $parent['term_taxonomy_id'],
						(int) $id['term_taxonomy_id'],
					);

					return $tax;

					break;
				case 'PRIVATEINTERNATIONALSALE':

					$parent = term_exists('Privat', 'saletype', 0);

					if (!$parent) {
						$parent = wp_insert_term('Privat', 'saletype', 0);
					}

					$id = term_exists('Salg International', 'saletype', $parent['term_taxonomy_id']);

					if (!$id) {
						$id = wp_insert_term('Salg International', 'saletype', array('parent' => $parent['term_taxonomy_id']));
					}

					$tax = array(
						(int) $parent['term_taxonomy_id'],
						(int) $id['term_taxonomy_id'],
					);

					return $tax;

					break;
				case 'BUSINESSSALE':

					$parent = term_exists('Erhverv', 'saletype', 0);

					if (!$parent) {
						$parent = wp_insert_term('Erhverv', 'saletype', 0);
					}

					$id = term_exists('Salg', 'saletype', $parent['term_taxonomy_id']);

					if (!$id) {
						$id = wp_insert_term('Salg', 'saletype', array('parent' => $parent['term_taxonomy_id']));
					}

					$tax = array(
						(int) $parent['term_taxonomy_id'],
						(int) $id['term_taxonomy_id'],
					);

					return $tax;

					break;
				case 'BUSINESSRENTAL':

					$parent = term_exists('Erhverv', 'saletype', 0);

					if (!$parent) {
						$parent = wp_insert_term('Erhverv', 'saletype', 0);
					}

					$id = term_exists('Leje', 'saletype', $parent['term_taxonomy_id']);

					if (!$id) {
						$id = wp_insert_term('Leje', 'saletype', array('parent' => $parent['term_taxonomy_id']));
					}

					$tax = array(
						(int) $parent['term_taxonomy_id'],
						(int) $id['term_taxonomy_id'],
					);

					return $tax;

					break;
			}
		} else if ($status === 'SOLD') {

			if (strpos($type, 'PRIVATE') !== false) {

				$parent = term_exists('Privat', 'saletype', 0);

				if (!$parent) {
					$parent = wp_insert_term('Privat', 'saletype', 0);
				}

				$id = term_exists('Solgt', 'saletype', $parent['term_taxonomy_id']);

				if (!$id) {
					$id = wp_insert_term('Solgt', 'saletype', array('parent' => $parent['term_taxonomy_id']));
				}

				$tax = array(
					(int) $parent['term_taxonomy_id'],
					(int) $id['term_taxonomy_id'],
				);

				return $tax;
			} else if (strpos($type, 'BUSINESS') !== false) {

				$parent = term_exists('Erhverv', 'saletype', 0);

				if (!$parent) {
					$parent = wp_insert_term('Erhverv', 'saletype', 0);
				}

				$id = term_exists('Solgt', 'saletype', $parent['term_taxonomy_id']);

				if (!$id) {
					$id = wp_insert_term('Solgt', 'saletype', array('parent' => $parent['term_taxonomy_id']));
				}

				$tax = array(
					(int) $parent['term_taxonomy_id'],
					(int) $id['term_taxonomy_id'],
				);

				return $tax;
			}
		}
	}

	private function thumb_has_changed($thumb = false, $url)
	{

		if (!$thumb) {
			return false;
		}
		var_dump(strtolower(pathinfo($thumb)['basename']));
		var_dump(strtolower(pathinfo($url)['basename']));
		return (strtolower(pathinfo($thumb)['basename']) <> strtolower(pathinfo($url)['basename']));
	}

	public static function check_and_download_images($case, $url_arr, $drawings = false, $force = false)
	{

		$order = array();

		if (count($url_arr) <= 0) {
			return;
		}

		$attachements_arr = $case->get_case_attachments();

		foreach ($url_arr as $url) {

			if (strpos($url, 'maps.googleapis.com') === false) {

				$a_id = array_search(basename($url), $attachements_arr);

				if ($a_id) {

					$order[] = $a_id;
					unset($attachements_arr[$a_id]);
				} else {

					if ($force) {
						$img_id = self::save_remote_image($url, $case->getPostID());
					} else {
						$img_id = bilinfo_Media_Queue::add((!$drawings) ? 'photo' : 'drawing', $url, $case->getPostID());
					}

					if ($img_id) {
						$order[] = $img_id;

						if ($force) {
							new bilinfo_Log('added_to_queue', array((!$drawings) ? 'photo' : 'drawing', $url, $case->getPostID()));
						}
					} else {
						if ($force) {
							new bilinfo_Log('added_to_queue_error', array((!$drawings) ? 'photo' : 'drawing', $url, $case->getPostID()));
						}
					}
				}
			}
		}
		if ($force) {
			if ($drawings) {
				$case->setDrawings($order);
				return $case->save();
			} else {
				$case->setPhotos($order);
				return $case->save();
			}

			if (count($attachements_arr) > 0) {
				echo 'Der er billeder til overs.. Sletter<br>';
				echo 'Featured image id: ' . get_post_thumbnail_id($case->getPostID());
				foreach ($attachements_arr as $id => $name) {

					if (get_post_thumbnail_id($case->getPostID()) != $id) {
						if (self::deleteImage($id)) {
							echo 'Billede ' . $name . ' blev slettet<br>';
						}
					} else {
						echo 'Billede er sat som featured.. Skipping<br>';
					}
				}
			}
		}
	}

	public static function check_and_download_single_image($case, $url, $drawings = false)
	{

		$order = (!$drawings) ? $case->getPhotos() : $case->getDrawings();

		$attachements_arr = $case->get_case_attachments();

		if (strpos($url, 'maps.googleapis.com') === false && strpos($url, 'streetview') === false) {

			$a_id = array_search(basename($url), $attachements_arr);

			if ($a_id) {

				$order[] = $a_id;
				unset($attachements_arr[$a_id]);
				return true;
			} else {

				$img_id = self::save_remote_image($url, $case->getPostID());

				if ($img_id) {
					$order[] = $img_id;

					if ($drawings) {
						$case->setDrawings($order);
						return $case->save();
					} else {
						$case->setPhotos($order);
						return $case->save();
					}
				} else {
					return false;
				}
			}
		} else {
			return true;
		}
	}


	private static function save_remote_image($url, $post_id)
	{
		set_time_limit(30);
		/*
		How To Locally Mirror Remote Images With WordPress
		Source: http://forrst.com/posts/Locally_Mirror_Remote_Images_With_WordPress-XSE
		*/
		// URL of the image you want to mirror. Girl in pink underwear for instance.
		$image = $url;
		// GET request
		$get = wp_remote_get($image);
		// Check content-type (eg image/png), might want to test & exit if applicable

		$type = pathinfo($image);

		if (!isset($type['extension']) || (strtolower($type['extension']) != 'jpg' && strtolower($type['extension']) != 'jpeg' && strtolower($type['extension']) !== 'png')) {
			new bilinfo_Log('download_fail_wrong_extension', array($image));
			echo 'wrong extension<br>';
			return false;
		}

		$mimetype = array(

			'png' => 'image/png',
			'jpg' => 'image/jpeg',
			'jpeg' => 'image/jpeg'

		);

		// Mirror this image in your upload dir
		$mirror = wp_upload_bits(basename($image), '', wp_remote_retrieve_body($get));
		/* Sample output for mirror:
		array(3) {
		["file"]=>
		string(64) "E:\home\planetozh\wordpress/wp-content/uploads/2010/09/Hq4QA.jpg"
		["url"]=>
		string(63) "http://127.0.0.1/wordpress/wp-content/uploads/2010/09/Hq4QA.jpg"
		["error"]=>
		bool(false)
		}
		*/
		// Attachment options
		$attachment = array(
			'post_title' => basename($image),
			'post_mime_type' => $mimetype[strtolower($type['extension'])],
			'post_content'   => '',
			'post_status'    => 'inherit',
			'post_author'	 => 0
		);
		// Add the image to your media library (won't be attached to a post)
		$attach_id =  wp_insert_attachment($attachment, $mirror['file'], $post_id);
		// Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
		require_once(ABSPATH . 'wp-admin/includes/image.php');

		// Generate the metadata for the attachment, and update the database record.
		$attach_data = wp_generate_attachment_metadata($attach_id, get_attached_file($attach_id));
		wp_update_attachment_metadata($attach_id, $attach_data);

		add_post_meta($attach_id, 'flexya-image', true);

		if ($attach_id) {
			new bilinfo_Log('download_success', array($image));
		} else {
			new bilinfo_Log('download_fail', array($image, $attach_id));
		}

		return $attach_id;
	}

	private static function deleteImage($id)
	{

		if (isset($id) && is_int($id)) {
			return wp_delete_attachment($id, true);
		}
	}
}
