<?php
namespace SamIT\Yii2\Components;


use yii\helpers\Json;

class Map implements \ArrayAccess, \JsonSerializable, \IteratorAggregate
{
    protected $data = [];

    public function __construct($data = [])
    {
        if (is_string($data)) {
            $this->data = json_decode($data, true);
        } elseif ($data === null || $data instanceof NullObject) {
            $this->data = [];
        } elseif ($data instanceof self) {
            $this->replaceWith($data);
        } else {
            $this->data = $data;
        }

        if(!is_array($this->data)) {
            throw new \DomainException('Data must be array or json encoded array');
        }

    }


    public function __isset($name)
    {
        return $this->offsetExists($name);
    }



    public function offsetExists($offset)
    {
        return isset($this->data[$offset]);
    }

    public function offsetGet($offset)
    {
        if (array_key_exists($offset, $this->data)) {
            if (is_array($this->data[$offset])) {
                $this->data[$offset] = new self($this->data[$offset]);
            }
            $result = $this->data[$offset];
        } else {
            $result = $this->data[$offset] = new Map();
        }
        return $result;
    }

    public function offsetSet($offset, $value)
    {
        if ($value instanceof self) {
            $this->data[$offset] = clone $value;
        } else {
            $this->data[$offset] = $value;
        }
    }

    public function offsetUnset($offset)
    {
        unset($this->data[$offset]);
    }

    public function __toString()
    {
        return Json::encode($this->data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }

    public function jsonSerialize()
    {
        return $this->data;
    }

    public function __get($name)
    {
        return $this->offsetGet($name);
    }

    public function __set($name, $value)
    {
        $this->offsetSet($name, $value);
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->data);
    }

    /**
     * Merges some extra data into the current map.
     * Supports recursive maps in the new data.
     * @param $extraData
     * @return mixed
     */
    public function mergeWith($extraData): void
    {
        if (is_array($extraData)) {
            $this->mergeWith(new self($extraData));
            return;
        }

        if ($extraData instanceof self) {
            foreach($extraData as $key => $value) {
                if (isset($this[$key])
                    && $value instanceof self
                    && $this[$key] instanceof self
                ) {
                    $this[$key]->mergeWith($value);
                } else {
                    $this[$key] = $value;
                }
            }
            return;
        }

        throw new \InvalidArgumentException("Argument must be array or Map");
    }

    public function replaceWith($data) {
        if (is_array($data)) {
            return $this->replaceWith(new self($data));
        } elseif ($data instanceof self) {
            $cloned = clone $data;
            $this->data = $cloned->data;
        }
    }

    public function __clone()
    {
        foreach($this->data as $key => $value) {
            if ($value instanceof self) {
                $this->data[$key] = clone $value;
            }
        }
    }

    public function asArray() {
        $result = $this->data;
        foreach($result as $key => $value) {
            if ($value instanceof self) {
                $result[$key] = $value->asArray();
            }

        }
        return $result;
    }
}
