<?php

class Boilers {

    public array $consumption;
    public array $efficiency;
    public  $name;

    public function __construct($consumption, $efficiency, $name) {
        $this->consumption = $consumption;
        $this->efficiency = $efficiency;
        $this->name = $name;
    }

}
