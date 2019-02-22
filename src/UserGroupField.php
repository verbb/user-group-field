<?php
/**
 * User Group Field plugin for Craft CMS 3.x
 *
 * Field type that let you select one or more user groups
 *
 * @link      https://superbig.co
 * @copyright Copyright (c) 2019 Superbig
 */

namespace superbig\usergroupfield;

use superbig\usergroupfield\services\UserGroupFieldService as UserGroupFieldServiceService;
use superbig\usergroupfield\fields\UserGroupFieldField as UserGroupFieldFieldField;

use Craft;
use craft\base\Plugin;
use craft\services\Plugins;
use craft\events\PluginEvent;
use craft\services\Fields;
use craft\events\RegisterComponentTypesEvent;

use yii\base\Event;

/**
 * Class UserGroupField
 *
 * @author    Superbig
 * @package   UserGroupField
 * @since     1.0.0
 *
 * @property  UserGroupFieldServiceService $userGroupFieldService
 */
class UserGroupField extends Plugin
{
    // Static Properties
    // =========================================================================

    /**
     * @var UserGroupField
     */
    public static $plugin;

    // Public Properties
    // =========================================================================

    /**
     * @var string
     */
    public $schemaVersion = '1.0.0';

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        self::$plugin = $this;

        Event::on(
            Fields::class,
            Fields::EVENT_REGISTER_FIELD_TYPES,
            function (RegisterComponentTypesEvent $event) {
                $event->types[] = UserGroupFieldFieldField::class;
            }
        );

        Event::on(
            Plugins::class,
            Plugins::EVENT_AFTER_INSTALL_PLUGIN,
            function (PluginEvent $event) {
                if ($event->plugin === $this) {
                }
            }
        );

        Craft::info(
            Craft::t(
                'user-group-field',
                '{name} plugin loaded',
                ['name' => $this->name]
            ),
            __METHOD__
        );
    }

    // Protected Methods
    // =========================================================================

}
