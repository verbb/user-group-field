<?php
/**
 * User Group Field plugin for Craft CMS 3.x
 *
 * Field type that let you select one or more user groups
 *
 * @link      https://superbig.co
 * @copyright Copyright (c) 2019 Superbig
 */

namespace superbig\usergroupfield\models;

use craft\elements\User;
use craft\models\UserGroup;
use superbig\usergroupfield\UserGroupField;

use Craft;
use craft\base\Model;

/**
 * @author    Superbig
 * @package   UserGroupField
 * @since     1.0.0
 */
class UserGroupFieldModel extends Model
{
    private $_groups;

    // Public Properties
    // =========================================================================

    /**
     * @var array
     */
    public $groupIds = [];

    // Public Methods
    // =========================================================================

    public function getGroupIds(): array
    {
        if (!\is_array($this->groupIds)) {
            $this->groupIds = [];
        }

        return array_values($this->groupIds);
    }

    /**
     * @return UserGroup[]
     */
    public function getGroups()
    {
        if (!$this->_groups) {
            $this->_groups = array_filter(Craft::$app->getUserGroups()->getAllGroups(), function(UserGroup $userGroup) {
                return \in_array($userGroup->uid, $this->getGroupIds(), false);
            });
        }

        return $this->_groups;
    }

    public function inGroup(User $user = null): bool
    {
        $groups  = $this->getGroups();
        $inGroup = false;

        foreach ($groups as $group) {
            if ($user->isInGroup($group)) {
                $inGroup = true;
            }
        }

        return $inGroup;
    }

    public function canAccess(User $user = null): bool
    {
        return $user->admin || $this->inGroup($user);
    }
}
