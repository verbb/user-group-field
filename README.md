# User Group Field plugin for Craft CMS 3.x

Field type that let you select one or more user groups

![Icon](resources/icon.png)

## Screenshot

![Screenshot](resources/screenshot.png)

## Requirements

This plugin requires Craft CMS 3.0.0 or later.

## Installation

To install the plugin, follow these instructions.

1. Open your terminal and go to your Craft project:

        cd /path/to/project

2. Then tell Composer to load the plugin:

        composer require superbigco/craft-usergroupfield

3. In the Control Panel, go to Settings → Plugins and click the “Install” button for User Group Field.

## User Group Field Overview

This plugin provides an Field Type where you can select one or more user groups.

## Configuring User Group Field

There is 3 display modes:
- As a dropdown, where you can select only 1 group
- As a group of checkboxes, where you can select 1 or more groups
- As a group of radio buttons, where you can select only 1 group

## Using User Group Field

To list the group(s) selected:
```twig
{% set groups = entry.userGroupFieldHandle.getGroups() %}
{% for group in groups %}
    {{ group.name }}
{% endfor %}
```

To get only the first group
```twig
{% set groups = entry.userGroupFieldHandle.getGroups()|first %}
{{ group.name }}
```

To check if the current user is in any of the groups selected
```twig
{% if entry.userGroupDropdown.inGroup(currentUser) %}
    <h1>User can access</h1>
{% endif %}
```

To check if the current user can access something based on the group selection
```twig
{% if entry.userGroupDropdown.canAccess(currentUser) %}
    <h1>User can access</h1>
{% endif %}
```

_Note: This check is always true for admins._

Brought to you by [Superbig](https://superbig.co)
