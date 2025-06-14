<?php

abstract class User{
    protected $id;
    public $fname;
    public $lname;
    public $role;
    protected $email;
    protected $password;
    public $Pnum;
    
    // public function __construct(int $id) {
    //     $this->id = $id;
    // }
    public function signIn($email,$passwrod){}
    public function signUp(): void {}
    public function signOut(): void {
        // remove all session variables
        session_unset();
        // destroy the session
        session_destroy();
    }
}

?>