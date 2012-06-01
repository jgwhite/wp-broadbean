=== Broadbean ===
Contributors: jgwhite, lawrencebrown
Tags: broadbean, adcourier, job, boards
Requires at least: 3.3.1
Tested up to: 3.3.1
Stable tag: trunk

Provides endpoint and integration with Broadbean's AdCourier Jobs Board API,
as described here:

http://api.adcourier.com/docs/index.cgi?page=jobboards_overview

NB: This plugin is a work-in-progress.

== Description ==

This plugin adds an endpoint for receiving Broadbean AdCourier job postings.

It also adds a new 'Job' custom post type.

When job postings are received from Broadbean, Job posts are generated with
all available metadata.

== Installation ==

1. Upload plugin files to wp-content/plugins/broadbean.
2. Activate Broadbean through the 'Plugins' menu in Wordpress.
3. Choose a username and password (you'll share these with Broadbean later)
   then go to the plugin's settings page and enter them in the fields provided.
4. Finally, submit your details to Broadbean here:
   http://api.adcourier.com/docs/index.cgi?page=jobboards_register

The endpoint address is '/broadbean-inbox' so in the fields
'Posting URL (LIVE environment)' and
'Staging URL (TEST environment where available)' enter
'http://www.yoursite.com/broadbean-inbox'.

The username/password you defined earlier should be entered both in
'Test credentials' and 'List of fields to be submitted'.

I'm not going to go into too much detail of how to fill in the rest of the
form because quite frankly I'm not too sure of the fine details yet.

== Frequently Asked Questions ==

=== Who is this for? ===

Anyone who uses Broadbean AdCourier Jobs Board to manage ad postings,
and would like those postings to automatically appear on their own
Wordpress site.

=== Where can I get more help? ===

Please get in touch with us on Twitter (@jgwhite).

== Screenshots ==

TODO

== Changelog ==

TODO

== Contributors ==

* [jgwhite](http://jgwhite.co.uk/)
* [lawrencebrown](http://lawrencebrown.eu/)

== Contributing ==

We'd welcome contributions from anyone else working with the Broadbean API.
Fork the project, submit issues, get in touch by whatever means.

