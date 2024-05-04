<?php
class ApiView{}
class JsonView extends ApiView{
    public function render($content){
        echo json_encode($content);return true;
    }
}