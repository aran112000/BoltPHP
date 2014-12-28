<?php

namespace Core\Statics;

/**
 * Class session
 *
 * @package Core\Statics
 */
final class session {

    public static $session_lifetime;
    public static $session_valid_domain;
    public static $session_valid_path = '/';

    private static $session_started = false;

    const SESSION_NAME = 'zert';
    const SECURE_SESSION = true;

    /**
     * @return bool
     */
    public static function start() {
        self::$session_lifetime = (!empty(self::$session_lifetime) ? self::$session_lifetime : (60 * 60 * 24 * 365)); // Defaults to 1 year
        self::$session_valid_domain = (!empty(self::$session_valid_domain) ? self::$session_valid_domain : $_SERVER['HTTP_HOST']);

        // Set the cookie settings and start the session
        session_set_cookie_params(self::$session_lifetime, self::$session_valid_path, self::$session_valid_domain, $secure = true, $http_only = true // If TRUE then this session can't be accessed via JavaScript - Helps prevent XSS hijacks
        );
        if (!session_start()) {
            return false;
        }

        if (self::is_session_hijacked()) {
            self::regenerate_id(true);
        }

        // Possible session hijack detected
        self::$session_started = true;

        return true;
    }

    /**
     * This is used to validate the current session - Data will be used to detect hijacks
     *
     * @return bool
     */
    protected static function is_session_hijacked() {
        if (!isset($_SESSION[self::SESSION_NAME]['session_info']['ip_address']) || !isset($_SESSION[self::SESSION_NAME]['session_info']['user_agent'])) {
            self::regenerate_id(); // This is a completely new session so regenerating won't cause any harm here, just ensure that it hasn't just been captured
            return false;
        } else if ($_SESSION[self::SESSION_NAME]['session_info']['ip_address'] != $_SERVER['REMOTE_ADDR']) {
            return true;
        } else if ($_SESSION[self::SESSION_NAME]['session_info']['user_agent'] != $_SERVER['HTTP_USER_AGENT']) {
            return true;
        }

        return false;
    }

    /**
     * @param bool $remove_old_session
     */
    public static function regenerate_id($remove_old_session = true) {
        session_regenerate_id($remove_old_session);
        $_SESSION[self::SESSION_NAME]['session_details']['ip_address'] = $_SERVER['REMOTE_ADDR'];
        $_SESSION[self::SESSION_NAME]['session_details']['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
    }

    /**
     * @param $var
     *
     * @return bool
     */
    public static function is_set($var) {
        echo '<p>CHECKING IF $_SESSION[self::SESSION_NAME][' . $var . '] is set...</p>' . "\n";
        if (!self::$session_started) {
            return false;
        }
        if (isset($_SESSION[self::SESSION_NAME]) && !empty($_SESSION[self::SESSION_NAME])) {
            return $_SESSION[self::SESSION_NAME];
        }

        return false;
    }

    /**
     * @param $var
     * @param $val
     */
    public static function set($var, $val) {
        if (!self::$session_started) {
            echo '<p>Session wasn\'t started... Now starting</p>' . "\n";
            self::start();
        } else {
            echo '<p>Session already started</p>' . "\n";
        }

        echo '<p>Setting session... $_SESSION[self::SESSION_NAME][' . $var . ']</p>' . "\n";
        $_SESSION[self::SESSION_NAME][$var] = $val;
    }
}