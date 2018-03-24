<?php


namespace app\controllers;

use app\models\gallery\GalleryUser;
use Yii;
use app\models\gallery\EditForm;
use app\models\gallery\GalleryForm;

class GalleryController extends AppController
{
    public $layout = 'gallery/main';
    private $error = 'Неизвестный запрос' . "\n";


    /**
     *  GALLERY_____________________________________________________
     */

    public function actionIndex($status = 'author')
    {
        $redirect = 'index';
        if ($status) {
            $galleryForm = new GalleryForm();
            $form = $galleryForm->getGalleryForm($status);
            $advertising = $galleryForm->getAdvertising();
        } else {
            $form = $this->error;
            $status = "";
        }

        return $this->render('index', [
            'redirect' => $redirect,
            'status' => $status,
            'form' => $form,
            'advertising' => $advertising,
        ]);
    }


    /**
     * Request page tab of the gallery of the author or gallery friend
     * @param string $status
     * @param string $galleryId
     * @return string
     */
    public function actionWorks($status = '', $galleryId = '')
    {
        $redirect = 'works';
        if ($status && $galleryId) {
            $galleryForm = new GalleryForm();
            $form = $galleryForm->getWorksForm($status, $galleryId);
            $advertising = $galleryForm->getAdvertising();
        } else $form = $this->error;

        return $this->render('works', [
            'redirect' => $redirect,
            'status' => $status,
            'form' => $form,
            'advertising' => $advertising,
        ]);
    }

    /**
     * A page request with a specific work
     * @param string $workId
     * @return string
     */
    public function actionConcreteWork($redirect = '', $workId = '')
    {
        $this->view->title = 'Отдельная работа';
        if ($workId) {
            $galleryForm = new GalleryForm();
            $form = $galleryForm->getConcreteWorkForm($workId);
        } else $form = $this->error;

        return $this->render('concrete-work', [
            'redirect' => $redirect,
            'form' => $form,
        ]);
    }


    /**
     * EDIT_______________________________________________________________________
     */

    public function actionEdit($remove = null) //$remove = null - служебный код
    {
        $editForm = new EditForm();
        if (Yii::$app->session->has('user')) {
            if ($remove == null) { // служебный код
                $editForm->authorization();
            }
            //служебный код
            if ($remove == 1) {
                Yii::$app->session->remove('user');
            }
        } else {
            if ($editForm->load(Yii::$app->request->post()) && $editForm->validate()) {
                $editForm->userIdentification($editForm);
            }
        }
        return $this->render('edit', [
            'editForm' => $editForm,
        ]);
    }

    /**
     * Challenge section editing area of creativity by the author
     * @param $userStatus
     * @param $userId
     * @param null $editUser
     * @param $editAction
     * @return string
     */
    public function actionEditDirection($userStatus, $userId, $editAction, $identification)
    {
        $editForm = new EditForm;
        $editForm->sectionEdit = 'direction';
        $editForm->action = $editAction;
        $editForm->userStatus = $userStatus;
        $editForm->userId = $userId;
        $editForm->identification = $identification;
        $objectEdit = $editForm->generateObjectEdit($editForm);

        if ($editAction == 'delete') {
            $editForm->title = 'Удаление направления творчества';
            $editForm->list = $objectEdit->getListEdit($editForm);
            if ($editForm->load(Yii::$app->request->post())) {
                if ($editForm->identification == 'check') {
                    $editForm->result = $objectEdit->deleteDirection($editForm);
                } else $editForm->result = $this->getNotCheck($editForm);
                $editForm->list = $objectEdit->getListEdit($editForm); //обновление списка
            }
        }
        if ($editAction == 'add') {
            $editForm->title = 'Добавление направления творчества';
            $editForm->list = null;
            if ($editForm->load(Yii::$app->request->post())) {
                if ($editForm->identification == 'check') {
                    if ($editForm->directionName) {
                        $editForm->directionName = strip_tags($editForm->directionName);
                        if ($editForm->validate()) $editForm->result = $objectEdit->addDirection($editForm);
                        else {
                            $editForm->result['text'] = 'Введите название раздела';
                            $editForm->result['color'] = 'yellow';
                        }
                    } else {
                        $editForm->result['text'] = 'Введите название раздела';
                        $editForm->result['color'] = 'yellow';
                    }
                } else $editForm->result = $this->getNotCheck($editForm);
            }
        }
        if ($editAction == 'change') {
            $editForm->title = 'Редактирование направлений творчества';
            $editForm->list = $objectEdit->getListEdit($editForm);
            if ($editForm->load(Yii::$app->request->post()) && $editForm->directionId) {
                if ($editForm->identification == 'check') {

                    if ($editForm->directionName) {
                        $editForm->directionName = strip_tags($editForm->directionName);//очистка от html тегов
                        $editForm->validate();
                    }
                    $editForm->result = $objectEdit->changeDirection($editForm);
                } else $editForm->result = $this->getNotCheck($editForm);
                $editForm->list = $objectEdit->getListEdit($editForm);
            }
        }
        return $this->render('edit-direction', [
            'editForm' => $editForm,
        ]);
    }

    /**
     * Edit friends list on the website
     * @param $userStatus
     * @param $userId
     * @param null $editUser
     * @param $editAction
     * @return string
     */
    public function actionEditUser($userStatus, $userId, $editAction, $identification)
    {
        $editForm = new EditForm;
        $editForm->sectionEdit = 'user';
        $editForm->action = $editAction;
        $editForm->userStatus = $userStatus;
        $editForm->userId = $userId;
        $editForm->identification = $identification;
        $objectEdit = $editForm->generateObjectEdit($editForm);
        if ($editAction == 'delete') {
            $editForm->title = 'Удаление из друзей';
            $editForm->list = $objectEdit->getListEdit($editForm);
            if ($editForm->load(Yii::$app->request->post())) {
                if ($editForm->identification == 'check') {
                    $editForm->result = $objectEdit->deleteUser($editForm);
                } else $this->getNotCheck($editForm);
                $editForm->list = $objectEdit->getListEdit($editForm); //обновление списка
            }
        }
        if ($editAction == 'add') {
            $editForm->title = 'Добавление в друзья';
            $editForm->list = null;
            if ($editForm->load(Yii::$app->request->post())) {
                if ($editForm->identification == 'check') {
                    $editForm->userName = strip_tags($editForm->userName); //очистка от html тегов
                    $editForm->userLogin = strip_tags($editForm->userLogin);
                    if ($editForm->validate()) $editForm->result = $objectEdit->addUser($editForm);
                } else $this->getNotCheck($editForm);
            }
        }
        if ($editAction == 'change') {
            $editForm->title = 'Редактирование данных друзей';
            $editForm->list = $objectEdit->getListEdit($editForm);
            if ($editForm->load(Yii::$app->request->post()) && !$editForm->userLogin) {
                $editForm->result = $objectEdit->changeUser($editForm);
                $editForm->list = $objectEdit->getListEdit($editForm);
            }
            if ($editForm->load(Yii::$app->request->post()) && $editForm->userLogin) {
                if ($editForm->identification == 'check') {
                    $editForm->userName = strip_tags($editForm->userName); //очистка от html тегов
                    $editForm->userLogin = strip_tags($editForm->userLogin);
                    if ($editForm->validate()) {
                        $editForm->result = $objectEdit->changeUser($editForm);
                        $editForm->list = $objectEdit->getListEdit($editForm);
                    }
                } else $this->getNotCheck($editForm);
            }
        }

        return $this->render('edit-user', [
            'editForm' => $editForm,
        ]);
    }

    public function actionEditWork($userStatus, $userId, $editUser = null, $editAction, $identification)
    {
        $editForm = new EditForm();
        $editForm->sectionEdit = 'work';
        $editForm->action = $editAction;
        $editForm->userStatus = $userStatus;
        $editForm->userId = $userId;
        $editForm->identification = $identification;
        $user = GalleryUser::findOne(['id' => $editForm->userId]);
        if ($user) {
            $editForm->userName = $user->name;
            $editForm->userLogin = $user->login;
            if ($editForm->userStatus == 'friend') {
                $editForm->avatar = $user->name_file;
            }
        }
        $editForm->editUser = $editUser;
        $objectEdit = $editForm->generateObjectEdit($editForm);
        $editForm->list = $objectEdit->getListEdit($editForm);
        if ($editForm->action == 'add') {
            if ($editForm->editUser == 'self') {
                $author = GalleryUser::findOne(['status' => 'author']);
                $editForm->userId = $author->id;
                $editForm->title = 'Добавление работы Автора';
            }
            if ($editForm->editUser == 'friend' || $editForm->userStatus == 'friend') {
                $editForm->title = 'Добавление работы друга';
            }
            if ($editForm->load(Yii::$app->request->post())) {
                if ($editForm->identification == 'check') {
                    $editForm->workName = strip_tags($editForm->workName); //очистка от html тегов
                    $editForm->description = strip_tags($editForm->description);
                    if ($editForm->validate()) {
                        $user = GalleryUser::findOne(['id' => $editForm->userId]);
                        $editForm->result = $objectEdit->addWork($editForm, $user);
                        $editForm->list = $objectEdit->getListEdit($editForm);
                    }
                } else $this->getNotCheckWork($editForm);
            }
        }
        if ($editForm->action == 'delete') {
            if ($editForm->editUser == 'self') {
                $author = GalleryUser::findOne(['status' => 'author']);
                $editForm->userId = $author->id;
                $editForm->title = 'Удаление работы Автора';
            }
            if ($editForm->editUser == 'friend' || $editForm->userStatus == 'friend') {
                $editForm->title = 'Удаление работы друга';
            }
            if ($editForm->load(Yii::$app->request->post())) {
                if ($editForm->identification == 'check') {
                    if ($editForm->workId) {
                        $editForm->result = $objectEdit->deleteWork($editForm);
                    }
                } else $this->getNotCheckWork($editForm);
                $editForm->list = $objectEdit->getListEdit($editForm);
            }
        }
        if ($editForm->action == 'change') {
            if ($editForm->editUser == 'self') {
                $author = GalleryUser::findOne(['status' => 'author']);
                $editForm->userId = $author->id;
                $editForm->title = 'Редактирование работы Автора';
            }
            if ($editForm->editUser == 'friend' || $editForm->userStatus == 'friend') {
                $editForm->title = 'Редактирование работы друга';
            }
            if ($editForm->load(Yii::$app->request->post())) {
                if ($editForm->identification == 'check') {
                    if ($editForm->directionId) {
                        if ($editForm->workId) {
                            if ($editForm->workName) {
                                $editForm->workName = strip_tags($editForm->workName); //очистка от html тегов
                                $editForm->description = strip_tags($editForm->description);
                                $editForm->validate();
                            }
                            $editForm->result = $objectEdit->changeWork($editForm);
                        }
                    }
                } else $this->getNotCheckWork($editForm);
                $editForm->list = $objectEdit->getListEdit($editForm);
            }

        }

        return $this->render('edit-work', [
            'editForm' => $editForm,
        ]);
    }

    private function getNotCheck(EditForm $editForm)
    {
        $editForm->result['text'] = 'У Вас нет доступа';
        $editForm->result['color'] = 'red';
        return $editForm->result;
    }

    private function getNotCheckWork(EditForm $editForm)
    {
        $editForm->result = "<p class='edit-result' style='background: red'>У Вас нет доступа</p>";
        return $editForm->result;
    }

}