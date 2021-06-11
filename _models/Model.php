<?php

class Model {
  public static function fillObject($object, $row){
    foreach ($row as $key => $value) {
      if(!is_null($value)){
        $object->{$key} = $value;
      }
    }
  }
}