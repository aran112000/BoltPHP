<?php
namespace Core\Fields;

use Core\Exception\Warning;

/**
 * Class Field
 * @package Core\Fields
 */
abstract class Field {

    /**
     * @var int
     */
    public $max_length = 255;
    /**
     * @var int
     */
    public $min_length = 0;

    /**
     * @var null|string
     */
    protected $value = null;

    /**
     * @var bool
     */
    private $value_set = false;

    /**
     * @var string
     */
    protected $sub_str_suffix = '...';

    /**
     * @return string|null
     */
    public function get() {
        return $this->value;
    }

    /**
     * @return bool
     */
    public function is_set() {
        return (bool) $this->value_set;
    }

    /**
     * @param string $value
     *
     * @throws \Core\Exception\Warning
     */
    public function set($value) {
        $length = strlen($value);
        if ($length > $this->max_length) {
            $this->value = substr($value, 0, $this->max_length);
            $this->value_set = true;
            throw new Warning('New field length is too long and has been truncated to ' . $this->max_length);
        } else if ($length < $this->min_length) {
            throw new Warning('New field length is too short so has been skipped. Minimum field length = ' . $this->min_length . ' chars');
        }

        $this->value = $value;
        $this->value_set = true;
    }

    /**
     * @param null $max_length
     *
     * @return null|string
     */
    public function sub_str($max_length = null) {
        if ($max_length === null) {
            $max_length = $this->max_length;
        }

        $value = $this->value;
        if (strlen($value) > $max_length) {
            $value = substr($value, 0, ($max_length - strlen($this->sub_str_suffix))) . $this->sub_str_suffix;
        }

        return $value;
    }
}