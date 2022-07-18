<?php
namespace verbb\usergroupfield;

use verbb\usergroupfield\base\PluginTrait;
use verbb\usergroupfield\fields\UserGroupField as UserGroupFieldField;

use craft\base\Plugin;
use craft\events\RegisterComponentTypesEvent;
use craft\services\Fields;

use yii\base\Event;

class UserGroupField extends Plugin
{
    // Properties
    // =========================================================================

    public $schemaVersion = '2.0.0';


    // Traits
    // =========================================================================

    use PluginTrait;


    // Public Methods
    // =========================================================================

    public function init(): void
    {
        parent::init();

        self::$plugin = $this;

        $this->_setPluginComponents();
        $this->_setLogging();
        $this->_registerFieldTypes();
    }


    // Private Methods
    // =========================================================================

    private function _registerFieldTypes(): void
    {
        Event::on(Fields::class, Fields::EVENT_REGISTER_FIELD_TYPES, function(RegisterComponentTypesEvent $event) {
            $event->types[] = UserGroupFieldField::class;
        });
    }

}
