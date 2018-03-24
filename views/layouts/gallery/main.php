<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\ltAppAsset;
use yii\helpers\Url;
use app\assets\GalleryAsset;

GalleryAsset::register($this);
ltAppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div id="portfolio-nav">
    <!-- <a href=" <?= Url::to('http://www.portfoliokot.dev') ?>" class='portfolio-nav'>Портфолио</a> -->
    <div style="float: left; color: white; margin-right: 20px">
        <p id="visiting-title">Визитка</p>
    </div>
    <div id="visiting-card">
        <a href="<?= \yii\helpers\Url::to('@web/images/gallery/site-elements/visiting-avatar.png'); ?>">
            <?= Html::img('@web/images/gallery/site-elements/visiting-avatar.png', [
                'id' => 'visiting-avatar',
                'src' => '@web/images/gallery/site-elements/visiting-avatar.png',
            ]); ?>
        </a>
        <div id="visiting-close"></div>
        <a href="<?= \yii\helpers\Url::to('@web/images/gallery/site-elements/visiting-avatar.png') ?>" download="visit_cart">
            <div id="visiting-load" title="скачать"></div>
        </a>
    </div>
</div>

<div class="wrap navbar" style="padding-top: 40px">
    <?php
    NavBar::begin([
        'brandLabel' => '',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => [
            ['label' => 'Галлерея Юлии', 'url' => ['/gallery/index', 'status' => 'author'],
                'options' => [
                    'class' => 'js_gallery',
                    'data-status' => 'author',
                ]
            ],
            ['label' => 'Галлерея друзей', 'url' => ['/gallery/index', 'status' => 'friend'],
                'options' => [
                    'class' => 'js_gallery',
                    'data-status' => 'friend'
                ]
            ],
            ['label' => 'Редактирование', 'url' => ['/gallery/edit', 'remove' => null]], //'remove' => null - служебный код
            ['label' => 'Очистить КЕШ(служеб)', 'url' => ['/gallery/edit/', 'remove' => 1]], //служебный код
        ],
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <section id="ulia">
            <?= $content ?>
        </section>

    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left"><?= date('Y') ?> &copy; konstantin.web.freelance@gmail.com </p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
