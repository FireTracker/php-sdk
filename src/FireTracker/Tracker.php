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

    public static $_FIRE_ENV_VAR_NAME = "_FIRE_ENV";
    public static $_FIRE_ENDPOINT = "https://api.firetracker.io/";

    public static $_FIRE_USER_SECRET = "_FIRE_USER_SECRET";
    public static $_FIRE_USER_KEY = "_FIRE_USER_KEY";

    public static $_FIRE_INPUT_LEVEL="fire_level";
    public static $_FIRE_INPUT_MESSAGE="fire_message";
    public static $_FIRE_INPUT_CONTEXT="fire_context";
    public static $_FIRE_INPUT_ENV="fire_env";
    public static $_FIRE_INPUT_KEY="fire_key";
    public static $_FIRE_INPUT_SECRET="fire_secret";
    public static $_FIRE_INPUT_HOST="fire_host";
    public static $_FIRE_INPUT_LOCAL_IP="fire_local_ip";
    public static $_FIRE_INPUT_REMOTE_IP="fire_remote_ip";
    public static $_FIRE_INPUT_LINE="fire_line";
    public static $_FIRE_INPUT_FILE="fire_file";
    public static $_FIRE_INPUT_DIR="fire_dir";
    public static $_FIRE_INPUT_CLASS="fire_class";
    public static $_FIRE_INPUT_FUNCTION="fire_function";
    public static $_FIRE_INPUT_METHOD="fire_method";
    public static $_FIRE_INPUT_TRAIT="fire_trait";
    public static $_FIRE_INPUT_NAMESPACE="fire_namespace";

    protected static $_FIRE_HASH_ALGO="sha256";

    /**
     * Fire Test Function
     */
    public static function test()
    {
        echo "Tracker Fired";
        $testQuery = array(
            self::$_FIRE_INPUT_LEVEL => "LEVEL",
            self::$_FIRE_INPUT_MESSAGE => "MESSAGE",
            self::$_FIRE_INPUT_CONTEXT => "CONTEXT",
            self::$_FIRE_INPUT_ENV => "ENV",
            self::$_FIRE_INPUT_KEY => "KEY",
            self::$_FIRE_INPUT_HOST=>php_uname(),
            self::$_FIRE_INPUT_LOCAL_IP=>$_SERVER["SERVER_ADDR"],
            self::$_FIRE_INPUT_REMOTE_IP=>$_SERVER["REMOTE_ADDR"],
            self::$_FIRE_INPUT_LINE=>__LINE__,
            self::$_FIRE_INPUT_FILE=>__FILE__,
            self::$_FIRE_INPUT_FUNCTION=>__FUNCTION__,
            self::$_FIRE_INPUT_METHODE=>__METHOD__,
            self::$_FIRE_INPUT_CLASS=>__CLASS__,
            self::$_FIRE_INPUT_TRAIT=>__TRAIT__,
            self::$_FIRE_INPUT_NAMESPACE=>__NAMESPACE__,
            self::$_FIRE_INPUT_DIR=>__DIR__
        );
        $testQuery[self::$_FIRE_INPUT_SECRET]=self::fireHash($testQuery);
        return $testQuery;
    }

    /**
     * Main function to fire logs
     * @param $level
     * @param string $message
     * @param string $context
     * @return mixed
     */
    public static function Fire($level, $message = "", $context = "",$_line=__LINE__,$_file=__FILE__,$_function=__FUNCTION__,$_method=__METHOD__,$_class=__CLASS__,$_trait=__TRAIT__,$_ns=__NAMESPACE__,$_dir=__DIR__)
    {
        $headers = array('Accept' => 'application/json');
        $query=self::_fire($level,$message, $context,$_line,$_file,$_function,$_method,$_class,$_trait,$_ns,$_dir);
        return Request::post(self::$_FIRE_ENDPOINT, $headers, $query);
    }
    /**
    * Fire Log With Exception
    */
    public static function Fire($level,Exception $x,$_function=__FUNCTION__,$_method=__METHOD__,$_class=__CLASS__,$_trait=__TRAIT__,$_ns=__NAMESPACE__,$_dir=__DIR__){
      $headers = array('Accept' => 'application/json');
      $query=self::_fire($level,$x->getMessage(), $x->__toString(),$x-> getLine(),$x->getFile(),$_function,$_method,$_class,$_trait,$_ns,$_dir);
      return Request::post(self::$_FIRE_ENDPOINT, $headers, $query);
    }

    /**
     * @param string $message
     * @param string $context
     */
    public static function FireEmergency($message = "", $context = "",$_line=__LINE__,$_file=__FILE__,$_function=__FUNCTION__,$_method=__METHOD__,$_class=__CLASS__,$_trait=__TRAIT__,$_ns=__NAMESPACE__,$_dir=__DIR__)
    {
        self::Fire(self::$_FIRE_LEVEL_EMERGENCY, $message, $context,$_line,$_file,$_function,$_method,$_class,$_trait,$_ns,$_dir);
    }

    /**
     * @param string $message
     * @param string $context
     * @return mixed
     */
    public static function FireDebug($message = "", $context = "",$_line=__LINE__,$_file=__FILE__,$_function=__FUNCTION__,$_method=__METHOD__,$_class=__CLASS__,$_trait=__TRAIT__,$_ns=__NAMESPACE__,$_dir=__DIR__)
    {
        return self::Fire(self::$_FIRE_LEVEL_DEBUG, $message, $context,$_line,$_file,$_function,$_method,$_class,$_trait,$_ns,$_dir);
    }

    /**
     * @param string $message
     * @param string $context
     * @return mixed
     */
    public static function FireInfo($message = "", $context = "",$_line=__LINE__,$_file=__FILE__,$_function=__FUNCTION__,$_method=__METHOD__,$_class=__CLASS__,$_trait=__TRAIT__,$_ns=__NAMESPACE__,$_dir=__DIR__)
    {
        return self::Fire(self::$_FIRE_LEVEL_INFO, $message, $context,$_line,$_file,$_function,$_method,$_class,$_trait,$_ns,$_dir);
    }

    /**
     * @param string $message
     * @param string $context
     * @return mixed
     */
    public static function FireNotice($message = "", $context = "",$_line=__LINE__,$_file=__FILE__,$_function=__FUNCTION__,$_method=__METHOD__,$_class=__CLASS__,$_trait=__TRAIT__,$_ns=__NAMESPACE__,$_dir=__DIR__)
    {
        return self::Fire(self::$_FIRE_LEVEL_NOTICE, $message, $context,$_line,$_file,$_function,$_method,$_class,$_trait,$_ns,$_dir);
    }

    /**
     * @param string $message
     * @param string $context
     * @return mixed
     */
    public static function FireWarning($message = "", $context = "",$_line=__LINE__,$_file=__FILE__,$_function=__FUNCTION__,$_method=__METHOD__,$_class=__CLASS__,$_trait=__TRAIT__,$_ns=__NAMESPACE__,$_dir=__DIR__)
    {
        return self::Fire(self::$_FIRE_LEVEL_WARNING, $message, $context,$_line,$_file,$_function,$_method,$_class,$_trait,$_ns,$_dir);
    }

    /**
     * @param string $message
     * @param string $context
     * @return mixed
     */
    public static function FireError($message = "", $context = "",$_line=__LINE__,$_file=__FILE__,$_function=__FUNCTION__,$_method=__METHOD__,$_class=__CLASS__,$_trait=__TRAIT__,$_ns=__NAMESPACE__,$_dir=__DIR__)
    {
        return self::Fire(self::$_FIRE_LEVEL_ERROR, $message, $context,$_line,$_file,$_function,$_method,$_class,$_trait,$_ns,$_dir);
    }

    /**
     * @param string $message
     * @param string $context
     * @return mixed
     */
    public static function FireAlert($message = "", $context = "",$_line=__LINE__,$_file=__FILE__,$_function=__FUNCTION__,$_method=__METHOD__,$_class=__CLASS__,$_trait=__TRAIT__,$_ns=__NAMESPACE__,$_dir=__DIR__)
    {
        return self::Fire(self::$_FIRE_LEVEL_ALERT, $message, ,$_line,$_file,$_function,$_method,$_class,$_trait,$_ns,$_dir);
    }

    /**
     * @param string $message
     * @param string $context
     * @return mixed
     */
    public static function FireCritical($message = "", $context = "",$_line=__LINE__,$_file=__FILE__,$_function=__FUNCTION__,$_method=__METHOD__,$_class=__CLASS__,$_trait=__TRAIT__,$_ns=__NAMESPACE__,$_dir=__DIR__)
    {
        return self::Fire(self::$_FIRE_LEVEL_CRITICAL, $message, $context,$_line,$_file,$_function,$_method,$_class,$_trait,$_ns,$_dir);
    }

    protected static function fireHash($query){
        $userSecret = (isset($GLOBALS[self::$_FIRE_USER_SECRET])) ? $GLOBALS[self::$_FIRE_USER_SECRET] : null;
        $response=false;
        if($userSecret==null){
            return $response;
        }
        $data="";
        $data.=(isset($query[self::$_FIRE_INPUT_LEVEL]))?$query[self::$_FIRE_INPUT_LEVEL]:"$";
        $data.=(isset($query[self::$_FIRE_INPUT_MESSAGE]))?$query[self::$_FIRE_INPUT_MESSAGE]:"$";
        $data.=(isset($query[self::$_FIRE_INPUT_CONTEXT]))?$query[self::$_FIRE_INPUT_CONTEXT]:"$";
        $data.=(isset($query[self::$_FIRE_INPUT_ENV]))?$query[self::$_FIRE_INPUT_ENV]:"$";
        $data.=(isset($query[self::$_FIRE_INPUT_KEY]))?$query[self::$_FIRE_INPUT_KEY]:"$";
        $data.=(isset($query[self::$_FIRE_INPUT_HOST]))?$query[self::$_FIRE_INPUT_HOST]:"$";
        $data.=(isset($query[self::$_FIRE_INPUT_LOCAL_IP]))?$query[self::$_FIRE_INPUT_LOCAL_IP]:"$";
        $data.=(isset($query[self::$_FIRE_INPUT_REMOTE_IP]))?$query[self::$_FIRE_INPUT_REMOTE_IP]:"$";
        $data.=(isset($query[self::$_FIRE_INPUT_LINE]))?$query[self::$_FIRE_INPUT_LINE]:"$";
        $data.=(isset($query[self::$_FIRE_INPUT_FILE]))?$query[self::$_FIRE_INPUT_FILE]:"$";
        $data.=(isset($query[self::$_FIRE_INPUT_FUNCTION]))?$query[self::$_FIRE_INPUT_FUNCTION]:"$";
        $data.=(isset($query[self::$_FIRE_INPUT_METHOD]))?$query[self::$_FIRE_INPUT_METHOD]:"$";
        $data.=(isset($query[self::$_FIRE_INPUT_CLASS]))?$query[self::$_FIRE_INPUT_CLASS]:"$";
        $data.=(isset($query[self::$_FIRE_INPUT_TRAIT]))?$query[self::$_FIRE_INPUT_TRAIT]:"$";
        $data.=(isset($query[self::$_FIRE_INPUT_NAMESPACE]))?$query[self::$_FIRE_INPUT_NAMESPACE]:"$";
        $data.=(isset($query[self::$_FIRE_INPUT_DIR]))?$query[self::$_FIRE_INPUT_DIR]:"$";
        $data.=$userSecret;
        $response=hash(self::$_FIRE_HASH_ALGO,$data);
        return $response;
    }
    protected static function _fire(){
      //api auth infos
      $userSecret = (isset($GLOBALS[self::$_FIRE_USER_SECRET])) ? $GLOBALS[self::$_FIRE_USER_SECRET] : null;
      $userKey = (isset($GLOBALS[self::$_FIRE_USER_KEY])) ? $GLOBALS[self::$_FIRE_USER_KEY] : null;
      //check auth
      if ($userKey == null || $userSecret == null) {
          return false;
      }
      //default env value DEVELOPMENT
      $env = self::$_FIRE_ENV_DEVELOPMENT;
      //array to check sended value
      $targetEnv = array(
          self::$_FIRE_ENV_DEVELOPMENT,
          self::$_FIRE_ENV_TEST,
          self::$_FIRE_ENV_PRODUCTION,
          self::$_FIRE_ENV_STAGING,
      );
      // check env var value
      if (isset($GLOBALS[self::$_FIRE_ENV_VAR_NAME])) {
          if (in_array($GLOBALS[self::$_FIRE_ENV_VAR_NAME], $targetEnv)) {
              $env = $GLOBALS[self::$_FIRE_ENV_VAR_NAME];
          }
      }
      $query = array(
          self::$_FIRE_INPUT_LEVEL => $level,
          self::$_FIRE_INPUT_MESSAGE => $message,
          self::$_FIRE_INPUT_CONTEXT => $context,
          self::$_FIRE_INPUT_ENV => $env,
          self::$_FIRE_INPUT_KEY => $userKey,
          self::$_FIRE_INPUT_HOST=>php_uname(),
          self::$_FIRE_INPUT_LOCAL_IP=>$_SERVER["SERVER_ADDR"],
          self::$_FIRE_INPUT_REMOTE_IP=>$_SERVER["REMOTE_ADDR"],
          self::$_FIRE_INPUT_LINE=>$_line,
          self::$_FIRE_INPUT_FILE=>$_file,
          self::$_FIRE_INPUT_FUNCTION=>$_function,
          self::$_FIRE_INPUT_METHODE=>$_method,
          self::$_FIRE_INPUT_CLASS=>$_class,
          self::$_FIRE_INPUT_TRAIT=>$_trait,
          self::$_FIRE_INPUT_NAMESPACE=>$_ns,
      );
      return $query;
    }
}
