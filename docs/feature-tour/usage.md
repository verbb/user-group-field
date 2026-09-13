# Usage
This plugin provides a Field Type where you can select one or more user groups. To create the field, go to Settings → Fields in your control panel.

## Field Settings
There are three display modes:
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
{% set group = entry.userGroupFieldHandle.getGroups() | first %}

{% if group %}
    {{ group.name }}
{% endif %}
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

You can also query a User Group Field using the `uid` of a user group.

```twig
{% set userGroup = craft.app.userGroups.getGroupByHandle('myUserGroup') %}

{% set entries = craft.entries.section('blog').userGroupFieldHandle(userGroup.uid).all() %}
```

## Caching
When you render this field across many entries, you can enable caching of the available user groups. Pass `true` as the cache argument in Twig:

```twig
{% set groups = entry.userGroupFieldHandle.getGroups(true) %}

{% if entry.userGroupFieldHandle.inGroup(currentUser, true) %}
    <p>You belong to one of the selected groups.</p>
{% endif %}

{% if entry.userGroupFieldHandle.canAccess(currentUser, true) %}
    <p>You have access.</p>
{% endif %}
```

`inGroup()` checks membership, while `canAccess()` also grants access to administrators. Both return `false` for a guest. These conditions control the output of this template; hiding a link does not protect its destination. Apply the corresponding access check on the page that serves restricted content too.
