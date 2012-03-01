<?php 
  $pageTitle = 'Annotation : Dashboard';
  head(array('title' => $pageTitle));
?>
 <h1><?php echo $pageTitle; ?> <?php echo __('(%s total)',count($note));?></h1>
<div id="primary">
      <?php echo flash(); ?>
    <?php if (!count($note)): ?>
     <?php echo "<p>No Items have been annotated.</p>"; ?>
    <?php else: ?>
     <ul id="note_sort" class="navigation">
     
      <li><strong><?php echo __('Quick Filter'); ?></strong></li>
     <?php
      echo nav(array(
        __('All')=>uri(get_option('annotation_page_path')),
        __('Note')=>uri(get_option('annotation_page_path')."/browse?note=1"),
        __('Bookmarked')=> uri(get_option('annotation_page_path')."/browse?bookmark=1")
      ));
     ?>  
     </ul>  
          
  <div class="noted_items">
  <div class="pagination"><?php echo pagination_links(); ?></div>
  <table id="annotations">
  <col id="col-title" />
  <col id="col-note" />
  <col id="bookmarked" />
  <col id="date-added" />
  <thead>
    <tr>    
      <?php
            $browseHeadings[__('Title')] = 'Dublin Core,Title';
            $browseHeadings[__('Note')] = 'note';        
            $browseHeadings[__('Bookmarks')]  = 'bookmark';        
            $browseHeadings[__('Date Added')] = 'added';
            echo browse_headings($browseHeadings);
      ?>
     </tr>
  </thead>
  <tbody>
  <?php
      foreach($note as $n){
        $item = get_item_by_id($n->item_id);
        set_current_item($item);
        $bookmarked = ($n->bookmark == 1) ? "<img src=".img('tick.png')." alt=".__('bookmark').">": '';
        echo "<tr><td>".link_to_item()."</td>";
        echo "<td>".substr($n->text,0,40)."</td>";
        echo "<td>".$bookmarked."</td>";
        echo "<td>".$n->modified."</td></tr>";
      }
   
     
  ?>
  </tbody>
  </table>
  <div class="pagination"><?php echo pagination_links(); ?></div>
  <?php endif; ?>
  </div>
</div>
<?php foot(); ?>
