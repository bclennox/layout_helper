<?php

require 'lib/LayoutHelper.php';

$helper = new LayoutHelper();
$helper->title('Layout Helper Test Page');
$helper->body_class('test-page');

$helper->js('application');
$helper->css('default', 'print[screen,print]', 'small[handheld]');

$helper->css('http://localhost/cdn/stylesheets/print.css[print]');
$helper->css('https://localhost/secure/stylesheets/secret.css');
$helper->js('http://maps.google.com/maps/api/js?sensor=false');

$helper->ie->begin('gt IE 6');
  $helper->js('fancy');
  $helper->css('pretty');
$helper->ie->end();

$helper->ie->begin('IE');
  $helper->js('ie');
  $helper->css('ie');
$helper->ie->end();

echo $helper->render('header');

?>

<h1>Test page</h1>
<p>What it do?</p>

<?php echo $helper->render_partial('partial'); ?>

<?php echo $helper->render('footer'); ?>
