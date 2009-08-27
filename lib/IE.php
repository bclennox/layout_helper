<?php

/**
 * Used in conjunction with LayoutHelper, provides a means for wrapping
 * code in conditional comments. For example:
 *
 *   $helper->ie->begin('IE 6');
 *     $helper->css('ie6');
 *     $helper->js('ie');
 *   $helper->ie->end();
 *
 * Now when the layout is interpreted, those two <link> and <script> tags
 * will be wrapped in a conditional comment targeting IE 6.
 */
class IE {
  private $condition = '';
  
  /**
   * Signals the start of a conditional comment block. Pass in the
   * condition (e.g., for "<!--[if gte IE 6]>", the condition is "gte IE 6").
   *
   * @param string
   */
  public function begin($condition){
    $this->condition = $condition;
  }
  
  /**
   * Signals the end of a conditional comment block.
   */
  public function end(){
    $this->condition = '';
  }
  
  /**
   * Returns a boolean indicating whether we're currently in a
   * conditional comment block
   *
   * @return boolean
   */
  public function is_active(){
    return !empty($this->condition);
  }
  
  /**
   * Wraps the given string in an appropriate conditional comment.
   *
   * @param string
   * @return string
   */
  public function wrap($html){
    return "<!--[if {$this->condition}]>$html<![endif]-->";
  }
}

?>
