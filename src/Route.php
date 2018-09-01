<?php


namespace CandleLight;

use \Slim\Http\Request as Request;
use \Slim\Http\Response as Response;

/**
 * Basic Template for a custom route
 * @package CandleLight
 */
abstract class Route{

    const GET = 'get';
    const POST = 'post';
    const PUT = 'put';
    const DELETE = 'delete';

    private $app;
    private $type;
    private $options;
    private $attributes;

    /**
     * Route constructor.
     * @param App $app CandleLight application instance
     * @param Type $type Content-Type Object
     * @param array $options Route options array
     */
    public function __construct(App $app, Type $type, array $options){
        $this->app = $app;
        $this->type = $type;
        $this->options = $options;
        if (isset($this->options['attributes'])){
            $this->attributes = $this->options['attributes'];
        }
        else{
            $this->attributes = [];
        }
    }

    /**
     * Returns the CDL-Application Object
     * @return App
     */
    public function getApp(): App{
        return $this->app;
    }

    /**
     * Returns the current Type-Object
     * @return Type
     */
    public function getType(): Type{
        return $this->type;
    }

    /**
     * Returns the Route options array
     * @return array
     */
    public function getOptions(): array{
        return $this->options;
    }

    /**
     * Function to execute, if this route is called
     * @param Request $request HTTP Request object
     * @param Response $response HTTP Response object
     * @param array $args arguments array
     * @return mixed
     */
    public abstract function dispatch(Request $request, Response $response, array $args);

    /**
     * Route to apply, if there is no proper route defined
     * @return Error
     */
    public static function noRoute(){
        return new Error('No action specified for this route.');
    }

    /**
     * Returns the given plugin values provided by the type.json
     * @return array
     */
    public function getAttributes(): array{
        return $this->attributes;
    }

    /**
     * Merges the given attributes with the given default values
     * and returns the result
     * @param array $defaults default attribute values
     * @return array
     */
    public function parseAttributes(array $defaults): array{
        return array_replace($defaults, $this->getAttributes());
    }
}