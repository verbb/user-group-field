<?php
namespace verbb\usergroupfield\models;

use Craft;
use craft\base\Model;
use craft\elements\User;
use craft\models\UserGroup;

class UserGroupCollection extends Model
{
    // Properties
    // =========================================================================

    public array $groupIds = [];

    private array $_groups = [];


    // Public Methods
    // =========================================================================

    public function getGroupIds(): array
    {
        return array_values($this->groupIds);
    }

    public function getGroups(): array
    {
        if (!$this->_groups) {
            $this->_groups = array_filter(Craft::$app->getUserGroups()->getAllGroups(), function(UserGroup $userGroup) {
                return in_array($userGroup->uid, $this->getGroupIds(), false);
            });
        }

        return $this->_groups;
    }

    public function inGroup(User $user = null): bool
    {
        if (!$user) {
            return false;
        }

        $groups = $this->getGroups();
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
        if (!$user) {
            return false;
        }

        return $user->admin || $this->inGroup($user);
    }
}
