<?php 
  $pageTitle = 'Annotation : Dashboard';
  head(array('title' => $pageTitle));
?>

<div id="primary">
  <h1><?php echo $pageTitle; ?> <?php echo __('(%s total)',$count);?></h1>
 
  <div class="noted_items">
  <div class="pagination"><?php echo pagination_links(); ?></div>
  <table class="noted_item_list">
  <tr>
    <th>Title</th>
    <th>Note</th>
    <th>Bookmarked</th>
    <th>Date Added</th>  
  </tr>
  <?php
    if($note){
      foreach($note as $n){
        $item = get_item_by_id($n->item_id);
        set_current_item($item);
        $bookmarked = ($n->bookmark == 1) ? "<img src=".img('tick.png').">": '';
        echo "<tr><td>".link_to_item()."</td>";
        echo "<td>".substr($n->text,0,40)."</td>";
        echo "<td>".$bookmarked."</td>";
        echo "<td>".$n->modified."</td></tr>";
      }
    }else {
      echo "<p>No Items have been annotated.</p>";
    }
  ?>
  </table>
  </div>
</div>

<?php foot(); ?>
