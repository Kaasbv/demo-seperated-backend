<?php
//Maak een class aan voor deze api call
class UserLogout {
  public static function run(){
    session_start();
    session_destroy(); 
  }
}

//Run de run functie hiervan
UserLogout::run();


