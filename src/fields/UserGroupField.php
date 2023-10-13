<?php
namespace verbb\usergroupfield\fields;

use verbb\usergroupfield\models\UserGroupCollection;

use Craft;
use craft\base\ElementInterface;
use craft\base\Field;
use craft\elements\db\ElementQueryInterface;
use craft\helpers\Db;
use craft\helpers\ElementHelper;
use craft\helpers\Html;
use craft\helpers\Json;
use craft\models\UserGroup;

use yii\db\Schema;

class UserGroupField extends Field
{
    // Constants
    // =========================================================================

    public const MODE_DROPDOWN = 'dropdown';
    public const MODE_CHECKBOXES = 'checkboxes';
    public const MODE_RADIO = 'radio';


    // Static Methods
    // =========================================================================

    public static function displayName(): string
    {
        return Craft::t('user-group-field', 'User Group');
    }

    public static function dbType(): string
    {
        return Schema::TYPE_TEXT;
    }


    // Properties
    // =========================================================================

    public string $mode = self::MODE_DROPDOWN;
    public array $userGroups = [];


    // Public Methods
    // =========================================================================

    public function normalizeValue(mixed $value, ElementInterface $element = null): mixed
    {
        if (is_string($value) && !empty($value)) {
            $value = Json::decodeIfJson($value);

            if (is_string($value)) {
                $value = [$value];
            }
        }

        if (is_a($value, UserGroupCollection::class)) {
            return $value;
        }

        return new UserGroupCollection([
            'groupIds' => $value,
        ]);
    }

    public function serializeValue(mixed $value, ElementInterface $element = null): mixed
    {
        $value = $value->getGroupIds();

        return parent::serializeValue($value, $element);
    }

    public function modifyElementsQuery(ElementQueryInterface $query, mixed $value): void
    {
        if ($value !== null) {
            $column = ElementHelper::fieldColumnFromField($this);

            $query->subQuery->andWhere(Db::parseParam("content.$column", $value, 'like', false, $this->dbType()));
        }
    }

    public function getSettingsHtml(): ?string
    {
        return Craft::$app->getView()->renderTemplate('user-group-field/_field/settings', [
            'field' => $this,
            'modes' => [
                self::MODE_DROPDOWN => Craft::t('user-group-field', 'Dropdown'),
                self::MODE_CHECKBOXES => Craft::t('user-group-field', 'Checkboxes'),
                self::MODE_RADIO => Craft::t('user-group-field', 'Radio buttons'),
            ],
        ]);
    }

    protected function inputHtml(mixed $value, ?ElementInterface $element, bool $inline): string
    {
        $id = Html::id($this->handle);
        $namespacedId = Craft::$app->getView()->namespaceInputId($id);

        $userGroups = array_map(function(UserGroup $userGroup) {
            return [
                'label' => $userGroup->name,
                'value' => $userGroup->uid,
            ];
        }, Craft::$app->getUserGroups()->getAllGroups());

        return Craft::$app->getView()->renderTemplate('user-group-field/_field/input', [
            'name' => $this->handle,
            'value' => $value,
            'field' => $this,
            'id' => $id,
            'namespacedId' => $namespacedId,
            'userGroups' => $userGroups,
            'mode' => $this->mode,
        ]);
    }
}
