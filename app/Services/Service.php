<?php

namespace App\Services;

use DateTime;
use DateInterval;

class Service {

    // public $models;
    
    public function __construct() {
        // $this->models = new MainModels();
    }

    public function generateAccessKey() {
        return uniqid('adminCraftIT.').'-'.uniqid();
    }

    public function isValid($keys, $arr) {
        foreach($keys as $key) {
            if(!isset($arr[$key])) return false;
            if(empty($arr[$key])) return false;
        }
        return true;
    }

    public function isInvalid($keys, $arr) {
        return !$this->isValid($keys, $arr);
    }

    public function isEmpty(...$args) {
        $arguments = func_get_args();

        foreach($arguments as $arg) {
            if(empty($arg)) return true;
        }

    }

    public function isNotEmpty(...$args) {
        return !$this->isEmpty(func_get_args());
    }

    public function setModelFromArray($model, $data) {

        foreach($data as $key => $e) {
            if(!isset($e)) continue;
            if(empty($e) || is_null($e)) continue;
            $model->$key = $e;
        }

        return $model;

    }

    public function createModelFromArray($model, $data) {
        $model = $this->setModelFromArray($model, $data);

        $model->save();

        return $model;
    }
    
    public function findNextDateMonthly($date) {
        $checkDate = DateTime::createFromFormat('Y-m-d H:i:s', $date);

        $i = 0;
        while($i <= 100) {
            if(new DateTime() < $checkDate)
                break;
            
            $checkDate->add(new DateInterval('P1M'));
            $i++;
        }

        return $checkDate;
    }

    public function morphToStdClass($array) {
        $stdClass = new \stdClass();
        foreach($array as $key => $e) {
            $stdClass->$key = $e;
        }
        return $stdClass;
    }

}
