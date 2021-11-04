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
				if ($case->PriceType == 'Leasing') {
					echo "updating case: $case->VehicleId<br>";
					$this->import_case($case, $force);
					$ex_case = array_search($case->VehicleId, $existing_cases);

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
		$case = new bilinfo_Case($c->VehicleId);

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


		$case->setBilID($c->VehicleId);
		$case->setMake($c->Make);
		$case->setModel($c->Model);
		$case->setVariant($c->Variant);
		$case->setType($c->Type);
		$case->setMileage($c->Mileage);
		$case->setYear($c->Year);
		$case->setProductionmonth($c->ProductionMonth);
		$case->setColor($c->Color);
		$case->setAcceleration0To100($c->Acceleration0To100);
		$case->setGeartype($c->GearType);
		$case->setNumberOfDoors($c->NumberOfDoors);
		$case->setNumberOfAirbags($c->NumberOfAirbags);
		$case->setKmPerLiter($c->KmPerLiter);
		$case->setPropellant($c->Propellant);
		$case->setEffect($c->Effect);
		$case->setEffectinnm($c->EffectInNm);
		$case->setMotor($c->Motor);
		$case->setNumberOfGears($c->NumberOfGears);
		$case->setCylinders($c->Cylinders);
		$case->setTopspeed($c->TopSpeed);
		$case->setCashPrice($c->CashPrice);
		$case->setLeasingPrice($c->LeasingPrice);
		$case->setLeasingPeriod($c->LeasingDuration);
		$case->setLeasingType($c->LeasingType);
		$case->setLeasingDownPayment($c->LeasingDownPayment);
		$case->setLeasingResidualValue($c->LeasingResidualValue);
		$case->setComment($c->Comment);
		$case->setEquipmentlist($c->EquipmentList);
		$case->setPictures($c->Pictures);
		$case->setVideos($c->Video);
		$case->setHash($hash);

		$saved_id = $case->save();

		// var_dump($case);

		new bilinfo_Log('import_case', array('bilID' => $c->VehicleId, 'saved' => $saved_id));


		if ($saved_id) {
			$case->setPostID($saved_id);
			echo 'Setting Url: ';
			$this->getAPI()->set_property_url(get_the_permalink($saved_id), $case->getBilID());
			//$this->setCaseThumbnail($case, $c->primaryPhoto);
			//self::check_and_download_images($case, $c->urlPhotos, null, $force);
			//self::check_and_download_images($case, $c->urlDrawings, true, $force);
		}

		// foreach ($c->Pictures as $picture) {

		// 	$keys = parse_url($picture);
		// 	$path = explode("/", $keys['path']);
		// }


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

				'post_type' => 'biler',
				'posts_per_page' => -1,

			);

			// The Query
			$the_query = new WP_Query($args);
			$ex_cases = $the_query->posts;
			$ids = array();

			foreach ($ex_cases as $case) {
				$ids[$case->ID] = get_field('bilID', $case->ID);
			}

			$this->ex_cases_id = $ids;
		}

		return $this->ex_cases_id;
	}

	public static function check_and_download_images($case, $url_arr, $force = false)
	{


		$order = array();

		if (count($url_arr) <= 0) {
			return;
		}

		$attachements_arr = $case->get_case_attachments();


		foreach ($url_arr as $url) {

			if (strpos($url) === false) {

				$a_id = array_search(basename($url), $attachements_arr);

				if ($a_id) {

					$order[] = $a_id;
					unset($attachements_arr[$a_id]);
				} else {

					if ($force) {
						$img_id = self::save_remote_image($url, $case->getPostID());
					} else {
						$img_id = bilinfo_Media_Queue::add('photo', $url, $case->getPostID());
					}

					if ($img_id) {
						$order[] = $img_id;

						if ($force) {
							new bilinfo_Log('added_to_queue', array('photo', $url, $case->getPostID()));
						}
					} else {
						if ($force) {
							new bilinfo_Log('added_to_queue_error', array('photo', $url, $case->getPostID()));
						}
					}
				}
			}
		}
	}

	public static function check_and_download_single_image($case, $url)
	{

		$order = $case->getPictures();

		$attachements_arr = $case->get_case_attachments();


		return true;
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

		// $type = pathinfo($image);
		$type = wp_remote_retrieve_header($get, 'content-type');

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

		add_post_meta($attach_id, 'bilinfo-image', true);

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
