<?php
/**
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 * @copyright Center for History and New Media, 2012
 * @package Annotation
 * @subpackage Models
 */
 
/**
 * Record for individual Annotate notes.
 *
 * @package Annotation
 * @subpackage Models
 */
class Annotate extends Omeka_Record {
  public $text;
  public $user_id;
  public $item_id;
  public $bookmark = 0;
  public $modified;
  
   protected function _validate(){
    if(empty($this->item_id)){
      $this->addError('item_id','Annotation note requires an item id.');
    }
    
    if(empty($this->user_id)){
      $this->addError('user_id', 'Annotations need a user id.');
    }
  }
  
  protected function beforeUpdate(){
    $this->modified = Zend_Date::now()->toString(self::DATE_FORMAT);
  }

}
