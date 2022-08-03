# Changelog

## 3.0.0 - 2022-07-19

### Changed
- Now requires PHP `8.0.2+`.
- Now requires Craft `4.0.0+`.

## 2.0.2 - 2022-08-03

### Fixed
- Fix an error when running migrations. (thanks @fthues).

## 2.0.1 - 2022-07-19

### Added
- Add ability to query elements using the user group uid.
- Add Craft 2 migration.

## 2.0.0 - 2022-07-18

> {note} The plugin’s package name has changed to `verbb/user-group-field`. User Group Field will need be updated to 2.0 from a terminal, by running `composer require verbb/user-group-field && composer remove superbig/craft-usergroupfield`.

### Changed
- Migration to `verbb/user-group-field`.
- Now requires Craft 3.7+.

## 1.0.4 - 2021-10-04

### Fixed
- Fixed error where user groups was not getting saved ([#16](https://github.com/verbb/user-group-field/pull/16))

## 1.0.3 - 2020-07-28

### Fixed
- Fixed error where it was impossible to remove any previously selected groups ([#12](https://github.com/verbb/user-group-field/pull/12))

## 1.0.2 - 2019-09-10

### Fixed
- Fixed error when no groups was returned

## 1.0.1 - 2019-02-25

### Fixed
- Fixed Craft constraint. Only works with 3.1 and up.

## 1.0.0 - 2019-02-22

### Added
- Initial release
