<?php
namespace FireTracker;
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

  public static function test(){
      echo "Tracker Fired";
  }
  public static function Fire(Exception $_x_=null,$level=null,$env=null){
    if(!defined(FT_API_KEY)||!defined(FT_APP_KEY)){
      return false;
    }
    echo "Fire Exception".self::$_FIRE_LEVEL_DEBUG;
  }
  public static function Fire($code="",$message="",$file="";$line="",$level=null,$env=null){

  }
}
