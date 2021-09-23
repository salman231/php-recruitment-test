<?php

namespace Snowdog\DevTest\Model;

use Snowdog\DevTest\Core\Database;

class VarnishManager
{

    /**
     * @var Database|\PDO
     */
    private $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function getAllByUser(User $user)
    {
        $userId = $user->getUserId();
        /** @var \PDOStatement $query */
        $query = $this->database->prepare('SELECT * FROM varnish_server WHERE user_id = :user');
        $query->bindParam(':user', $userId, \PDO::PARAM_INT);
        $query->execute();
        return $query->fetchAll(\PDO::FETCH_CLASS, Varnish::class);
    }

    public function getWebsites(Varnish $varnish)
    {
        $serverId = $varnish->getServerId();
        /** @var \PDOStatement $query */
        $query = $this->database->prepare('SELECT * FROM webserver_association WHERE server_id = :server');
        $query->bindParam(':server', $serverId, \PDO::PARAM_INT);
        $query->execute();
        return $query->fetchAll(\PDO::FETCH_CLASS, VarnishAssociation::class);
    }

    public function create(User $user, $ip)
    {
        $userId = $user->getUserId();
        /** @var \PDOStatement $statement */
        $statement = $this->database->prepare('INSERT INTO varnish_server (ip, user_id) VALUES (:ip, :user)');
        $statement->bindParam(':ip', $ip, \PDO::PARAM_STR);
        $statement->bindParam(':user', $userId, \PDO::PARAM_INT);
        $statement->execute();
        return $this->database->lastInsertId();
    }

    public function link($varnish, $website)
    {
        $select = $this->database->prepare('SELECT * FROM webserver_association WHERE server_id = :server_id AND website_id = :website_id');
        $select->bindParam(':server_id', $varnish, \PDO::PARAM_INT);
        $select->bindParam(':website_id', $website, \PDO::PARAM_INT);
        $select->execute();

        if ($select->rowCount() == 0) {
            /** @var \PDOStatement $statement */
            $statement = $this->database->prepare('INSERT INTO webserver_association (server_id, website_id) VALUES (:server_id, :website_id)');
            $statement->bindParam(':server_id', $varnish, \PDO::PARAM_INT);
            $statement->bindParam(':website_id', $website, \PDO::PARAM_INT);
            $statement->execute();
        }
    }

    public function unlink($varnish, $website)
    {
        $select = $this->database->prepare('SELECT * FROM webserver_association WHERE server_id= :server_id AND website_id= :website_id');
        $select->bindParam(':server_id', $varnish, \PDO::PARAM_INT);
        $select->bindParam(':website_id', $website, \PDO::PARAM_INT);
        $select->execute();

        if ($select->rowCount() > 0) {
            /** @var \PDOStatement $statement */
            $statement = $this->database->prepare('DELETE FROM webserver_association WHERE server_id= :server_id AND website_id= :website_id');
            $statement->bindParam(':server_id', $varnish, \PDO::PARAM_INT);
            $statement->bindParam(':website_id', $website, \PDO::PARAM_INT);
            $statement->execute();
        }
    }

}