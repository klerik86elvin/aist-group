<?php


namespace App\DTO;


class UserDTO
{
    public $id;
    public $firstName;
    public $lastName;
    public $middleName;
    public $email;
    public $balance;

    public function __construct($id, $firstName, $lastName, $middleName, $email, $balance)
    {
         $this->id = $id;
         $this->firstName = $firstName;
         $this->lastName = $lastName;
         $this->middleName = $middleName;
         $this->email = $email;
         $this->balance = $balance;
    }

}
