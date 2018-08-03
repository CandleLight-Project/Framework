<?php

namespace CandleLight\Filter;

use CandleLight\Filter;

class UniqueIncrement extends Filter{
    private $increment = 0;

    public function apply(): void{
        $model = $this->getModel();
        $field = $this->getField();
        $value = $model->{$field};

        $found = false;
        while(!$found){
            $found = true;
            $query = $model->newInstance();
            $query = $query->where($field, $value. $this->getIncrementString())->get()->toArray();
            foreach ($query as $compare){
                if ($compare['id'] != $model->id){
                    $this->increment++;
                    $found = false;
                    break;
                }
            }
        }
        $model->{$field} = $value . $this->getIncrementString();
    }

    private function getIncrementString(): string{
        if ($this->increment === 0){
            return '';
        }
        return sprintf('-%d', $this->increment);
    }


}

/* @var \CandleLight\App $app */
$app->addFilter('unique-increment', UniqueIncrement::class);