<?php
namespace SamIT\Yii2\Components;

/**
 * {@inheritDoc}
 */
class NullObject implements \ArrayAccess
{
    public function offsetExists($offset)
    {
        return false;
    }

    public function offsetGet($offset)
    {
        throw new \BadMethodCallException("Cannot call " . __FUNCTION__ . " on " . __CLASS__);
    }

    public function offsetSet($offset, $value)
    {
        throw new \BadMethodCallException("Cannot call " . __FUNCTION__ . " on " . __CLASS__);
    }

    public function offsetUnset($offset)
    {
        throw new \BadMethodCallException("Cannot call " . __FUNCTION__ . " on " . __CLASS__);
    }
}