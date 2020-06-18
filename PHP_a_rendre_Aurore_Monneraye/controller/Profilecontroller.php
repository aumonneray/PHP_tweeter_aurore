<?php

namespace controller;

use app\src\App;

class Profilecontroller 
{
    private $app;
    /**
     * Profilecontroller constructor.
     * @param App $app
     */
    public function __construct(App $app) {
        $this->app = $app;
        session_start();
    }

    /**
     * Render Accueil php file
     *
     * @param Request $request
     * @return mixed
     */
    public function accueil(Request $request) {
        return $this->app->getService('render')('Accueil');
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function login(Request $request) {
        return $this->app->getService('render')('Login');
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function create(Request $request) {
        return $this->app->getService('render')('Create');
    }

    /**
     * @param Request $request
     * @param $id
     */
    public function like(Request $request, $id) {
        $verif = $this->app->getService('messageFinder')->findOneLikeById($id);

        if ($verif['tweet_id'] === $id and $verif['user_id'] === $_SESSION['id']) {
            $destroy = $this->app->getService('messageFinder')->destroyLikeById($id);
            if (!$destroy) return $this->app->getService('render')('profile', ['error' => "Le message selectionner n'as pas pu être dislike"]);
            $this->redirect('/profile/');
        }

        $create = $this->app->getService('messageFinder')->createLikeById($id);

        if (!$create) return $this->app->getService('render')('profile', ['error' => "Le message n'as pas pu être like"]);

        $this->redirect('/profile/');
    }

    /**
     * @param Request $request
     * @param $id
     */
    public function retweet(Request $request, $id) {
        $verif = $this->app->getService('messageFinder')->findOneRetweetById($id);

        if ($verif['tweet_id'] === $id and $verif['user_id'] === $_SESSION['id']) {
            $destroy = $this->app->getService('messageFinder')->destroyRetweetById($id);
            if (!$destroy) return $this->app->getService('render')('profile', ['error' => "Le message selectionner n'as pas pu être unretweet"]);
            $this->redirect('/profile/');
        }

        $create = $this->app->getService('messageFinder')->createRetweetById($id);

        if (!$create) return $this->app->getService('render')('profile', ['error' => "Le message n'as pas pu être retweet"]);

        $this->redirect('/profile/');
    }

    /**
     * Recover Speudo and password send by form, check if Speudo is not empty if yes render accueil whit a error
     * else find user profile with Speudo, if the result is empty render accueil with a error
     * after all this, compare the speudo and password recover on Database with the recover form var
     * if is equal save the id and Speudo user on $_SESSION variable, to use on this file
     *
     * @param Request $request
     * @return mixed
     */
    public function log(Request $request) {
        $login = ['speudo' => $request->GetParameters('user'), 'mdp' => $request->GetParameters('pass')];

        if ($login['speudo'] === '') {
            return $this->app->getService('render')('Login', ['error' => 'Pseudo or Password is missing!']);
        }

        $result = $this->app->getService('profileFinder')->FindOneBySpeudo($login['speudo']);

        if (!$result) {
            return $this->app->getService('render')('Login', ['error' => 'Pseudo or Password is wrong or missing!']);
        }

        if ($login['speudo'] === $result->getSpeudo() && md5($login['mdp']) === $result->getMdp()) {
            $_SESSION['username'] = $result->getSpeudo();
            $_SESSION['id'] = $result->getId();
            $this->redirect('profile/');
        }

        return $this->app->getService('render')('Login', ['error' => 'Pseudo or Password is wrong!']);
    }

    /**
     * First verif, after find whit id, if result is empty render '404'
     * else render Profile with the result
     *
     * @param Request $request
     * @return mixed
     */
    public function public(Request $request, $id) {
        $this->verif();

        $result = $this->app->getService('profileFinder')->FindOneById($id);

        if (!$result) return $this->app->getService('render')('public', ['error' => 'Probleme dans la recuperation du compte trouver']);

        $messages = $this->app->getService('messageFinder')->findPublicMessageById($id);
        if ($messages === null) return $this->app->getService('render')('public', ['error' => 'Probleme dans la récupération des messages!', 'profile' => $result]);
        foreach ($messages as $message) {
            $message->setRetweet($this->app->getService('messageFinder')->findAllRetweetById($message->getId()));
            $message->setLike($this->app->getService('messageFinder')->findAllLikeById($message->getId()));
        }

        $isFollow = $this->app->getService('profileFinder')->findOneFollowersById($result->getId());
        $followers = $this->app->getService('profileFinder')->FindFollowerByID($id);

        return $this->app->getService('render')('public', ['profile' => $result, 'messages' => $messages, 'followers' => $followers, 'isOk' => $isFollow]);
    }

    /**
     * Render the login filder and put $_SESSION with user information
     *
     * @param Request $request
     * @return mixed
     */
    public function view(Request $request) {
        $this->verif();

        $messages = $this->app->getService('messageFinder')->findAllMyMessageById($_SESSION['id']);
        $followers = $this->app->getService('profileFinder')->FindFollowerByID($_SESSION['id']);

        if ($messages !== NULL) {
            foreach ($messages as $message) {
                $message->setRetweet($this->app->getService('messageFinder')->findAllRetweetById($message->getId()));
                $message->setLike($this->app->getService('messageFinder')->findAllLikeById($message->getId()));
            }
        }

        return $this->app->getService('render')('profile', ['message' => $messages, 'followers' => $followers]);
    }

    /**
     * Function for verif $_Session is not empty or null
     * if yes redirect to home and set $_SESSION variable to null
     */
    public function verif() {
        if (empty($_SESSION['username']) || empty($_SESSION['id'])) {
            session_destroy();
            $this->redirect('/');
        }
    }

    /**
     * Disconnect the user unset username and id and redirect to home
     */
    public function disconnect() {
        $this->verif();

        session_destroy();
        $this->redirect('/');
    }

    /**
     * Add account on the database
     *
     * @param Request $request
     * @return mixed
     */
    public function createDBHandler(Request $request) {
        $account = ['FirstName' => $request->GetParameters('FirstName'),
                    'LastName' => $request->GetParameters('LastName'),
                    'Pseudo' => $request->GetParameters('newpseudo'),
                    'MDP' => $request->GetParameters('newMDP'),
                    'Mail' => $request->GetParameters('mail')];

        $terms = $request->GetParameters('terms');

        foreach ($account as $data) {
            if ($data === '' || $data === NULL) return $this->app->getService('render')('Create', ['error' => "Vous avez une information vide", 'Michel' => $account]);
        }

        if ($terms === NULL) return $this->app->getService('render')('Create', ['error' => "Vous n'avez pas accepté les termes d'utilisations!", 'Michel' => $account]);

        $all = $this->app->getService('profileFinder')->RecupAllUser();

        foreach ($all as $profile) {
            if ($account['Pseudo'] === $profile->getSpeudo()) return $this->app->getService('render')('Create', ['error' => "Pseudo déjà utilisé!", 'Michel' => $account]);

            if  ($account['Mail'] === $profile->getMail()) return $this->app->getService('render')('Create', ['error' => "Mail déjà utilise!", 'Michel' => $account]);
        }

        $accountCreate = $this->app->getService('profileFinder')->Create($account);

        if (!$accountCreate) return $this->app->getService('render')('Create', ['error' => "Erreur dans la création du compte", 'Michel' => $account]);

        return $this->app->getService('render')('Accueil', ['info' => "Compte créé correctement"]);
    }

    /**
     * Function get ID and add followers with the current account connect
     *
     * @param Request $request
     * @param int $id
     * @return mixed
     */
    public function addFollower(Request $request, $id) {
        if ($id === null) return $this->app->getService('render')('profile', ['error' => 'Impossible de récupérer les infos du compte en question']);

        $verif = $this->app->getService('profileFinder')->findOneFollowersById($id);
        if ($verif) return $this->app->getService('render')('profile', ['info' => "Vous êtes déjà un followers de cette personne"]);

        $addFollower = $this->app->getService('profileFinder')->addFollowerById($id);

        if (!$addFollower) {
            $account = $this->app->getService('profileFinder')->FindOneById($id);
            var_dump("no add follower");
            die();
            return $this->app->getService('render')('profile', ['error' => "Probléme rencontré dans le follow du compte actuel", 'profile' => $account]);
        }

        $this->redirect('/public/' . $id);
    }

    /**
     * Delete on database the followers choose
     *
     * @param Request $request
     * @param mixed $id
     * @return mixed
     */
    public function removeFollower(Request $request, $id) {
        $verif = $this->app->getService('profileFinder')->findOneFollowersById($id);
        if (!$verif) return $this->app->getService('render')('profile', ['info' => "Vous n'êtes pas un followers de cette personne"]);

        $removefollower = $this->app->getService('profileFinder')->removeFollowerById($id);
        if (!$removefollower) {
            $account = $this->app->getService('profileFinder')->FindOneById($id);
            return $this->app->getService('render')('profile', ['error' => "Probléme rencontré dans le follow du compte actuel", 'profile' => $account]);
        }

        $this->redirect('/public/' . $id);
    }


    /**
     * recup the search name and go to take the account like research string
     *
     * @param Request $request
     * @return mixed
     */
    public function searchAccount(Request $request) {
        $this->verif();
        $search = $request->GetParameters('search');

        if ($search === null || $search === '') return $this->app->getService('render')('profile', ['error' => "Le mot clé de la recherche est invalide"]);

        $searchRelease = $this->app->getService('profileFinder')->searchAccount($search);

        if ($searchRelease === null || $searchRelease === 0) return $this->app->getService('render')('profile', ['error' => "La recherche n'a pas put aboutir"]);

        return $this->app->getService('render')('search', ['search' => $searchRelease]);
    }

    protected function redirect($location) {
        header("Location: $location");
        exit();
    }
}