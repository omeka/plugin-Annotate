<?php 
  $pageTitle = 'Annotation : Dashboard';
  head(array('title' => $pageTitle));
?>
 <h1><?php echo $pageTitle; ?> <?php echo __('(%s total)',$count);?></h1>
<div id="primary">
      <?php echo flash(); ?>
    <?php if ($count): ?>
   <script type="text/javascript">
        jQuery(window).load(function() {
            var toggleText = <?php echo js_escape(__('Toggle')); ?>;
            var detailsText = <?php echo js_escape(__('Details')); ?>;
            var showDetailsText = <?php echo js_escape(__('Show Details')); ?>;
            var hideDetailsText = <?php echo js_escape(__('Hide Details')); ?>;
            jQuery('.item-details').hide();
            jQuery('.action-links').prepend('<li class="details">' + detailsText + '</li>');

            jQuery('tr.item').each(function() {
                var itemDetails = jQuery(this).find('.item-details');
                if (jQuery.trim(itemDetails.html()) != '') {
                    jQuery(this).find('.details').css({'color': '#389', 'font-weight' : 'bold', 'cursor': 'pointer'}).click(function() {
                        itemDetails.slideToggle('fast');
                    });
                }
            });

            var toggleList = '<ul id="browse-toggles">'
                           + '<li><strong>' + toggleText + '</strong></li>'
                           + '<li><a href="#" id="toggle-all-details">' + showDetailsText + '</a></li>'
                           + '</ul>';

            jQuery('#items-sort').after(toggleList);

            // Toggle item details.
            jQuery('#toggle-all-details').toggle(function(e) {
                e.preventDefault();
                jQuery(this).text(hideDetailsText);
                jQuery('.item-details').slideDown('fast');
            }, function(e) {
                e.preventDefault();
                jQuery(this).text(showDetailsText);
                jQuery('.item-details').slideUp('fast');
            });

            var itemCheckboxes = jQuery("table#items tbody input[type=checkbox]");
            var globalCheckbox = jQuery('th#batch-edit-heading').html('<input type="checkbox">').find('input');
            var batchEditSubmit = jQuery('.batch-edit-option input');
            /**
             * Disable the batch submit button first, will be enabled once item
             * checkboxes are checked.
             */
            batchEditSubmit.prop('disabled', true);

            /**
             * Check all the itemCheckboxes if the globalCheckbox is checked.
             */
            globalCheckbox.change(function() {
                itemCheckboxes.prop('checked', !!this.checked);
                checkBatchEditSubmitButton();
            });

            /**
             * Unchecks the global checkbox if any of the itemCheckboxes are
             * unchecked.
             */
            itemCheckboxes.change(function(){
                if (!this.checked) {
                    globalCheckbox.prop('checked', false);
                }
                checkBatchEditSubmitButton();
            });

            /**
             * Function to check whether the batchEditSubmit button should be
             * enabled. If any of the itemCheckboxes is checked, the
             * batchEditSubmit button is enabled.
             */
            function checkBatchEditSubmitButton() {
                var checked = false;
                itemCheckboxes.each(function() {
                    if (this.checked) {
                        checked = true;
                        return false;
                    }
                });

                batchEditSubmit.prop('disabled', !checked);
            }
        });
    </script>
     <ul id="note_sort" class="navigation">
      <li><strong><?php echo __('Quick Filter'); ?></strong></li>
     <?php
      echo nav(array(
        __('All')=>uri(get_option('annotation_page_path')),
        __('Note')=>uri(get_option('annotation_page_path')."/index?note=1"),
        __('Bookmarked')=> uri(get_option('annotation_page_path')."/index?bookmark=1")
      ));
     ?>  
     </ul>       
  <div class="noted_items">
  <div class="pagination"><?php echo pagination_links(); ?></div>
  <table id="noted_list">
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
    if($note){
      foreach($note as $n){
        $item = get_item_by_id($n->item_id);
        set_current_item($item);
        $bookmarked = ($n->bookmark == 1) ? "<img src=".img('tick.png')." alt=".__('bookmark').">": '';
        echo "<tr><td>".link_to_item()."</td>";
        echo "<td>".substr($n->text,0,40)."</td>";
        echo "<td>".$bookmarked."</td>";
        echo "<td>".$n->modified."</td></tr>";
      }
    }else {
      echo "<p>No Items have been annotated.</p>";
    }
  ?>
  </tbody>
  </table>
  <div class="pagination"><?php echo pagination_links(); ?></div>
  </div>
</div>

<?php endif; foot(); ?>
