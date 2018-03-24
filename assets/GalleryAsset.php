<?php


namespace app\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class GalleryAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/gallery/gallery.css',
        'css/gallery/edit.css',
        'css/gallery/styles-portfolio.css',
        'fonts/ofont.ru_LadyFairCurlyC.ttf'
    ];
    public $js = [
        'js/gallery/gallery.js',
        'js/gallery/edit.js',
        'js/gallery/portfolio.js'
    ];
    public $depends = [
        'yii\web\YiiAsset', //jQuery
        'yii\bootstrap\BootstrapPluginAsset', //Bootstrap.min.css и Bootstrap.min.js
    ];
}
