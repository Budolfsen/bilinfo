<?php

class bilinfo_Case
{
  private $postID;
  private $description;
  private $bilID;
  private $make;
  private $model;
  private $variant;
  private $type;
  private $mileage;
  private $year;
  private $productionMonth;
  private $color;
  private $acceleration0To100;
  private $gearType;
  private $payLoad;
  private $numberOfAirbags;
  private $kmPerLiter;
  private $propellant;
  private $effect;
  private $effectinNM;
  private $motor;
  private $numberOfGears;
  private $cylinders;
  private $topSpeed;
  private $cashPrice;
  private $leasingPrice;
  private $leasingPeriod;
  private $leasingType;
  private $leasingDownPayment;
  private $leasingResidualValue;
  private $comment;
  private $pictures;
  private $videos;
  private $hash;


  /**
   * bilinfo_Case constructor.
   *
   * @param $id "Post ID (int) or bilID (string)"
   *
   */
  public function __construct($id = null)
  {

    if (is_numeric($id)) {
      $this->setPostID($id);
    } else if (is_string($id)) {
      $this->setBilID($id);
      $this->setPostID($this->findPostId());
    }
  }


  public function fetch()
  {
    if (!$this->getPostID()) {
      return false;
    }

    $meta = get_post_meta($this->getPostID(), null, true);
    foreach ($meta as $key => $value) {
      $value = array_shift($value);
      //Go through the easy properties fast
      if (property_exists($this, $key)) {
        $funcName = "set" . ucfirst($key);
        $this->$funcName($value);
      }
    }

    // $this->setDescription(get_post_field('post_content', $this->getPostID()));
  }

  public function save()
  {


    $postarr = array(
      'ID'          => $this->getPostID(),
      'post_title'      => Bilinfo_Helpers::create_post_title($this->getMake(), $this->getModel()),
      'post_name'        => Bilinfo_Helpers::create_post_slug($this->getMake(), $this->getModel()),
      'post_author'    =>  1,
      'post_type'        =>  'biler',
      'post_status'      =>  'publish',
      'post_date'        =>  date('Y-m-d H:i:s', strtotime($this->getCreatedDate())),
      // 'post_content'      =>  $this->getDescription(),
      'meta_input'      =>  array(
        'bilID'            => $this->getBilID(),
        'createdDate'          => $this->getCreatedDate(),
        'make'          => $this->getMake(),
        'model'          => $this->getModel(),
        'variant'          => $this->getVariant(),
        'type'          => $this->getType(),
        'mileage'          => $this->getMileage(),
        'productionYear'          => $this->getYear(),
        'productionMonth'          => $this->getProductionmonth(),
        'color'          => $this->getColor(),
        'Acceleration0To100'          => $this->getAcceleration0To100(),
        'gearType'          => $this->getGeartype(),
        'payLoad'          => $this->getPayload(),
        'numberOfAirbags'          => $this->getNumberOfAirbags(),
        'KmPerLiter'          => $this->getKmPerLiter(),
        'propellant'          => $this->getPropellant(),
        'effect'          => $this->getEffect(),
        'effectinNM'          => $this->getEffectinnm(),
        'motor'          => $this->getMotor(),
        'NumberOfGears'          => $this->getNumberOfGears(),
        'cylinders'          => $this->getCylinders(),
        'topSpeed'          => $this->getTopspeed(),
        'cashPrice'          => $this->getCashPrice(),
        'leasingPrice'          => $this->getLeasingPrice(),
        'leasingPeriod'          => $this->getLeasingPeriod(),
        'leasingType'          => $this->getLeasingType(),
        'leasingDownPayment'          => $this->getLeasingDownPayment(),
        'leasingResidualValue'          => $this->getLeasingResidualValue(),
        'equipment'          => $this->getEquipmentlist(),
        'comment'          => $this->getComment(),
        'pictures'          => $this->getPictures(),
        'video'          => $this->getVideos(),
        'hash'                  => $this->getHash(),
        // '_yoast_wpseo_opengraph-image' => array($this->getPrimaryPhoto1000())
      ),
    );



    $id = wp_insert_post($postarr);

    return $id;
  }


  public function get_case_attachments($use_url = false)
  {
    $attachements = get_attached_media('image', $this->getPostID());
    $attachements_arr = array();

    foreach ($attachements as $att) {

      if ($use_url) {
        $attachements_arr[$att->ID] = wp_get_attachment_image_src($att->ID, 'full')[0];
      } else {
        $attachements_arr[$att->ID] = $att->post_title;
      }
    }

    return $attachements_arr;
  }

  public function delete()
  {

    if (!$this->getPostID()) {
      return false;
    }

    $this->deleteImages();

    $deleted_post = wp_delete_post($this->getPostID(), true);

    return $deleted_post;
  }

  public function findPostId()
  {
    if ($this->getPostID()) {
      return $this->getPostID();
    }

    if ($this->getBilID()) {

      $search = Bilinfo_Helpers::get_post_by_meta_value('biler', 'bilID', $this->getBilID());
      $id = (is_array($search) && count($search) == 1) ? $search[0]->ID : null;


      return $id;
    }
  }

  public function deleteImages()
  {
    if (!$this->getPostID()) {
      return false;
    }

    $attachments = get_children(array(
      'post_type' => 'attachment',
      'post_parent' => (int) $this->getPostID(),
      'numberposts' => -1
    ));

    if (count($attachments) > 0) {
      foreach ($attachments as $attachment) {
        wp_delete_attachment($attachment->ID, true);
      }
      return true;
    } else {
      return false;
    }
  }

  public function getOldHash()
  {
    if ($this->getPostID()) {
      return get_field('hash', $this->getPostID());
    }
    return false;
  }

  public function get_attachments($post_id, $use_url = false)
  {
    $attachements = get_attached_media('image', $post_id);

    $attachements_arr = array();

    foreach ($attachements as $att) {

      if ($use_url) {
        $attachements_arr[$att->ID] = wp_get_attachment_image_src($att->ID, 'full')[0];
      } else {
        $attachements_arr[$att->ID] = $att->post_title;
      }
    }

    return $attachements_arr;
  }

  /**
   * @return mixed
   */
  public function getPostID()
  {

    return $this->postID;
  }

  /**
   * @param mixed $postID
   */
  public function setPostID($postID)
  {

    $this->postID = $postID;
  }

  /**
   * @return mixed
   */
  public function getType()
  {

    return $this->type;
  }

  /**
   * @param mixed $postID
   */
  public function setType($type)
  {

    $this->type = $type;
  }

  /**
   * @return mixed
   */
  public function getDescription()
  {
    return $this->description;
  }

  /**
   * @param mixed $description
   */
  public function setDescription($description)
  {
    $this->description = $description;
  }

  /**
   * @return mixed
   */
  public function getBilID()
  {
    return $this->bilID;
  }

  /**
   * @param mixed $bilID
   */
  public function setBilID($bilID)
  {
    $this->bilID = $bilID;
  }

  /**
   * @return mixed
   */
  public function getKmPerLiter()
  {
    return $this->kmPerLiter;
  }

  /**
   * @param mixed $payLoad
   */
  public function setKmPerLiter($kmPerLiter)
  {
    $this->kmPerLiter = $kmPerLiter;
  }

  /**
   * @return mixed
   */
  public function getNumberOfAirbags()
  {
    return $this->numberOfAirbags;
  }

  /**
   * @param mixed $payLoad
   */
  public function setNumberOfAirbags($numberOfAirbags)
  {
    $this->numberOfAirbags = $numberOfAirbags;
  }

  /**
   * @return mixed
   */
  public function getPayload()
  {
    return $this->payLoad;
  }

  /**
   * @param mixed $payLoad
   */
  public function setPayload($payLoad)
  {
    $this->payLoad = $payLoad;
  }

  /**
   * @return mixed
   */
  public function getGeartype()
  {
    return $this->gearType;
  }

  /**
   * @param mixed $gearType
   */
  public function setGeartype($gearType)
  {
    $this->gearType = $gearType;
  }

  /**
   * @return mixed
   */
  public function getNumberOfGears()
  {
    return $this->numberOfGears;
  }

  /**
   * @param mixed $gearType
   */
  public function setNumberOfGears($numberOfGears)
  {
    $this->numberOfGears = $numberOfGears;
  }

  /**
   * @return mixed
   */
  public function getMake()
  {
    return $this->make;
  }

  /**
   * @param mixed $make
   */
  public function setMake($make)
  {
    $this->make = $make;
  }

  /**
   * @return mixed
   */
  public function getModel()
  {
    return $this->model;
  }

  /**
   * @param mixed $model
   */
  public function setModel($model)
  {
    $this->model = $model;
  }

  /**
   * @return mixed
   */
  public function getAcceleration0To100()
  {
    return $this->acceleration0To100;
  }

  /**
   * @param mixed $model
   */
  public function setAcceleration0To100($acceleration0To100)
  {
    $this->acceleration0To100 = $acceleration0To100;
  }

  /**
   * @return mixed
   */
  public function getColor()
  {
    return $this->color;
  }

  /**
   * @param mixed $model
   */
  public function setColor($color)
  {
    $this->color = $color;
  }

  /**
   * @return mixed
   */
  public function getVariant()
  {
    return $this->variant;
  }

  /**
   * @param mixed $variant
   */
  public function setVariant($variant)
  {
    $this->variant = $variant;
  }

  /**
   * @return mixed
   */
  public function getMileage()
  {
    return $this->mileage;
  }

  /**
   * @param mixed $mileage
   */
  public function setMileage($mileage)
  {
    $this->mileage = $mileage;
  }

  /**
   * @return mixed
   */
  public function getComment()
  {
    return $this->comment;
  }

  /**
   * @param mixed $comment
   */
  public function setComment($comment)
  {
    $this->comment = $comment;
  }

  /**
   * @return mixed
   */
  public function getCashPrice()
  {
    return $this->cashPrice;
  }

  /**
   * @param mixed $cashPrice
   */
  public function setCashPrice($cashPrice)
  {
    $this->cashPrice = $cashPrice;
  }
  /**
   * @return mixed
   */
  public function getLeasingPrice()
  {
    return $this->leasingPrice;
  }

  /**
   * @param mixed $leasingPrice
   */
  public function setLeasingPrice($leasingPrice)
  {
    $this->leasingPrice = $leasingPrice;
  }

  /**
   * @return mixed
   */
  public function getLeasingDuration()
  {
    return $this->leasingDuration;
  }

  /**
   * @param mixed $leasingDuration
   */
  public function setLeasingDuration($leasingDuration)
  {
    $this->leasingDuration = $leasingDuration;
  }

  /**
   * @return mixed
   */
  public function getLeasingType()
  {
    return $this->leasingType;
  }

  /**
   * @param mixed $leasingtype
   */
  public function setLeasingType($leasingType)
  {
    $this->leasingType = $leasingType;
  }

  /**
   * @return mixed
   */
  public function getLeasingPeriod()
  {
    return $this->leasingPeriod;
  }

  /**
   * @param mixed $leasingPeriod
   */
  public function setLeasingPeriod($leasingPeriod)
  {
    $this->leasingPeriod = $leasingPeriod;
  }

  /**
   * @return mixed
   */
  public function getLeasingDownPayment()
  {
    return $this->leasingDownPayment;
  }

  /**
   * @param mixed $leasingDownPayment
   */
  public function setLeasingDownPayment($leasingDownPayment)
  {
    $this->leasingDownPayment = $leasingDownPayment;
  }

  /**
   * @return mixed
   */
  public function getLeasingResidualValue()
  {
    return $this->leasingResidualValue;
  }

  /**
   * @param mixed $leasingResidualValue
   */
  public function setLeasingResidualValue($leasingResidualValue)
  {
    $this->leasingResidualValue = $leasingResidualValue;
  }

  /**
   * @return mixed
   */
  public function getPrice()
  {
    return $this->price;
  }

  /**
   * @param mixed $price
   */
  public function setPrice($price)
  {
    $this->price = $price;
  }


  /**
   * @return mixed
   */
  public function getEffect()
  {
    return $this->effect;
  }

  /**
   * @param mixed $effect
   */
  public function setEffect($effect)
  {
    $this->effect = $effect;
  }


  /**
   * @return mixed
   */
  public function getEffectinnm()
  {
    return $this->effectinNM;
  }

  /**
   * @param mixed $effectinnm
   */
  public function setEffectinnm($effectinNM)
  {
    $this->effectinNM = $effectinNM;
  }

  /**
   * @return mixed
   */
  public function getPropellant()
  {
    return $this->propellant;
  }

  /**
   * @param mixed $propellant
   */
  public function setPropellant($propellant)
  {
    $this->propellant = $propellant;
  }

  /**
   * @return mixed
   */
  public function getMotor()
  {
    return $this->motor;
  }

  /**
   * @param mixed $motor
   */
  public function setMotor($motor)
  {
    $this->motor = $motor;
  }


  /**
   * @return mixed
   */
  public function getCylinders()
  {
    return $this->cylinders;
  }

  /**
   * @param mixed $cylinders
   */
  public function setCylinders($cylinders)
  {
    $this->cylinders = $cylinders;
  }


  /**
   * @return mixed
   */
  public function getTopspeed()
  {
    return $this->topSpeed;
  }

  /**
   * @param mixed $topspeed
   */
  public function setTopspeed($topSpeed)
  {
    $this->topSpeed = $topSpeed;
  }


  /**
   * @return mixed
   */
  public function getYear()
  {
    return $this->year;
  }

  /**
   * @param mixed $productionyear
   */
  public function setYear($year)
  {
    $this->year = $year;
  }


  /**
   * @return mixed
   */
  public function getProductionmonth()
  {
    return $this->productionMonth;
  }

  /**
   * @param mixed $productionmonth
   */
  public function setProductionmonth($productionMonth)
  {
    $this->productionMonth = $productionMonth;
  }


  /**
   * @return mixed
   */
  public function getEquipmentlist()
  {
    return $this->equipmentlist;
  }

  /**
   * @param mixed $equipmentlist
   */
  public function setEquipmentlist($equipmentlist)
  {
    $this->equipmentlist = $equipmentlist;
  }

  /**
   * @return mixed
   */
  public function getCreatedDate()
  {
    if (!$this->createdDate) {
      return date('d-m-Y H:i:s', strtotime("now"));
    }
    return $this->createdDate;
  }

  /**
   * @param mixed $createdDate
   */
  public function setCreatedDate($createdDate)
  {
    $this->createdDate = $createdDate;
  }

  /**
   * @return mixed
   */
  public function getPictures()
  {
    return $this->pictures;
  }

  public function getUnserializedPhotos()
  {
    if (is_serialized($this->pictures)) {
      return unserialize($this->pictures);
    }
    if (is_array($this->pictures)) {
      return $this->pictures;
    } else {
      return array();
    }
  }


  /**
   * @param mixed $pictures
   */
  public function setPictures($pictures)
  {
    $this->pictures = $pictures;
  }


  /**
   * @return mixed
   */
  public function getVideos()
  {
    return $this->videos;
  }

  /**
   * @return mixed
   */
  public function getUnserializedVideos()
  {
    if (is_serialized($this->videos)) {
      return unserialize($this->videos);
    }
    if (is_array($this->videos)) {
      return $this->videos;
    } else {
      return array();
    }
  }


  /**
   * @param mixed $videos
   */
  public function setVideos($videos)
  {
    $this->videos = $videos;
  }

  /**
   * @return mixed
   */
  public function getHash()
  {
    return $this->hash;
  }

  /**
   * @param mixed $hash
   */
  public function setHash($hash)
  {
    $this->hash = $hash;
  }
}
