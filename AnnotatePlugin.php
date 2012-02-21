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
    'public_append_to_items_show'
  );
  //Needed filters for this plugin
  protected $_filters = array(
    'guest_user_widgets'
  );
  //define default options here.
  protected $_options = array(
    'annotation_page_path' => 'annotations',
    'annotation_page_title' => 'Annotations',
    'annotation_bookmarks'  => 'Bookmarks'
  );
   
  //Standard class constructor
  //public function setUp(){
    //parent::setUp();
  //}
  
  public function __construct(){
    parent::setUp();
  }
  
  public function hookInstall(){
    $db = get_db();
    //create the query for the table
    $sql = "CREATE TABLE IF NOT EXISTS `$db->Annotate` (
              `id`       BIGINT UNSIGNED NOT NULL auto_increment PRIMARY KEY,
              `text`     TEXT,
              `user_id`  BIGINT UNSIGNED NOT NULL,
              `item_id`  BIGINT UNSIGNED NOT NULL,
              `bookmark` TINYINT NOT NULL DEFAULT 0,
              `modified` TIMESTAMP NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP  
            ) ENGINE = MYISAM;";
            
    $db->exec($sql);
  }
  
  public function hookUninstall(){
    $db = get_db();
    $sql = "DROP TABLE IF EXISTS `$this->db->Annotate`";
    $db->exec($sql);
  }
  
  public function hookConfig($post){
    $pagePath = !empty($post['annotation_page_path']) ? trim($post['annotation_page_path']) : self::$_options['annotation_page_path'];
    set_option('annotation_page_path',$pagePath);
    
    $pageTitle = !empty($post['annotation_page_title'])
                 ? trim($post['annotation_page_title'])
                 : self::$_options['annotation_page_path'];
    set_option('annotation_page_title',$pageTitle);
    
    $bookmark = !empty($post['annotation_bookmark'])
                ? trim($post['annotation_bookmark'])
                : self::$_options['annotation_bookmark'];
    set_option('annotation_bookmark',$bookmark);
  }
  
  public function hookConfigForm(){
    $pagePath = get_option('annotation_page_path');
    $pageTitle = get_option('annotation_page_title');
    $bookmark = get_option('annotation_bookmark');
    
    include 'forms/config_form.php';
  }
  
  public function hookDefineRoutes($router){
    $router->addRoute(
      'annotation_default_route',
      new Zend_Controller_Router_Route(
        'annotation/:action',
        array(
          'module'     => 'annotate',
          'controller' => 'index',
           'action'    => 'index'
        )
      )
    );
    
    if($bp = get_option('annotate_page_path')){
      $router->addRoute(
        'annotation_custom_route',
        new Zend_Controller_Router_Route(
          "{$bp}/:action/*",
          array(
            'module'  =>  'annotate',
            'controller' => 'index',
            'action'     => 'index'
          )
        )
      );
    }
  }
  
  public function hookPublicAppendToItemsShow(){
    if($user = current_user()){
      $userAnnotation = annotate_get_user_note_for_item();
      $tags = tag_string(current_user_tags_for_item());
      
      include 'forms/public_form.php';
    }
  }
  
  public function filterGuestUserWidgets($widgets){
    $user = current_user();
    
    if($user){
      //Dashboard Title
      $html = "<h2><a href=\"".html_escape(uri(get_option('annotation_page_path')))."\">"
                              .get_option('annotation_page_title')." ".get_option('annotation_bookmark')
                              ."</a></h2><hr>";
      $note = annotate_get_items_and_notes_by_user_favorite($user,1);
      
      //place the html in the $widgets variable
      foreach($note as $n){
        $item = get_item_by_id($n->item_id);
        set_current_item($item);
        $html .= "<h2>".link_to_item()."</h2>";
        $html .= "<ul><li>".$n->text."</li></ul>";
      }
    }
    $widgets[] = $html;
    return $widgets;
  }
}
