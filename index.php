<?php

require 'lib/LayoutHelper.php';

$layout = new LayoutHelper();
$layout->title('Layout Helper Test Page');
$layout->body_class('test-page');

$layout->js('application');
$layout->css('default');
$layout->css('print:print');
$layout->css('small:handheld');

$layout->ie->begin('gt IE 6');
  $layout->js('fancy');
  $layout->css('pretty');
$layout->ie->end();

$layout->ie->begin('IE');
  $layout->js('ie');
  $layout->css('ie');
$layout->ie->end();

echo $layout->section('header');

?>

<h1>Test page</h1>
<p>What it do?</p>

<?php echo $layout->section('footer'); ?>
