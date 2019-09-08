# QuidPHP/Site

## About
**QuidPHP/Site** is an extended platform to build a website using the QuidPHP framework and LemurCMS. It is part of the [QuidPHP](https://github.com/quidphp/project) package. 

## License
**QuidPHP/Site** is available as an open-source software under the [MIT license](LICENSE).

## Installation
**QuidPHP/Site** can be easily installed with [Composer](https://getcomposer.org). It is available on [Packagist](https://packagist.org/packages/quidphp/site).
``` bash
$ composer require quidphp/site
```
Once installed, the **Quid\Site** namespace will be available within your PHP application.

## Requirement
**QuidPHP/Site** requires the following:
- PHP 7.2+ with fileinfo, curl, openssl, posix, PDO and pdo_mysql

## Dependency
**QuidPHP/Site** has the following dependencies:
- [quidphp/base](https://github.com/quidphp/base) | Quid\Base - PHP library that provides a large set of low-level static methods
- [quidphp/main](https://github.com/quidphp/main) | Quid\Main - PHP library that provides a set of base objects and collections 
- [quidphp/orm](https://github.com/quidphp/orm) | Quid\Orm - PHP library that provides database access and a comprehensive Object-Relational Mapper
- [quidphp/routing](https://github.com/quidphp/routing) | Quid\Routing - PHP library that provides a simple route matching and triggering procedure
- [quidphp/core](https://github.com/quidphp/core) | Quid\Core - PHP library that provides an extendable platform to create dynamic applications
- [quidphp/lemur](https://github.com/quidphp/lemur) | Quid\Lemur - LemurCMS, a content management system built on top of the QuidPHP framework
- [verot/class.upload.php](https://github.com/verot/class.upload.php) | Verot\Upload - A popular PHP class used for resizing images
- [phpmailer/phpmailer](https://github.com/phpmailer/phpmailer) | PHPMailer\PHPMailer - The classic email sending library for PHP
- [tedivm/jshrink](https://github.com/tedious/JShrink) | JShrink - Javascript Minifier built in PHP
- [scssphp/scssphp](https://github.com/scssphp/scssphp) | ScssPhp\ScssPhp - SCSS compiler written in PHP

All dependencies will be resolved by using the [Composer](https://getcomposer.org) installation process.

## Included
**QuidPHP/Site** comes bundled with the following front-end package:
- [tinymce/tinymce](https://github.com/tinymce/tinymce) | TinyMCE - The popular web-based WYSIWYG editor

## Comment
**QuidPHP/Site** code is commented and all methods are explained. However, most of the comments are currently written in French.

## PHP

### Convention
**QuidPHP/Site** is built on the following conventions:
- *Traits*: Traits filenames start with an underscore (_).
- *Coding*: No curly braces are used in a IF statement if the condition can be resolved in only one statement.
- *Type*: Files, function arguments and return types are strict typed.
- *Config*: A special $config static property exists in all classes. This property gets recursively merged with the parents' property on initialization.
- *Auto-alias*: All class names that finishes by Alias will resolve to the existing class if no alias exists. Exemple: MyRole extents RoleAlias -> will resolve to Role if no alias is found.
- *Core overloading*: This namespace overloads many classes from Quid\Core and Quid\Lemur.

### Overview
**QuidPHP/Site** contains 57 classes and traits. Here is an overview:
- [Boot](src/Boot.php)
- [Cell](src/Cell)
    - [GoogleMaps](src/Cell/GoogleMaps.php)
    - [JsonForm](src/Cell/JsonForm.php)
    - [JsonFormRelation](src/Cell/JsonFormRelation.php)
    - [Vimeo](src/Cell/Vimeo.php)
    - [YouTube](src/Cell/YouTube.php)
- [Col](src/Col)
    - [EmailNewsletter](src/Col/EmailNewsletter.php)
    - [GoogleMaps](src/Col/GoogleMaps.php)
    - [Hierarchy](src/Col/Hierarchy.php)
    - [JsonForm](src/Col/JsonForm.php)
    - [JsonFormRelation](src/Col/JsonFormRelation.php)
    - [TinyMce](src/Col/TinyMce.php)
    - [TinyMceAdvanced](src/Col/TinyMceAdvanced.php)
    - [Vimeo](src/Col/Vimeo.php)
    - [YouTube](src/Col/YouTube.php)
- [Contract](src/Contract)
    - [Newsletter](src/Contract/Newsletter.php)
- [Lang](src/Lang)
    - [En](src/Lang/En.php)
    - [Fr](src/Lang/Fr.php)
- [Route](src/Route)
    - [ContactSubmit](src/Route/ContactSubmit.php)
    - [NewsletterSubmit](src/Route/NewsletterSubmit.php) | MewsletterSubmit
    - [_breadcrumbs](src/Route/_breadcrumbs.php)
    - [_generalFeed](src/Route/_generalFeed.php)
    - [_page](src/Route/_page.php)
    - [_pageBreadcrumbs](src/Route/_pageBreadcrumbs.php)
    - [_pageSection](src/Route/_pageSection.php)
    - [_specificPointer](src/Route/_specificPointer.php)
    - [_specificSlug](src/Route/_specificSlug.php)
    - [_specificSlugSection](src/Route/_specificSlugSection.php)
- [Row](src/Row)
    - [Contact](src/Row/Contact.php)
    - [Document](src/Row/Document.php)
    - [Event](src/Row/Event.php)
    - [EventSubmit](src/Row/EventSubmit.php)
    - [Form](src/Row/Form.php)
    - [FormSubmit](src/Row/FormSubmit.php)
    - [Media](src/Row/Media.php)
    - [News](src/Row/News.php)
    - [Page](src/Row/Page.php)
    - [PageContent](src/Row/PageContent.php)
    - [Poll](src/Row/Poll.php)
    - [PollSubmit](src/Row/PollSubmit.php)
    - [Section](src/Row/Section.php)
    - [_meta](src/Row/_meta.php)
    - [_pageConfig](src/Row/_pageConfig.php)
    - [_pageSection](src/Row/_pageSection.php)
    - [_pageSectionConfig](src/Row/_pageSectionConfig.php)
    - [_pageSectionSlug](src/Row/_pageSectionSlug.php)
    - [_sectionPages](src/Row/_sectionPages.php)
- [Service](src/Service)
    - [Github](src/Service/Github.php) | GitHub
    - [GoogleAnalytics](src/Service/GoogleAnalytics.php)
    - [GoogleGeocoding](src/Service/GoogleGeocoding.php)
    - [GoogleMaps](src/Service/GoogleMaps.php)
    - [IpApi](src/Service/IpApi.php)
    - [Mailchimp](src/Service/Mailchimp.php)
    - [Office365](src/Service/Office365.php)
    - [PdfCrowd](src/Service/PdfCrowd.php)
    - [Vimeo](src/Service/Vimeo.php)
    - [YouTube](src/Service/YouTube.php)
	
### Testing
**QuidPHP/Site** contains 3 test classes:
- [Boot](test/Boot.php) | Class for testing Quid\Site\Boot
- [Service](test/Service.php) | Class for testing services
- [Table](test/Table.php) | Class for testing table

**QuidPHP/Site** testsuite can be run by creating a new [quidphp/project](https://github.com/quidphp/project).

## JS

### Convention
- *jQuery*: All behaviours and widgets are programmed on top of the jQuery library. Many functions are connected with jQuery.fn. Custom events are used across the board, a lot of calls to the jQuery [trigger](https://api.jquery.com/trigger/) and [triggerHandler](https://api.jquery.com/triggerHandler/) methods.
- *Include*: Some scripts are in the include folder. These scripts are used for the CMS but can also be reused within the application.

### Overview
**QuidPHP/Site** contains 3 JavaScript files and one folder. Here is an overview:
- [cms](js/cms)
    - [specific.js](js/cms/specific.js) | Script of additional behaviours for the specific form page of the CMS
- [include](js/include)
    - [service.js](js/include/service.js) | Script containing logic for third-party services
    - [widget.js](js/include/widget.js) | Script containing logic for some advanced widgets
- [tinymce](js/tinymce) | TinyMCE - The popular web-based WYSIWYG editor

## SCSS

### Convention
- *Mixins*: Nesting, variables and mixins are used within the SCSS stylesheets.

### Overview
**QuidPHP/Site** contains 2 SCSS stylesheets. Here is an overview:
- [cms](scss/cms)
    - [form.scss](scss/cms/form.scss) | Stylesheet for additional form inputs in the CMS
    - [tinymce.scss](scss/cms/tinymce.scss) | Stylesheet providing default styling for the tinymce wysiwyg editor