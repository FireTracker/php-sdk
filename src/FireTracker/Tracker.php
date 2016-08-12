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
            self::$_FIRE_INPUT_REMOTE_IP=>$_SERVER["REMOTE_ADDR"]
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
    public static function Fire($level, $message = "", $context = "")
    {
        $headers = array('Accept' => 'application/json');
        $query=self::_fire($level,$message, $context);
        return Request::post(self::$_FIRE_ENDPOINT, $headers, $query);
    }
    /**
    * Fire Log With Exception
    */
    public static function Fire($level,Exception $x){
      $headers = array('Accept' => 'application/json');
      $query=self::_fire($level,$x->getMessage(),$x->__toString());
      return Request::post(self::$_FIRE_ENDPOINT, $headers, $query);
    }

    /**
     * @param string $message
     * @param string $context
     */
    public static function FireEmergency($message = "", $context = "")
    {
        self::Fire(self::$_FIRE_LEVEL_EMERGENCY, $message, $context);
    }

    public static function FireEmergency(Exception $x)
    {
        self::Fire(self::$_FIRE_LEVEL_EMERGENCY, $x);
    }

    /**
     * @param string $message
     * @param string $context
     * @return mixed
     */
    public static function FireDebug($message = "", $context = "")
    {
        return self::Fire(self::$_FIRE_LEVEL_DEBUG, $message, $context);
    }

    public static function FireDebug(Exception $x)
    {
        return self::Fire(self::$_FIRE_LEVEL_DEBUG, $x);
    }
    /**
     * @param string $message
     * @param string $context
     * @return mixed
     */
    public static function FireInfo($message = "", $context = "")
    {
        return self::Fire(self::$_FIRE_LEVEL_INFO, $message, $context);
    }

    public static function FireInfo(Exception $x)
    {
        return self::Fire(self::$_FIRE_LEVEL_INFO, $x);
    }
    /**
     * @param string $message
     * @param string $context
     * @return mixed
     */
    public static function FireNotice($message = "", $context = "")
    {
        return self::Fire(self::$_FIRE_LEVEL_NOTICE, $message, $context);
    }

    public static function FireNotice(Exception $x)
    {
        return self::Fire(self::$_FIRE_LEVEL_NOTICE, $x);
    }

    /**
     * @param string $message
     * @param string $context
     * @return mixed
     */
    public static function FireWarning($message = "", $context = "")
    {
        return self::Fire(self::$_FIRE_LEVEL_WARNING, $message, $context);
    }

    public static function FireWarning(Exception $x)
    {
        return self::Fire(self::$_FIRE_LEVEL_WARNING, $x);
    }

    /**
     * @param string $message
     * @param string $context
     * @return mixed
     */
    public static function FireError($message = "", $context = "")
    {
        return self::Fire(self::$_FIRE_LEVEL_ERROR, $message, $context);
    }

    public static function FireError(Exception $x)
    {
        return self::Fire(self::$_FIRE_LEVEL_ERROR, $x);
    }

    /**
     * @param string $message
     * @param string $context
     * @return mixed
     */
    public static function FireAlert($message = "", $context = "")
    {
        return self::Fire(self::$_FIRE_LEVEL_ALERT, $message, $context);
    }

    public static function FireAlert(Exception $x)
    {
        return self::Fire(self::$_FIRE_LEVEL_ALERT, $x);
    }

    /**
     * @param string $message
     * @param string $context
     * @return mixed
     */
    public static function FireCritical($message = "", $context = "")
    {
        return self::Fire(self::$_FIRE_LEVEL_CRITICAL, $message, $context);
    }

    public static function FireCritical(Exception $x)
    {
        return self::Fire(self::$_FIRE_LEVEL_CRITICAL, $x);
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
        $data.=$userSecret;
        $response=hash(self::$_FIRE_HASH_ALGO,$data);
        return $response;
    }
    protected static function _fire($level,$message,$context){
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
      );
      return $query;
    }
}
