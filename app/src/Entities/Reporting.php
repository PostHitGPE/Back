<?php

namespace Entities;


/**
 * Class Reporting
 * @package Entities
 */
class Reporting
{
    private $post_hit_id;
    private $user_id;
    private $comment;

    /**
     * @return int post_hit_id
     */
    public function getPostHitId()
    {
        return $this->post_hit_id;
    }

    /**
     * @param int $post_hit_id
     * set $post_hit_id
     */
    public function setPostHitId($post_hit_id)
    {
        $this->post_hit_id = $post_hit_id;
    }

    /**
     * @return int $user_id
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * @param int $user_id
     * set $user_id
     */
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
    }

    /**
     * @return string $comment
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @param string $comment
     * set $comment
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
    }
}