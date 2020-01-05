<?php namespace check; // vim: se fdm=marker:

abstract class verify{
  abstract static function validate(string $data):bool;
  //abstract static exists(string $data):bool;
}
