<?php


namespace CandleLight;

use Illuminate\Database\Capsule\Manager;

/**
 * Main Database interaction API
 * @package CandleLight
 */
class Database{

    /**
     * The Database capsule
     * @var Manager
     */
    private $capsule;

    /**
     * Database constructor.
     */
    public function __construct(){
        $this->capsule = new Manager();
        $this->capsule->setAsGlobal();
        $this->capsule->bootEloquent();
    }

    /**
     * Adds a new connection to the database eloquent model
     * @param \stdClass $settings database connection settings
     * @param string $name the connection name
     */
    public function addConnection(\stdClass $settings, string $name): void{
        $this->capsule->addConnection((array)$settings, $name);
    }

}