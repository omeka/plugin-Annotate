<div id="annotation-form">
  <h2><?php echo get_option('annotation_page_title'); ?></h2>
  <form action="<?php echo uri('annotation/save-item-data'); ?>"
        method="post">
        <label = for="annotation_bookmark"><?php echo get_option('annotation_bookmark'); ?></label>
        <?php $checked = ($annotation->favorite == 1) ? true : false; ?>
        <input name="annotation_bookmark"
               type="checkbox"
               value="on"
               <?php if($checked){echo "checked='checked'"; }?>>
        
        <p>
          <label for="annotation_note_text">Notes</label>
          <textarea rows="10"
                    cols="40"
                    name="annotation_note_text" />
            <?php echo $annotation->text; ?>
          </textarea>
        </p>  
        <p>
          <label for="annotation_tags">Tags</label><br />
          <input type="text"
                 class="textinput"
                 name="annotation_tags"
                 value="<?php echo $tags; ?>" />
        </p>
        <input type="hidden"
               name="item_id"
               value="<?php echo item('id'); ?>">
        <p>
          <input type="submit"
                 class="submit"
                 value="save">
        </p>
  </form>
</div>
