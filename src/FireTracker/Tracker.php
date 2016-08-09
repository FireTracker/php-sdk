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
    public static $_FIRE_ENDPOINT = "https://target.firetracker.io";

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
    public static $_FIRE_INPUT_METHODE="fire_methode";
    public static $_FIRE_INPUT_TRAIT="fire_trait";
    public static $_FIRE_INPUT_NAMESPACE="fire_namespace";

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
            self::$_FIRE_INPUT_SECRET=> "SECRET",
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
        );
        print_r($testQuery);
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
            self::$_FIRE_INPUT_SECRET=> $userSecret,
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
        );
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

    /**
     * @param string $message
     * @param string $context
     * @return mixed
     */
    public static function FireDebug($message = "", $context = "")
    {
        return self::Fire(self::$_FIRE_LEVEL_DEBUG, $message, $context);
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

    /**
     * @param string $message
     * @param string $context
     * @return mixed
     */
    public static function FireNotice($message = "", $context = "")
    {
        return self::Fire(self::$_FIRE_LEVEL_NOTICE, $message, $context);
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

    /**
     * @param string $message
     * @param string $context
     * @return mixed
     */
    public static function FireError($message = "", $context = "")
    {
        return self::Fire(self::$_FIRE_LEVEL_ERROR, $message, $context);
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

    /**
     * @param string $message
     * @param string $context
     * @return mixed
     */
    public static function FireCritical($message = "", $context = "")
    {
        return self::Fire(self::$_FIRE_LEVEL_CRITICAL, $message, $context);
    }
}
