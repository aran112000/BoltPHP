<?php

namespace Bolt\Statics;

use Bolt\Database\Mysql,
    Bolt\Exception,
    Bolt\Exception\Fatal,
    Bolt\Exception\Warning;

/**
 * Class Setting
 * @package Bolt\Statics
 */
class Setting {

    /**
     * @var string
     */
    protected static $ini_settings_dir = '/.Settings/';

    /**
     * @var null
     */
    private static $ini_settings_cache = null;
    /**
     * @var null
     */
    private static $db_settings_cache = null;

    /**
     * @param string $setting_name
     * @param null $default_value
     * @param bool $throw_warning_on_no_result
     * @param bool $check_database
     *
     * @return null
     * @throws Fatal
     * @throws Warning
     */
    public static function get($setting_name, $default_value = null, $throw_warning_on_no_result = false, $check_database = true) {
        if (empty($setting_name)) {
            throw new Fatal('Please specify a valid setting name');
        }
        if (static::$ini_settings_cache === null) {
            static::setIniSettings();
        }
        if (static::$db_settings_cache === null && $check_database) {
            static::setDatabaseSettings();
        }

        if (isset(static::$ini_settings_cache[$setting_name])) {
            return static::$ini_settings_cache[$setting_name];
        } else if (isset(static::$db_settings_cache[$setting_name])) {
            return static::$db_settings_cache[$setting_name];
        } else if ($throw_warning_on_no_result) {
            throw new Warning('Setting \'' . $setting_name . '\' not found');
        }

        return $default_value;
    }

    /**
     *
     */
    protected static function setDatabaseSettings() {
        $mysql = new Mysql();
        try {
            $settings = $mysql->select(['setting', 'value'])->from('Settings')->exec();

            foreach ($settings as $setting) {
                static::$settings_cache[$setting->setting] = $setting->value;
            }
        } catch (Exception\Exception $e) {
            // No database settings available
        }
    }

    /**
     *
     */
    protected static function setIniSettings() {
        foreach (static::getIniSettingsFiles() as $ini_setting_file) {
            foreach (parse_ini_file($ini_setting_file) as $setting => $value) {
                static::$settings_cache[$setting] = $value;
            }
        }
    }

    /**
     * @return array
     */
    protected static function getIniSettingsFiles() {
        $files = [];
        $glob_pattern = ROOT . DS . trim(static::$ini_settings_dir, '\\/') . DS . '*.ini';
        foreach (glob($glob_pattern) as $file) {
            $files[] = $file;
        }

        return array_unique($files);
    }
}
