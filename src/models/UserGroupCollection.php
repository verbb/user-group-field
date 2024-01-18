<?php
namespace verbb\usergroupfield\models;

use Craft;
use craft\base\Model;
use craft\elements\User;
use craft\models\UserGroup;

use yii\caching\TagDependency;

class UserGroupCollection extends Model
{
    // Constants
    // =========================================================================

    public const CACHE_KEY = 'userGroupsCache';


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

    public function getGroups(bool $cache = false): array
    {
        if (!$this->_groups) {
            $this->_groups = array_filter($this->_getAllGroups($cache), function(UserGroup $userGroup) {
                return in_array($userGroup->uid, $this->getGroupIds(), false);
            });
        }

        return $this->_groups;
    }

    public function inGroup(User $user = null, bool $cache = false): bool
    {
        if (!$user) {
            return false;
        }

        $groups = $this->getGroups($cache);
        $inGroup = false;

        foreach ($groups as $group) {
            if ($user->isInGroup($group)) {
                $inGroup = true;
            }
        }

        return $inGroup;
    }

    public function canAccess(User $user = null, bool $cache = false): bool
    {
        if (!$user) {
            return false;
        }

        return $user->admin || $this->inGroup($user, $cache);
    }


    // Private Methods
    // =========================================================================

    private function _getUserGroups(): array
    {
        return Craft::$app->getUserGroups()->getAllGroups();
    }

    private function _getAllGroups(bool $cache = false): array
    {
        if ($cache) {
            $cacheService = Craft::$app->getCache();
            $cacheKey = self::CACHE_KEY;
            $allGroups = $cacheService->get($cacheKey);

            if ($allGroups === false) {
                $allGroups = $this->_getUserGroups();

                // Store the result in cache based on the url - will be cleard on entry save
                $cacheService->set($cacheKey, $allGroups, 0, new TagDependency(['tags' => ['url:' . Craft::$app->getRequest()->getUrl()]]));
            }
        } else {
            $allGroups = $this->_getUserGroups();
        }

        return $allGroups;
    }
}
