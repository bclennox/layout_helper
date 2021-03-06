<?php

require_once 'Layout.php';
require_once 'IE.php';

@include 'layout_helper_utilities.php';

class LayoutHelper {
  
  private $body_class = '';
  private $title = 'Untitled';
  private $js = array();
  private $css = array();
  
  private $css_path = '/stylesheets';
  private $js_path = '/javascripts';
  
  /**
   * See the docs on the IE class to see how to use this.
   */
  public $ie;
  
  /**
   * Makes sure the filename has the given extension.
   *
   * @static
   * @param string
   * @param string
   * @return string
   */
  public static function extensionize($filename, $extension){
    if (!preg_match("/\.${extension}$/", $filename)){
      $filename = "${filename}.${extension}";
    }
    
    return $filename;
  }
  
  public function __construct(){
    $this->ie = new IE();
  }
  
  /**
   * Sets or gets the CSS class for the <body> tag.
   *
   * @param string
   * @return string
   */
  public function body_class(){
    if (func_num_args() > 0){
      $this->body_class = func_get_arg(0);
    }
    
    return $this->body_class;
  }
  
  /**
   * Sets or gets the page title.
   *
   * @param string
   * @return string
   */
  public function title(){
    if (func_num_args() > 0){
      $this->title = func_get_arg(0);
    }
    
    return $this->title;
  }
  
  /**
   * Adds a stylesheet <link> tag. Each argument should be a string
   * denoting the filename of the stylesheet, optionally including
   * the media it applies to in square brackets. For example:
   *
   *   'screen.css[screen,projection]'
   *   'print.css[print]'
   *   'default'
   *
   * See the notes on stylesheet_tag() about treatment of filenames.
   *
   * Returns the concatenation of all <link> tags.
   *
   * @param string
   * @return string
   */
  public function css(){
    if (func_num_args() > 0){
      for ($i = 0; $i < func_num_args(); $i++){
        $arg = func_get_arg($i);    // Fatal error: func_get_arg(): Can't be used as a function parameter
        if (preg_match('/^(.+)\[(.+)\]$/', $arg, $matches)){
          $filename = $matches[1];
          $media = $matches[2];
        } else {
          $filename = $arg;
          $media = 'screen,projection';
        }
        
        array_push($this->css, $this->stylesheet_tag($filename, $media));
      }
    }
    
    return join($this->css, "\n");
  }
  
  /**
   * Returns the path to the top-level stylesheets directory. This is
   * not configurable yet, other than editing the source.
   * 
   * @return string
   */
  public function css_path(){
    return $this->css_path;
  }
  
  /**
   * Adds a <script> tag. Each argument should be a string denoting
   * the filename of the JavaScript file.
   *
   * See the notes on javascript_tag() about treatment of filenames.
   *
   * Returns the concatenation of all <script> tags.
   *
   * @param string
   * @return string
   */
  public function js(){
    if (func_num_args() > 0){
      for ($i = 0; $i < func_num_args(); $i++){
        $filename = func_get_arg($i);    // Fatal error: func_get_arg(): Can't be used as a function parameter
        array_push($this->js, $this->javascript_tag($filename));
      }
    }
    
    return join($this->js, "\n");
  }
  
  /**
   * Returns the path to the top-level javascripts directory. This is
   * not configurable yet, other than editing the source.
   * 
   * @return string
   */
  public function js_path(){
    return $this->js_path;
  }
  
  /**
   * Creates a new Layout for the given template and interprets
   * this helper within that layout. The argument passed should be
   * the name of the template with an optional .html.php extension.
   *
   * Returns a huge chunk of HTML, probably.
   *
   * @param string
   * @return string
   */
  public function render($template){
    $layout = new Layout($template);
    return $layout->interpret($this);
  }
  
  /**
   * Renders a template in the current directory named like a Rails
   * partial template. For example, if $partial were the string
   * "subnav", this method would attempt to render a template named
   * "_subnav.html.php".
   *
   * Currently, the partial must be in the current directory.
   *
   * Returns the same thing as render().
   *
   * @param string
   * @return string
   */
  public function render_partial($partial){
    $basename = preg_replace('/^_/', '', basename($partial));
    $path = '_' . LayoutHelper::extensionize($basename, 'html.php');
    
    return $this->render($path);
  }
  
  
  /*-- Private functions --*/
  
  
  /**
   * Returns a <link> tag for the given stylesheet with the given
   * media type, wrapped in a conditional comment if needed.
   *
   * If the filename appears to be a URL, it will be used as the href
   * attribute exactly as it is given. Otherwise, $this->css_path() will be
   * prepended, and the .css extension is optional.
   *
   * @param string
   * @param string
   * @return string
   */
  private function stylesheet_tag($filename, $media){
    $href = ($this->is_remote($filename)) ?
      $filename :
      $this->css_path() . '/' . self::extensionize($filename, 'css');
    
    return $this->wrap("<link rel=\"stylesheet\" type=\"text/css\" href=\"$href\" media=\"$media\" />");
  }
  
  /**
   * Returns a <script> tag for the given JavaScript, wrapped in
   * a conditional comment if needed.
   *
   * If the filename appears to be a URL, it will be used as the src
   * attribute exactly as it is given. Otherwise, $this->js_path() will be
   * prepended, and the .js extension is optional.
   *
   * @param string
   * @return string
   */
  private function javascript_tag($filename){
    $src = ($this->is_remote($filename)) ?
      $filename :
      $this->js_path() . '/' . self::extensionize($filename, 'js');
    
    return $this->wrap("<script src=\"$src\"></script>");
  }
  
  /**
   * If our IE object is active, then this method will tell it to
   * wrap the given HTML in the appropriate conditional comment.
   * Otherwise, returns the HTML untouched.
   *
   * @param string
   * @return string
   */
  private function wrap($html){
    return ($this->ie->is_active()) ? $this->ie->wrap($html) : $html;
  }
  
  private function is_remote($filename){
    return preg_match('/^https?:\/\//', $filename);
  }
}

if (!function_exists('h')){

  /**
   * This function is equivalent to htmlspecialchars().
   *
   * @param string
   * @param int
   * @param string
   * @return string
   */
  function h($string, $quote_style = ENT_COMPAT, $charset = 'ISO-8859-1'){
    return htmlentities($string, $quote_style, $charset);
  }
}

if (!function_exists('d')){
  
  /**
   * Debugger.
   *
   * @param mixed
   * @param string
   * @param boolean
   */
  function d($var, $msg = '[no message]', $escape = true){
    if (is_null($var)){
      $var = '::NULL::';
    } else if (is_bool($var)){
      $var = $var ? '::TRUE::' : '::FALSE::';
    }

    $var = print_r($var, true);

    if ($escape){
      $var = htmlspecialchars($var);
    }

    // find our caller
    $bt = debug_backtrace();
    if (count($bt) > 1){
      $c = $bt[1];
      if (array_key_exists('class', $c)){
        $caller = "$c[class]$c[type]$c[function]()";
      } else {
        $caller = "$c[function]()";
      }
    } else {
      $caller = '';
    }
    
    if (strlen($msg) > 0 && strlen($caller) > 0){
      $msg = "$msg [$caller]";
    } else if (strlen($caller) > 0){
      $msg = $caller;
    }

    if (strlen($msg) > 0){
      $msg = "$msg:\n";
    }

    echo "<pre class=\"debug\">$msg$var</pre>";
  }
}

?>
