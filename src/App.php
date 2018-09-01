<?php

namespace CandleLight;

use Slim\App as Slim;

/**
 * Main Application Class
 * @package CandleLight
 */
class App{

    private $db;
    private $app;

    private $types = [];
    private $validations = [];
    private $calculators = [];
    private $filters = [];
    private $middleware = [];
    private $routes = [];

    /**
     * Prepares the database interaction
     * @param array $connections
     */
    public function initDb(array $connections): void{
        $this->db = new Database();
        foreach ($connections as $name => $settings) {
            $this->db->addConnection($settings, $name);
        }
    }

    /**
     * Loads the main routing framework and builds the application routes
     * @param bool $debug True if the system boots in debug mode
     */
    public function load(bool $debug = false): void{
        $this->app = new Slim([
            'settings' => [
                'displayErrorDetails' => $debug
            ]
        ]);
        if (!$debug) {
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
     * @param string $validation Calculator class name
     */
    public function addCalculator(string $name, string $validation): void{
        $this->calculators[$name] = $validation;
    }

    /**
     * Returns the classname of the given calculator option
     * @param string $name calculator name
     * @return string Calculator class name
     */
    public function getCalculator(string $name): string{
        return $this->calculators[$name];
    }

    /**
     * Checks if the calculator with the given name exists
     * @param string $name the calculator name
     * @return bool
     */
    public function hasCalculator(string $name): bool{
        return isset($this->calculators[$name]);
    }


    /**
     * Adds a new Filter option to the application
     * @param string $name Filter name
     * @param string $filter Filter class name
     */
    public function addFilter(string $name, string $filter): void{
        $this->filters[$name] = $filter;
    }

    /**
     * Returns the classname of the given Filter option
     * @param string $name Filter name
     * @return string Filter class name
     */
    public function getFilter(string $name): string{
        return $this->filters[$name];
    }

    /**
     * Checks if the Filter with the given name exists
     * @param string $name the Filter name
     * @return bool
     */
    public function hasFilter(string $name): bool{
        return isset($this->filters[$name]);
    }


    /**
     * Adds a new Middleware option to the application
     * @param string $name Middleware name
     * @param string $middleware Middleware class name
     */
    public function addMiddleware(string $name, string $middleware): void{
        $this->middleware[$name] = $middleware;
    }

    /**
     * Returns the classname of the given Middleware option
     * @param string $name Middleware name
     * @return string Middleware class name
     */
    public function getMiddleware(string $name): string{
        return $this->middleware[$name];
    }

    /**
     * Checks if the Middleware with the given name exists
     * @param string $name the Middleware name
     * @return bool
     */
    public function hasMiddleware(string $name): bool{
        return isset($this->middleware[$name]);
    }


    /**
     * Adds a new Route option to the application
     * @param string $type Route HTTP-Request type
     * @param string $name Route name
     * @param string $route Route class name
     */
    public function addRoute(string $type, string $name, string $route): void{
        if (!isset($this->routes[$name])) {
            $this->routes[$name] = [];
        }
        $this->routes[$name][$type] = $route;
    }

    /**
     * Returns the classname of the given Route option
     * @param string $name Route name
     * @return array List of routes associated with this name
     */
    public function getRoutes(string $name): array{
        return $this->routes[$name];
    }

    /**
     * Returns the classname of the given Route option
     * @param string $type HTTP Request Type
     * @param string $name Route name
     * @return string Route class name
     */
    public function getRoute(string $type, string $name): string{
        return $this->routes[$name][$type];
    }

    /**
     * Checks if the Route with the given name exists
     * @param string $name the Route name
     * @return bool
     */
    public function hasRoutes(string $name): bool{
        return (isset($this->routes[$name]) && !empty($this->routes[$name]));
    }

    /**
     * Checks if the Route with the given name exists
     * @param string $type HTTP Request Type
     * @param string $name the Route name
     * @return bool
     */
    public function hasRoute(string $type, string $name): bool{
        return (isset($this->routes[$name]) && isset($this->routes[$name][$type]));
    }

    /**
     * Add a content type to the CDL System
     * @param array $type content type definition array
     * @return void
     */
    public function addType(string $name, array $type): void{
        $this->types[$name] = new Type($type);
    }

    /**
     * Checks if a type with the given name is registered
     * @param string $name type name to check
     * @return bool
     */
    public function hasType(string $name): bool{
        return isset($this->types[$name]);
    }
}