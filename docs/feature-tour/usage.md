# Usage
This plugin provides a Field Type where you can select one or more user groups. To create the field, go to Settings → Fields in your control panel.

## Field Settings
There is 3 display modes:
- As a dropdown, where you can select only 1 group.
- As a group of checkboxes, where you can select 1 or more groups.
- As a group of radio buttons, where you can select only 1 group.

## Templating
To list the group(s) selected:

```twig
{% set groups = entry.userGroupFieldHandle.getGroups() %}

{% for group in groups %}
    {{ group.name }}
{% endfor %}
```

To get only the first group:

```twig
{% set groups = entry.userGroupFieldHandle.getGroups() | first %}

{{ group.name }}
```

To check if the current user is in any of the groups selected:

```twig
{% if entry.userGroupFieldHandle.inGroup(currentUser) %}
    <h1>User can access</h1>
{% endif %}
```

To check if the current user can access something based on the group selection:

```twig
{% if entry.userGroupFieldHandle.canAccess(currentUser) %}
    <h1>User can access</h1>
{% endif %}
```

_Note: This check is always true for admins._

You can also query a User Group Field based on the `uid` of a user.

```twig
{% set userGroup = craft.app.userGroups.getGroupByHandle('myUserGroup') %}

{% set entries = craft.entries.section('blog').userGroupFieldHandle(userGroup.uid).all() %}
```

## Caching
By default `Craft::$app->getUserGroups()->getAllGroups();` is called for every element that has a user group field, requesting all the groups which won't change between entries, unless it's a CP request and a user group is added, edited or removed. 
This data can be cached per url by adding a flag to the following calls - 
`{% set groups = entry.userGroupFieldHandle.getGroups($cache = true) %}`

`{% if entry.userGroupFieldHandle.inGroup(currentUser, $cache = true) %}`

`{% if entry.userGroupFieldHandle.canAccess(currentUser, $cache = true) %}`
