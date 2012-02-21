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
      
      if(!$itemId){
        throw new exception('Item Id must be an integer!');
      }
      
      //Save Notes.
      $note = $this->getTable('Annotate')->findByUserIdAndItemId($userId,$itemId);
      $noteText = $this->getRequest()->getPost('annotate_note_text');
      
      if(!empty($noteText)){
        if(!$note){
          $note = new Annotate;
          $note->user_id = $userId;
          $note->item_id = $itemId;
        }
        
        $note->text = $noteText;
        $note->save();
      } else {
        if($note instanceof Annotate){
          $note->delete();
        }
      }
      
      //save tags
      $tags = $this->getRequest()->getPost('annotation_tags');
      $item = $this->getTable('Item')->find($itemId);
      $item->applyTagString($tags,$user->Entity);
      
      $this->redirect
            ->gotoRoute(
              array(
                'controller' => 'items',
                'action' => 'show',
                'id' => $itemId
              ),
              'id'
            );
    }
  }
}
