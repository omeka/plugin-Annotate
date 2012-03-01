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

function annotate_getItems_and_notes_by_user($user = null,$filter){
  if(!user){
    $user = current_user();
  }
  if($filter['bookmark'] == 1){
    $fltr = "bookmark = 1";
  } elseif($filter['note'] == 1){
    $fltr = "text != ''";
  }else {
    $fltr = false;
  }
  
  $notes = get_db()->getTable('Annotate')->getUserNotes($user->id,$fltr);
  
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

function get_notes_by_filter($user = null, $filter = null){
    if(!$user){
        $user = current_user();
    }
    if($filter['note'] === 1 && $filter['bookmark'] === 1){
         $note = get_db()->getTable('Annotate')->getByFilter($user->id,$filter);
    } else {
        $note = get_db()->getTable('Annotate')->getUserNotes($user->id);
    }
    
    return $note;
}
