PHP layouts made simple
=======================

LayoutHelper is a really, really basic templating system. Really basic. Sort of based on Ryan Bates's [nifty_layout](http://github.com/ryanb/nifty-generators).

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
    $helper->css('lib/blueprint/screen.css');
    $helper->js('http://www.google.com/jsapi');

Methods take multiple arguments if they make sense:

    $helper->css('screen[screen,projection]', 'print[print]', 'dancing_bunny[handheld]');
    $helper->js('lib/jquery-1.3.2.min', 'application');

When you're done:
    
    // more about templates in the next section
    echo $helper->render('header');

Creating template files
-----------------------

Templates are named `whatever.html.php` and should live in a `/templates` subdirectory beneath your project root. Method substitutions within a template are denoted by `{{method}}`, where the method will be called on the given LayoutHelper object and the return value substituted for the string within the curly brackets. So if you have this in a template:

    // templates/header.html.php
    <body class="{{body_class}}">

then `$helper->render('header')` will call the `body_class` method of the `$helper` object and substitute its return value (say it's "home"), resulting in this:

    <body class="home">

The template itself is also `require`d, so any PHP-executable code within the template will also be evaluated:

    <meta name="description" content="<?php perch_content('Site Description'); " />

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

To-do
-----

* Support arbitrary methods on LayoutHelper
* Allow configuration of stylesheet, JavaScript, and template directories

License
-------

See COPYING.
