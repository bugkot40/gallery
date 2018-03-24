<?php

namespace app\controllers;


use yii\web\Controller;

class AppController extends Controller
{
    //public $enableCsrfValidation = false; //эта строчка для боевого хостинга
    public function actionIndex(){
        echo 'Hello, this AppController!!!';
    }

    protected function UliaMetaTag($title = null, $keywords = null, $descript = null)
    {
        //$this->view - вид который был вызван этим action и для него будут применены эти метатеги
        $this->view->title = $title;
        $this->view->registerMetaTag([
            'name' => 'keywords', //ключевые слова
            'content' => "$keywords" // "" -обязательно, что бы передавалось значение (даже если пусто)
        ]);
        $this->view->registerMetaTag([
            'name' => 'descript',//описание
            'content' => "$descript"
        ]);
    }
}