# User Group Field plugin for Craft CMS
<img width="500" src="https://verbb.imgix.net/plugins/user-group-field/user-group-field-social-card.png?v=2">

User Group Field is a Craft CMS plugin with a field type that lets you select one or more user groups.

## Documentation
Visit the [User Group Field Plugin page](https://verbb.io/craft-plugins/user-group-field) for all documentation, guides, pricing and developer resources.

### Caching Craft::$app->getUserGroups()->getAllGroups(); request.
By default `Craft::$app->getUserGroups()->getAllGroups();` is called for every element that has a user group field, requesting all the groups which won't change between entries, unless it's a CP request and a user group is added, edited or removed. 
This data can be cached per url by adding a flag to the following calls - 
`{% set groups = entry.userGroupFieldHandle.getGroups($cache = true) %}`

`{% if entry.userGroupFieldHandle.inGroup(currentUser, $cache = true) %}`

`{% if entry.userGroupFieldHandle.canAccess(currentUser, $cache = true) %}`

## Credit & Thanks
Originally created by the team at [Superbig](https://superbig.co/).

## Support
Get in touch with us via the [User Group Field Support page](https://verbb.io/craft-plugins/user-group-field/support) or by [creating a Github issue](https://github.com/verbb/user-group-field/issues)

## Sponsor
User Group Field is licensed under the MIT license, meaning it will always be free and open source – we love free stuff! If you'd like to show your support to the plugin regardless, [Sponsor](https://github.com/sponsors/verbb) development.

<h2></h2>

<a href="https://verbb.io" target="_blank">
    <img width="100" src="https://verbb.io/assets/img/verbb-pill.svg">
</a>
