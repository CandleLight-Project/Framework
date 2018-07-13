<?php


namespace CandleLight;

use Illuminate\Database\Eloquent\Model;


class Type{
    private $settings;
    public function __construct($settings){
        $this->settings = $settings;
    }

    public function new(): Model{
        // Anonymous class instance
        $model = new class extends Model{};

        // Set main data
        $model->setTable($this->settings->table);
        $model->setConnection($this->settings->connection);

        return $model;
    }

    public function __invoke(): Model{
        return $this->new();
    }

    public function getSettings(): \stdClass{
        return $this->settings;
    }
}