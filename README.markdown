PHP layouts made simple
=======================

LayoutHelper is a really, really basic templating system. Really basic. Sort of based on Ryan Bates's [nifty_layout](http://github.com/ryanb/nifty-generators) and Rails's ActionView.

Setting template substitution values
------------------------------------

Create a new LayoutHelper object. Then call methods to set appropriate values:

    $helper = new LayoutHelper();
    $helper->title('My Web Homepage');
    $helper->body_class('home');
    $helper->css('bunnies');
    $helper->js('DancingBunny');

CSS and JS methods are reasonably smart. Extensions are optional, remote URLs are acceptable, and CSS media type defaults to "screen,projection" if not specified in square brackets:

    $helper->css('http://assets.example.com/css/print.css[print]');
    $helper->css('lib/blueprint/screen');
    $helper->js('http://www.google.com/jsapi');

Methods take multiple arguments if they make sense:

    $helper->css('screen[screen,projection]', 'print[print]', 'DancingBunny[handheld]');
    $helper->js('lib/jquery-1.3.2.min', 'application');

Creating template files
-----------------------

Templates are named `whatever.html.php` and should live in a `/templates` subdirectory beneath your project root. Method substitutions within a template are denoted by `{{method}}`, where the method will be called on the given LayoutHelper object and the return value substituted for the string within the curly brackets. So if you have this in a template:

    // templates/header.html.php
    <body class="{{body_class}}">

then `$helper->render('header')` will call the `body_class` method of the `$helper` object and substitute its return value (say it's "home"), resulting in this:

    <body class="home">

The template itself is also `require`d, so any PHP-executable code within the template will also be evaluated:

    // templates/header.html.php
    <meta name="description" content="<?php perch_content('Site Description'); " />

Rendering templates
-------------------

Once you've created a template and put it in the proper directory:
    
    echo $helper->render('header');

You can also render "partials," which aren't really as sophisticated as Rails's partials, but reside in whatever the current directory is and start with an underscore:

    // attempts to render a template named "_subnav.html.php" in the current directory
    echo $helper->render_partial('subnav');

Conditional comments
--------------------

To target Internet Explorer:

    $helper->ie->begin('IE 6');
      $helper->css('ie6');
      $helper->js('broken-browser');
    $helper->ie->end();
    
    $helper->ie->begin('lt IE 8');
      $helper->css('lib/blueprint/ie.css');
    $helper->ie->end();
    
    echo $helper->render('header');

which will result in this output:

    <!--[if IE 6]><style href="/stylesheets/ie6.css" ...></style><![endif]-->
    <!--[if lt IE 8]><style href="/stylesheets/lib/blueprint/ie.css" ...></style><![endif]-->
    <!--[if IE 6]><script src="/javascripts/broken-browser.js"></script><![endif]-->

Utilities
---------

Two utility functions will also be defined in the global namespace if no such functions already exist.

The `h()` function is a proxy to the PHP built-in function `htmlspecialchars()` and takes the same arguments:

    echo h($user_generated_content);  // same as "echo htmlspecialchars($user_generated_content);"

The `d()` function is a wrapper for `print_r()` that includes a debug message, last stack frame, and string values for booleans and nulls instead of ints or empty strings. It also wraps the output in a `<pre class="debug">` tag:

    d($some_variable);  // bunch of debug output

LayoutHelper will also attempt to include a file named `layout_helper_utilities.php` if it exists in the include path, so that you can put your own helpers in there.

To-do
-----

* Support arbitrary methods on LayoutHelper
* Allow configuration of stylesheet, JavaScript, and template directories

License
-------

See COPYING.
