<?php
    abstract class  Person {
	    public $id;
	    public $name;
	    public $phone;
	    public $mail;
	    public $identification;
	    public $password;
        public $db;

        abstract protected function registration($phone,$password,$personIp);
        abstract protected function authorization($phone, $password);
        abstract protected function passwordReset();
	}

