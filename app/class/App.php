<?php

namespace CandleLight;

use Slim\App as Slim;

class App{

    private $db;
    private $types = [];
    private $app;

    /**
     * Prepares the database interaction
     * @param Loader $loader
     */
    public function initDb(Loader $loader): void{
        require_once 'Database.php';
        $this->db = new Database();
        foreach ($loader() as $name => $settings) {
            $this->db->addConnection($settings, $name);
        }
    }

    /**
     * Prepares the data-types
     * @param MultiLoader $loader
     */
    public function initTypes(MultiLoader $loader): void{
        require_once 'Type.php';
        foreach ($loader() as $type) {
            /* @var $type Loader */
            $this->types[$type->getBasename('.json')] = new Type($type());
        }
    }

    /**
     * Loads the main routing framework and builds the application routes
     */
    public function load(): void{
        $debug = false;
        $this->app = new Slim([
            'settings' => [
                'displayErrorDetails' => $debug
            ]
        ]);
        if (!$debug) {
            require_once 'Error.php';
            Error::registerSystemErrors($this->app);
        }
        $this->buildRoutes();
    }

    /**
     * Adds all routes to the System
     */
    private function buildRoutes(){
        require_once 'System.php';
        System::reflectionRoutes($this, $this->app);
        foreach ($this->types as $type){
            /* @var $type Type */
            $type->applyRoutes($this->app);
        }
    }

    /**
     * Starts the main application
     */
    public function run(){
        $this->app->run();
    }

    /**
     * Returns all registered types
     * @return Type[]
     */
    public function getTypes(): array{
        return $this->types;
    }
}