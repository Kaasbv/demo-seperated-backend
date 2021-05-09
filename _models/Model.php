<?php

class Model {
  protected static function fillObject($object, $row){
    foreach ($row as $key => $value) {
      if(!is_null($value)){
        $object->{$key} = $value;
      }
    }
  }
}