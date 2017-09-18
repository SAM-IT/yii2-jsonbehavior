<?php
namespace SamIT\Yii2\Components;


use Traversable;

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
        return json_encode($this->data, JSON_FORCE_OBJECT);
    }

    public function jsonSerialize()
    {
        return $this->data;
    }

    /**
     * is utilized for reading data from inaccessible members.
     *
     * @param $name string
     * @return mixed
     * @link http://php.net/manual/en/language.oop5.overloading.php#language.oop5.overloading.members
     */
    function __get($name)
    {
        return $this->offsetGet($name);
    }

    /**
     * run when writing data to inaccessible members.
     *
     * @param $name string
     * @param $value mixed
     * @return void
     * @link http://php.net/manual/en/language.oop5.overloading.php#language.oop5.overloading.members
     */
    function __set($name, $value)
    {
        $this->offsetSet($name, $value);
    }


    /**
     * Retrieve an external iterator
     * @link http://php.net/manual/en/iteratoraggregate.getiterator.php
     * @return Traversable An instance of an object implementing <b>Iterator</b> or
     * <b>Traversable</b>
     * @since 5.0.0
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->data);
    }

    public function mergeWith($extraData) {
        if (is_array($extraData)) {
            return $this->mergeWith(new self($extraData));
        } elseif ($extraData instanceof self) {
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

        }
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
                $this->data[$key] = clone self;
            }
        }
    }

    public function asArray() {
        $result = $this->data;
        foreach($result as $key => $value) {
            if ($value instanceof self) {
                $result[$value] = $value->asArray();
            }

        }
        return $result;
    }



}