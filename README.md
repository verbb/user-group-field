# User Group Field plugin for Craft CMS
A field type that lets you select one or more user groups.

## Installation
You can install User Group Field via the plugin store, or through Composer.

### Craft Plugin Store
To install **User Group Field**, navigate to the _Plugin Store_ section of your Craft control panel, search for `User Group Field`, and click the _Try_ button.

### Composer
You can also add the package to your project using Composer.

1. Open your terminal and go to your Craft project:

        cd /path/to/project

2. Then tell Composer to load the plugin:
    
        composer require verbb/user-group-field

3. In the Control Panel, go to Settings → Plugins and click the “Install” button for User Group Field.

## Usage
This plugin provides a Field Type where you can select one or more user groups. To create the field, go to Settings → Fields in your control panel.

### Field Settings
There is 3 display modes:
- As a dropdown, where you can select only 1 group.
- As a group of checkboxes, where you can select 1 or more groups.
- As a group of radio buttons, where you can select only 1 group.

### Templating
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

## Credits
Originally created by the team at [Superbig](https://superbig.co/).

## Show your Support
User Group Field is licensed under the MIT license, meaning it will always be free and open source – we love free stuff! If you'd like to show your support to the plugin regardless, [Sponsor](https://github.com/sponsors/verbb) development.

<h2></h2>

<a href="https://verbb.io" target="_blank">
    <img width="100" src="https://verbb.io/assets/img/verbb-pill.svg">
</a>
