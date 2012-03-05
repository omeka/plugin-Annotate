<?php
/**
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 * @copyright Center for History and New Media, 2011
 * @package MyOmeka
 */

/**
 * MyOmeka controller
 *
 * @package MyOmeka
 */
 
class Annotate_IndexController extends Omeka_Controller_Action {
  
  protected $_session;
  
  public function init(){
    if(version_compare(OMEKA_VERSION, '2.0-dev', '>=')){
        $this->_helper->db->setDefaultModelName('Annotate');
    } else {
        $this->_modelClass = 'Annotate';
    }
    $this->_browseRecordsPerPage = 10;
    
    require_once 'Zend/Session.php';
    $this->session = new Zend_Session_Namespace('Annotate');
  }
  
 public function browseAction(){
     
      parent::browseAction();
  }
  
  public function saveItemDataAction(){
    if(($user = $this->getCurrentUser())){
      $userId = $user->id;
      $itemId = (int)$this->getRequest()->getPost('item_id');
      
      if(!$itemId){
        throw new Exception('Item Id must an integer!');
      }
      //Save notes.
      if(!($note = $this->getTable('Annotate')->findByUserIdAndItemId($userId,$itemId))){
        $note = new Annotate;
      }
      $noteText = $this->getRequest()->getPost('annotation_note_text');
      $bookmark = $this->getRequest()->getPost('annotation_bookmarks');
      
    
      $note->user_id = $userId;
      $note->item_id = $itemId;      
      if(!empty($noteText) && $bookmark != "on"){       
        $note->text = $noteText;
        $note->bookmark = 0;
        $note->save();
      }elseif($bookmark == "on" && empty($noteText)){
        $note->text = '';
        $note->bookmark = 1;
        $note->save();
      }elseif($bookmark == "on" && !empty($noteText)){
        $note->text = $noteText;
        $note->bookmark = 1;
        $note->save();
      }else{
        if($note instanceof Annotate){
          $note->delete();
        }
      }
      
      
      //SaveTags
      $tags = $this->getRequest()->getPost('annotation_tags');
      $item = $this->getTable('Item')->find($itemId);
      $item->applyTagString($tags,$user->Entity);
      
      $this->redirect->gotoRoute(array('controller'=>'items','action'=>'show','id'=>$itemId),'id');
    }  
  }
}
