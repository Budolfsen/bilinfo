<?php
	
class Flexyapress_Helpers{

	public static function property_type_nice_name($type){

		$names = array(
			'HOUSE' => __('Villa', 'flexyapress'),
			'SUMMERHOUSE' => __('Fritidsbolig', 'flexyapress'),
			'RECREATIONAL' => __('Fritidsbolig', 'flexyapress'),
			'APARTMENT' => __('Ejerlejlighed', 'flexyapress'),
			'TOWNHOUSE' => __('Rækkehus', 'flexyapress'),
			'SHARED_APARTMENT' => __('Andelsbolig', 'flexyapress'),
			'LAND_FOR_HOUSE' => __('Helårsgrund', 'flexyapress'),
			'LAND_FOR_SUMMERHOUSE' => __('Fritidsgrund', 'flexyapress'),
			'HOUSE_APARTMENT' => __('Villalejlighed', 'flexyapress'),
			'FARM' => __('Landbrug', 'flexyapress'),
			'ALLOTMENT_HOUSE' => __('Kolonihavehus', 'flexyapress'),
			'LEISURETIME_FARM' => __('Fritidslandbrug', 'flexyapress'),
			'RENTAL_PROPERTY' => __('Lejelejlighed', 'flexyapress'),
			'OTHER' => __('Anden', 'flexyapress'),
			'UNKNOWN' => __('Ukendt', 'flexyapress'),
			'BUSINESS_UNIT_OTHER' => __('Andet erhverv', 'flexyapress'),
			'BUSINESS' => __('Erhverv', 'flexyapress'),
			'PRODUCTION' => __('Produktion', 'flexyapress'),
			'STORAGE' => __('Lager', 'flexyapress'),
			'PARKING' => __('Parkering/garage', 'flexyapress'),
			'RETAIL' => __('Butik', 'flexyapress'),
			'OFFICE' => __('Kontor', 'flexyapress'),
			'OFFICE_HOTEL' => __('Kontorhotel', 'flexyapress'),
			'HOTEL_RESTAURANT' => __('Hotel/restaurant', 'flexyapress'),
			'BUSINESS_LOT' => __('Grund - erhverv', 'flexyapress'),
			'BUSINESS_INVESTMENT_PROPERTY' => __('Investeringsejendom', 'flexyapress'),
			'BUSINESS_RENTAL_PROPERTY' => __('Udlejningsejendom', 'flexyapress'),
		);
		
		if(isset($names[$type])){
			return $names[$type];
		}else{
			return $names['OTHER'];
		}
		
	}

	public static function sale_type_nice_name($type){

		$names = array(
			'PRIVATESALE' => __('Salg', 'flexyapress'),
			'PRIVATERENTAL' => __('Udlejning', 'flexyapress'),
			'PRIVATEINTERNATIONALSALE' => __('Salg - Interational', 'flexyapress'),
			'BUSINESSSALE' => __('Salg', 'flexyapress'),
			'BUSINESSRENTAL' => __('Udlejning', 'flexyapress'),
		);

		if(isset($names[$type])){
			return $names[$type];
		}else{
			return $type;
		}

	}
	
	
	public static function get_date_unix($timestamp = false ,$ms = false, $isGM = false){
		
		if($timestamp == false){
			$timestamp = strtotime('today');
		}
				
		if($ms){
			$timestamp = (string) $timestamp . '000';
		}
		
		return $timestamp;
		
	}
	
	public static function isJson($string) {
	 json_decode($string);
	 return (json_last_error() == JSON_ERROR_NONE);
	}
	
	public static function convert_unix_to_datetime($timestamp, $ms = false, $isGM = false){
	
		if($ms === true){
			
			$timestamp = (string) $timestamp;
			$timestamp = substr($timestamp,0,strlen($timestamp)-3);
			
		}
		
		
		return date('d-m-Y H:i', $timestamp);
		
	}

	public static function convert_unix_to_local_datetime($timestamp, $ms = false, $isGM = false){

		if($ms === true){

			$timestamp = (string) $timestamp;
			$timestamp = substr($timestamp,0,strlen($timestamp)-3);

		}

		$local_time = new DateTime();
		$local_time->setTimestamp($timestamp);
		$local_time->setTimezone ( new DateTimeZone("Europe/Copenhagen") );
		//$local_time = date_default_timezone_get();

		return $local_time->format('d-m-Y H:i');

	}

	public static function convert_unix_to_local_time_hours($timestamp, $ms = false){

		if($ms === true){

			$timestamp = (string) $timestamp;
			$timestamp = substr($timestamp,0,strlen($timestamp)-3);

		}

		$local_time = new DateTime();
		$local_time->setTimestamp($timestamp);
		$local_time->setTimezone ( new DateTimeZone("Europe/Copenhagen") );
		//$local_time = date_default_timezone_get();

		return $local_time->format('H:i');

	}
	
	public static function formatText($string){
		
		$string = str_replace('u0022', '"', $string);
		
		return $string;
		
	}

	public static function create_post_title($roadname = '', $roadnumber = '', $floor = '', $door = '', $zipcode = '', $city = ''){

		$formatted_address = sprintf( '%s %s%s%s, %s %s',
			! empty( $roadname ) ? $roadname : '',
			! empty( $roadnumber ) ? $roadnumber : '',
			! empty( $floor ) ? ', ' . strtolower( $floor ) . '.' : '',
			! empty( $door ) ? ' ' . strtoupper( $door ) : '',
			! empty( $zipcode ) ? $zipcode : '',
			! empty( $city ) ? $city : ''
		);

		return $formatted_address;

	}

	public static function create_address($roadname = '', $roadnumber = '', $floor = '', $door = ''){

		$formatted_address = sprintf( '%s %s%s%s',
			! empty( $roadname ) ? $roadname : '',
			! empty( $roadnumber ) ? $roadnumber : '',
			! empty( $floor ) ? ', ' . strtolower( $floor ) . '.' : '',
			! empty( $door ) ? ' ' . strtoupper( $door ) : ''
		);

		return $formatted_address;

	}

	public static function create_zipcode($zipcode = '', $city = ''){

		$formatted_address = sprintf( '%s %s',
			! empty( $zipcode ) ? $zipcode : '',
			! empty( $city ) ? $city : ''
		);

		return $formatted_address;

	}

	public static function create_post_slug($roadname = '', $roadnumber = '', $floor = '', $door = '', $zipcode = '', $city = ''){


		$formatted_address = sprintf( '%s-%s-%s-%s%s%s',
			! empty($city ) ? self::remove_special_chars($city) : '',
			! empty( $zipcode ) ? self::remove_special_chars($zipcode) : '',
			! empty( $roadname ) ? self::remove_special_chars($roadname) : '',
			! empty( $roadnumber ) ? self::remove_special_chars($roadnumber) : '',
			! empty( $floor ) ? '-' . strtolower( $floor ) : '',
			! empty( $door ) ? '-' . strtolower( $door ) : ''
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

	public static function remove_special_chars($string){
		$string = strtolower($string);
		$string = str_replace('ø', 'o', $string);
		$string = str_replace('æ', 'ae', $string);
		$string = str_replace('å', 'aa', $string);
		return iconv('UTF-8', 'ISO-8859-1//TRANSLIT//IGNORE', $string);
	}

	public static function get_post_by_meta_value($post_type, $meta_key, $meta_value, $posts_per_page = -1 ) {
		$post_where = function ($where) use ( $meta_key, $meta_value ) {
			global $wpdb;
			$where .= ' AND ID IN (SELECT post_id FROM ' . $wpdb->postmeta
			          . ' WHERE meta_key = "' . $meta_key .'" AND meta_value = "' . $meta_value . '")';
			return $where;
		};
		add_filter( 'posts_where', $post_where );
		$args = array(
			'post_type' => $post_type,
			'posts_per_page' => $posts_per_page,
			'suppress_filters' => FALSE
		);
		$posts = get_posts( $args );
		remove_filter( 'posts_where' , $post_where );
		return $posts;
	}


}