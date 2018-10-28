<?php

/**
 * Created by PhpStorm.
 * User: Mathieu
 * Date: 09/11/2017
 * Time: 23:29
 */

namespace Repository;

use Entities\DataBase;
use PDO;

/**
 * Class Tags
 * @package Repository
 * Repository of Tags where all the methods linked directly to tags for the post-hit are codded
 */
class Tags extends DataBase {

    /**
     * @param $name
     * @return mixed
     */
    public function findTagByName($name) {
        $db = parent::$dbConnection;
        $stmt = $db->prepare("SELECT * FROM tags WHERE name = ?");
        $stmt->bindParam(1, $name, PDO::PARAM_STR);
        $stmt->execute();
        $tag = $stmt->fetchObject();
        return ($tag);
    }

    function findPostItTagsByIds($tagId, $postHitId) {
        $db = parent::$dbConnection;
        $stmt = $db->prepare("SELECT * FROM post_hit_tags WHERE tag_id = ? AND post_hit_id = ?");
        $stmt->bindParam(1, $tagId, PDO::PARAM_INT);
        $stmt->bindParam(2, $postHitId, PDO::PARAM_INT);
        $stmt->execute();
        $postHitTag = $stmt->fetchObject();
        return ($postHitTag);
    }

    function insertNewTag($tag) {
        $db = parent::$dbConnection;
        $stmt = $db->prepare("INSERT INTO tags (name) VALUES(?)");
        $stmt->bindParam(1, $tag, PDO::PARAM_STR);
        $stmt->execute();
        return ($db->lastInsertId());
    }

    function insertPostItTag($postItId, $tagId)
    {
        $db = parent::$dbConnection;
        $stmt = $db->prepare("INSERT INTO post_hit_tags (post_hit_id, tag_id) VALUES (?,?)");
        $stmt->bindParam(1, $postItId, PDO::PARAM_INT);
        $stmt->bindParam(2, $tagId, PDO::PARAM_INT);
        $stmt->execute();
    }

    function removeAllPostHitTags($postHit) {
        $db = parent::$dbConnection;
        $stmt = $db->prepare("DELETE FROM post_hit_tags WHERE post_hit_id = ?");
        $stmt->bindParam(1, $postHit->id, PDO::PARAM_INT);
        try {
            $result = $stmt->execute();
            return ($result);
        } catch (\PDOException $exception)
        {
            throw ($exception);
        }
    }

    function getPostHitTags($postHit) {
        $db = parent::$dbConnection;
        $stmt = $db->prepare("SELECT tags.name FROM tags INNER JOIN post_hit_tags ON tags.id = post_hit_tags.tag_id WHERE post_hit_tags.post_hit_id = ?");
        $stmt->bindParam(1, $postHit->id, PDO::PARAM_INT);
        try {
            $stmt->execute();
            $tags = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
            return ($tags);
        } catch (\PDOException $exception) {
            return $exception;
        }
        return ($tags);
    }
}