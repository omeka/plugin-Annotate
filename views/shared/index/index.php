<?php 
  $pageTitle = 'Annotation : Dashboard';
  head(array('title' => $pageTitle);
?>

<div id="primary">
  <h1><?php echo $pageTitle; ?></h1>
  <?php
    if($note){
      foreach($note as $n){
        $item = get_item_by_id($n->item_id);
        set_current_item($item);
        
        echo "<h2>".link_to_item()."</h2>";
        echo "<ul><li>".$n->text."</li></ul>";
      }
    }else {
      echo "<p>Annotated Items will appear here.</p>";
    }
  ?>
</div>

<?php foot(); ?>
