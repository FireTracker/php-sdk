<?php
namespace FireTracker;
use Unirest\Request;

/**
 *
 */
class Tracker
{
  public static $_FIRE_LEVEL_DEBUG = 100;
  public static $_FIRE_LEVEL_INFO = 200;
  public static $_FIRE_LEVEL_NOTICE = 250;
  public static $_FIRE_LEVEL_WARNING = 300;
  public static $_FIRE_LEVEL_ERROR = 400;
  public static $_FIRE_LEVEL_CRITICAL = 500;
  public static $_FIRE_LEVEL_ALERT = 550;
  public static $_FIRE_LEVEL_EMERGENCY = 600;

  public static $_FIRE_ENV_TEST = 'TEST';
  public static $_FIRE_ENV_DEVELOPMENT = 'DEVELOPMENT';
  public static $_FIRE_ENV_STAGING = 'STAGING';
  public static $_FIRE_ENV_PRODUCTION = 'PRODUCTION';

  public static $_FIRE_ENV_VAR_NAME="_FIRE_ENV";
  public static $_FIRE_ENDPOINT="https://target.firetracker.io";

  public static $_FIRE_USER_SECRET="_FIRE_USER_SECRET";
  public static $_FIRE_USER_KEY="_FIRE_USER_KEY";

  public static function test(){
      echo "Tracker Fired";
  }

  /**
   * Main function to fire logs
   * @param $level
   * @param string $message
   * @param string $context
   * @return mixed
   */
  public static function Fire($level,$message="",$context=""){
    $headers = array('Accept' => 'application/json');
    //api auth infos
    $userSecret=(isset($GLOBALS[self::$_FIRE_USER_SECRET]))?$GLOBALS[self::$_FIRE_USER_SECRET]:null;
    $userKey=(isset($GLOBALS[self::$_FIRE_USER_KEY]))?$GLOBALS[self::$_FIRE_USER_KEY]:null;
    //check auth
    if($userKey==null || $userSecret==null){
      return false;
    }
    //default env value DEVELOPMENT
    $env=self::$_FIRE_ENV_DEVELOPMENT;
    //array to check sended value
    $targetEnv=array(
      self::$_FIRE_ENV_DEVELOPMENT,
      self::$_FIRE_ENV_TEST,
      self::$_FIRE_ENV_PRODUCTION,
      self::$_FIRE_ENV_STAGING,
    );
    // check env var value
    if(isset($GLOBALS[self::$_FIRE_ENV_VAR_NAME])){
      if(in_array($GLOBALS[self::$_FIRE_ENV_VAR_NAME],$targetEnv)){
        $env=$GLOBALS[self::$_FIRE_ENV_VAR_NAME];
      }
    }
    $query = array(
        'fire_level' => $level,
        'fire_message' => $message,
        'fire_context' => $context,
        'fire_env' => $env,
        'fire_key'=>$userKey,
        'fire_secret'=>$userSecret,
    );
    return Request::post(self::$_FIRE_ENDPOINT,$headers,$query);
  }

  /**
   * @param string $message
   * @param string $context
   */
  public static function FireEmergency($message="",$context=""){
      self::Fire(self::$_FIRE_LEVEL_EMERGENCY,$message,$context);
  }

  /**
   * @param string $message
   * @param string $context
   * @return mixed
   */
  public static function FireDebug($message="",$context=""){
    return self::Fire(self::$_FIRE_LEVEL_DEBUG,$message,$context);
  }

  /**
   * @param string $message
   * @param string $context
   * @return mixed
   */
  public static function FireInfo($message="",$context=""){
      return self::Fire(self::$_FIRE_LEVEL_INFO,$message,$context);
  }

  /**
   * @param string $message
   * @param string $context
   * @return mixed
   */
  public static function FireNotice($message="",$context=""){
    return self::Fire(self::$_FIRE_LEVEL_NOTICE,$message,$context);
  }

  /**
   * @param string $message
   * @param string $context
   * @return mixed
   */
  public static function FireWarning($message="",$context=""){
   return self::Fire(self::$_FIRE_LEVEL_WARNING,$message,$context);
  }

  /**
   * @param string $message
   * @param string $context
   * @return mixed
   */
  public static function FireError($message="",$context=""){
    return self::Fire(self::$_FIRE_LEVEL_ERROR,$message,$context);
  }

  /**
   * @param string $message
   * @param string $context
   * @return mixed
   */
  public static function FireAlert($message="",$context=""){
    return self::Fire(self::$_FIRE_LEVEL_ALERT,$message,$context);
  }

  /**
   * @param string $message
   * @param string $context
   * @return mixed
   */
  public static function FireCritical($message="",$context=""){
    return self::Fire(self::$_FIRE_LEVEL_CRITICAL,$message,$context);
  }
}
