<?php

namespace app\components\gallery;

use app\models\gallery\GalleryUser;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Url;

class EditFormWidget extends Widget
{
    public $editForm;

    /**
     * The formation vidget
     */
    public function run()
    {

        parent::run(); // TODO: Change the autogenerated stub

        $imgDelete = Html::tag('div', '', ['class' => 'edit-command img-delete']);
        $imgAdd = Html::tag('div', '', ['class' => 'edit-command img-add']);
        $imgChange = Html::tag('div', '', ['class' => 'edit-command img-change']);
        if ($this->editForm->userStatus == 'author' || $this->editForm->identification == 'notCheck') {
            $fileAvatar = 'site-elements/ulia.png';
            $author = GalleryUser::findOne(['status' => 'author']);
            $admin = $author->name;
            $editCommands = [
                'Разделы творчества' => [
                    [
                        'command' => $imgDelete,
                        'action' => 'gallery/edit-direction',
                        'editUser' => null,
                        'editAction' => 'delete',
                    ],
                    [
                        'command' => $imgAdd,
                        'action' => 'gallery/edit-direction',
                        'editUser' => null,
                        'editAction' => 'add',
                    ],
                    [
                        'command' => $imgChange,
                        'action' => 'gallery/edit-direction',
                        'editUser' => null,
                        'editAction' => 'change',
                    ]
                ],
                'Пользователей' => [
                    [
                        'command' => $imgDelete,
                        'action' => 'gallery/edit-user',
                        'editUser' => null,
                        'editAction' => 'delete',
                    ],
                    [
                        'command' => $imgAdd,
                        'action' => 'gallery/edit-user',
                        'editUser' => null,
                        'editAction' => 'add',
                    ],
                    [
                        'command' => $imgChange,
                        'action' => 'gallery/edit-user',
                        'editUser' => null,
                        'editAction' => 'change',
                    ]
                ],
                'Свои работы' => [
                    [
                        'command' => $imgDelete,
                        'action' => 'gallery/edit-work',
                        'editUser' => 'self',
                        'editAction' => 'delete',
                    ],
                    [
                        'command' => $imgAdd,
                        'action' => 'gallery/edit-work',
                        'editUser' => 'self',
                        'editAction' => 'add',
                    ],
                    [
                        'command' => $imgChange,
                        'action' => 'gallery/edit-work',
                        'editUser' => 'self',
                        'editAction' => 'change',
                    ]
                ],
                'Работы друзей' => [
                    [
                        'command' => $imgDelete,
                        'action' => 'gallery/edit-work',
                        'editUser' => 'friend',
                        'editAction' => 'delete',
                    ],
                    [
                        'command' => $imgAdd,
                        'action' => 'gallery/edit-work',
                        'editUser' => 'friend',
                        'editAction' => 'add',
                    ],
                    [
                        'command' => $imgChange,
                        'action' => 'gallery/edit-work',
                        'editUser' => 'friend',
                        'editAction' => 'change',
                    ]
                ]
            ];
            $this->getMenuEditCommand($editCommands, $fileAvatar, $admin, $this->editForm->identification);
        }
        if ($this->editForm->userStatus == 'friend') {
            $fileAvatar = $this->editForm->userLogin . '/' . $this->editForm->avatar;
            $admin = $this->editForm->userName;
            $editCommands = [
                'Работы' => [
                    [
                        'command' => $imgDelete,
                        'action' => 'gallery/edit-work',
                        'editUser' => null,
                        'editAction' => 'delete'
                    ],
                    [
                        'command' => $imgAdd,
                        'action' => 'gallery/edit-work',
                        'editUser' => null,
                        'editAction' => 'add'
                    ],
                    [
                        'command' => $imgChange,
                        'action' => 'gallery/edit-work',
                        'editUser' => null,
                        'editAction' => 'change'
                    ]
                ]
            ];
            $this->getMenuEditCommand($editCommands, $fileAvatar, $admin);
        }
    }

    /**
     * The formation menu edit
     * @param $editCommands
     */
    private function getMenuEditCommand($editCommands, $fileAvatar, $admin)
    {
        //debug($this->editForm->identification['value']);
        if ($this->editForm->identification == 'check') {
            $file = Url::to('@web/images/gallery/' . $fileAvatar);
            $avatar = Html::tag('div', '', ['style' => "background: url($file); width: 100px; height: 100px; float: left; border-radius: 50px;"]);
            //$span = Html::tag('span', $avatar);
            $admin = Html::tag('h3', $admin, [
                'style' => 'font-family: "Tibetan Machine Uni"; font-weight: bold; font-style: italic; font-size: 4em; color: #ff0ee2;
              ']);
            echo $avatar . $admin;
            echo Html::tag('h4', 'Редактирует: ', ['style' => 'clear: left; font-weight: bold']);
        } else echo Html::tag('h4', 'Мы Вас не знаем, включен режим "demo" ',
            ['style' => 'clear: left; font-weight: bold']);
        foreach ($editCommands as $edit => $commands) {
            foreach ($commands as $command) {
                $editList[$edit][$command['editAction']] = Html::a(
                    $command['command'], //контент ссылки
                    Url::toRoute([
                        $command['action'],
                        'userStatus' => $this->editForm->userStatus,
                        'userId' => $this->editForm->userId,
                        'editUser' => $command['editUser'],
                        'editAction' => $command['editAction'],
                        'identification' => $this->editForm->identification,
                    ]), //url
                    ['data-id' => '$editCommand[\'action\']', 'style' => 'display: block; float: left'] // опции - атрибуты : данных, стиля
                );
            }
            $p = Html::tag('p', $edit);
            $ul = Html::ul($editList[$edit], ['encode' => false, 'style' => 'list-style-type: none; display: none']);
            $div = Html::tag('div', $p . $ul, ['class' => 'container col-sm-12 edit-menu']);
            echo $div;
        }
    }
}