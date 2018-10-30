<?php

namespace Entities;

/**
 * Class User
 * @package Entities
 */
class User
{

    /**
     * constant of role ADMIN (role class coming...)
     */
    const ROLE_ADMIN = "ADMIN";
    /**
     * constant of role USER (role class coming...)
     */
    const ROLE_USER = "USER";

    /**
     * @var int
     */
    private $id;
    /**
     * @var string
     */
    private $pseudo;
    /**
     * @var string
     */
    private $email;
    /**
     * @var string
     */
    private $password;
    /**
     * @var int
     */
    private $status;
    /**
     * @var int
     */
    private $role;

    /**
     * User constructor.
     * @param int $id
     * @param string $pseudo
     * @param string $email
     * @param string $password
     * @param int $status
     * @param int $role
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
     * @return int $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string $pseudo
     */
    public function getPseudo()
    {
        return $this->pseudo;
    }

    /**
     * @param string $pseudo
     */
    public function setPseudo($pseudo)
    {
        $this->pseudo = $pseudo;
    }

    /**
     * @return string $email
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return string $password
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return int $status
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param int $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return int $role
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @param int $role
     */
    public function setRole($role)
    {
        $this->role = $role;
    }

}