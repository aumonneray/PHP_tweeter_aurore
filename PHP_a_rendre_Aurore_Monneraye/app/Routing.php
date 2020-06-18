<?php


namespace app;

use controller\ProfileController;
use controller\Messagecontroller;
use app\src\App;

class Routing
{
    private $app;

    /**
     * Routing constructor.
     * @param App $app
     */
    public function __construct(App $app) {
        $this->app = $app;
    }

    public function setup() {
        $login = new ProfileController($this->app);
        $message = new Messagecontroller($this->app) ;

        // Home
        $this->app->get('/', [$login, 'log']);

        // Connect or Disconnect
        $this->app->get('/login', [$login, 'login']);
        $this->app->post('/log', [$login, 'log']);
        $this->app->get('/disconnect', [$login, 'disconnect']);

        // View Route profile
            $this->app->get('/public/(\d+)', [$login, 'public']);
        $this->app->get('/profile/', [$login, 'view']);


        // Add Like, Retweet or Remove
        $this->app->get('/like/(\d+)', [$login, 'like']);
        $this->app->get('/retweet/(\d+)', [$login, 'retweet']);

        // Add Follower
        $this->app->get('/followersAdd/(\w+)', [$login, 'addFollower']);
        $this->app->get('/followersRemove/(\w+)', [$login, 'removeFollower']);

        // Create Message
        $this->app->post('/profile/addmessage', [$message, 'addMessage']);

        // Change message
        $this->app->post('/message/modifier', [$message, 'change']);
        $this->app->post('/message/changeDB', [$message, 'changeDBMessage']);

        // Search other Account
        $this->app->post('/search', [$login, 'searchAccount']);

        // Decouverte
        $this->app->get('/new', [$message, 'new']);
        
        // Create Account
        $this->app->get('/create', [$login, 'create']);
        $this->app->post('/iscreate', [$login, 'createDBHandler']);
    }
}