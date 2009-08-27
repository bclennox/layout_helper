PHP layouts made simple
=======================

LayoutHelper is a really, really basic templating system. Really basic.

Setting template substitution values
------------------------------------

Create a new LayoutHelper object. Then call methods to set appropriate values:

    $helper = new LayoutHelper();
    $helper->title("My Web Homepage");
    $helper->css("bunnies");
    $helper->js("DancingBunny");

Methods take multiple arguments if they make sense:

    $helper->css("screen:screen,projection", "print:print", "dancing_bunny:handheld");
    $helper->js("lib/jquery-1.3.2.min", "application");

When you're done:
    
    // more about templates in the next section
    echo $helper->render("header");

Creating template files
-----------------------

Templates are named `whatever.html.section` and should live in a `/sections` subdirectory beneath your project root. Method substitutions within a template are denoted by `{{method}}`, where the method will be called on the given LayoutHelper object and the return value substituted for the string within the curly brackets. So if you have this in a template:

    <body class="{{body_class}}">

then `$helper->render("&lt;your_template&gt;")` will call the `body_class` method of the `$helper` object and substitute its return value (say it's "home"), resulting in this:

    <body class="home">

Everything else is just plain old HTML (or whatever).

Conditional comments
--------------------

To target a specific version of IE:

    $helper->ie->begin("IE 6");
      $helper->css("ie6");
      $helper->js("broken-browser");
    $helper->ie->end();

which will result in this output:

    <!--[if IE 6]><style href="/stylesheets/ie6.css" ...></style><![endif]-->
    <!--[if IE 6]><script src="/javascripts/broken-browser.js" ...></script><![endif]-->

Ugly, but it works.

To-do
-----

* Support arbitrary methods on LayoutHelper
* Allow configuration of stylesheet, JavaScript, and template directories

License
-------

See COPYING.
