<?php

namespace SamIT\Yii2\JsonBehavior;

use SamIT\Yii2\JsonBehavior\Map;
use yii\base\Behavior;
use yii\db\ActiveRecord;

/**
 * JsonBehavior
 * Automatically converts to / from json before saving / after reading.
 *
 */
class JsonBehavior extends Behavior
{
    public $jsonAttributes = [];
    public $defaultAttribute;

    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_INSERT => 'beforeSave',
            ActiveRecord::EVENT_BEFORE_UPDATE => 'beforeSave',
            ActiveRecord::EVENT_AFTER_FIND => 'afterFind',
        ];
    }

    /**
     *
     */
    protected function createMaps()
    {
        foreach ($this->jsonAttributes as $attribute) {
            if (!$this->owner->$attribute instanceof Map) {
                $this->owner->$attribute = new Map($this->owner->$attribute);
            }
        }
    }

    public function attach($owner): void
    {
        parent::attach($owner);
        $this->createMaps();
    }

    public function afterFind(): void
    {
        $this->createMaps();
    }

    public function beforeSave(): bool
    {
        $this->createMaps();
        return true;
    }

    public function canGetProperty($name, $checkVars = true)
    {
        // Only do this if we have exactly 1 json attribute.
        return isset($this->defaultAttribute)
            || parent::canGetProperty($name, $checkVars);
    }

    public function canSetProperty($name, $checkVars = true)
    {
        return isset($this->defaultAttribute)
            || parent::canSetProperty($name, $checkVars);
    }

    public function __set($name, $value)
    {
        if (parent::canSetProperty($name, false)) {
            parent::__set($name, $value);
        } elseif (isset($this->defaultAttribute)) {
            $this->owner->{$this->defaultAttribute}[$name] = $value;
        } else {
            // Throw standard error:
            parent::__set($name, $value);
        }
    }

    public function __get($name)
    {
        if (isset($this->defaultAttribute)) {
            if (isset($this->owner->{$this->defaultAttribute}[$name])) {
                return $this->owner->{$this->defaultAttribute}[$name];
            }
            return null;
        }

        return parent::__get($name);
    }

    /**
     * Initializes the object.
     * This method is invoked at the end of the constructor after the object is initialized with the
     * given configuration.
     */
    public function init()
    {
        parent::init();
        if (!isset($this->defaultAttribute) && count($this->jsonAttributes) == 1) {
            $this->defaultAttribute = $this->jsonAttributes[0];
        }
    }
}
