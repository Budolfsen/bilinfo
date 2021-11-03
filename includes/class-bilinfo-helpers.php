<?php

class Bilinfo_Helpers
{

	// public static function property_type_nice_name($type)
	// {

	// 	$names = array(
	// 		'Id' => __('Id', 'bilinfo'),
	// 		'Mileage' => __('Kilometer', 'bilinfo'),
	// 		'Year' => __('', 'bilinfo'),
	// 		'APARTMENT' => __('Ejerlejlighed', 'bilinfo'),
	// 		'TOWNHOUSE' => __('Rækkehus', 'bilinfo'),
	// 		'SHARED_APARTMENT' => __('Andelsbolig', 'bilinfo'),
	// 		'LAND_FOR_HOUSE' => __('Helårsgrund', 'bilinfo'),
	// 		'LAND_FOR_SUMMERHOUSE' => __('Fritidsgrund', 'bilinfo'),
	// 		'HOUSE_APARTMENT' => __('Villalejlighed', 'bilinfo'),
	// 		'FARM' => __('Landbrug', 'bilinfo'),
	// 		'ALLOTMENT_HOUSE' => __('Kolonihavehus', 'bilinfo'),
	// 		'LEISURETIME_FARM' => __('Fritidslandbrug', 'bilinfo'),
	// 		'RENTAL_PROPERTY' => __('Lejelejlighed', 'bilinfo'),
	// 		'OTHER' => __('Anden', 'bilinfo'),
	// 		'UNKNOWN' => __('Ukendt', 'bilinfo'),
	// 		'BUSINESS_UNIT_OTHER' => __('Andet erhverv', 'bilinfo'),
	// 		'BUSINESS' => __('Erhverv', 'bilinfo'),
	// 		'PRODUCTION' => __('Produktion', 'bilinfo'),
	// 		'STORAGE' => __('Lager', 'bilinfo'),
	// 		'PARKING' => __('Parkering/garage', 'bilinfo'),
	// 		'RETAIL' => __('Butik', 'bilinfo'),
	// 		'OFFICE' => __('Kontor', 'bilinfo'),
	// 		'OFFICE_HOTEL' => __('Kontorhotel', 'bilinfo'),
	// 		'HOTEL_RESTAURANT' => __('Hotel/restaurant', 'bilinfo'),
	// 		'BUSINESS_LOT' => __('Grund - erhverv', 'bilinfo'),
	// 		'BUSINESS_INVESTMENT_PROPERTY' => __('Investeringsejendom', 'bilinfo'),
	// 		'BUSINESS_RENTAL_PROPERTY' => __('Udlejningsejendom', 'bilinfo'),
	// 	);

	// 	if (isset($names[$type])) {
	// 		return $names[$type];
	// 	} else {
	// 		return $names['OTHER'];
	// 	}
	// }

	// public static function sale_type_nice_name($type)
	// {

	// 	$names = array(
	// 		'PRIVATESALE' => __('Salg', 'bilinfo'),
	// 		'PRIVATERENTAL' => __('Udlejning', 'bilinfo'),
	// 		'PRIVATEINTERNATIONALSALE' => __('Salg - Interational', 'bilinfo'),
	// 		'BUSINESSSALE' => __('Salg', 'bilinfo'),
	// 		'BUSINESSRENTAL' => __('Udlejning', 'bilinfo'),
	// 	);

	// 	if (isset($names[$type])) {
	// 		return $names[$type];
	// 	} else {
	// 		return $type;
	// 	}
	// }


	public static function get_date_unix($timestamp = false, $ms = false, $isGM = false)
	{

		if ($timestamp == false) {
			$timestamp = strtotime('today');
		}

		if ($ms) {
			$timestamp = (string) $timestamp . '000';
		}

		return $timestamp;
	}

	public static function isJson($string)
	{
		json_decode($string);
		return (json_last_error() == JSON_ERROR_NONE);
	}

	public static function convert_unix_to_datetime($timestamp, $ms = false, $isGM = false)
	{

		if ($ms === true) {

			$timestamp = (string) $timestamp;
			$timestamp = substr($timestamp, 0, strlen($timestamp) - 3);
		}


		return date('d-m-Y H:i', $timestamp);
	}

	public static function convert_unix_to_local_datetime($timestamp, $ms = false, $isGM = false)
	{

		if ($ms === true) {

			$timestamp = (string) $timestamp;
			$timestamp = substr($timestamp, 0, strlen($timestamp) - 3);
		}

		$local_time = new DateTime();
		$local_time->setTimestamp($timestamp);
		$local_time->setTimezone(new DateTimeZone("Europe/Copenhagen"));
		//$local_time = date_default_timezone_get();

		return $local_time->format('d-m-Y H:i');
	}

	public static function convert_unix_to_local_time_hours($timestamp, $ms = false)
	{

		if ($ms === true) {

			$timestamp = (string) $timestamp;
			$timestamp = substr($timestamp, 0, strlen($timestamp) - 3);
		}

		$local_time = new DateTime();
		$local_time->setTimestamp($timestamp);
		$local_time->setTimezone(new DateTimeZone("Europe/Copenhagen"));
		//$local_time = date_default_timezone_get();

		return $local_time->format('H:i');
	}

	public static function formatText($string)
	{

		$string = str_replace('u0022', '"', $string);

		return $string;
	}

	public static function create_post_title($make = '', $model = '')
	{

		$formatted_address = sprintf(
			'%s %s',
			!empty($make) ? $make : '',
			!empty($model) ? $model : '',
		);

		return $formatted_address;
	}

	public static function create_post_slug($make = '', $model = '')
	{


		$formatted_address = sprintf(
			'%s-%s',
			!empty($make) ? self::remove_special_chars($make) : '',
			!empty($model) ? self::remove_special_chars($model) : '',
		);

		return $formatted_address;
	}

	public static function post_without_wait($url)
	{
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_TIMEOUT, 1);
		curl_setopt($curl, CURLOPT_HEADER, 0);
		curl_setopt($curl,  CURLOPT_RETURNTRANSFER, false);
		curl_setopt($curl, CURLOPT_FORBID_REUSE, true);
		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 1);
		curl_setopt($curl, CURLOPT_DNS_CACHE_TIMEOUT, 10);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($curl, CURLOPT_FRESH_CONNECT, true);
		curl_exec($curl);
		curl_close($curl);
		return true;
	}

	public static function remove_special_chars($string)
	{
		$string = strtolower($string);
		$string = str_replace('ø', 'o', $string);
		$string = str_replace('æ', 'ae', $string);
		$string = str_replace('å', 'aa', $string);
		return iconv('UTF-8', 'ISO-8859-1//TRANSLIT//IGNORE', $string);
	}

	public static function get_post_by_meta_value($post_type, $meta_key, $meta_value, $posts_per_page = -1)
	{
		$post_where = function ($where) use ($meta_key, $meta_value) {
			global $wpdb;
			$where .= ' AND ID IN (SELECT post_id FROM ' . $wpdb->postmeta
				. ' WHERE meta_key = "' . $meta_key . '" AND meta_value = "' . $meta_value . '")';
			return $where;
		};
		add_filter('posts_where', $post_where);
		$args = array(
			'post_type' => $post_type,
			'posts_per_page' => $posts_per_page,
			'suppress_filters' => FALSE
		);
		$posts = get_posts($args);
		remove_filter('posts_where', $post_where);
		return $posts;
	}
}
