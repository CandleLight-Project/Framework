<?php


namespace CandleLight;


class TypeRoute extends Route{
    private $type;
    private $settings;
    public function __construct(Type $type){
        $this->type = $type;
        $this->settings = $type->getSettings();
    }

    function apply(){
        print_r($this->settings->routing);
    }
}