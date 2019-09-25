# QuidPHP/Site

## About
**QuidPHP/Site** is an extended platform to build a website using the QuidPHP framework and LemurCMS. It is part of the [QuidPHP](https://github.com/quidphp/project) package. 

## License
**QuidPHP/Site** is available as an open-source software under the [MIT license](LICENSE).

## Documentation
**QuidPHP/Site** documentation is being written. Once ready, it will be available at https://quidphp.github.io/project.

## Installation
**QuidPHP/Site** can be easily installed with [Composer](https://getcomposer.org). It is available on [Packagist](https://packagist.org/packages/quidphp/site).
``` bash
$ composer require quidphp/site
```
Once installed, the **Quid\Site** namespace will be available within your PHP application.

## Requirement
**QuidPHP/Site** requires the following:
- PHP 7.3+
- All other requirements specified in [quidphp/core](https://github.com/quidphp/core)

## Dependency
**QuidPHP/Site** has the following dependencies:
- [quidphp/base](https://github.com/quidphp/base) | Quid\Base - PHP library that provides a set of low-level static methods
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
**QuidPHP/Site** contains 51 classes and traits. Here is an overview:
- [Boot](src/Boot.php) - Extended abstract class for the object that bootstraps the app and cms
- [Cell](src/Cell)
    - [GoogleMaps](src/Cell/GoogleMaps.php) - Class to work with a cell containing google maps geo-localization data
    - [JsonForm](src/Cell/JsonForm.php) - Class to work with a cell containing a json form (advanced jsonArray)
    - [JsonFormRelation](src/Cell/JsonFormRelation.php) - Class to manage a cell containing a relation value to another cell containing a json form
    - [Vimeo](src/Cell/Vimeo.php) - Class for dealing with a cell containing a vimeo video
    - [YouTube](src/Cell/YouTube.php) - Class for working with a cell containing a youTube video
- [Col](src/Col)
    - [EmailNewsletter](src/Col/EmailNewsletter.php) - Class for an email newsletter column (subscribes to a third-party newsletter)
    - [GoogleMaps](src/Col/GoogleMaps.php) - Class for a googleMaps column, with geo-localization data
    - [Hierarchy](src/Col/Hierarchy.php) - Class for an hierarchy column, like a website page sitemap
    - [JsonForm](src/Col/JsonForm.php) - Class for a column containing a json form (advanced jsonArray)
    - [JsonFormRelation](src/Col/JsonFormRelation.php) - Class to manage a column containing a relation value to another column which is a jsonForm
    - [TinyMce](src/Col/TinyMce.php) - Class for a column which transforms the textarea in a simple tinymce WYSIWYG editor
    - [TinyMceAdvanced](src/Col/TinyMceAdvanced.php) - Class for a column which transforms the textarea in a complex tinymce WYSIWYG editor
    - [Vimeo](src/Col/Vimeo.php) - Class for a column containing a vimeo video
    - [YouTube](src/Col/YouTube.php) - Class for a column containing a youTube video
- [Contract](src/Contract)
    - [Newsletter](src/Contract/Newsletter.php) - Interface to describe methods for a newsletter third-party service
- [Lang](src/Lang)
    - [En](src/Lang/En.php) - English language content used by this namespace
    - [Fr](src/Lang/Fr.php) - French language content used by this namespace
- [Role](src/Role.php) - Extended abstract class that provides app logic for a role
- [Route](src/Route)
    - [ContactSubmit](src/Route/ContactSubmit.php) - Abstract class for a contact submit route
    - [NewsletterSubmit](src/Route/NewsletterSubmit.php) - Abstract class for a newsletter submit route
    - [_breadcrumbs](src/Route/_breadcrumbs.php) - Trait that provides methods related to generating breadcrumbs
    - [_generalFeed](src/Route/_generalFeed.php) - Trait that grants methods related a general feed (load more)
    - [_page](src/Route/_page.php) - Trait that provides basic logic for a page route
    - [_pageBreadcrumbs](src/Route/_pageBreadcrumbs.php) - Trait that provides a method related to generating breadcrumbs for a page
    - [_pageSection](src/Route/_pageSection.php) - Trait that provides basic logic for a page route within a section
    - [_specificPointer](src/Route/_specificPointer.php) - Trait that grants methods to deal with a specific resource represent by a pointer (table/id)
    - [_specificSlug](src/Route/_specificSlug.php) - Trait with methods to work with a specific resource represent by a URI slug
    - [_specificSlugSection](src/Route/_specificSlugSection.php) - Trait to work with a specific resource, within a section, represent by a URI slug
- [Row](src/Row)
    - [Contact](src/Row/Contact.php) - Class to work with a row of the contact table, stores contact messages
    - [Media](src/Row/Media.php) - Class to work with a row of the media table, can contain medias, storages and videos
    - [Page](src/Row/Page.php) - Class for a row which represents a page
    - [Section](src/Row/Section.php) - Class for a row which represents a section containing one or many pages
    - [_meta](src/Row/_meta.php) - Trait with methods to make a row a meta-source
    - [_pageConfig](src/Row/_pageConfig.php) - Trait related to the configuration of a row representing a page
    - [_pageSection](src/Row/_pageSection.php) - Trait with methods to deal with a page row within a section
    - [_pageSectionConfig](src/Row/_pageSectionConfig.php) - Trait related to the configuration of a row representing a page within a section
    - [_pageSectionSlug](src/Row/_pageSectionSlug.php) - Trait related to the slug of a page within a section
    - [_sectionPages](src/Row/_sectionPages.php) - Trait related to a row representing a section which contains pages
- [Service](src/Service)
    - [Github](src/Service/Github.php) - Class that grants some static methods related to github
    - [GoogleAnalytics](src/Service/GoogleAnalytics.php) - Class that provides some methods to integrate googleAnalytics tracking
    - [GoogleGeocoding](src/Service/GoogleGeocoding.php) - Class used to make googleGeocoding localization requests
    - [GoogleMaps](src/Service/GoogleMaps.php) - Class used to generate javascript googleMaps
    - [IpApi](src/Service/IpApi.php) - Class that grants methods to use the ipApi API, which converts IP to localization data
    - [Mailchimp](src/Service/Mailchimp.php) - Class that provides some methods to communication with mailchimp (subscribe to a list)
    - [Office365](src/Service/Office365.php) - Class that grants some static methods related to office365
    - [PdfCrowd](src/Service/PdfCrowd.php) - Class that provides some methods to communication with pdfcrowd (and generate a pdf from html)
    - [TinyMce](src/Service/TinyMce.php) - Class that provides a method to integrate tinyMce WYSIWYG editor
    - [Vimeo](src/Service/Vimeo.php) - Class used to make requests to the vimeo API
    - [YouTube](src/Service/YouTube.php) - Class that can be used to make requests to the youTube API
- [Table](src/Table.php) - Extended class to represent an existing table within a database, adds app config
	
### Testing
**QuidPHP/Site** contains 3 test classes:
- [Boot](test/Boot.php) - Class for testing Quid\Site\Boot
- [Service](test/Service.php) - Class for testing services
- [Table](test/Table.php) - Class for testing table

**QuidPHP/Site** testsuite can be run by creating a new [quidphp/project](https://github.com/quidphp/project).

## JS

### Convention
- *Strict*: All files declare *use strict* on the first line.
- *ES5*: All code is compatible with ES5, there is no need for any JavaScript transpiler.
- *jQuery*: All behaviours and widgets are programmed on top of the jQuery library. Many functions are connected with jQuery.fn. Custom events are used across the board, a lot of calls to the jQuery [trigger](https://api.jquery.com/trigger/) and [triggerHandler](https://api.jquery.com/triggerHandler/) methods.
- *Include*: Some scripts are in the include folder. These scripts are used for the CMS but can also be reused within the application.

### Overview
**QuidPHP/Site** contains 3 JavaScript files and one folder. Here is an overview:
- [cms](js/cms)
    - [specific.js](js/cms/specific.js) - Script of additional behaviours for the specific form page of the CMS
- [include](js/include)
    - [service.js](js/include/service.js) - Script containing logic for third-party services
    - [widget.js](js/include/widget.js) - Script containing logic for some advanced widgets
- [vendor/tinymce](js/vendor/tinymce) - TinyMCE, the popular web-based WYSIWYG editor

## SCSS

### Convention
- *Mixins*: Nesting, variables and mixins are used within the SCSS stylesheets.

### Overview
**QuidPHP/Site** contains 2 SCSS stylesheets. Here is an overview:
- [cms](scss/cms)
    - [form.scss](scss/cms/form.scss) - Stylesheet for additional form inputs in the CMS
    - [tinymce.scss](scss/cms/tinymce.scss) - Stylesheet providing default styling for the tinymce wysiwyg editor