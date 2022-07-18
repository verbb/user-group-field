<?php
namespace verbb\usergroupfield\base;

use Craft;

use yii\log\Logger;

use verbb\base\BaseHelper;

trait PluginTrait
{
    // Static Properties
    // =========================================================================

    public static $plugin;


    // Public Methods
    // =========================================================================

    public static function log($message, $attributes = []): void
    {
        if ($attributes) {
            $message = Craft::t('user-group-field', $message, $attributes);
        }

        Craft::getLogger()->log($message, Logger::LEVEL_INFO, 'user-group-field');
    }

    public static function error($message, $attributes = []): void
    {
        if ($attributes) {
            $message = Craft::t('user-group-field', $message, $attributes);
        }

        Craft::getLogger()->log($message, Logger::LEVEL_ERROR, 'user-group-field');
    }


    // Private Methods
    // =========================================================================

    private function _setPluginComponents(): void
    {
        BaseHelper::registerModule();
    }

    private function _setLogging(): void
    {
        BaseHelper::setFileLogging('user-group-field');
    }

}