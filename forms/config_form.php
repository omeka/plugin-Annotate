<div class="field">
  <label for="annotation_page_path">Relative Page Path</label>
  <div class="inputs">
    <input type="text" 
           class="textinput" 
           name="annotation_page_path" 
           value="<?php echo settings('annotation_page_path'); ?>" >
    <p class="explanation">
      Please enter the relative path from the project root where you wanth the Annotation
      page to be located. Use forward slashes to indicate subdirectories, but do not begin with a forward slash.
    </p>
  </div>
</div>

<div class="field">
  <label for="annotation_page_title">Page Title:</label>
  <div class="inputs">
    <input type="text" 
           class="textinput" 
           name="annotation_page_title"
           value="<?php echo settings('annotaion_page_title'); ?>" />
    <p class="explanation">
      Please enter the title you'd like to use for your Annotation installation.
    </p>
  </div>
</div>

<div class="field">
  <label for="annotation_page_title">Bookmarking</label>
  <div class="inputs">
    <input type="text"
           class="textinput"
           name="annotation_bookmark"
           value="<?php echo settings('annotation_bookmark'); ?>">
    <p class="explanation">
      The default value is bookmark. to change it please specify above.
    </p>
  </div>
</div>
