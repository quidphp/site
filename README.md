# QuidPHP/Site
[![Release](https://img.shields.io/github/v/release/quidphp/site)](https://packagist.org/packages/quidphp/site)
[![License](https://img.shields.io/github/license/quidphp/site)](https://github.com/quidphp/site/blob/master/LICENSE)
[![PHP Version](https://img.shields.io/packagist/php-v/quidphp/site)](https://www.php.net)
[![Style CI](https://styleci.io/repos/206858888/shield)](https://styleci.io)
[![Code Size](https://img.shields.io/github/languages/code-size/quidphp/site)](https://github.com/quidphp/site)

## About
**QuidPHP/Site** is an extended platform to build a website using the QuidPHP framework and LemurCMS. It is part of the [QuidPHP](https://github.com/quidphp/project) package. 

## License
**QuidPHP/Site** is available as an open-source software under the [MIT license](LICENSE).

## Documentation
**QuidPHP/Site** documentation is available at [QuidPHP/Docs](https://github.com/quidphp/docs).

## Installation
**QuidPHP/Site** can be easily installed with [Composer](https://getcomposer.org). It is available on [Packagist](https://packagist.org/packages/quidphp/site).
``` bash
$ composer require quidphp/site
```
Once installed, the **Quid\Site** namespace will be available within your PHP application.

## Requirement
**QuidPHP/Site** requires the following:
- PHP 8.1
- All requirements of [quidphp/lemur](https://github.com/quidphp/lemur)
- Any modern browser (not Internet Explorer)

## Dependency
**QuidPHP/Site** has the following dependency:
- [quidphp/lemur](https://github.com/quidphp/lemur) - Quid\Lemur - LemurCMS, a content management system built on top of the QuidPHP framework

All dependencies will be resolved by using the [Composer](https://getcomposer.org) installation process.

## Comment
**QuidPHP/Site** code is commented and all methods are explained (in French).

## PHP

### Convention
**QuidPHP/Site** is built on the following conventions:
- *Core overloading*: This namespace overloads many classes from Quid\Core and Quid\Lemur.
- *Auto-alias*: All class names that finishes by Alias will resolve to the existing class if no alias exists. Exemple: MyRole extents RoleAlias -> will resolve to Role if no alias is found.
- *Traits*: Traits filenames start with an underscore (_).
- *Type*: Files, function arguments and return types are strict typed.
- *Config*: A special $config static property exists in all classes. This property gets recursively merged with the parents' property on initialization.
- *Coding*: No curly braces are used in a IF statement if the condition can be resolved in only one statement.

### Overview
**QuidPHP/Site** contains 49 classes, traits and interfaces. Here is an overview:
- [App](src/App)
    - [CliClearAll](src/App/CliClearAll.php) - Class for a cli route to remove all cached and logged data
    - [CliClearCache](src/App/CliClearCache.php) - Class for a cli route to remove all cached data
    - [CliClearLog](src/App/CliClearLog.php) - Class for a cli route to remove all log data
    - [CliCompile](src/App/CliCompile.php) - Class for a cli route to compile assets (js and css)
    - [CliPreload](src/App/CliPreload.php) - Class for a cli route to generate the preload PHP script
    - [CliSessionGc](src/App/CliSessionGc.php) - Class for a cli route to remove expired sessions for the app
    - [CliVersion](src/App/CliVersion.php) - Class for a version route of the app, accessible via the cli
    - [Error](src/App/Error.php) - Abstract class for the error route of the app
    - [Home](src/App/Home.php) - Abstract class for the home route of the app
    - [Robots](src/App/Robots.php) - Class for the robots.txt route of the app
    - [Sitemap](src/App/Sitemap.php) - Class for the automated sitemap.xml route of the app
- [Boot](src/Boot.php) - Extended abstract class for the object that bootstraps the app and cms
- [Cell](src/Cell)
    - [EmailNewsletter](src/Cell/EmailNewsletter.php) - Class for an email newsletter cell (subscribes to a third-party newsletter)
    - [GoogleMaps](src/Cell/GoogleMaps.php) - Class to work with a cell containing Google maps geo-localization data
    - [JsonForm](src/Cell/JsonForm.php) - Class to work with a cell containing a json form
    - [JsonFormRelation](src/Cell/JsonFormRelation.php) - Class to manage a cell containing a relation value to another cell containing a json form
- [Cms](src/Cms)
    - [CliPreload](src/Cms/CliPreload.php) - Class for a cli route to generate the preload PHP script for the CMS
- [Col](src/Col)
    - [EmailNewsletter](src/Col/EmailNewsletter.php) - Class for an email newsletter column (subscribes to a third-party newsletter)
    - [Embed](src/Col/Embed.php) - Class for a column containing an embed video (from youtube or vimeo)
    - [GoogleMaps](src/Col/GoogleMaps.php) - Class for a GoogleMaps column, with geo-localization data
    - [Hierarchy](src/Col/Hierarchy.php) - Class for an hierarchy column, like a website page sitemap
    - [JsonForm](src/Col/JsonForm.php) - Class for a column containing a json form
    - [JsonFormRelation](src/Col/JsonFormRelation.php) - Class to manage a column containing a relation value to another column which is a jsonForm
    - [Route](src/Col/Route.php) - Class for a column that creates an enum relation with route classes
    - [Vimeo](src/Col/Vimeo.php) - Class for a column containing a Vimeo video
    - [YouTube](src/Col/YouTube.php) - Class for a column containing a YouTube video
- [Contract](src/Contract)
    - [Newsletter](src/Contract/Newsletter.php) - Interface to describe methods for a newsletter third-party service
- [Db](src/Db.php) - Extended class used to query the database, adds app config
- [Lang](src/Lang)
    - [En](src/Lang/En.php) - English language content used by this namespace
    - [Fr](src/Lang/Fr.php) - French language content used by this namespace
- [Route](src/Route.php) - Extended abstract class for a route, adds app logic
    - [NewsletterSubmit](src/Route/NewsletterSubmit.php) - Abstract class for a newsletter submit route
    - [_breadcrumbs](src/Route/_breadcrumbs.php) - Trait that provides methods related to generating breadcrumbs
    - [_general](src/Route/_general.php) - Trait that provides basic methods used for a general route
    - [_page](src/Route/_page.php) - Trait that provides basic logic for a page route
    - [_pageBreadcrumbs](src/Route/_pageBreadcrumbs.php) - Trait that provides a method related to generating breadcrumbs for a page
    - [_specific](src/Route/_specific.php) - Trait that provides basic methods used for a specific route
- [Row](src/Row.php) - Extended class to represent a row within a table, adds app config
    - [Page](src/Row/Page.php) - Class for a row which represents a page
    - [User](src/Row/User.php) - Extended class for a row of the user table, with app logic
    - [_pageConfig](src/Row/_pageConfig.php) - Trait related to the configuration of a row representing a page
- [Service](src/Service)
    - [GoogleAnalytics](src/Service/GoogleAnalytics.php) - Class that provides some methods to integrate GoogleAnalytics tracking
    - [GoogleGeocoding](src/Service/GoogleGeocoding.php) - Class used to make GoogleGeocoding localization requests
    - [GoogleMaps](src/Service/GoogleMaps.php) - Class used to generate javascript GoogleMaps
    - [GoogleTagManager](src/Service/GoogleTagManager.php) - Class used to generate the googleTagManager trackers
    - [Mailchimp](src/Service/Mailchimp.php) - Class that provides some methods to communicate with Mailchimp using api 3
    - [PdfCrowd](src/Service/PdfCrowd.php) - Class that provides some methods to communicate with Pdfcrowd (and generate a pdf from html)
    - [Vimeo](src/Service/Vimeo.php) - Class used to make requests to the Vimeo API
    - [YouTube](src/Service/YouTube.php) - Class that can be used to make requests to the YouTube API
	
### Testing
**QuidPHP/Site** contains 3 test classes:
- [Boot](test/Boot.php) - Class for testing Quid\Site\Boot
- [Db](test/Db.php) - Class for testing Quid\Site\Db
- [Suite](test/Suite)
    - [BootSite](test/Suite/BootSite.php) - Class for booting the Quid\Site testsuite

**QuidPHP/Site** testsuite can be run by creating a new [QuidPHP/Assert](https://github.com/quidphp/assert) project.

## JavaScript

### Convention
- *ES5*: All code is compatible with ES5, there is no need for any JavaScript transpiler.
- *Strict*: All generated files declare *use strict* on the first line.
- *Compiling*: The concatenation of the JS files is done on the PHP side.

### Overview
**QuidPHP/Site** contains 14 JavaScript files. Here is an overview:
- [cms](js/cms)
    - [jsonForm](js/cms/jsonForm.js) - Script containing logic for the jsonForm component which is based on the addRemove input
    - [site](js/cms/site.js) - Script of additional behaviours for the specific form page of the CMS
- [component](js/component)
    - [carouselScroll](js/component/carouselScroll.js) - Script for a carousel component which scrolls
    - [clickRemove](js/component/clickRemove.js) - Component that removes itself on click
    - [googleAnalytics](js/component/googleAnalytics.js) - Script containing logic for googleAnalytics
    - [googleMaps](js/component/googleMaps.js) - Script containing logic for a simple googleMaps component
    - [hoverSlide](js/component/hoverSlide.js) - Component to change height of target with mouseenter/mouseleave
    - [preload](js/component/preload.js) - Component to preload assets, currently only images
    - [scrollSections](js/component/scrollSections.js) - Script containing logic for scrolling multiple sections linked to a hash
    - [tabsScroll](js/component/tabsScroll.js) - Component that adds scrolling support to tabsSlider
    - [tabsSlider](js/component/tabsSlider.js) - Component that adds timeout and iframe support to the tabsNav component
    - [toggler](js/component/toggler.js) - Component to toggle attributes on many elements using a trigger
    - [windowSmall](js/component/windowSmall.js) - Component to open a small window from an anchor link
    - [wrapConsecutive](js/component/wrapConsecutive.js) - Component to wrap consecutive nodes of the same type in another node

## CSS

### Convention
- *SCSS*: Nesting, variables and mixins are used within the stylesheets.
- *Compiling*: The compiling and concatenation of the SCSS files is done on the PHP side.

### Overview
**QuidPHP/Site** contains 8 SCSS stylesheets. Here is an overview:
- [cms](css/cms)
    - [site](css/cms/site.scss) - Stylesheet to bind styles to the components
- [cms-component](css/cms-component)
    - [emailNewsletter](css/cms-component/emailNewsletter.scss) - Stylesheet for the emailNewsletter component
    - [googleMaps](css/cms-component/googleMaps.scss) - Stylesheet for the Google maps component
    - [hierarchy](css/cms-component/hierarchy.scss) - Stylesheet for the hierarchy component
    - [jsonForm](css/cms-component/jsonForm.scss) - Stylesheet for the jsonForm component
    - [range](css/cms-component/range.scss) - Stylesheet for the range component
    - [video](css/cms-component/video.scss) - Stylesheet for the video component
- [component](css/component)
    - [tabsSlider](css/component/tabsSlider.scss) - Stylesheet for the tabsSlider component