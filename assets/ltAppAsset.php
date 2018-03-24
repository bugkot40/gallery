<?php

/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;


/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class ltAppAsset extends AssetBundle
//алиасы путей
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
//скрипты "выправляющие" IE
    public $js = [
        'js/html5shiv.js',
        'js/respond.min.js',
    ];
//опции
    public $jsOptions = [
        'condition' => 'lte IE9', //условие подключения (только для IE)
        'position' => \yii\web\View::POS_HEAD //позиция подключения- в голове
    ];
}	