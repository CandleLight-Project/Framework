<?php

namespace CandleLight;

use Slim\App as Slim;

/**
 * Main Application Class
 * @package CandleLight
 */
class App{

    private $db;
    private $types = [];
    private $app;

    private $validations = [];

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
        $debug = true;
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
    private function buildRoutes(): void{
        require_once 'System.php';
        System::reflectionRoutes($this, $this->app);
        foreach ($this->types as $type) {
            /* @var $type Type */
            $type->applyRoutes($this, $this->app);
        }
    }

    /**
     * Starts the main application
     */
    public function run(): void{
        $this->app->run();
    }

    /**
     * Returns all registered types
     * @return Type[]
     */
    public function getTypes(): array{
        return $this->types;
    }


    /**
     * Adds a new validation option to the application
     * @param string $name validation name
     * @param string $validation Validation class name
     */
    public function addValidation(string $name, string $validation): void{
        $this->validations[$name] = $validation;
    }

    /**
     * Returns the classname of the given validation option
     * @param string $name validation name
     * @return string Validation class name
     */
    public function getValidation(string $name): string{
        return $this->validations[$name];
    }

    /**
     * Checks if the validation with the given name exists
     * @param string $name the validation name
     * @return bool
     */
    public function hasValidation(string $name): bool{
        return isset($this->validations[$name]);
    }
}