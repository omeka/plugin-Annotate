<?php

function annotate_get_user_note_for_item($user = null,$item = null){
    if (!$item) {
        $item = get_current_item();
    }

    if (!$user) {
        $user = current_user();
    }
    $note = get_db()->getTable('Annotate')->findByUserIdAndItemId($user->id, $item->id);
    
    return $note;
    
}

function annotate_getItems_and_notes_by_user($user = null){
  if(!user){
    $user = current_user();
  }
  
  $notes = get_db()->getTable('Annotate')->getUserNotes($user->id);
  
  return $notes;
}

function annotate_get_items_and_notes_by_user_favorite($user = null, $favorite = 0){
  if(!$user){
    $user = current_user();
  }
  
  if($favorite == 1){
    $favorite = get_db()->getTable('Annotate')->getUserBookmarked($user->id,1);
  }
  
  return $favorite;
}
