<?php


/**
 * Created by PhpStorm.
 * User: Mathieu
 * Date: 08/11/2017
 * Time: 14:42
 */

namespace Entities;

class User
{

    const ROLE_ADMIN = "ADMIN";
    const ROLE_USER = "USER";

    private $id;
    private $pseudo;
    private $email;
    private $password;
    private $status;
    private $role;

    /**
     * User constructor.
     * @param $id
     * @param $pseudo
     * @param $email
     * @param $password
     * @param $status
     * @param $role
     */
    public function __construct($id, $pseudo, $email, $password, $status, $role)
    {
        $this->id = $id;
        $this->pseudo = $pseudo;
        $this->email = $email;
        $this->password = $password;
        $this->status = $status;
        $this->role = $role;
    }


    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getPseudo()
    {
        return $this->pseudo;
    }

    /**
     * @param mixed $pseudo
     */
    public function setPseudo($pseudo)
    {
        $this->pseudo = $pseudo;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @param mixed $role
     */
    public function setRole($role)
    {
        $this->role = $role;
    }

}