<?php

// class bilinfo_Case
// {

// 	private $postID;
// 	private $caseKey;
// 	private $caseNumber;
// 	private $description;
// 	private $description1;
// 	private $description2;
// 	private $description3;
// 	private $status;
// 	private $reserved;
// 	private $propertyType;
// 	private $propertyClass;
// 	private $publishedDate;
// 	private $soldDate;
// 	private $realtor;
// 	private $realtorEmail;
// 	private $realtorName;
// 	private $realtorPhone;
// 	private $address;
// 	private $addressFreetext;
// 	private $floor;
// 	private $door;
// 	private $zipcode;
// 	private $city;
// 	private $placename;
// 	private $municipality;
// 	private $latitude;
// 	private $longitude;
// 	private $sizeArea;
// 	private $sizeAreaTotal;
// 	private $sizeLand;
// 	private $sizeLandHa;
// 	private $sizeBasement;
// 	private $sizeOtherbuildingsTotal;
// 	private $sizePatio;
// 	private $sizeCarport;
// 	private $sizeGarage;
// 	private $sizeCommercial;
// 	private $numberRooms;
// 	private $numberBedrooms;
// 	private $numberBathrooms;
// 	private $numberLivingRooms;
// 	private $numberFloors;
// 	private $title;
// 	private $teaser;
// 	private $tag;
// 	private $keywords;
// 	private $price;
// 	private $downPayment;
// 	private $monthlyOwnerExpenses;
// 	private $monthlyNetPayment;
// 	private $monthlyGrossPayment;
// 	private $priceReductionPercent;
// 	private $priceReductionDate;
// 	private $primaryPhoto;
// 	private $primaryPhoto1000;
// 	private $photos;
// 	private $thumbnails;
// 	private $drawings;
// 	private $videos;
// 	private $documents;
// 	private $constructionYear;
// 	private $reconstructionYear;
// 	private $energyBrand;
// 	private $heatingInstallation;
// 	private $heatingInstallationSuppl;
// 	private $daysForSale;
// 	private $attachments;
// 	private $openHouseActive;
// 	private $openhouseSignupRequired;
// 	private $openHouseDate;
// 	private $roadname;
// 	private $roadnumber;
// 	private $content;
// 	private $saleType;
// 	private $officeId;
// 	private $imageOrder;
// 	private $hash;

// 	/**
// 	 * bilinfo_Case constructor.
// 	 *
// 	 * @param $id "Post ID (int) or Casekey (string)"
// 	 *
// 	 */
// 	public function __construct($id = null)
// 	{

// 		if (is_numeric($id)) {
// 			$this->setPostID($id);
// 		} else if (is_string($id)) {
// 			$this->setCaseKey($id);
// 			$this->setPostID($this->findPostId());
// 		}
// 	}

// 	public function fetch()
// 	{
// 		if (!$this->getPostID()) {
// 			return false;
// 		}

// 		$meta = get_post_meta($this->getPostID(), null, true);
// 		foreach ($meta as $key => $value) {
// 			$value = array_shift($value);
// 			//Go through the easy properties fast
// 			if (property_exists($this, $key)) {
// 				$funcName = "set" . ucfirst($key);
// 				$this->$funcName($value);
// 			}
// 		}

// 		$this->setDescription(get_post_field('post_content', $this->getPostID()));
// 	}

// 	public function save()
// 	{


// 		$postarr = array(

// 			'ID'			    => $this->getPostID(),
// 			'post_title'	    => bilinfo_Helpers::create_post_title($this->getRoadname(), $this->getRoadnumber(), $this->getFloor(), $this->getDoor(), $this->getZipcode(), $this->getCity()),
// 			'post_name'		    => bilinfo_Helpers::create_post_slug($this->getRoadname(), $this->getRoadnumber(), $this->getFloor(), $this->getDoor(), $this->getZipcode(), $this->getCity()),
// 			'post_author'		=>	1,
// 			'post_type'		    =>	'sag',
// 			'post_status'	    =>	'publish',
// 			'post_date'		    =>	date('Y-m-d H:i:s', strtotime($this->getPublishedDate())),
// 			'post_content'      =>  $this->getDescription(),
// 			'meta_input'	    =>	array(
// 				'caseKey'	            => $this->getCaseKey(),
// 				'caseNumber'            => $this->getCaseNumber(),
// 				'status'	            => $this->getStatus(),
// 				'reserved'		        => $this->getReserved(),
// 				'publishedDate'	        => $this->getPublishedDate(),
// 				'publishedDateEpoch'	=> strtotime($this->getPublishedDate()),
// 				'soldDate'	            => $this->getSoldDate(),
// 				'realtor'               => $this->getRealtor(),
// 				'realtorName'           => $this->getRealtorName(),
// 				'realtorEmail'           => $this->getRealtorEmail(),
// 				'realtorPhone'           => $this->getRealtorPhone(),
// 				'roadname'		        => $this->getRoadname(),
// 				'roadnumber'		    => $this->getRoadnumber(),
// 				'addressFreetext'	    => $this->getAddressFreetext(),
// 				'address'	            => $this->getAddress(),
// 				'floor'                 => $this->getFloor(),
// 				'door'                  => $this->getDoor(),
// 				'zipcode'               => $this->getZipcode(),
// 				'city'		            => $this->getCity(),
// 				'placename'             => $this->getPlacename(),
// 				'municipality'          => $this->getMunicipality(),
// 				'latitude'              => $this->getLatitude(),
// 				'longitude'             => $this->getLongitude(),
// 				'sizeArea'	            => $this->getSizeArea(),
// 				'sizeAreaTotal'         => $this->getSizeAreaTotal(),
// 				'sizeLand'	        => $this->getSizeLand(),
// 				'sizeLandHa'        => $this->getSizeLandHa(),
// 				'sizeBasement'	    => $this->getSizeBasement(),
// 				'sizeOtherbuildingsTotal' => $this->getSizeOtherbuildingsTotal(),
// 				'sizePatio'             => $this->getSizePatio(),
// 				'sizeCarport'           => $this->getSizeCarport(),
// 				'sizeGarage'            => $this->getSizeGarage(),
// 				'sizeCommercial'        => $this->getSizeCommercial(),
// 				'numberRooms'           => $this->getNumberRooms(),
// 				'numberBedrooms'        => $this->getNumberBedrooms(),
// 				'numberBathrooms'       => $this->getNumberBathrooms(),
// 				'numberLivingRooms'     => $this->getNumberLivingRooms(),
// 				'numberFloors'          => $this->getNumberFloors(),
// 				'title'                 => $this->getTitle(),
// 				'teaser'                => $this->getTeaser(),
// 				'tag'                   => $this->getTag(),
// 				'price'	                => $this->getPrice(),
// 				'downPayment'           => $this->getDownPayment(),
// 				'monthlyOwnerExpenses'	=> $this->getMonthlyOwnerExpenses(),
// 				'monthlyNetPayment'     => $this->getMonthlyNetPayment(),
// 				'monthlyGrossPayment'   => $this->getMonthlyGrossPayment(),
// 				'priceReductionPercent' => $this->getPriceReductionPercent(),
// 				'priceReductionDate'    => $this->getPriceReductionDate(),
// 				'primaryPhoto'          => $this->getPrimaryPhoto(),
// 				'primaryPhoto1000'      => $this->getPrimaryPhoto1000(),
// 				'photos'                => $this->getPhotos(),
// 				'thumbnails'            => $this->getThumbnails(),
// 				'drawings'              => $this->getDrawings(),
// 				'videos'                => $this->getVideos(),
// 				'documents'             => $this->getDocuments(),
// 				'constructionYear'	    => $this->getConstructionYear(),
// 				'reconstructionYear'    => $this->getReconstructionYear(),
// 				'energyBrand'           => $this->getEnergyBrand(),
// 				'heatingInstallation'   => $this->getHeatingInstallation(),
// 				'heatingInstallationSuppl' => $this->getHeatingInstallationSuppl(),
// 				'daysForSale'           => $this->getDaysForSale(),
// 				'openHouseActive'       => $this->getOpenHouseActive(),
// 				'openhouseSignupRequired' => $this->getOpenhouseSignupRequired(),
// 				'openHouseDate'         => $this->getOpenHouseDate(),
// 				'saleType'              => $this->getSaleType(),
// 				'propertyType'          => $this->getPropertyType(),
// 				'propertyClass'          => $this->getPropertyClass(),
// 				'description1'          => $this->getDescription1(),
// 				'description2'          => $this->getDescription2(),
// 				'description3'          => $this->getDescription3(),
// 				'imageOrder'            => $this->getImageOrder(),
// 				'hash'                  => $this->getHash(),
// 				'_yoast_wpseo_opengraph-image' => array($this->getPrimaryPhoto1000())
// 			),
// 		);

// 		$id = wp_insert_post($postarr);

// 		return $id;
// 	}

// 	public function get_case_attachments($use_url = false)
// 	{
// 		$attachements = get_attached_media('image', $this->getPostID());
// 		$attachements_arr = array();

// 		foreach ($attachements as $att) {

// 			if ($use_url) {
// 				$attachements_arr[$att->ID] = wp_get_attachment_image_src($att->ID, 'full')[0];
// 			} else {
// 				$attachements_arr[$att->ID] = $att->post_title;
// 			}
// 		}

// 		return $attachements_arr;
// 	}

// 	public function delete()
// 	{

// 		if (!$this->getPostID()) {
// 			return false;
// 		}

// 		$this->deleteImages();

// 		$deleted_post = wp_delete_post($this->getPostID(), true);

// 		return $deleted_post;
// 	}

// 	public function findPostId()
// 	{

// 		if ($this->getPostID()) {
// 			return $this->getPostID();
// 		}

// 		if ($this->getCaseKey()) {

// 			$search = bilinfo_Helpers::get_post_by_meta_value('sag', 'caseKey', $this->getCaseKey());
// 			$id = (is_array($search) && count($search) == 1) ? $search[0]->ID : null;
// 			return $id;
// 		}
// 	}

// 	public function deleteImages()
// 	{
// 		if (!$this->getPostID()) {
// 			return false;
// 		}

// 		$attachments = get_children(array(
// 			'post_type' => 'attachment',
// 			'post_parent' => (int) $this->getPostID(),
// 			'numberposts' => -1
// 		));

// 		if (count($attachments) > 0) {
// 			foreach ($attachments as $attachment) {
// 				wp_delete_attachment($attachment->ID, true);
// 			}
// 			return true;
// 		} else {
// 			return false;
// 		}
// 	}

// 	public function getOldHash()
// 	{
// 		if ($this->getPostID()) {
// 			return get_field('hash', $this->getPostID());
// 		}
// 		return false;
// 	}

// 	public function get_attachments($post_id, $use_url = false)
// 	{
// 		$attachements = get_attached_media('image', $post_id);

// 		$attachements_arr = array();

// 		foreach ($attachements as $att) {

// 			if ($use_url) {
// 				$attachements_arr[$att->ID] = wp_get_attachment_image_src($att->ID, 'full')[0];
// 			} else {
// 				$attachements_arr[$att->ID] = $att->post_title;
// 			}
// 		}

// 		return $attachements_arr;
// 	}

// 	/**
// 	 * @return mixed
// 	 */
// 	public function getPostID()
// 	{
// 		return $this->postID;
// 	}

// 	/**
// 	 * @param mixed $postID
// 	 */
// 	public function setPostID($postID)
// 	{
// 		$this->postID = $postID;
// 	}

// 	/**
// 	 * @return mixed
// 	 */
// 	public function getCaseKey()
// 	{
// 		return $this->caseKey;
// 	}

// 	/**
// 	 * @param mixed $caseKey
// 	 */
// 	public function setCaseKey($caseKey)
// 	{
// 		$this->caseKey = $caseKey;
// 	}

// 	/**
// 	 * @return mixed
// 	 */
// 	public function getCaseNumber()
// 	{
// 		return $this->caseNumber;
// 	}

// 	/**
// 	 * @param mixed $caseNumber
// 	 */
// 	public function setCaseNumber($caseNumber)
// 	{
// 		$this->caseNumber = $caseNumber;
// 	}

// 	/**
// 	 * @return mixed
// 	 */
// 	public function getStatus()
// 	{
// 		return $this->status;
// 	}

// 	/**
// 	 * @param mixed $status
// 	 */
// 	public function setStatus($status)
// 	{
// 		$this->status = $status;
// 	}

// 	/**
// 	 * @return mixed
// 	 */
// 	public function getReserved()
// 	{
// 		return $this->reserved;
// 	}

// 	/**
// 	 * @param mixed $reserved
// 	 */
// 	public function setReserved($reserved)
// 	{
// 		$this->reserved = $reserved;
// 	}

// 	public function isActive()
// 	{
// 		return ($this->getStatus() == 'ACTIVE');
// 	}

// 	public function printFlag($is_single = false)
// 	{

// 		/*
// 		 * Flag Priority:
// 		 * 1: Solgt
// 		 * 2: Købsaftale underskrevet
// 		 * 3: Custom
// 		 * 4: Ny pris
// 		 * 5: Nyhed
// 		 */

// 		$c = '';
// 		if ($this->getStatus() == 'SOLD') {
// 			$c .= '<div class="flag flag-sold">';
// 			$c .= ($this->getSaleType() == 'PRIVATERENTAL') ? __('Udlejet', 'bilinfo') : __('Solgt', 'bilinfo');
// 			$c .= '</div>';
// 		} else if ($this->getReserved()) {
// 			$c .= '<div class="flag flag-reserved">';
// 			$c .= __('Købsaftale underskrevet', 'bilinfo');
// 			$c .= '</div>';
// 		} else if ($this->getTag()) {
// 			$c .= '<div class="flag flag-custom">';
// 			$c .= $this->getTag();
// 			$c .= '</div>';
// 		} else if ($this->getPriceReductionDate() && $this->getPriceReductionDate() <> $this->getPublishedDate() && strtotime($this->getPriceReductionDate()) > strtotime('-14 days')) {
// 			$c .= '<div class="flag flag-newprice">';
// 			$c .= __('Ny pris', 'bilinfo');
// 			$c .= '</div>';
// 		} else if (strtotime($this->getPublishedDate()) > strtotime('-14 days')) {
// 			$c .= '<div class="flag flag-new">';
// 			$c .= __('Nyhed', 'bilinfo');
// 			$c .= '</div>';
// 		}

// 		return $c;
// 	}

// 	public function printSpecItem($title, $value)
// 	{

// 		if (method_exists($this, $value)) {
// 			$value = $this->$value();
// 		} else {
// 			return;
// 		}
// 		if (!$value) {
// 			return;
// 		}
// 		ob_start();
// 		echo '<div class="spec-item">';
// 		echo '<div class="spec-title">' . __($title, 'bilinfo') . '</div>';
// 		echo '<div class="spec-value">' . $value . '</div>';
// 		echo '</div>';
// 		$c = ob_get_clean();
// 		return $c;
// 	}

// 	public function printOpenHouseFlag($is_single = false)
// 	{

// 		$c = '';
// 		if ($this->isActive() && $this->getOpenHouseActive()) {
// 			$c .= '<div class="flag flag-openhouse">';
// 			$c .= '<div class="open-house-title">' . __('Åbent hus', 'bilinfo') . '</div>';
// 			$c .= '<div class="open-house-date">' . $this->getOpenHouseDate() . '</div>';
// 			if ($is_single && $this->getOpenhouseSignupRequired()) {
// 				$c .= '<div class="open-house-signup btn btn-grey-border" data-toggle="modal" data-target="#order-openhouse-signup-modal">Tilmeld</div>';
// 			}
// 			$c .= '</div>';
// 		}

// 		return $c;
// 	}

// 	/**
// 	 * @return mixed
// 	 */
// 	public function getPropertyType()
// 	{
// 		return $this->propertyType;
// 	}

// 	/**
// 	 * @param mixed $propertyType
// 	 */
// 	public function setPropertyType($propertyType)
// 	{
// 		$this->propertyType = $propertyType;
// 	}

// 	/**
// 	 * @return mixed
// 	 */
// 	public function getPropertyClass()
// 	{
// 		return $this->propertyClass;
// 	}

// 	/**
// 	 * @param mixed $propertyClass
// 	 */
// 	public function setPropertyClass($propertyClass)
// 	{
// 		$this->propertyClass = $propertyClass;
// 	}


// 	/**
// 	 * @return mixed
// 	 */
// 	public function getPublishedDate()
// 	{
// 		if (!$this->publishedDate) {
// 			return date('d-m-Y H:i:s', strtotime("now"));
// 		}
// 		return $this->publishedDate;
// 	}

// 	/**
// 	 * @param mixed $publishedDate
// 	 */
// 	public function setPublishedDate($publishedDate)
// 	{
// 		$this->publishedDate = $publishedDate;
// 	}

// 	/**
// 	 * @return mixed
// 	 */
// 	public function getSoldDate()
// 	{
// 		return $this->soldDate;
// 	}

// 	/**
// 	 * @param mixed $soldDate
// 	 */
// 	public function setSoldDate($soldDate)
// 	{
// 		$this->soldDate = $soldDate;
// 	}

// 	/**
// 	 * @return mixed
// 	 */
// 	public function getRealtorEmail()
// 	{
// 		return $this->realtorEmail;
// 	}

// 	/**
// 	 * @param mixed $realtorEmail
// 	 */
// 	public function setRealtorEmail($realtorEmail)
// 	{
// 		$this->realtorEmail = $realtorEmail;
// 	}

// 	/**
// 	 * @return mixed
// 	 */
// 	public function getRealtorName()
// 	{
// 		return $this->realtorName;
// 	}

// 	/**
// 	 * @param mixed $realtorName
// 	 */
// 	public function setRealtorName($realtorName)
// 	{
// 		$this->realtorName = $realtorName;
// 	}

// 	/**
// 	 * @return mixed
// 	 */
// 	public function getRealtorPhone()
// 	{
// 		return $this->realtorPhone;
// 	}

// 	/**
// 	 * @param mixed $realtorPhone
// 	 */
// 	public function setRealtorPhone($realtorPhone)
// 	{
// 		$this->realtorPhone = $realtorPhone;
// 	}


// 	/**
// 	 * @return mixed
// 	 */
// 	public function getRealtor()
// 	{
// 		return $this->realtor;
// 	}

// 	public function getRealtorInfo()
// 	{

// 		$realtor = new bilinfo_Realtor();
// 		$id = $this->getRealtor();

// 		if (!$id) {
// 			$id = bilinfo_Realtor::findIdByEmail($this->getRealtorEmail());
// 		}
// 		if ($id) {
// 			$realtor = new bilinfo_Realtor($id);
// 		}

// 		if (!$realtor->getId()) {
// 			$realtor->setRealtorName($this->getRealtorName());
// 			$realtor->setRealtorEmail($this->getRealtorEmail());
// 			$realtor->setRealtorPhone($this->getRealtorPhone());
// 		} else {
// 			$realtor->fetch();
// 		}

// 		return $realtor;
// 	}

// 	/**
// 	 * @param mixed $realtor
// 	 */
// 	public function setRealtor($realtor)
// 	{
// 		$this->realtor = $realtor;
// 	}

// 	/**
// 	 * @return mixed
// 	 */
// 	public function getAddress()
// 	{
// 		return $this->address;
// 	}

// 	/**
// 	 * @return mixed
// 	 */
// 	public function getFullAddress()
// 	{
// 		return $this->getAddress() . '<br>' . $this->getZipcode() . ' ' . $this->getCity();
// 	}

// 	/**
// 	 * @return mixed
// 	 */
// 	public function getMapAddress()
// 	{
// 		return $this->getRoadname() . ' ' . $this->getRoadnumber() . ' ' . $this->getZipcode() . ' ' . $this->getCity();
// 	}

// 	/**
// 	 * @param mixed $address
// 	 */
// 	public function setAddress($address)
// 	{
// 		$this->address = $address;
// 	}

// 	/**
// 	 * @return mixed
// 	 */
// 	public function getAddressFreetext()
// 	{
// 		return $this->addressFreetext;
// 	}

// 	/**
// 	 * @param mixed $addressFreetext
// 	 */
// 	public function setAddressFreetext($addressFreetext)
// 	{
// 		$this->addressFreetext = $addressFreetext;
// 	}

// 	/**
// 	 * @return mixed
// 	 */
// 	public function getFloor()
// 	{
// 		return $this->floor;
// 	}

// 	/**
// 	 * @param mixed $floor
// 	 */
// 	public function setFloor($floor)
// 	{
// 		$this->floor = $floor;
// 	}

// 	/**
// 	 * @return mixed
// 	 */
// 	public function getDoor()
// 	{
// 		return $this->door;
// 	}

// 	/**
// 	 * @param mixed $door
// 	 */
// 	public function setDoor($door)
// 	{
// 		$this->door = $door;
// 	}

// 	/**
// 	 * @return mixed
// 	 */
// 	public function getZipcode()
// 	{
// 		return $this->zipcode;
// 	}

// 	/**
// 	 * @param mixed $zipcode
// 	 */
// 	public function setZipcode($zipcode)
// 	{
// 		$this->zipcode = $zipcode;
// 	}

// 	/**
// 	 * @return mixed
// 	 */
// 	public function getCity()
// 	{
// 		return $this->city;
// 	}

// 	/**
// 	 * @param mixed $city
// 	 */
// 	public function setCity($city)
// 	{
// 		$this->city = $city;
// 	}

// 	/**
// 	 * @return mixed
// 	 */
// 	public function getPlacename()
// 	{
// 		return $this->placename;
// 	}

// 	/**
// 	 * @param mixed $placename
// 	 */
// 	public function setPlacename($placename)
// 	{
// 		$this->placename = $placename;
// 	}

// 	/**
// 	 * @return mixed
// 	 */
// 	public function getMunicipality()
// 	{
// 		return $this->municipality;
// 	}

// 	/**
// 	 * @param mixed $municipality
// 	 */
// 	public function setMunicipality($municipality)
// 	{
// 		$this->municipality = $municipality;
// 	}

// 	/**
// 	 * @return mixed
// 	 */
// 	public function getLatitude()
// 	{
// 		return $this->latitude;
// 	}

// 	/**
// 	 * @param mixed $latitude
// 	 */
// 	public function setLatitude($latitude)
// 	{
// 		$this->latitude = $latitude;
// 	}

// 	/**
// 	 * @return mixed
// 	 */
// 	public function getLongitude()
// 	{
// 		return $this->longitude;
// 	}

// 	/**
// 	 * @param mixed $longitude
// 	 */
// 	public function setLongitude($longitude)
// 	{
// 		$this->longitude = $longitude;
// 	}

// 	/**
// 	 * @return mixed
// 	 */
// 	public function getSizeArea()
// 	{
// 		return $this->sizeArea;
// 	}

// 	/**
// 	 * @return mixed
// 	 */
// 	public function getPrettySizeArea()
// 	{
// 		if (!$this->sizeArea) {
// 			return;
// 		}
// 		return $this->sizeArea . ' m<sup>2</sup>';
// 	}

// 	/**
// 	 * @param mixed $sizeArea
// 	 */
// 	public function setSizeArea($sizeArea)
// 	{
// 		$this->sizeArea = $sizeArea;
// 	}

// 	/**
// 	 * @return mixed
// 	 */
// 	public function getSizeAreaTotal()
// 	{
// 		return $this->sizeAreaTotal;
// 	}

// 	/**
// 	 * @param mixed $sizeAreaTotal
// 	 */
// 	public function setSizeAreaTotal($sizeAreaTotal)
// 	{
// 		$this->sizeAreaTotal = $sizeAreaTotal;
// 	}

// 	/**
// 	 * @return mixed
// 	 */
// 	public function getSizeLand()
// 	{
// 		return $this->sizeLand;
// 	}

// 	/**
// 	 * @return mixed
// 	 */
// 	public function getPrettySizeLand()
// 	{

// 		if (!$this->sizeLand) {
// 			return;
// 		}
// 		return $this->sizeLand . ' m<sup>2</sup>';
// 	}

// 	/**
// 	 * @param mixed $sizeLand
// 	 */
// 	public function setSizeLand($sizeLand)
// 	{
// 		$this->sizeLand = $sizeLand;
// 	}

// 	/**
// 	 * @return mixed
// 	 */
// 	public function getSizeLandHa()
// 	{
// 		return $this->sizeLandHa;
// 	}

// 	/**
// 	 * @return mixed
// 	 */
// 	public function getPrettySizeLandHa()
// 	{
// 		if (!$this->sizeLandHa) {
// 			return;
// 		}
// 		return $this->sizeLandHa . ' ha';
// 	}

// 	/**
// 	 * @param mixed $sizeLandHa
// 	 */
// 	public function setSizeLandHa($sizeLandHa)
// 	{
// 		$this->sizeLandHa = $sizeLandHa;
// 	}


// 	/**
// 	 * @return mixed
// 	 */
// 	public function getSizeBasement()
// 	{
// 		return $this->sizeBasement;
// 	}

// 	/**
// 	 * @return mixed
// 	 */
// 	public function getPrettySizeBasement()
// 	{
// 		if (!$this->sizeBasement) {
// 			return;
// 		}
// 		return $this->sizeBasement . ' m<sup>2</sup>';
// 	}

// 	/**
// 	 * @param mixed $sizeBasement
// 	 */
// 	public function setSizeBasement($sizeBasement)
// 	{
// 		$this->sizeBasement = $sizeBasement;
// 	}

// 	/**
// 	 * @return mixed
// 	 */
// 	public function getSizeOtherbuildingsTotal()
// 	{
// 		return $this->sizeOtherbuildingsTotal;
// 	}

// 	/**
// 	 * @return mixed
// 	 */
// 	public function getPrettyOtherbuildingsTotal()
// 	{
// 		if (!$this->sizeOtherbuildingsTotal) {
// 			return;
// 		}
// 		return $this->sizeOtherbuildingsTotal . ' m<sup>2</sup>';
// 	}

// 	/**
// 	 * @param mixed $sizeOtherbuildingsTotal
// 	 */
// 	public function setSizeOtherbuildingsTotal($sizeOtherbuildingsTotal)
// 	{
// 		$this->sizeOtherbuildingsTotal = $sizeOtherbuildingsTotal;
// 	}

// 	/**
// 	 * @return mixed
// 	 */
// 	public function getSizePatio()
// 	{
// 		return $this->sizePatio;
// 	}

// 	/**
// 	 * @return mixed
// 	 */
// 	public function getPrettySizePatio()
// 	{
// 		if (!$this->sizePatio) {
// 			return;
// 		}
// 		return $this->sizePatio . ' m<sup>2</sup>';
// 	}


// 	/**
// 	 * @param mixed $sizePatio
// 	 */
// 	public function setSizePatio($sizePatio)
// 	{
// 		$this->sizePatio = $sizePatio;
// 	}

// 	/**
// 	 * @return mixed
// 	 */
// 	public function getSizeCarport()
// 	{
// 		return $this->sizeCarport;
// 	}

// 	/**
// 	 * @return mixed
// 	 */
// 	public function getPrettySizeCarport()
// 	{
// 		if (!$this->sizeCarport) {
// 			return;
// 		}
// 		return $this->sizeCarport . ' m<sup>2</sup>';
// 	}

// 	/**
// 	 * @param mixed $sizeCarport
// 	 */
// 	public function setSizeCarport($sizeCarport)
// 	{
// 		$this->sizeCarport = $sizeCarport;
// 	}

// 	/**
// 	 * @return mixed
// 	 */
// 	public function getSizeGarage()
// 	{
// 		return $this->sizeGarage;
// 	}

// 	/**
// 	 * @return mixed
// 	 */
// 	public function getPrettySizeGarage()
// 	{
// 		if (!$this->sizeGarage) {
// 			return;
// 		}
// 		return $this->sizeGarage . ' m<sup>2</sup>';
// 	}

// 	/**
// 	 * @param mixed $sizeGarage
// 	 */
// 	public function setSizeGarage($sizeGarage)
// 	{
// 		$this->sizeGarage = $sizeGarage;
// 	}

// 	/**
// 	 * @return mixed
// 	 */
// 	public function getSizeCommercial()
// 	{
// 		return $this->sizeCommercial;
// 	}

// 	/**
// 	 * @return mixed
// 	 */
// 	public function getPrettySizeCommercial()
// 	{
// 		if (!$this->sizeCommercial) {
// 			return;
// 		}
// 		return $this->sizeCommercial . ' m<sup>2</sup>';
// 	}

// 	/**
// 	 * @param mixed $sizeCommercial
// 	 */
// 	public function setSizeCommercial($sizeCommercial)
// 	{
// 		$this->sizeCommercial = $sizeCommercial;
// 	}

// 	/**
// 	 * @return mixed
// 	 */
// 	public function getNumberRooms()
// 	{
// 		return $this->numberRooms;
// 	}

// 	/**
// 	 * @param mixed $numberRooms
// 	 */
// 	public function setNumberRooms($numberRooms)
// 	{
// 		$this->numberRooms = $numberRooms;
// 	}

// 	/**
// 	 * @return mixed
// 	 */
// 	public function getNumberBedrooms()
// 	{
// 		return $this->numberBedrooms;
// 	}

// 	/**
// 	 * @param mixed $numberBedrooms
// 	 */
// 	public function setNumberBedrooms($numberBedrooms)
// 	{
// 		$this->numberBedrooms = $numberBedrooms;
// 	}

// 	/**
// 	 * @return mixed
// 	 */
// 	public function getNumberBathrooms()
// 	{
// 		return $this->numberBathrooms;
// 	}

// 	/**
// 	 * @param mixed $numberBathrooms
// 	 */
// 	public function setNumberBathrooms($numberBathrooms)
// 	{
// 		$this->numberBathrooms = $numberBathrooms;
// 	}

// 	/**
// 	 * @return mixed
// 	 */
// 	public function getNumberLivingRooms()
// 	{
// 		return $this->numberLivingRooms;
// 	}

// 	/**
// 	 * @param mixed $numberLivingRooms
// 	 */
// 	public function setNumberLivingRooms($numberLivingRooms)
// 	{
// 		$this->numberLivingRooms = $numberLivingRooms;
// 	}

// 	/**
// 	 * @return mixed
// 	 */
// 	public function getNumberFloors()
// 	{
// 		return $this->numberFloors;
// 	}

// 	/**
// 	 * @param mixed $numberFloors
// 	 */
// 	public function setNumberFloors($numberFloors)
// 	{
// 		$this->numberFloors = $numberFloors;
// 	}

// 	/**
// 	 * @return mixed
// 	 */
// 	public function getTitle()
// 	{
// 		return $this->title;
// 	}

// 	/**
// 	 * @param mixed $title
// 	 */
// 	public function setTitle($title)
// 	{
// 		$this->title = $title;
// 	}

// 	/**
// 	 * @return mixed
// 	 */
// 	public function getTeaser()
// 	{
// 		return $this->teaser;
// 	}

// 	/**
// 	 * @param mixed $teaser
// 	 */
// 	public function setTeaser($teaser)
// 	{
// 		$this->teaser = $teaser;
// 	}

// 	/**
// 	 * @return mixed
// 	 */
// 	public function getTag()
// 	{
// 		return $this->tag;
// 	}

// 	/**
// 	 * @param mixed $tag
// 	 */
// 	public function setTag($tag)
// 	{
// 		$this->tag = $tag;
// 	}

// 	/**
// 	 * @return mixed
// 	 */
// 	public function getKeywords()
// 	{
// 		return $this->keywords;
// 	}

// 	/**
// 	 * @param mixed $keywords
// 	 */
// 	public function setKeywords($keywords)
// 	{
// 		$this->keywords = $keywords;
// 	}

// 	/**
// 	 * @return mixed
// 	 */
// 	public function getPrice()
// 	{
// 		return $this->price;
// 	}

// 	/**
// 	 * @return string
// 	 */
// 	public function getPrettyPrice()
// 	{
// 		if (!$this->price) {
// 			return;
// 		}
// 		return number_format($this->price, 0, ',', '.');
// 	}

// 	/**
// 	 * @param mixed $price
// 	 */
// 	public function setPrice($price)
// 	{
// 		$this->price = $price;
// 	}

// 	/**
// 	 * @return mixed
// 	 */
// 	public function getDownPayment()
// 	{
// 		return $this->downPayment;
// 	}

// 	/**
// 	 * @return mixed
// 	 */
// 	public function getPrettyDownPayment()
// 	{
// 		if (!$this->downPayment) {
// 			return;
// 		}
// 		return number_format($this->downPayment, 0, ',', '.');
// 	}

// 	/**
// 	 * @param mixed $downPayment
// 	 */
// 	public function setDownPayment($downPayment)
// 	{
// 		$this->downPayment = $downPayment;
// 	}

// 	/**
// 	 * @return mixed
// 	 */
// 	public function getMonthlyOwnerExpenses()
// 	{
// 		return $this->monthlyOwnerExpenses;
// 	}

// 	/**
// 	 * @return mixed
// 	 */
// 	public function getPrettyMonthlyOwnerExpenses()
// 	{
// 		if (!$this->monthlyOwnerExpenses) {
// 			return;
// 		}
// 		return number_format($this->monthlyOwnerExpenses, 0, ',', '.');
// 	}

// 	/**
// 	 * @return mixed
// 	 */
// 	public function getPrettyGrossNettoMonthlyOwnerExpenses()
// 	{
// 		if (!$this->monthlyNetPayment || !$this->monthlyGrossPayment) {
// 			return;
// 		}
// 		return number_format($this->monthlyGrossPayment, 0, ',', '.') . ' / ' . number_format($this->monthlyNetPayment, 0, ',', '.');
// 	}


// 	/**
// 	 * @param mixed $monthlyOwnerExpenses
// 	 */
// 	public function setMonthlyOwnerExpenses($monthlyOwnerExpenses)
// 	{
// 		$this->monthlyOwnerExpenses = $monthlyOwnerExpenses;
// 	}

// 	/**
// 	 * @return mixed
// 	 */
// 	public function getMonthlyNetPayment()
// 	{
// 		return $this->monthlyNetPayment;
// 	}

// 	/**
// 	 * @param mixed $monthlyNetPayment
// 	 */
// 	public function setMonthlyNetPayment($monthlyNetPayment)
// 	{
// 		$this->monthlyNetPayment = $monthlyNetPayment;
// 	}

// 	/**
// 	 * @return mixed
// 	 */
// 	public function getMonthlyGrossPayment()
// 	{
// 		return $this->monthlyGrossPayment;
// 	}

// 	/**
// 	 * @param mixed $monthlyGrossPayment
// 	 */
// 	public function setMonthlyGrossPayment($monthlyGrossPayment)
// 	{
// 		$this->monthlyGrossPayment = $monthlyGrossPayment;
// 	}

// 	/**
// 	 * @return mixed
// 	 */
// 	public function getPriceReductionPercent()
// 	{
// 		return $this->priceReductionPercent;
// 	}

// 	/**
// 	 * @param mixed $priceReductionPercent
// 	 */
// 	public function setPriceReductionPercent($priceReductionPercent)
// 	{
// 		$this->priceReductionPercent = $priceReductionPercent;
// 	}

// 	/**
// 	 * @return mixed
// 	 */
// 	public function getPriceReductionDate()
// 	{
// 		return $this->priceReductionDate;
// 	}

// 	/**
// 	 * @param mixed $priceReductionDate
// 	 */
// 	public function setPriceReductionDate($priceReductionDate)
// 	{
// 		$this->priceReductionDate = $priceReductionDate;
// 	}

// 	/**
// 	 * @return mixed
// 	 */
// 	public function getPrimaryPhoto()
// 	{
// 		return $this->primaryPhoto;
// 	}

// 	/**
// 	 * @param mixed $primaryPhoto
// 	 */
// 	public function setPrimaryPhoto($primaryPhoto)
// 	{

// 		$primaryPhoto = str_replace('/1920/', '/500/', $primaryPhoto);
// 		$primaryPhoto = (empty($primaryPhoto) || strpos($primaryPhoto, 'streetview') > -1) ?  get_field('placeholder_billede', 'options')['sizes']['large'] : $primaryPhoto;
// 		$this->primaryPhoto = $primaryPhoto;
// 	}

// 	/**
// 	 * @return mixed
// 	 */
// 	public function getPrimaryPhoto1000()
// 	{
// 		return $this->primaryPhoto1000;
// 	}

// 	/**
// 	 * @param mixed $primaryPhoto
// 	 */
// 	public function setPrimaryPhoto1000($primaryPhoto)
// 	{

// 		$primaryPhoto = str_replace('/1920/', '/1000/', $primaryPhoto);
// 		$primaryPhoto = (empty($primaryPhoto) || strpos($primaryPhoto, 'streetview') > -1) ?  get_field('placeholder_billede', 'options')['sizes']['large'] : $primaryPhoto;

// 		$this->primaryPhoto1000 = $primaryPhoto;
// 	}

// 	/**
// 	 * @return mixed
// 	 */
// 	public function getPhotos()
// 	{
// 		return $this->photos;
// 	}

// 	/**
// 	 * @return mixed
// 	 */
// 	public function getUnserializedPhotos()
// 	{
// 		if (is_serialized($this->photos)) {
// 			return unserialize($this->photos);
// 		}
// 		if (is_array($this->photos)) {
// 			return $this->photos;
// 		} else {
// 			return array();
// 		}
// 	}


// 	/**
// 	 * @param mixed $photos
// 	 */
// 	public function setPhotos($photos)
// 	{
// 		$this->photos = $photos;
// 	}

// 	/**
// 	 * @return mixed
// 	 */
// 	public function getThumbnails()
// 	{
// 		return $this->thumbnails;
// 	}


// 	/**
// 	 * @return mixed
// 	 */
// 	public function getUnserializedThumbnails()
// 	{
// 		if (is_serialized($this->thumbnails)) {
// 			return unserialize($this->thumbnails);
// 		}
// 		if (is_array($this->thumbnails)) {
// 			return $this->thumbnails;
// 		} else {
// 			return array();
// 		}
// 	}


// 	/**
// 	 * @param mixed $thumbnails
// 	 */
// 	public function setThumbnails($thumbnails)
// 	{
// 		$this->thumbnails = $thumbnails;
// 	}



// 	/**
// 	 * @return mixed
// 	 */
// 	public function getDrawings()
// 	{
// 		return $this->drawings;
// 	}

// 	/**
// 	 * @return mixed
// 	 */
// 	public function getUnserializedDrawings()
// 	{
// 		if (is_serialized($this->drawings)) {
// 			return unserialize($this->drawings);
// 		}
// 		if (is_array($this->drawings)) {
// 			return $this->drawings;
// 		} else {
// 			return array();
// 		}
// 	}


// 	/**
// 	 * @param mixed $drawings
// 	 */
// 	public function setDrawings($drawings)
// 	{
// 		$this->drawings = $drawings;
// 	}

// 	/**
// 	 * @return mixed
// 	 */
// 	public function getVideos()
// 	{
// 		return $this->videos;
// 	}

// 	/**
// 	 * @return mixed
// 	 */
// 	public function getUnserializedVideos()
// 	{
// 		if (is_serialized($this->videos)) {
// 			return unserialize($this->videos);
// 		}
// 		if (is_array($this->videos)) {
// 			return $this->videos;
// 		} else {
// 			return array();
// 		}
// 	}


// 	/**
// 	 * @param mixed $videos
// 	 */
// 	public function setVideos($videos)
// 	{
// 		$this->videos = $videos;
// 	}

// 	/**
// 	 * @return mixed
// 	 */
// 	public function getDocuments()
// 	{
// 		return $this->documents;
// 	}

// 	/**
// 	 * @param mixed $documents
// 	 */
// 	public function setDocuments($documents)
// 	{
// 		$this->documents = $documents;
// 	}

// 	/**
// 	 * @return mixed
// 	 */
// 	public function getConstructionYear()
// 	{
// 		return $this->constructionYear;
// 	}

// 	/**
// 	 * @return mixed
// 	 */
// 	public function getPrettyConstructionYear()
// 	{
// 		if ($this->getReconstructionYear()) {
// 			return $this->getConstructionYear() . '/' . $this->getReconstructionYear();
// 		} else {
// 		}
// 		return $this->getConstructionYear();
// 	}

// 	/**
// 	 * @param mixed $constructionYear
// 	 */
// 	public function setConstructionYear($constructionYear)
// 	{
// 		$this->constructionYear = $constructionYear;
// 	}

// 	/**
// 	 * @return mixed
// 	 */
// 	public function getReconstructionYear()
// 	{
// 		return $this->reconstructionYear;
// 	}

// 	/**
// 	 * @param mixed $reconstructionYear
// 	 */
// 	public function setReconstructionYear($reconstructionYear)
// 	{
// 		$this->reconstructionYear = $reconstructionYear;
// 	}

// 	/**
// 	 * @return mixed
// 	 */
// 	public function getEnergyBrand()
// 	{
// 		return $this->energyBrand;
// 	}

// 	/**
// 	 * @return mixed
// 	 */
// 	public function getPrettyEnergyBrand()
// 	{
// 		return ($this->energyBrand) ?: __('Ikke oplyst', 'bilinfo');
// 	}

// 	/**
// 	 * @param mixed $energyBrand
// 	 */
// 	public function setEnergyBrand($energyBrand)
// 	{
// 		$this->energyBrand = $energyBrand;
// 	}

// 	/**
// 	 * @return mixed
// 	 */
// 	public function getHeatingInstallation()
// 	{
// 		return $this->heatingInstallation;
// 	}

// 	/**
// 	 * @param mixed $heatingInstallation
// 	 */
// 	public function setHeatingInstallation($heatingInstallation)
// 	{
// 		$this->heatingInstallation = $heatingInstallation;
// 	}

// 	/**
// 	 * @return mixed
// 	 */
// 	public function getHeatingInstallationSuppl()
// 	{
// 		return $this->heatingInstallationSuppl;
// 	}

// 	/**
// 	 * @param mixed $heatingInstallationSuppl
// 	 */
// 	public function setHeatingInstallationSuppl($heatingInstallationSuppl)
// 	{
// 		$this->heatingInstallationSuppl = $heatingInstallationSuppl;
// 	}

// 	/**
// 	 * @return mixed
// 	 */
// 	public function getDaysForSale()
// 	{
// 		return $this->daysForSale;
// 	}

// 	/**
// 	 * @param mixed $daysForSale
// 	 */
// 	public function setDaysForSale($daysForSale)
// 	{
// 		$this->daysForSale = $daysForSale;
// 	}

// 	/**
// 	 * @return mixed
// 	 */
// 	public function getAttachments()
// 	{
// 		return $this->attachments;
// 	}

// 	/**
// 	 * @param mixed $attachments
// 	 */
// 	public function setAttachments($attachments)
// 	{
// 		$this->attachments = $attachments;
// 	}

// 	/**
// 	 * @return mixed
// 	 */
// 	public function getOpenHouseActive()
// 	{
// 		return $this->openHouseActive;
// 	}

// 	/**
// 	 * @param mixed $openHouseActive
// 	 */
// 	public function setOpenHouseActive($openHouseActive)
// 	{
// 		$this->openHouseActive = $openHouseActive;
// 	}

// 	/**
// 	 * @return mixed
// 	 */
// 	public function getOpenhouseSignupRequired()
// 	{
// 		return $this->openhouseSignupRequired;
// 	}

// 	/**
// 	 * @param mixed $openhouseSignupRequired
// 	 */
// 	public function setOpenhouseSignupRequired($openhouseSignupRequired)
// 	{
// 		$this->openhouseSignupRequired = $openhouseSignupRequired;
// 	}

// 	/**
// 	 * @return mixed
// 	 */
// 	public function getOpenHouseDate()
// 	{
// 		return $this->openHouseDate;
// 	}

// 	/**
// 	 * @param mixed $openHouseDate
// 	 */
// 	public function setOpenHouseDate($openHouseDate)
// 	{
// 		if (is_array($openHouseDate) && count($openHouseDate) > 0) {
// 			$openHouseDate = $openHouseDate[0];
// 		}
// 		$this->openHouseDate = $openHouseDate;
// 	}

// 	/**
// 	 * @return mixed
// 	 */
// 	public function getRoadname()
// 	{
// 		return $this->roadname;
// 	}

// 	/**
// 	 * @param mixed $roadname
// 	 */
// 	public function setRoadname($roadname)
// 	{
// 		$this->roadname = $roadname;
// 	}

// 	/**
// 	 * @return mixed
// 	 */
// 	public function getRoadnumber()
// 	{
// 		return $this->roadnumber;
// 	}

// 	/**
// 	 * @param mixed $roadnumber
// 	 */
// 	public function setRoadnumber($roadnumber)
// 	{
// 		$this->roadnumber = $roadnumber;
// 	}

// 	/**
// 	 * @return mixed
// 	 */
// 	public function getContent()
// 	{
// 		return $this->content;
// 	}

// 	/**
// 	 * @param mixed $content
// 	 */
// 	public function setContent($content)
// 	{
// 		$this->content = $content;
// 	}

// 	/**
// 	 * @return mixed
// 	 */
// 	public function getSaleType()
// 	{
// 		return $this->saleType;
// 	}

// 	/**
// 	 * @return mixed
// 	 */
// 	public function getPrettySaleType()
// 	{
// 		if (!$this->saleType) {
// 			return;
// 		}
// 		return bilinfo_Helpers::sale_type_nice_name($this->saleType);
// 	}

// 	/**
// 	 * @param mixed $saleType
// 	 */
// 	public function setSaleType($saleType)
// 	{
// 		$this->saleType = $saleType;
// 	}

// 	/**
// 	 * @return mixed
// 	 */
// 	public function getOfficeId()
// 	{
// 		return $this->officeId;
// 	}

// 	/**
// 	 * @param mixed $officeId
// 	 */
// 	public function setOfficeId($officeId)
// 	{
// 		$this->officeId = $officeId;
// 	}

// 	/**
// 	 * @return mixed
// 	 */
// 	public function getDescription()
// 	{
// 		return $this->description;
// 	}

// 	/**
// 	 * @param mixed $description
// 	 */
// 	public function setDescription($description)
// 	{
// 		$this->description = $description;
// 	}

// 	/**
// 	 * @return mixed
// 	 */
// 	public function getDescription1()
// 	{
// 		return $this->description1;
// 	}

// 	/**
// 	 * @param mixed $description1
// 	 */
// 	public function setDescription1($description1)
// 	{
// 		$this->description1 = $description1;
// 	}

// 	/**
// 	 * @return mixed
// 	 */
// 	public function getDescription2()
// 	{
// 		return $this->description2;
// 	}

// 	/**
// 	 * @param mixed $description2
// 	 */
// 	public function setDescription2($description2)
// 	{
// 		$this->description2 = $description2;
// 	}

// 	/**
// 	 * @return mixed
// 	 */
// 	public function getDescription3()
// 	{
// 		return $this->description3;
// 	}

// 	/**
// 	 * @param mixed $description3
// 	 */
// 	public function setDescription3($description3)
// 	{
// 		$this->description3 = $description3;
// 	}


// 	/**
// 	 * @return mixed
// 	 */
// 	public function getImageOrder()
// 	{
// 		return $this->imageOrder;
// 	}

// 	/**
// 	 * @param mixed $imageOrder
// 	 */
// 	public function setImageOrder($imageOrder)
// 	{
// 		$this->imageOrder = $imageOrder;
// 	}

// 	/**
// 	 * @return mixed
// 	 */
// 	public function getHash()
// 	{
// 		return $this->hash;
// 	}

// 	/**
// 	 * @param mixed $hash
// 	 */
// 	public function setHash($hash)
// 	{
// 		$this->hash = $hash;
// 	}
// }
