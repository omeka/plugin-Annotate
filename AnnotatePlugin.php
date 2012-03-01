<?php
/**
 * @license http://www.gnu.org/licenses/gpl=3.0.txt
 * @copyright Center for History and New Media, 2012
 * @package Contribution
 */
 
/**
 * Annotate plugin class
 * 
 * @package Annotate
 */
class AnnotatePlugin extends Omeka_Plugin_Abstract {
  //Needed hooks for this plugin.
  protected $_hooks = array(
    'install',
    'uninstall',
    'config',
    'config_form',
    'define_routes',
    'public_append_to_items_show',
    'public_theme_header'
  );
  //Needed filters for this plugin
  protected $_filters = array(
    'guest_user_widgets',
    'public_navigation_main'
  );
  //define default options here.
  protected $_options = array(
    'annotation_page_path' => 'annotations',
    'annotation_page_title' => 'Annotations',
    'annotation_bookmarks'  => 'Bookmarks'
  );
   
  public function hookInstall(){
   
    //create the query for the table
    $sql = "CREATE TABLE IF NOT EXISTS `{$this->_db->Annotate}` (
              `id`       BIGINT UNSIGNED NOT NULL auto_increment PRIMARY KEY,
              `text`     TEXT,
              `user_id`  BIGINT UNSIGNED NOT NULL,
              `item_id`  BIGINT UNSIGNED NOT NULL,
              `bookmark` TINYINT NOT NULL DEFAULT 0,
              `modified` TIMESTAMP NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP  
            ) ENGINE = MYISAM;";
            
    $this->_db->exec($sql);
    
    $this->_installOptions();
  }
  
  public function hookUninstall(){
  
    $sql = "DROP TABLE IF EXISTS `{$this->_db->Annotate}`";
    $this->_db->exec($sql);
    
    $this->_uninstallOptions();
  }
  
  public function hookConfig($post){
    $pagePath = !empty($post['annotation_page_path']) 
                ? trim($post['annotation_page_path']) 
                : $this->_options['annotation_page_path'];
    set_option('annotation_page_path',$pagePath);
    
    $pageTitle = !empty($post['annotation_page_title'])
                 ? trim($post['annotation_page_title'])
                 : $this->_options['annotation_page_title'];
    set_option('annotation_page_title',$pageTitle);
    
    $bookmark = !empty($post['annotation_bookmarks'])
                ? trim($post['annotation_bookmarks'])
                : $this->_options['annotation_bookmarks'];
    set_option('annotation_bookmarks',$bookmark);
  }
  
  public function hookConfigForm(){
    $pagePath = get_option('annotation_page_path');
    $pageTitle = get_option('annotation_page_title');
    $bookmark = get_option('annotation_bookmarks');
    
    include 'forms/config_form.php';
  }
  
  public function hookDefineRoutes($router){
  
  $router->addRoute(
            'annotationDefaultRoute',
            new Zend_Controller_Router_Route(
                'annotation/:action',
                array(
                    'module'        => 'annotate',
                    'controller'    => 'index',
                    'action'        => 'browse'
                    )
            )
        );
        
        if ($bp = get_option('annotation_page_path')) {
            $router->addRoute(
                'annotationCustomRoute',
                new Zend_Controller_Router_Route(
                "{$bp}/:action/*",
                    array('module'     => 'annotate',
                          'controller' => 'index',
                          'action'     => 'browse')));
        }
    
  }
  
  public function hookPublicAppendToItemsShow(){
    if($user = current_user()){
      $bp = get_option('annotation_page_path');
      $bt = get_option('annotation_page_title');
      $annotation = annotate_get_user_note_for_item();
      $tags = tag_string(current_user_tags_for_item());
      $bookmark = get_option('annotation_bookmarks');
      
      include 'forms/public_form.php';
    }
  }
  
  public function filterGuestUserWidgets($widgets){
    $user = current_user();
    
    if($user){
      //Dashboard Title
      $bp = get_option('annotation_page_path');
      $bt = get_option('annotation_page_title');
      $bb = get_option('annotation_bookmarks');
      $html = "<h2><a href=\"".html_escape(uri($bp))."\">$bt $bb</a></h2><hr>";
      $note = annotate_get_items_and_notes_by_user_favorite($user,1);
      
      //place the html in the $widgets variable
      foreach($note as $n){
        $item = get_item_by_id($n->item_id);
        set_current_item($item);
        $html .= "<h3>".link_to_item()."</h3>";
        if($n->text != ''){
          $html .= "<li>".$n->text."</li>";
        }
      }
    }
    $widgets['notes'] = $html;
    return $widgets;
  }
  
  public function filterPublicNavigationMain($nav){
   
    if(!plugin_is_active('GuestUser')){
      if($user= current_user()){
       $path = get_option('annotation_page_path');
       $nav['Notes'] = uri($path);
       $nav['logout'] = uri('users/logout');
    
      }else{
        $nav['login'] = uri('users/login');
      }
    }
    return $nav;
  }
  
  public function hookPublicThemeHeader($request){
    queue_css('note');
  }
  
}
