/** Seed all User Group Field modes against genuine Craft user groups. */

use craft\elements\Entry;
use craft\fieldlayoutelements\CustomField;
use craft\fieldlayoutelements\entries\EntryTitleField;
use craft\helpers\Json;
use craft\models\EntryType;
use craft\models\FieldLayout;
use craft\models\FieldLayoutTab;
use craft\models\Section;
use craft\models\Section_SiteSettings;
use craft\models\UserGroup;
use verbb\usergroupfield\fields\UserGroupField;

$fields = Craft::$app->getFields();
$entries = Craft::$app->getEntries();
$elements = Craft::$app->getElements();
$userGroups = Craft::$app->getUserGroups();
$site = Craft::$app->getSites()->getPrimarySite();
$sectionHandle = 'screenshotAudienceRules';

$groups = [];

foreach ([
    'contentEditors' => 'Content editors',
    'marketingTeam' => 'Marketing team',
    'members' => 'Members',
] as $handle => $name) {
    $group = $userGroups->getGroupByHandle($handle) ?? new UserGroup([
        'name' => $name,
        'handle' => $handle,
    ]);

    if (!$group->id && !$userGroups->saveGroup($group)) {
        throw new RuntimeException('Unable to save user group: ' . Json::encode($group->getErrors()));
    }

    $groups[$handle] = $group;
}

$fieldDefinitions = [
    'primaryAudience' => ['Primary audience', UserGroupField::MODE_DROPDOWN],
    'visibleToGroups' => ['Visible to groups', UserGroupField::MODE_CHECKBOXES],
    'approvalGroup' => ['Approval group', UserGroupField::MODE_RADIO],
];

$userGroupFields = [];

foreach ($fieldDefinitions as $handle => [$name, $mode]) {
    $field = $fields->getFieldByHandle($handle);

    if (!$field instanceof UserGroupField) {
        $field = new UserGroupField([
            'name' => $name,
            'handle' => $handle,
            'mode' => $mode,
        ]);

        if (!$fields->saveField($field)) {
            throw new RuntimeException('Unable to save User Group field: ' . Json::encode($field->getErrors()));
        }
    }

    $userGroupFields[$handle] = $field;
}

$section = $entries->getSectionByHandle($sectionHandle);

if (!$section) {
    $entryType = new EntryType([
        'name' => 'Audience rules',
        'handle' => $sectionHandle . 'Type',
    ]);

    $layout = new FieldLayout(['type' => Entry::class]);
    $tab = new FieldLayoutTab([
        'name' => Craft::t('app', 'Content'),
        'layout' => $layout,
    ]);
    $tab->setElements([
        new EntryTitleField(),
        ...array_map(static fn(UserGroupField $field) => new CustomField($field), array_values($userGroupFields)),
    ]);
    $layout->setTabs([$tab]);
    $entryType->setFieldLayout($layout);

    if (!$entries->saveEntryType($entryType)) {
        throw new RuntimeException('Unable to save User Group entry type: ' . Json::encode($entryType->getErrors()));
    }

    $section = new Section([
        'name' => 'Audience rules',
        'handle' => $sectionHandle,
        'type' => Section::TYPE_CHANNEL,
    ]);
    $section->setEntryTypes([$entryType]);
    $section->setSiteSettings([
        new Section_SiteSettings([
            'siteId' => $site->id,
            'enabledByDefault' => true,
            'hasUrls' => false,
        ]),
    ]);

    if (!$entries->saveSection($section)) {
        throw new RuntimeException('Unable to save User Group section: ' . Json::encode($section->getErrors()));
    }
}

$entryType = $entries->getEntryTypesBySectionId($section->id)[0] ?? null;

if (!$entryType) {
    throw new RuntimeException('User Group section has no entry type.');
}

$entry = Entry::find()
    ->sectionId($section->id)
    ->slug('member-offer')
    ->siteId($site->id)
    ->status(null)
    ->one();

if (!$entry) {
    $entry = new Entry([
        'sectionId' => $section->id,
        'typeId' => $entryType->id,
        'siteId' => $site->id,
        'slug' => 'member-offer',
        'enabled' => true,
    ]);
}

$entry->title = 'Member offer';
$entry->setFieldValue('primaryAudience', [$groups['members']->uid]);
$entry->setFieldValue('visibleToGroups', [$groups['contentEditors']->uid, $groups['marketingTeam']->uid]);
$entry->setFieldValue('approvalGroup', [$groups['marketingTeam']->uid]);

if (!$elements->saveElement($entry)) {
    throw new RuntimeException('Unable to save User Group screenshot entry: ' . Json::encode($entry->getErrors()));
}

echo Json::encode([
    'entryEditRoute' => parse_url((string)$entry->getCpEditUrl(), PHP_URL_PATH),
], JSON_THROW_ON_ERROR);
