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
 
class AnnotateTable extends Omeka_Db_Table {


  public function findByUserIdAndItemId($userId, $itemId){
    return $this->fetchObject($this->getSelect()
                   ->where('user_id = ?', $userId)
                   ->where('item_id = ?', $itemId)
                  );
  }
  
  public function getUserNotes($userId,$filter){  
   
       $sql = $this->getSelect()->where('user_id = ?',$userId);
       if($filter){
            $query = $sql->where($filter);
       }else{
         $query  = $sql;
       }   
    
    return $this->fetchObjects($query);
  }
  
}
