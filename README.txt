=== Custom Job Fields for WP Job Manager ===
Contributors: devcrazy
Donate link: https://paypal.me/888999517
Tags: wp-job-manager, custom field, job board
Requires at least: 5.3
Tested up to: 5.5
Stable tag: 5.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Custom Job Fields for WP Job Manager is flexible and easy to add your custom fields for WP Job Manager.

== Description ==

Custom Job Fields for WP Job Manager is flexible and easy to use plugin that allows you to add custom various types of fields for WP Job Manager pages.

This plugin does not work alone. It is created for [WP Job Manager](https://wordpress.org/plugins/wp-job-manager/ "WP Job Manager plugin"), so you have to install WP Job Manager before.

Supported field types:

* Date
* Radio
* Select
* Text
* Check Box
* Check Box Group
* Tag Input

## Usage

Setting page is under `Job Listings` Menu. In admin page, go to **Job Listings > Job Manager Custom Fields**.
And then all you need to do is to input field attributes as you want and click `submit`.

You can see `Cfwjm Tag` attribute, it can be used for grouping fields.

For example, consider the job you are posting may have a salary range, not an exact one.

Then, it needs at least two fields: `salary_from`, `salary_to`.

Also, you may have to add a salary type indicating `hourly`, `weekly`, or `monthly`.

These fields are all related to salary. And you can add your custom tag like `cfwjm_salary`. The prefix `cfwjm_` is not required.

We can use this `Cfwjm Tag` for future purposes in the next version.

**Note:** `Key` attribute is snake-case(recommended) while `Label` is any format you want.
`Key` is used for management purposes and `Label` is displayed on the front-end.

For example, ```salary_type``` is for `Key` and ```Salary Type``` is for `Label`

And `Arena` is to indicate whether Job specific or a Company specific field.
