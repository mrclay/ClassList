<?php

namespace MrClay;

/**
 * HTML class list based on DOMTokenList
 *
 * @link https://developer.mozilla.org/en-US/docs/Web/API/DOMTokenList
 */
class ClassList
{
    /**
     * Names (each a key mapping to true)
     *
     * @var int[]
     */
    protected $namesAsKeys = array();

    /**
     * Constructor
     *
     * @param string|string[]|ClassList $names Name(s) for list
     */
    public function __construct($names = null)
    {
        $this->add($names);
    }

    /**
     * Get as an HTML class string
     *
     * @return string
     */
    public function value()
    {
        return implode(' ', $this->values());
    }

    /**
     * Alias of values()
     *
     * @return string
     */
    public function toString()
    {
        return $this->value();
    }

    /**
     * Count the names
     *
     * @return int
     */
    public function length()
    {
        return count($this->namesAsKeys);
    }

    /**
     * Get a name by index
     *
     * @param int $index Index
     * @return string|null
     */
    public function item($index)
    {
        $names = $this->values();

        return isset($names[$index]) ? $names[$index] : null;
    }

    /**
     * Does the list contain a name?
     *
     * @param string $name Name
     * @return bool
     */
    public function contains($name)
    {
        return isset($this->namesAsKeys[trim($name)]);
    }

    /**
     * Add name(s)
     *
     * @param string|string[]|ClassList $names Name(s)
     * @return self
     */
    public function add($names)
    {
        foreach ($this->normalizeInput($names) as $name) {
            $this->namesAsKeys[$name] = true;
        }

        return $this;
    }

    /**
     * Remove name(s)
     *
     * @param string|string[]|ClassList $names Name(s)
     * @return self
     */
    public function remove($names)
    {
        foreach ($this->normalizeInput($names) as $name) {
            unset($this->namesAsKeys[$name]);
        }

        return $this;
    }

    /**
     * Replace a name with another
     *
     * @param string $old Name to replace
     * @param string $new Name to replace with
     * @return self
     */
    public function replace($old, $new)
    {
        $old = trim($old);
        $new = trim($new);

        // preserve order of names
        $tmp = $this->namesAsKeys;
        $this->namesAsKeys = array();
        foreach ($tmp as $name => $true) {
            $this->namesAsKeys[$name === $old ? $new : $name] = true;
        }

        return $this;
    }

    /**
     * Toggle a name
     *
     * @param string $name  Name
     * @param bool   $force If set, class will be added/removed based on truth/false
     * @return bool
     */
    public function toggle($name, $force = null)
    {
        if (isset($force)) {
            $force ? $this->add($name) : $this->remove($name);
            return (bool) $force;
        }

        if ($this->contains($name)) {
            $this->remove($name);
            return false;
        }

        $this->add($name);

        return true;
    }

    /**
     * Reset the contents
     *
     * @param string|string[]|ClassList $names Name(s)
     * @return self
     */
    public function reset($names = null)
    {
        $this->namesAsKeys = array();

        return $this->add($names);
    }

    /**
     * Get all names
     *
     * @return string[]
     */
    public function values()
    {
        return array_keys($this->namesAsKeys);
    }

    /**
     * Factory accepting values
     *
     * @param string|string[]|ClassList $names Name(s)
     * @return self
     */
    public static function fromValues($names = null)
    {
        return new static($names);
    }

    /**
     * Normalize an input value to array of non-empty strings
     *
     * @param string|string[]|ClassList $names Name(s)
     * @return string[]
     */
    protected function normalizeInput($names)
    {
        if ($names === null) {
            return array();
        }

        if ($names instanceof ClassList) {
            return $names->values();
        }

        if (is_string($names)) {
            $names = explode(' ', $names);
        }

        $ret = array();
        foreach ($names as $name) {
            $name = trim($name);
            if ($name !== '') {
                $ret[] = $name;
            }
        }

        return $ret;
    }
}
