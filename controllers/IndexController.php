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

  public function indexAction(){
    $user = current_user();
    
    if($user){
      $note = annotate_getItems_and_notes_by_user($user);
    }
    
    $this->view->note = $note;
  }
  
  public function saveItemDataAction(){
    
    if(($user = $this->getCurrentUser())){
        $userId = $user->id;
        $itemId = (int)$this->getRequest()->getPost('item_id');
        
        //save the Notes.
        if(!($note = $this->getTable('Annotate')->findByUserIdAndItemId($userId,$itemId))){
            $note = new Annotate;            
        }
        
        $noteText = $this->getRequest()->getPost('annotation_note_text');
        $bookmark = $this->getRequest()->getPost('annotation_bookmark');
        
        $note->user_id = $userId;
        $note->item_id = $itemId;
        $note->bookmark = $bookmark;
        $note->text = $noteText;
        $note->save();
        
        if($bookmark != 1 && $noteText == ''){
            if($note instanceof Annotate){
                $note->delete();
            }
        }
        
        
        //save tags 
        $tags = $this->getRequest()->getPost('annotation_tags');
        $item = $this->getTable('Item')->find($itemId);
        $item->applyTagString($tags,$user->Entity);
        
        $this->redirect->gotoRoute(array('controller'=>'Items', 'action'=>'show', 'id'=>$itemId), 'id');
        
    }
  }
}
