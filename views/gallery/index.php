<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

$n = 0;
//debug ($form);
?>
<?php $this->title = 'Галлерея автора' ?>
<?php if ($status == 'author'): ?>
    <div class="menu-content container">
        <div class="row">
            <div class="col-lg-12 nav menu">
                <button type="button" class="btn btn-default dropdown-toggle menu" data-toggle="dropdown">РАЗДЕЛЫ
                </button>
                <ul class="navbar-nav navbar-left nav menu">
                    <?php if ($form['directions']) : ?>
                        <?php foreach ($form['directions'] as $key => $direction): ?>
                            <li>
                                <a class="get_author_works"
                                   href="<?= Url::to(['/gallery/works', 'status' => 'author', 'galleryId' => $direction['id']]) ?>"
                                   data-id="<?= $direction['id'] ?>">
                                    <?= $direction['name']; ?>
                                    <?php if ($direction['name_file']): ?>
                                        <span>
                                                <?= Html::img("@web/images/gallery/direction-icon/{$direction['name_file']}", ['alt' => 'logo', 'width' => '25px', 'height' => '25px']) ?>
                                            </span>
                                    <?php endif; ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>
<?php endif; ?>

<!-- меню с друзьями -->
<?php if ($status == 'friend'): ?>
    <?php $this->title = 'Галлерея друзей' ?>
    <div class="menu-content container">
        <div class="row">
            <div class="col-lg-12 nav menu">
                <button type="button" class="btn btn-default dropdown-toggle menu" data-toggle="dropdown">ДРУЗЬЯ
                </button>
                <ul class="navbar-nav navbar-left nav menu">
                    <?php if ($form['users']): ?>
                        <?php foreach ($form['users'] as $key => $user): ?>
                            <li>
                                <a class="get_friend_works"
                                   href="<?= Url::to(['/gallery/works', 'status' => 'friend', 'galleryId' => $user['id']]) ?>"
                                   data-id="<?= $user['id'] ?>"><?= $user['name'] /*. ' (' . $user['login'] . ')'*/
                                    ; ?>
                                    <span>
                                            <?php if ($user['name_file']): ?>
                                                <?= Html::img("@web/images/gallery/{$user['login']}/{$user['name_file']}", ['width' => '25px', 'height' => '25px']) ?>
                                            <?php else: ?>
                                                <?= Html::img("@web/images/gallery/site-elements/no_avatar.png", ['alt' => 'аватарка', 'width' => '25px', 'height' => '25px']) ?>
                                            <?php endif; ?>
                                        </span>
                                </a>
                            </li>
                        <?php endforeach; ?>
                        <li>
                            <a class="index" href="<?= Url::to(['/gallery/index', 'status' => 'author']) ?>"
                               data-id='author'><?= 'На главную'; ?>
                                <?= Html::img("@web/images/gallery/site-elements/index.jpg", ['alt' => 'Юля', 'width' => '25px', 'height' => '25px']) ?> </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>
<?php endif; ?>
<!---------------------- РЕКЛАММНЫЙ БАННЕР НАЧАЛО-------------------------------------->
<div id="testCarousel" class="carousel slide" data-ride="carousel">
    <div id="advertising">
        <!-- Индикаторы карусели -->
        <ol class="carousel-indicators">
            <?php $slides = 1 ?>
            <?php foreach ($advertising as $advertisingOne): ?>
                <?php if ($slides == 1): ?>
                    <?php $active = 'active' ?>
                <?php else: ?>
                    <?php $active = null ?>
                <?php endif; ?>

                <!-- Перейти к слайду  -->
                <li data-target="#testCarousel" data-slide-to=<?= $slides - 1 ?> class=<?= $active ?>></li>
                <?php $slides += 1; ?>
            <?php endforeach; ?>
        </ol>

        <!-- Слайды карусели -->
        <div class="carousel-inner">
            <?php $slides = 1 ?>
            <?php foreach ($advertising as $advertisingOne): ?>
                <?php if ($slides == 1): ?>
                    <?php $active = 'active' ?>
                <?php else: ?>
                    <?php $active = null ?>
                <?php endif; ?>
                <!-- Слайд  -->
                <div class="item <?= $active ?>">
                    <?php $fileAdvertising = Url::to("@web/images/gallery/advertising/{$advertisingOne['name_file']}"); ?>
                    <div id="advertising" style="background:url(<?= $fileAdvertising ?>);">
                        <p>ЗДЕСЬ МОЖЕТ БЫТЬ ВАШ РЕКЛАММНЫЙ БАННЕР</p>
                    </div>
                </div>
                <?php $slides += 1; ?>
            <?php endforeach; ?>

        </div>

        <!-- Навигация карусели (следующий или предыдущий слайд) -->
        <!-- Кнопка, переход на предыдущий слайд с помощью атрибута data-slide="prev" -->
        <a class="left carousel-control" href="#testCarousel" role="button" data-slide="prev">
            <span class="glyphicon glyphicon-chevron-left"></span>
        </a>
        <!-- Кнопка, переход на следующий слайд с помощью атрибута data-slide="next" -->
        <a class="right carousel-control" href="#testCarousel" role="button" data-slide="next">
            <span class="glyphicon glyphicon-chevron-right"></span>
        </a>
    </div>
</div>


<!---------------------- РЕКЛАММНЫЙ БАННЕР КОНЕЦ-------------------------------------->

<!-- вывод галлерии -->
<?php Pjax::begin() ?>

<div class="row works-content">
    <?php if ($form['gallery']['gallery']): ?>
        <?php if ($status == 'friend'): ?>
            <h1>Галлерея лучших работ моих друзей </h1>
        <?php else: ?>
            <h1>Галлерея моих лучших работ </h1>
        <?php endif ?>
        <?php foreach ($form['gallery']['gallery'] as $work): ?>
            <?php if ($status == 'author'): ?>
                <?php $userLogin = 'ulia'; ?>
            <?php endif; ?>
            <?php if ($status == 'friend'): ?>
                <?php $userLogin = $form['users'][$work['id_user']]['login'] ?>
            <?php endif; ?>
            <?php if ($n % 2 == 0): ?>
                <div class="shell_work col-md-6">
                <div class="row">
            <?php endif; ?>
            <div class="work col-sm-6">
                <a href="<?= Url::toRoute(['gallery/concrete-work', 'redirect' => $redirect, 'workId' => $work['id']]) ?>"
                   data-id="<?= $work['id'] ?>">
                    <p><?= $work['name_work']; ?><p>
                        <?php $file = Url::to("@web/images/gallery/$userLogin/{$work['name_file']}"); ?>
                    <div class="work-img"
                         style="background: url(<?= $file ?>) no-repeat; background-size: contain; background-position: center top">
                    </div>
                    <?php /* Html::img("@web/images/gallery/$userLogin/{$work['name_file']}", ['alt' => $work['name_file'], 'width' => '100%']); */ ?>
                </a>
                <div class="work-description">
                    <p><?= 'Дата размещения: ' . $work['load_time'] ?></p>
                    <?php if ($status == 'author') : ?>
                        <?php foreach ($form['directions'] as $direction): ?>
                            <?php if ($work['id_direction'] == $direction['id']) : ?>
                                <a href="<?= Url::toRoute(['gallery/works', 'status' => $status, 'galleryId' => $direction['id']]) ?>"
                                   data-id="<?= $direction['id'] ?>">
                                    <?php if ($direction['name_file']): ?>
                                        <!-- Сдесь показан оригенальный многоступенчатый доступ к name_file, а можно было проще : {$direction['name_file']} -->
                                        <?= Html::img("@web/images/gallery/direction-icon/{$form['directions'][$work['id_direction']]['name_file']} ", ['alt' => 'avatar', 'width' => '25px', 'height' => '25px']) ?>
                                    <?php endif; ?>
                                    <?= $direction['name'] ?>
                                </a>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    <?php if ($status == 'friend') : ?>
                        <?php foreach ($form['users'] as $user): ?>
                            <?php if ($work['id_user'] == $user['id']) : ?>
                                <a href="<?= Url::toRoute(['gallery/works', 'status' => $status, 'galleryId' => $user['id']]) ?>"
                                   data-id="<?= $user['id'] ?>">
                                    автор:
                                    <?php if ($user['name_file']): ?>
                                        <?= Html::img("@web/images/gallery/{$user['login']}/{$user['name_file']}", ['alt' => 'avatar', 'width' => "25px", 'height' => '25px']) ?>
                                    <?php endif; ?>
                                    <?= $user['name'] /*. ' (' . $user['login'] . ')' */ ?>
                                </a>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
            <?php $n++ ?>
            <?php if ($n % 2 == 0): ?>
                </div>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
        <div id="pagination">
            <?= \yii\widgets\LinkPager::widget(['pagination' => $form['gallery']['pagination']]) ?>
        </div>
    <?php else: ?>
        <div class="gallery-empty">
            Галлерея пуста
        </div>
    <?php endif; ?>

</div>

<?php Pjax::end() ?>




