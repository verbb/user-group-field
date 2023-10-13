<?php
namespace verbb\usergroupfield\base;

use verbb\usergroupfield\UserGroupField;

use verbb\base\LogTrait;
use verbb\base\helpers\Plugin;

trait PluginTrait
{
    // Properties
    // =========================================================================

    public static ?UserGroupField $plugin = null;


    // Traits
    // =========================================================================

    use LogTrait;


    // Static Methods
    // =========================================================================

    public static function config(): array
    {
        Plugin::bootstrapPlugin('user-group-field');

        return [];
    }

}