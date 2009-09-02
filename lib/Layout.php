<?php

require_once 'LayoutHelper.php';

class Layout {
  private $path;
  private $helper;
  private $templates_path = '/templates';
  
  /**
   * Constructs this object to use the given template. The .html.php
   * extension is optional.
   *
   * @param string
   */
  public function __construct($template){
    $this->path = "$_SERVER[DOCUMENT_ROOT]/{$this->templates_path}/" . LayoutHelper::extensionize($template, 'html.php');
  }
  
  /**
   * Accepts a LayoutHelper object to use for interpreting the given
   * layout. This is probably the only method you'll ever call. Methods
   * encountered in the layout file (i.e., "{{method_name}}") are called
   * on the LayoutHelper, and the return values are substituted in the
   * layout.
   *
   * @param object LayoutHelper
   * @return string
   */
  public function interpret($helper){
    $this->helper = $helper;
    
    ob_start();
    include $this->path;
    $lines = ob_get_contents();
    ob_end_clean();
    
    $retval = '';
    foreach (explode("\n", $lines) as $line){
      $retval .= $this->parse($line) . "\n";
    }
    
    return $retval;
  }
  
  /**
   * Parses a single line of a layout file. If any method substitutions
   * appear, and the LayoutHelper responds to that method, then
   * the method return value will replace the substitution value.
   *
   * @param string
   * @return string
   */
  private function parse($line){
    if (preg_match('/{{([\w]+)}}/', $line, $matches)){
      
      // ignore the full-pattern match
      array_shift($matches);
      
      foreach ($matches as $method){
        $line = str_replace('{{' . $method . '}}', $this->helper->$method(), $line);
      }
    }
    
    return $line;
  }
}

?>
