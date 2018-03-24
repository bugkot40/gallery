<?php

use yii\helpers\Html;
use yii\helpers\Url;

$n = 0;

if ($form['works']) {
    if ($status == 'author') $this->title = $form['direction'];
    elseif ($status == 'friend') $this->title = $form['nameUser'];
}
?>

<section id="ulia">
    <!-- меню категорий работ автора-->
    <?php if ($status == 'author'): ?>
        <div class="menu-content container">
            <div class="row">
                <div class="col-lg-12 nav menu">
                    <button type="button" class="btn btn-default dropdown-toggle menu" data-toggle="dropdown">РАЗДЕЛЫ
                    </button>
                    <ul class="navbar-nav navbar-left nav menu">
                        <?php if ($form['directions']): ?>
                            <?php foreach ($form['directions'] as $key => $direction): ?>
                                <li>
                                    <a class="get_author_works"
                                       href="<?= Url::to(['/gallery/works', 'status' => 'author', 'galleryId' => $direction['id']]) ?>"
                                       data-id="<?= $direction['id'] ?>"><?= $direction['name']; ?>
                                        <?php if ($direction['name_file']): ?>
                                            <span>
                                                <?= Html::img("@web/images/gallery/direction-icon/{$direction['name_file']}", ['alt' => 'logo', 'width' => '25px', 'height' => '25px']) ?>
                                            </span>
                                        <?php endif; ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                            <li>
                                <a class="ago" href='<?= Url::toRoute(['gallery/index', 'status' => $status]) ?>'>
                                    <?= 'Галлерея'; ?>
                                    <?= Html::img("@web/images/gallery/site-elements/ago.png", ['alt' => 'назад', 'width' => '25px']) ?>
                                </a>
                            </li>
                            <li>
                                <a class="index" href="<?= Url::to(['/gallery/index', 'status' => 'author']) ?>"
                                   data-id='author'><?= 'На главную'; ?>
                                    <?= Html::img("@web/images/gallery/site-elements/index.jpg", ['alt' => 'Юля', 'width' => '25px']) ?>
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <!-- меню с друзьями -->
    <?php if ($status == 'friend'): ?>
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
                                       data-id="<?= $user['id'] ?>"><?= $user['name']/*.'(' . $user['login'] . ')'*/ ?>
                                        <span>
                                            <?php if ($user['name_file']): ?>
                                                <?= Html::img("@web/images/gallery/{$user['login']}/{$user['name_file']}", ['alt' => 'аватарка', 'width' => '25px', 'height' => '25px']) ?>
                                            <?php else: ?>
                                                <?= Html::img("@web/images/gallery/site-elements/no_avatar.png", ['alt' => 'аватарка', 'width' => '25px', 'height' => '25px']) ?>
                                            <?php endif; ?>
                                        </span>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                            <li>
                                <a class="ago" href='<?= Url::toRoute(['gallery/index', 'status' => $status]) ?>'>
                                    <?= 'Галлерея'; ?>
                                    <?= Html::img("@web/images/gallery/site-elements/ago.png", ['alt' => 'назад', 'width' => '25px', 'height' => '25px']) ?>
                                </a>
                            </li>
                            <li>
                                <a class="index" href="<?= Url::to(['/gallery/index', 'status' => 'author']) ?>"
                                   data-id='author'><?= 'На главную'; ?>
                                    <?= Html::img("@web/images/gallery/site-elements/index.jpg", ['alt' => 'Юля', 'width' => '25px', 'height' => '25px']) ?>
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <!-- блок с рекламой -->
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
    <!-- Вывод всех работ отдельного раздела-->
    <?php \yii\widgets\Pjax::begin() ?>
    <div class="row works-content">
        <?php if ($form['works']): ?>
            <?php if ($status == 'author'): ?>
                <h1>
                    Мои работы в категории:
                    <span>
                        <?php foreach ($form['directions'] as $direction): ?>
                            <?php if ($form['direction'] == $direction['name']): ?>
                                <?php $nameFile = $direction['name_file'] ?>
                            <?php endif; ?>
                        <?php endforeach; ?>
                        <?= Html::img("@web/images/gallery/direction-icon/{$nameFile}", ['alt' => $form['direction'], 'width' => '50px', 'height' => '50px']) ?>
                    </span>
                    <?= $form['direction'] ?>
                </h1>
                <?php $userLogin = 'ulia'; ?>
            <?php endif; ?>
            <?php if ($status == 'friend'): ?>
                <h1>
                    Работы моего друга:
                    <?php foreach ($form['users'] as $user): ?>
                        <?php if ($form['login'] == $user['login']): ?>
                            <?php $nameFile = $user['name_file'] ?>
                        <?php endif; ?>
                    <?php endforeach; ?>
                    <?= Html::img("@web/images/gallery/{$form['login']}/{$nameFile}", ['alt' => $form['login'], 'width' => '50px', 'height' => '50px']) ?>
                    <?= $form['nameUser'] /*. ' (' . $form['login'] . ')'*/ ?>
                </h1>
                <?php $userLogin = $form['login']; ?>
            <?php endif; ?>
            <?php foreach ($form['works'] as $work): ?>
                <?php if ($n % 2 == 0): ?>
                    <div class="shell_work col-md-6">
                    <div class="row">
                <?php endif; ?>
                <div class="work col-sm-6">
                    <a href="<?= Url::to(['/gallery/concrete-work', 'redirect' => $redirect, 'workId' => $work['id']]) ?>"
                       data-id="<?= $work['id'] ?>">
                        <?php $file = Url::to("@web/images/gallery/$userLogin/{$work['name_file']}") ?>
                        <p><?= $work['name_work']; ?></p>
                        <div class="work-img"
                             style="background: url(<?= $file ?>) no-repeat; background-size: contain; background-position: center top">
                        </div>
                        <?php /* Html::img("@web/images/gallery/$userLogin/{$work['name_file']}", ['alt' => $work['name_file'], 'width' => '100%',]); */ ?>
                    </a>
                    <p class="work-description"><?= 'Дата размещения: ' . $work['load_time'] ?></p>
                </div>
                <?php $n++ ?>
                <?php if ($n % 2 == 0): ?>
                    </div>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php else: ?>
            <?php $this->title = 'Пусто' ?>
            <?php if ($status == 'author'): ?>
                <div class="gallery-empty">
                    <?= 'В категории ' . $form['direction'] . ' работ нет'; ?>
                </div>
            <?php endif; ?>
            <?php if ($status == 'friend'): ?>
                <div class="gallery-empty">
                    <?= 'У друга ' . $form['nameUser'] /*. ' (логин - ' . $form['login'] . ')' */ . ' работы не представлены'; ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>
        <div id="pagination">
            <?= \yii\widgets\LinkPager::widget(['pagination' => $form['pagination']]) ?>
        </div>
    </div>
    <?php \yii\widgets\Pjax::end() ?>
</section>