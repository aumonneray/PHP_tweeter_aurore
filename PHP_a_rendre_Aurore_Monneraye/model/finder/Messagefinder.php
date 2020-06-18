<?php


namespace model\finder;

use model\finder\FinderInterface;
use model\gateway\MessageGateway;
use App\SRC\App;

class MessageFinder implements FinderInterface
{
    /**
     * @var \PDO
     */
    private $conn;

    /**
     * @var App
     */
    private $app;

    /**
     * ProfileFinder constructor.
     * @param App $app
     */
    public function __construct(App $app) {
        $this->app = $app;
        $this->conn = $this->app->getService('database')->GetConnexion();
    }

    /**
     * findOnebyId
     * Used for recup one message for change or juste focus message
     *
     * @param  mixed $id
     * @return void
     */
    public function findOnebyId($id) {
        $query = $this->conn->prepare('SELECT t.id, t.Message, t.Creator, t.Date
        , c.Speudo FROM tweet t INNER JOIN compte c ON c.id = t.Creator WHERE t.id = :id');
        $query->execute([
            ':id' => $id
        ]);
        $element = $query->fetch(\PDO::FETCH_ASSOC);
        if ($element === 0) return null;

        $message = new MessageGateway($this->app);
        $message->hydrate($element);

        return $message;
    }

    /**
     * findAllMyMessageById
     * Recup all message of id account
     * 
     * @param  mixed $id
     * @return void
     */
    public function findAllMyMessageById($id) {
        $query = $this->conn->prepare('SELECT t2.* FROM (SELECT t.id, c.Speudo, t.Creator, t.Message, t.Date
            FROM `folowers` f
                INNER JOIN tweet t ON t.Creator = f.id_profile
                INNER JOIN compte c ON c.id = f.id_profile
                WHERE f.id_follower = :id
        UNION
        SELECT t.id, c.Speudo, t.Creator, t.Message, t.Date
            FROM tweet t
                INNER JOIN compte c ON c.id = t.Creator
                WHERE t.Creator = :id
        UNION
        SELECT t.id, c.Speudo, t.Creator, t.Message, t.Date
        	FROM user_tweet us
            	INNER JOIN tweet t ON t.id = us.tweet_id
                INNER JOIN compte c ON c.id = t.Creator
                WHERE us.user_id = :id
        )AS t2 ORDER BY t2.Date DESC');
        $query->execute([
            ':id' => $id
        ]); 
        $elements = $query->fetchAll(\PDO::FETCH_ASSOC);
        if (count($elements) === 0) return null;
        
        $messages = [];
        $message = null;
        foreach($elements as $element) {
            $message = new MessageGateway($this->app);
            $message->hydrate($element);

            $messages[] = $message;
        }

        return $messages;
    }

    public function findFlux() {
        $query = $this->conn->prepare('SELECT m.id, a.pseudo, m.Creator, m.contenue, m.Date FROM `message` m INNER JOIN account a ON a.id = m.Creator ORDER BY `m`.`Date` DESC LIMIT 20');
        $query->execute();

        $elements = $query->fetchAll(\PDO::FETCH_ASSOC);
        if (count($elements) === 0) return null;

        $messages = [];
        $message = null;
        foreach($elements as $element) {
            $message = new MessageGateway($this->app);
            $message->hydrate($element);

            $messages[] = $message;
        }

        return $messages;
    }

    /**
     * Find all message for public profile view
     *
     * @param $id
     * @return array|null
     */
    public function findPublicMessageById($id) {
        $query = $this->conn->prepare('SELECT t2.* FROM (
                SELECT t.id, c.Speudo, t.Creator, t.Message, t.Date
                    FROM tweet t
                        INNER JOIN compte c ON c.id = t.Creator
                        WHERE t.Creator = :id
            UNION
                SELECT t.id, c.Speudo, t.Creator, t.Message, t.Date
                    FROM user_tweet us
                        INNER JOIN tweet t ON t.id = us.tweet_id
                        INNER JOIN compte c ON c.id = t.Creator
                        WHERE us.user_id = :id
                )AS t2 ORDER BY t2.Date DESC
            ');

        $query->execute([
            ':id' => $id
        ]); 
        $elements = $query->fetchAll(\PDO::FETCH_ASSOC);
        if (count($elements) === 0) return null;

        $messages = [];
        $message = null;
        foreach($elements as $element) {
            $message = new MessageGateway($this->app);
            $message->hydrate($element);

            $messages[] = $message;
        }

        return $messages;
    }

    /**
     * findAllRetweetById
     *
     * @param  mixed $id
     * @return void
     */
    public function findAllRetweetById($id) {
        $query = $this->conn->prepare('SELECT COUNT(id) as nombre FROM `user_tweet` WHERE tweet_id = :id');
        $query->execute([
            ':id' => $id
        ]);
        return $query->fetch(\PDO::FETCH_ASSOC);
    }

    public function findOneRetweetById($id) {
        $query = $this->conn->prepare('SELECT * FROM `user_tweet` us WHERE us.tweet_id = :id AND us.user_id = :current');
        $query->execute([
            ':id' => $id,
            ':current' => $_SESSION['id']
        ]);
        return $query->fetch(\PDO::FETCH_ASSOC);
    }

    public function destroyRetweetById($id) {
        $query = $this->conn->prepare('DELETE FROM `user_tweet` WHERE tweet_id = :id AND user_id = :current');
        return $query->execute([
            ':id' => $id,
            ':current' => $_SESSION['id']
        ]);
    }

    public function createRetweetById($id) {
        $query = $this->conn->prepare('INSERT INTO user_tweet(tweet_id, user_id) VALUES (:id, :current)');
        return $query->execute([
            ':id' => $id,
            ':current' => $_SESSION['id']
        ]);
    }

    public function findOneLikeById($id) {
        $query = $this->conn->prepare('SELECT * FROM `user_like` us WHERE us.tweet_id = :id AND us.user_id = :current');
        $query->execute([
            ':id' => $id,
            ':current' => $_SESSION['id']
        ]);
        return $query->fetch(\PDO::FETCH_ASSOC);
    }

    public function destroyLikeById($id) {
        $query = $this->conn->prepare('DELETE FROM `user_like` WHERE tweet_id = :id AND user_id = :current');
        return $query->execute([
            ':id' => $id,
            ':current' => $_SESSION['id']
        ]);
    }

    public function createLikeById($id) {
        $query = $this->conn->prepare('INSERT INTO user_like(tweet_id, user_id) VALUES (:id, :current)');
        return $query->execute([
            ':id' => $id,
            ':current' => $_SESSION['id']
        ]);
    }

    /**
     * findAllLikeById
     *
     * @param  mixed $id
     *
     * @return void
     */
    public function findAllLikeById($id) {
        $query = $this->conn->prepare('SELECT COUNT(id) as nombre FROM `user_like` WHERE tweet_id = :id');
        $query->execute([
            ':id' => $id
        ]);
        return $query->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * LikeAdd
     *
     * @param  mixed $idlike
     * @param  mixed $idUser
     *
     * @return void
     */
    public function LikeAdd($idlike, $idUser) {
        $query = $this->conn->prepare('INSERT INTO user_like (tweet_id, user_id) VALUES (:idlike, idUser)');
        return $query->execute([
            ':idlike' => $idlike,
            ':idUser' => $idUser
        ]);
    }

    /**
     * LikeDel
     *
     * @param  mixed $idlike
     * @param  mixed $idUser
     *
     * @return void
     */
    public function LikeDel($idlike, $idUser) {
        $query = $this->conn->prepare('DELETE * FROM user_like WHERE user_id = :idUser AND tweet_id = :idlike');
        return $query->execute([
            ':idlike' => $idlike,
            ':idUser' => $idUser
        ]);
    }

    /**
     * Create
     *
     * @param  mixed $tab
     *
     * @return void
     */
    public function Create($tab) {
        $query = $this->conn->prepare('INSERT INTO tweet (Creator, Message, Date) VALUES (:creator, :message, :date)');
        return $query->execute([
            ':creator' => $tab['idCreator'],
            ':message' => $tab['message'],
            ':date' => $tab['date']
        ]);
    }

    /**
     * Change information on database for message choose
     *
     * @param  mixed $tab
     * @return void
     */
    public function Change($tab) {
        $query = $this->conn->prepare('UPDATE tweet SET Message = :message WHERE id = :id');
        return $query->execute([
            ':id' => $tab['id'],
            ':message' => $tab['message'] . ' (modifier)'
        ]);
    }
}