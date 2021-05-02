<?php

class Model {
  protected static function fillObject($object, $row){
    foreach ($row as $key => $value) {
      if(!is_null($value)){
        $object->{self::snakeToCamel($key)} = $value;
      }
    }
  }

  protected static function camelToSnake($input){
    return preg_replace(['/([a-z\d])([A-Z])/', '/([^_])([A-Z][a-z])/'], '$1_$2', $input);
  }

  protected static function snakeToCamel($input){
    return lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ',strtolower($input)))));
  }
}