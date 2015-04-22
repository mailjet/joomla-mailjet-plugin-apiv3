# [API v3] Mailjet for Joomla

Contributors: mailjet  
Tags: email, marketing, signup, newsletter, widget, smtp, mailjet  
Requires at least: 3.0.0  
Tested up to: 3.3.0  
Stable tag: 3.1.8  
License: GPLv2 or later  
License URI: http://www.gnu.org/licenses/gpl-2.0.html  

The Mailjet plugin allows you to Send both transactional and marketing emails from your Joomla site by reconfiguring the mail settings to use Mailjet’s enhanced SMTP relay in conjunction with signup widgets that sync new user subscriptions directly to Mailjet as well as provide an easy drag-and-drop marketing newsletter tool to send marketing emails directly from with your site.
## Description


Mailjet's official plugin for Joomla will:

* Send both transactional and marketing emails from your Joomla site;
* Reconfigure your mail settings to use Mailjet's SMTP for enhanced deliverability and tracking;
* Create an "Options" page to manage your email settings;
* To easily create contact lists and manage personalization variables;
* To create and send marketing campaigns using a drag and drop Newsletter builder or selecting from our comprehensive template library;
* Have the insight you need from a real-time dashboard showing opens, clicks, geographies, average time to click, user agents, etc;
* Provide a signup widget that allows your visitors to sign up directly to your Mailjet mailing lists;


### Secure & real-time SMTP relay for all your emails
- A lot of features and plugins from your Joomla site send email notifications. All these messages are very important for your users, as well as for your business. Therefore, you want to track success and ensure a successful delivery.

Our plugin simply overrides the Joomla mail settings, to use Mailjet's SMTP instead. This will improve your deliverability. You will also get live and in-depth statistics to track and optimize in real time. Making the choice of Mailjet is the right solution for your transactional emails , bulk emails and newsletter emails.


### Sign up form & contact lists Management
-  Another great feature of this plugin is the sign up form Widget. It allows your site visitors to join your Mailjet lists. You can create contact lists directly from the plugin dashboard. The Widget will let you add forms to any post or any page.

### Mailjet’s latest generation v3 iframes
-  Last but not least, the plugin features Mailjet’s latest v3 iframes to manage contacts, create drag-and-drop marketing campaigns and view in depth sending statistics directly from within the plug-in.


### Installing the Plugin
-  Enable Mailjet's Plugin like you would do for any other Joomla extension. Enter your Mailjet API and Secret Key credentials and refer to the FAQs for any other information. If you don't have a Mailjet account yet, signup now for free!


## Installation


1. Log in as administrator in Joomla.
2. Go to Extensions > Add and send Joomla-Mailjet-3.1.zip.
3. Activate the Mailjet extension through the 'Extensions' menu in Joomla.


## Frequently Asked Questions


### What is Mailjet?
[Mailjet](http://www.mailjet.com) is an all-in-one solution to send, track and deliver both marketing and transactional emails. Its Cloud-Based infrastructure is unique and highly scalable. A proprietary technology optimizes the sending and the deliverability of your messages.

### Why use Mailjet as an SMTP relay for Joomla ?
1 in 4 legitimate email gets lost or ends up a the spam folder. By Sending your email through Mailjet's SMTP relay, you will avoid this and make sure that your recipients receive your messages in real time, straight into their inbox. You will also be able to track the delivery (opens, bounces, etc.) as well as the activity of your emails (clicks, opens, etc.). On top of that, tools such as our Newsletter editor will let you create and send a beautiful marketing campaign in only a few minutes. All this added value comes with no engagement and very low prices.

### Do I need a Mailjet Account?
Yes. You can create one for free: it's easy and it only takes a few minutes.

### How to get started with this plugin?
Once you have a Mailjet account, an installation Wizard will guide you through. You want to use Mailjet as an SMTP relay, so you will need to change these parameters in your Joomla email configuration: username and password. These credentials are provided in your “My Account > API Keys” section: https://app.mailjet.com/account/api_keys.

### How do I get synchronize my lists?
Synchronization is automatic, that's the beauty of this plugin! It doesn't matter whether your lists were uploaded on your Joomla interface or on your Mailjet account: they will always remain up-to-date on both sides.

### In which languages is this plugin available?
This plugin is currently available in English. Support can be provided in English, Spanish, German and French via our online Helpdesk at https://www.mailjet.com/support/ticket.

### How do I create a signup form?
Once your Mailjet plugin is installed, click on "Extensions" in the top menu and choose the "Module Manager" option. Then, search and click on the Mailjet module, choose a position and the pages where the widget should appear. 


## Screenshots

1. Simply change a few parameters to get started.
2. Manage your lists and contacts in no time!
3. Create and send beautiful email campaigns
4. View detailed statistics about your account


## Changelog
= 3.1.8 =
* The Spanish language now functions properly. When submitting api and secret keys the error messages (if any) are displayed in the corresponding language instead of English only.

= 3.1.7= 
* When we create a TOKEN we also send SentData containing plugin name

= 3.1.6= 
* When API key or Secret key are wrong, a corresponding error message is displayed

= 3.1.5=
* The "recipient email" on the Settings page is now saved correctly

= 3.1.4=
* In the Settings page the left sidebar is now displayed.

= 3.1.3=
* Modification of how to detect v1 and v3 users.

= 3.1.2=
* Bug fix related to the compatibility of the plugin for v1 and v3 users

= 3.1.1=
* The generation of auth token for V1 user has to be executed via CURL, but not only with file_get_contents.

= 3.1.0=
* Supports V1 and V3 Mailjet's users

= 3.0.2 =
* Add signup and use tracking on the Joomla plugin.
* Fix IsActive parameter for token creation.

= 3.0.1 =
* The plugin has been updated to use contact, campaign and stats iFrames. The update also includes compatibility with all Mailjet v3 accounts. 
* Any historical Mailjet v1 accounts will need to use the v1 version of the plug-in (https://www.mailjet.com/plugin/joomla.htm).
* Additional GET parameters are added for some of the URL's, sending additional information to Mailjet.
