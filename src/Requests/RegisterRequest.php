<?php

namespace Conkal\Columbus\Requests;

class RegisterRequest
{

   public $wsdl= __DIR__."/Register.wsdl";
   public $password;
   public $username;
   public $version = '1.0';

   public function __construct($password, $username)
   {
      $this->password = $password;
      $this->username = $username;
   }

}