<?php
/**
 * User Group Field plugin for Craft CMS 3.x
 *
 * Field type that let you select one or more user groups
 *
 * @link      https://superbig.co
 * @copyright Copyright (c) 2019 Superbig
 */

namespace superbig\usergroupfield\fields;

use craft\models\UserGroup;
use superbig\usergroupfield\models\UserGroupFieldModel;
use superbig\usergroupfield\UserGroupField;
// use superbig\usergroupfield\assetbundles\usergroupfieldfieldfield\UserGroupFieldFieldFieldAsset;

use Craft;
use craft\base\ElementInterface;
use craft\base\Field;
use craft\helpers\Db;
use yii\db\Schema;
use craft\helpers\Json;

/**
 * @author    Superbig
 * @package   UserGroupField
 * @since     1.0.0
 */
class UserGroupFieldField extends Field
{
    const MODE_DROPDOWN   = 'dropdown';
    const MODE_CHECKBOXES = 'checkboxes';
    const MODE_RADIO      = 'radio';

    // Public Properties
    // =========================================================================

    /**
     * @var string
     */
    public $mode = self::MODE_DROPDOWN;

    /**
     * @var array
     */
    public $userGroups = [];

    // Static Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public static function displayName(): string
    {
        return Craft::t('user-group-field', 'User Group');
    }

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $rules = parent::rules();
        $rules = array_merge($rules, [
            ['mode', 'string'],
            ['mode', 'default', 'value' => self::MODE_DROPDOWN],
        ]);

        return $rules;
    }

    /**
     * @inheritdoc
     */
    public function getContentColumnType(): string
    {
        return Schema::TYPE_TEXT;
    }

    /**
     * @inheritdoc
     */
    public function normalizeValue($value, ElementInterface $element = null)
    {
        if (\is_string($value) && !empty($value)) {
            $value = Json::decodeIfJson($value);

            if (\is_string($value)) {
                $value = [$value];
            }
        }

        if (is_a($value, UserGroupFieldModel::class)) {
            return $value;
        } else {
            return new UserGroupFieldModel([
                'groupIds' => $value
            ]);
        }
    }

    /**
     * @inheritdoc
     */
    public function serializeValue($value, ElementInterface $element = null)
    {
        /** @var UserGroupFieldModel $value */
        $value = $value->getGroupIds();

        return parent::serializeValue($value, $element);
    }

    /**
     * @inheritdoc
     */
    public function getSettingsHtml()
    {
        // Render the settings template
        return Craft::$app->getView()->renderTemplate(
            'user-group-field/_components/fields/UserGroupFieldField_settings',
            [
                'field' => $this,
                'modes' => [
                    self::MODE_DROPDOWN   => Craft::t('user-group-field', 'Dropdown'),
                    self::MODE_CHECKBOXES => Craft::t('user-group-field', 'Checkboxes'),
                    self::MODE_RADIO      => Craft::t('user-group-field', 'Radio buttons'),
                ],
            ]
        );
    }

    /**
     * @inheritdoc
     */
    public function getInputHtml($value, ElementInterface $element = null): string
    {
        // Get our id and namespace
        $id           = Craft::$app->getView()->formatInputId($this->handle);
        $namespacedId = Craft::$app->getView()->namespaceInputId($id);

        $userGroups = array_map(function(UserGroup $userGroup) {
            return [
                'label' => $userGroup->name,
                'value' => $userGroup->uid,
            ];
        }, Craft::$app->getUserGroups()->getAllGroups());

        // Render the input template
        return Craft::$app->getView()->renderTemplate(
            'user-group-field/_components/fields/UserGroupFieldField_input',
            [
                'name'         => $this->handle,
                'value'        => $value,
                'field'        => $this,
                'id'           => $id,
                'namespacedId' => $namespacedId,
                'userGroups'   => $userGroups,
                'mode'         => $this->mode,
            ]
        );
    }
}
