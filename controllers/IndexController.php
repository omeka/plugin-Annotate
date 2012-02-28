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
  
  protected $nottedItemsPerPage = 10;
  
  //public function indexAction(){}
  
  public function indexAction(){
  $result = $this->_helper->searchItems();
    $user = current_user();
    
      if($user){
        $note = annotate_getItems_and_notes_by_user($user);
      }
      
      $paginationUrl = $this->getRequest()->getBaseUrl()."/".get_option('annotation_page_path')."/index";
      
      $pagination = array(
         'menu'          =>   null,
         'page'          => $result['page'],
         'per_page'      => $result['per_page'],
         'total_results' => $result['total_results'],
         'link'          => $paginationUrl
         
      );
      
      Zend_Registry::set('pagination',$pagination);
      fire_plugin_hook('browse_annotations',$result['annotations']);
    
    $count = totalNotedItems();
    $this->view->note = $note;
    $this->view->count = $count;
    $this->view->assign(array('items'=>$result['annotation'],'total_annotations'=>$result['total_annotations']));
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
