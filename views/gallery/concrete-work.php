<?php

use yii\helpers\Html;
use yii\helpers\Url;

$folder = $form['login'];
//debug($form);
?>
<section id="ulia">
    <div id="content" class="container">
        <div class="row">
            <div class="col-lg-12">
                <div id="work-concrete-description">
                    <h1>"<?= $form['work']->name_work ?>"
                        <span>
                        <?=
                        ' (' .
                        Html::img("@web/images/gallery/direction-icon/{$form['nameFileDirection']}", ['alt' => $form['directionName'], 'width' => '60px'])
                        . $form['directionName'] . ')';
                        ?>
                    </span>
                    </h1>
                    <h3>
                        <?= 'Автор работы: ' ?>
                        <?php if ($form['status'] == 'author'): ?>
                            <?= Html::img("@web/images/gallery/site-elements/ulia.png", ['alt' => $form['login'], 'width' => '60px'])
                            . $form['name'] . ' (' . $form['login'] . ')' ?>
                        <?php endif; ?>
                        <?php if ($form['status'] == 'friend'): ?>
                            <?= Html::img("@web/images/gallery/{$form['login']}/{$form['nameFileUser']}", ['alt' => $form['login'], 'width' => '60px'])
                            . $form['name'] . ' (' . $form['login'] . ')' ?>
                        <?php endif; ?>
                    </h3>
                    <div class="work-concrete-end">
                        <?php if ($redirect == 'index'): ?>
                            <a class="work-concrete-close <?= 'get_' . $form['status'] . '_works' ?>"
                               href="<?= Url::to(['/gallery/' . $redirect, 'status' => $form['status']]) ?>"
                               data-id="<?= $form['galleryId'] ?>">
                                <?= Html::img("@web/images/gallery/site-elements/close.png", ['alt' => 'выйти', 'width' => '25px']) ?>
                            </a>
                        <?php endif; ?>
                        <?php if ($redirect == 'works'): ?>
                            <?php if ($form['status'] == 'author'): ?>
                                <?php $galleryId = $form['work']['id_direction'] ?>
                            <?php endif; ?>
                            <?php if ($form['status'] == 'friend'): ?>
                                <?php $galleryId = $form['work']['id_user'] ?>
                            <?php endif; ?>
                            <a class="work-concrete-close <?= 'get_' . $form['status'] . '_works' ?>"
                               href="<?= Url::to(['/gallery/' . $redirect, 'status' => $form['status'], 'galleryId' => $galleryId]) ?>"
                               data-id="<?= $form['galleryId'] ?>">
                                <?= Html::img("@web/images/gallery/site-elements/close.png", ['alt' => 'выйти', 'width' => '30 px']) ?>
                            </a>
                        <?php endif; ?>
                        <p><?= 'Дата размещения: ' . $form['work']->load_time ?></p>
                        <p><?= 'Описание работы: ' . $form['work']->description ?></p>
                    </div>
                </div>
                <?php $file = Url::to("@web/images/gallery/$folder/{$form['work']->name_file}") ?>
                <div class="col-lg-12 concrete-work"
                     style="background: url(<?= $file ?>) no-repeat; background-size: contain; background-position: center top">
                </div>
            </div>
        </div>
    </div>
</section>
