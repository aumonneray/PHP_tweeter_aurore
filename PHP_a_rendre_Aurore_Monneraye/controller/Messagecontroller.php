<?php

namespace controller;

use app\src\App;

class Messagecontroller
{
    protected $app;
    /**
     * ProfileController constructor.
     * @param App $app
     */
    public function __construct(App $app) {
        $this->app = $app;
    }

    /**
     * addMessage
     * Add Message in database with the post value of actually session start
     *
     * @param  mixed $request
     * @return void
     */
    public function addMessage(Request $request) {
        $message = ['message' => $request->GetParameters('message'), 'idCreator' => $_SESSION['id'], 'date' => date("Y-m-d H:i:s")];

        $createMessage = $this->app->getService('messageFinder')->Create($message);
        if (!$createMessage) return $this->app->getService('render')('profile', ['error' => 'Erreur lors de la création du message.']); 
        $this->redirect('/profile/');
    }
    
    /**
     * change message view
     *
     * @param  mixed $request
     * @return void
     */
    public function change(Request $request) {
        $messageid = $request->GetParameters('idmessage');

        $message = $this->app->getService('messageFinder')->findOneById($messageid);

        return $this->app->getService('render')('change', ['message' => $message]);
    }

    
    /**
     * decouverte
     *
     * @param  mixed $request
     * @return void
     */
    public function decouverte(Request $request) {
        $fluxs = $this->app->getService('messageFinder')->findFlux();
        foreach ($fluxs as $flux) {
            $flux->setRetweet($this->app->getService('messageFinder')->findAllRetweetById($flux->getId()));
            $flux->setLike($this->app->getService('messageFinder')->findAllLikeById($flux->getId()));
        }
        if ($flux === null) return $this->app->getService('render')('', ['error' => "Flux non récupérer", 'message' => $messages, 'followers' => $followers]);

        return $this->app->getService('render')('Decouverte', ['flux' => $fluxs]);
    }
    
    /**
     * changeDBMessage
     * Change message in Database
     *
     * @param  mixed $request
     * @return void
     */
    public function changeDBMessage(Request $request) {
        $message = ['id' => $request->GetParameters('id'), 'message' => $request->GetParameters('messageChange')];
        $change = $this->app->getService('messageFinder')->Change($message);

        if ($change === null || !$change) return $this->app->getService('render')('change', ['error' => 'Erreur lors du changement du message!']);

        $this->redirect('/reception/');
    } 
	protected function redirect($location) {
        header("Location: $location");
        exit();
    }
}
