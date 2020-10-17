=== Easy Ajax Pagination ===
Tags: pagination, infinite, infinite scroll, load more, ajax, ajax reload
Requires at least: 5.4.2
Tested up to: 5.5.1
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A lightweight and useful plugin that provides an easy way to add infinite scrolling or ajax pagination to your site.

== Description ==

The plugin adds a shortcode with parameters. You can set a shortcode in a container with downloadable elements and get either infinite scrolling, a button, or pagination depending on your preferences. Also, if you prefer the button, you can enable the joint mode - this is when the "load more" button and pagination are together, just like in popular online stores. If you like old school, you can choose the pagination mode. Don't worry, pagination in this case also uses ajax.
This plugin has a cool feature - support for forms with parameters. This means that you can use ajax loading even on pages with filters!
The advantage of the plugin is that it works with the main WP query without changing it in any way. All types of records are supported. You just need to insert a shortcode, specify the container selector and type element (see below) ...voila, everything works!
Thus, we can say that it is almost universal for all cases.

**Try it, it will suit you! It's free!**

### Features

-  **Variability** - the plugin supports 4 types of ajax loading: infinite scrolling, "load more" button, ajax pagination, "load more" button + ajax pagination
-  **Versatility** - the plugin will work with your theme without any problems in most cases
-  **Form params** - The plugin supports forms with parameters for reloading filtered and sorted entities. However, there is something to pay attention to, see below...
-  **Setting Panel** - Here you can choose the plugin mode (infinite scroll, button, button + pagination, pagination), customize buttons and pagination, and set your own loading icon.
-  **Fast and easy** - copy the shortcode pattern, set selectors, insert the shortcode into the template and you're done!

### Shortcodes parameters

-  **container** - selector of the container that your posts will be loaded into. Required.
-  **element** - selector of the element that will be loaded into the container. Required.
-  **form** - selector of the form with params. Optional.

#### Example Easy Ajax Pagination Shortcode

    [eap_load container='#content' element='.element-block' form='#filter-form']

### Plugin filters and actions

Здесь опишем зарегистированные фильтры и экшены

### How to use

#### Without filter and sorting logic

1) First, copy the example shortcode and paste it at the end of the container with elements. For example, the theme twentytwenty: <main>, and the element in this case will be <article>.
2) Put in the shortcode settings the selector of the container and the selector of the container element
3) Save template
4) ...
5) Profit

#### With filter and sorting logic

1) p.1 and p.2 as above
2) Add form params with next specific:

    //FILTERING
    <input type='checkbox' name='TAXONOMY_NAME[]' value='TERM_ID/TERM_SLUG/TERM_NAME' />
    OR
    <input type='checkbox' name='META_KEY[]' value='META_VALUE' />

    <input type='radio' name='TAXONOMY_NAME' value='TERM_ID/TERM_SLUG/TERM_NAME' />
    OR
    <input type='radio' name='META_KEY' value='META_VALUE' />

    <select name='TAXONOMY_NAME'>
        <option value='TERM_ID/TERM_SLUG/TERM_NAME'>any text</option>
    </select>
    OR
    <select name='META_KEY'>
       <option value='META_VALUE'>any text</option>
    </select>

    //Slider
    <input type='number' name='META_KEY_from' value='10' />
    <input type='number' name='META_KEY_to' value='10000' />

    //SORTING
    <select name='sorting'>
       <option value='META_KEY[DESC]'>any text</option>
       <option value='META_KEY[ASC]'>any text</option>
       <option value='title[DESC]'>any text</option>
       <option value='title[ASC]'>any text</option>
    OR
    <input name="sorting[]" value="META_KEY[DESC]">
    <input name="sorting[]" value="META_KEY[ASC]">
    <input name="sorting[]" value="title[DESC]">
    <input name="sorting[]" value="title[ASC]">

3) Put in the shortcode settings the selector of the form
4) ...
5) Profit
Now when you try to submit a form with parameters, the plugin will intercept the event. The elements will be "gently" reloaded according to the selected form parameters. In this case, further loading of content using a button, pagination, or scroll will be sorted and filtered according to the selected form parameters

== Installation ==

1. Login to your WordPress admin and go to Plugins -> Add New
2. Type "Easy Ajax Pagination" in the search bar and select this plugin
3. Click "Install", and then "Activate Plugin"

== Frequently Asked Questions ==

= It's free? =

Yes, it's absolutly free!

= Why was this plugin created? =

Because I needed a universal tool for a few sites, and a similar suitable plugin was not free)
I decided to share

= I implemented several plugin shortcodes on the page, but only the first one was displayed. What's the matter? =

The fact is that only one plugin control element can be located on a single page. It is output by a shortcode.

== Screenshots ==

1. This screen shot description corresponds to screenshot-1.(png|jpg|jpeg|gif). Note that the screenshot is taken from
the /assets directory or the directory that contains the stable readme.txt (tags or trunk). Screenshots in the /assets
directory take precedence. For example, `/assets/screenshot-1.png` would win over `/tags/4.3/screenshot-1.png`
(or jpg, jpeg, gif).
2. This is the second screen shot

== Changelog ==

= 1.0 =
* A change since the previous version.
* Another change.

= 0.5 =
* List versions from most recent at top to oldest at bottom.

== Upgrade Notice ==

= 1.0 =
Upgrade notices describe the reason a user should upgrade.  No more than 300 characters.

= 0.5 =
This version fixes a security related bug.  Upgrade immediately.

== Arbitrary section ==

You may provide arbitrary sections, in the same format as the ones above.  This may be of use for extremely complicated
plugins where more information needs to be conveyed that doesn't fit into the categories of "description" or
"installation."  Arbitrary sections will be shown below the built-in sections outlined above.

== A brief Markdown Example ==

Ordered list:

1. Some feature
1. Another feature
1. Something else about the plugin

Unordered list:

* something
* something else
* third thing

Here's a link to [WordPress](http://wordpress.org/ "Your favorite software") and one to [Markdown's Syntax Documentation][markdown syntax].
Titles are optional, naturally.

[markdown syntax]: http://daringfireball.net/projects/markdown/syntax
            "Markdown is what the parser uses to process much of the readme file"

Markdown uses email style notation for blockquotes and I've been told:
> Asterisks for *emphasis*. Double it up  for **strong**.

`<?php code(); // goes in backticks ?>`