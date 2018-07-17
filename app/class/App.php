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
    private $calulators = [];
    private $filters = [];

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
     * Checks if the calculator with the given name exists
     * @param string $name the validation name
     * @return bool
     */
    public function hasValidation(string $name): bool{
        return isset($this->validations[$name]);
    }





    /**
     * Adds a new calculator option to the application
     * @param string $name calculator name
     * @param string $validation Validation class name
     */
    public function addCalculator(string $name, string $validation): void{
        $this->calulators[$name] = $validation;
    }

    /**
     * Returns the classname of the given calculator option
     * @param string $name calculator name
     * @return string Calculator class name
     */
    public function getCalculator(string $name): string{
        return $this->calulators[$name];
    }

    /**
     * Checks if the calculator with the given name exists
     * @param string $name the calculator name
     * @return bool
     */
    public function hasCalculator(string $name): bool{
        return isset($this->calulators[$name]);
    }




    /**
     * Adds a new Filter option to the application
     * @param string $name Filter name
     * @param string $filter Validation class name
     */
    public function addFilter(string $name, string $filter): void{
        $this->calulators[$name] = $filter;
    }

    /**
     * Returns the classname of the given Filter option
     * @param string $name Filter name
     * @return string Filter class name
     */
    public function getFilter(string $name): string{
        return $this->calulators[$name];
    }

    /**
     * Checks if the Filter with the given name exists
     * @param string $name the Filter name
     * @return bool
     */
    public function hasFilter(string $name): bool{
        return isset($this->calulators[$name]);
    }
}