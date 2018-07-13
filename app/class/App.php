<?php

namespace CandleLight;

class App{

    private $db;
    private $types = [];

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
     * Returns all registered types
     * @return Type[]
     */
    public function getTypes(): array{
        return $this->types;
    }
}