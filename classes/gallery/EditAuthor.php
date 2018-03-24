<?php


namespace app\classes\gallery;

use app\models\gallery\GalleryDirection;
use app\models\gallery\EditForm;
use app\models\gallery\GalleryUser;
use app\models\gallery\GalleryWork;

use Yii;
use yii\web\UploadedFile;

class EditAuthor extends Edit
{
    /**
     * Getting all lists for the author
     * @param EditForm $editForm
     * @return array
     */
    public function getListEdit(EditForm $editForm)
    {
        // TODO: Implement getListEdit() method.
        if ($editForm->sectionEdit == 'direction') {
            return $this->prepareDirectionList();
        }
        if ($editForm->sectionEdit == 'user') {
            return $this->prepareUserList();
        }
        if ($editForm->sectionEdit == 'work') {
            return [
                'direction' => $this->prepareDirectionList(),
                'user' => $this->prepareUserList(),
                'work' => $this->prepareWorkList($editForm),
            ];
        }
    }

    /**
     * Removal directions creativity
     * @param EditForm $editForm
     * @return string
     */
    public function deleteDirection(EditForm $editForm)
    {
        $directionDelete = GalleryDirection::findOne(['id' => $editForm->directionId]);
        if ($directionDelete) {
            $file = $directionDelete->name_file;
            if ($file) {
                $fileDirectory = Yii::getAlias("images/gallery/direction-icon/");
                unlink($fileDirectory . $file);
            }
            $works = GalleryWork::find()->where(['id_direction' => $directionDelete->id])->indexBy('id')->asArray()->all();
            $directionDelete->delete();
            if ($works) {
                $userId = null;
                foreach ($works as $work) {
                    if ($work['id_user'] == $userId) continue;
                    $idUsers[] = $work['id_user'];
                }
                for ($i = 0; $i != count($idUsers); $i++) {
                    $users[$idUsers[$i]] = GalleryUser::find()->where(['id' => $idUsers[$i]])->asArray()->one();
                }

                foreach ($users as $user) {
                    $idUser = $user['id'];
                    $login = $user['login'];
                    foreach ($works as $work) {
                        if ($work['id_user'] == $idUser) {
                            $fileName = $login . '/' . $work['name_file'];
                            unlink(\Yii::getAlias("images/gallery/$fileName"));
                        }
                    }
                }
            }
            return [
                'text' => 'Удален раздел: ' . $directionDelete->name,
                'color' => 'rgba(10, 10, 10, 0.7)'
            ];
        }
        return [
            'text' => '',
            'color' => ''
        ];
    }

    /**
     * Add directions creativity
     * @param EditForm $editForm
     * @return string
     */
    public function addDirection(EditForm $editForm)
    {
        $direction = GalleryDirection::findOne(['name' => $editForm->directionName]);
        if (!$direction) {
            $direction = new GalleryDirection;
            $direction->name = $editForm->directionName;
            $editForm->file = UploadedFile::getInstance($editForm, 'file');
            if ($editForm->file) {
                $result = $this->addDirectionLogo($editForm, $direction);
                if ($result) return $result;
                return [
                    'text' => 'Раздел ' . $direction->name . ' с иконкой добавлены',
                    'color' => 'rgba(10, 10, 10, 0.7)',
                ];
            }
            $direction->save();
            return [
                'text' => 'Раздел ' . $direction->name . ' добавлен.',
                'color' => 'rgba(10, 10, 10, 0.7)'
            ];
        }
        return [
            'text' => 'Раздел - ' . $editForm->directionName . ' уже существует.',
            'color' => 'red'
        ];
    }

    private function addDirectionLogo(EditForm $editForm, GalleryDirection $direction)
    {
        $fileDirectory = Yii::getAlias("images/gallery/direction-icon/");
        $fileNameBase = $editForm->file->baseName . '.' . $editForm->file->extension;
        $fileName = $direction->name . '_' . $editForm->file->baseName . '.' . $editForm->file->extension;
        $file = $fileDirectory . $fileName;
        if ($file) {
            $check = $this->checkOnCloningLogo($editForm, $direction);
            if ($check) return $check;
            $editForm->file->saveAs($file);
            chmod("$file", 0777);
            $direction->base_name_file = $fileNameBase;
            $direction->name_file = $fileName;
            $editForm->logo = $direction->name_file;
            $direction->save();
        }
    }

    private function deleteDirectionLogo(EditForm $editForm)
    {
        $direction = GalleryDirection::findOne(['id' => $editForm->directionId]);
        $file = $direction->name_file;
        if ($file) {
            $fileDirectory = Yii::getAlias("images/gallery/direction-icon/");
            unlink($fileDirectory . $file);
        }
    }

    private function renameDirectionLogo($fileOld, GalleryDirection $direction)
    {
        $fileNew = $direction->name_file;
        $fileDirectory = Yii::getAlias("images/gallery/direction-icon/");
        rename($fileDirectory . $fileOld, $fileDirectory . $fileNew);
    }

    private function checkOnCloningNameDirection(EditForm $editForm, GalleryDirection $direction)
    {
        $directionNameDb = GalleryDirection::findOne(['name' => $editForm->directionName]);
        if ($directionNameDb) {
            $editForm->logo = $direction->name_file;
            return [
                'text' => 'Раздел: ' . $editForm->directionName . ' уже существует',
                'color' => 'red'
            ];
        }
    }

    private function checkOnCloningLogo(EditForm $editForm, $direction)
    {
        $fileNameBase = $editForm->file->baseName . '.' . $editForm->file->extension;
        $fileNameBaseDb = GalleryDirection::findOne(['base_name_file' => $fileNameBase]);
        if ($fileNameBase) {
            if ($fileNameBaseDb) {
                $editForm->logo = $direction->name_file;
                return [
                    'text' => 'Такая иконка уже есть ',
                    'color' => 'red'
                ];
            }
        }
    }

    /**
     * Change the areas of creativity
     * @param EditForm $editForm
     * @return string
     */

    public function changeDirection(EditForm $editForm)
    {
        $direction = GalleryDirection::findOne(['id' => $editForm->directionId]);
        if ($direction) {
            if ($editForm->directionName) {
                $directionChange = $direction->name;
                $editForm->file = UploadedFile::getInstance($editForm, 'file');
                //Если меняется название дирректории, но не меняется файл-иконка
                if ($editForm->directionName != $direction->name && !$editForm->file) {
                    $check = $this->checkOnCloningNameDirection($editForm, $direction);
                    if ($check) return $check;
                    if ($direction->name_file) {
                        $fileOld = $direction->name_file;
                        $direction->name = $editForm->directionName;
                        $direction->name_file = $direction->name . '_' . $direction->base_name_file;
                        $editForm->logo = $direction->name_file;
                        $this->renameDirectionLogo($fileOld, $direction);
                    }
                    $direction->name = $editForm->directionName;
                    $direction->save();
                    return [
                        'text' => 'Раздел: ' . $directionChange . ' изменен на: ' . $direction->name,
                        'color' => 'rgba(10, 10, 10, 0.7)'
                    ];
                }
                //Если меняется только файл-иконка
                if ($editForm->directionName == $direction->name && $editForm->file) {
                    $check = $this->checkOnCloningLogo($editForm, $direction);
                    if ($check) return $check;
                    $this->deleteDirectionLogo($editForm);
                    $this->addDirectionLogo($editForm, $direction);
                    return [
                        'text' => 'Иконка заменена',
                        'color' => 'rgba(10, 10, 10, 0.7)'
                    ];
                }
                //Если меняется название дирректории, и меняется файл-иконка
                if ($editForm->directionName != $direction->name && $editForm->file) {
                    $check = $this->checkOnCloningNameDirection($editForm, $direction);
                    if ($check) return $check;
                    $direction->name = $editForm->directionName;
                    $direction->save();
                    $check = $this->checkOnCloningLogo($editForm, $direction);
                    if ($check) {
                        $fileOld = $direction->name_file;
                        $direction->name_file = $direction->name . '_' . $direction->base_name_file;
                        $editForm->logo = $direction->name_file;
                        $direction->save();
                        $this->renameDirectionLogo($fileOld, $direction);
                        return [
                            'text' => 'Раздел: ' . $directionChange . ' изменен на: ' . $direction->name. "<p class=\"edit-result\" style='background: red'>А иконка такая уже есть</p>",
                            'color' => 'rgba(10, 10, 10, 0.7)',
                        ];
                    }
                    $this->deleteDirectionLogo($editForm);
                    $this->addDirectionLogo($editForm, $direction);
                    return [
                        'text' => 'Раздел: ' . $directionChange . ' изменен на: ' . $direction->name . ' . Иконка заменена',
                        'color' => 'rgba(10, 10, 10, 0.7)'
                    ];
                }
                $editForm->logo = $direction->name_file;
                return [
                    'text' => 'Не внесено никаких изменений',
                    'color' => 'red',
                ];
            }
            $editForm->directionName = $direction->name;
            $editForm->logo = $direction->name_file;
        }
        if (!$direction) return [
            'text' => 'разделы отсутствуют',
            'color' => 'red'
        ];
    }

    /**
     * Deleting a user
     * @param EditForm $editForm
     * @return string
     */

    /**
     * Пример рекурсии(очистка подкаталогов с файлами)
     * function removeDirectory($dir)
     * {
     * if ($objs = glob($dir."/*")) {
     * foreach($objs as $obj) {
     * is_dir($obj) ? removeDirectory($obj) : unlink($obj);
     * }
     * }
     * rmdir($dir);
     * }
     */
    /**
     * Deleting a user
     * @param EditForm $editForm
     * @return string
     */
    public function deleteUser(EditForm $editForm)
    {
        $user = GalleryUser::findOne(['id' => $editForm->userId]);
        if ($user && $user->status != 'author') {
            $user->delete();
            $author = GalleryUser::findOne(['status' => 'author']);
            $editForm->userId = $author->id;
            $directory = \Yii::getAlias("images/gallery/$user->login");
            $files = glob($directory . "/*"); // выборка всех файлов в дирректории
            if ($files) {
                foreach ($files as $file) {
                    unlink($file);
                }
            }
            rmdir($directory);
            return [
                'text' => 'Пользователь ' . $user->login . ' удален из друзей',
                'color' => 'rgba(10, 10, 10, 0.7)'
            ];
        }
        return [
            'text' => '',
            'color' => ''
        ];
    }

    public function changeUser(EditForm $editForm)
    {
        $user = GalleryUser::findOne(['id' => $editForm->userId]);
        if ($user && $user->status != 'author') {
            $editForm->file = UploadedFile::getInstance($editForm, 'file');
            $userNameChange = $user->name;
            $userLoginChange = $user->login;
            $resultName = '';
            $resultLogin = '';
            $resultAvatar = '';
            $resultPassword = '';
            //если изменилось имя друга
            if ($editForm->userName && $editForm->userName != $user->name) {
                $user->name = $editForm->userName;
                $user->save();
                $editForm->avatar = $user->name_file;
                $resultName = '- изменено имя друга на ' . $user->name . "<br />";
            }
            // если изменился логин
            if ($editForm->userLogin && $editForm->userLogin != $user->login) {
                $check = $this->checkOnCloningLoginUser($editForm);
                if ($check) $resultLogin = "<p class='edit-result' style='background: red'>$check</p>";
                if (!$check) {
                    $this->renameUserFiles($editForm, $user);
                    $user->login = $editForm->userLogin;
                    if ($user->name_file) {
                        $user->name_file = $editForm->userLogin . '_' . $user->base_name_file;
                        $editForm->avatar = $user->name_file;
                    }
                    $this->renameWorks($editForm, $user);
                    $user->save();
                    $resultLogin = '- изменен логин на ' . $user->login . "<br />";
                }
            }
            // если изменяется аватарка
            if ($editForm->file) {
                $check = $this->checkOnCloningAvatar($editForm);
                if ($check) $resultAvatar = "<p class='edit-result' style='background: red'>$check</p>";
                if (!$check) {
                    $this->replaceAvatar($editForm, $user);
                    $user->base_name_file = $editForm->file->baseName . '.' . $editForm->file->extension;
                    $user->name_file = $user->login . '_' . $user->base_name_file;
                    $user->save();
                    $editForm->avatar = $user->name_file;
                    $resultAvatar = '- заменена аватарка' . "<br />";
                }
            }
            //если изменился пароль
            if ($editForm->userPassword && md5($editForm->userPassword) != $user->password) {
                $user->password = md5($editForm->userPassword);
                $user->save();
                $resultPassword = '- изменен пароль' . "<br />";
            }
            if ($resultName || $resultLogin || $resultAvatar || $resultPassword) {
                $result = [
                    'text' => 'Результаты редактирования данных друга ' . $userNameChange . ' ( ' . $userLoginChange . ' ) :' . "<br />"
                        . $resultName
                        . $resultPassword
                        . $resultLogin
                        . $resultAvatar,
                    'color' => 'rgba(10, 10, 10, 0.7)'
                ];
            } else $result = false;
            if (!$result && $editForm->userPassword) {
                $result = [
                    'text' => 'Нет ни каких изменений',
                    'color' => 'red'
                ];
            }
            $editForm->userName = $user->name;
            $editForm->userLogin = $user->login;
            $editForm->avatar = $user->name_file;
            $author = GalleryUser::findOne(['status' => 'author']);
            $editForm->userId = $author->id;
            return $result;
        }
    }

    private function replaceAvatar(EditForm $editForm, GalleryUser $user)
    {
        if (!$user->name_file) {
            $this->addAvatar($editForm, $user);
        }
        if ($user->name_file) {
            $this->deleteAvatar($editForm);
            $this->addAvatar($editForm, $user);
        }
    }

    private function renameWorks(EditForm $editForm, GalleryUser $user)
    {
        $works = GalleryWork::find()->where(['id_user' => $user->id])->all();
        if ($works) {
            foreach ($works as $work) {
                $work->name_file = $editForm->userLogin . '_' . $work->base_name_file;
                $work->save();
            }
        }
    }

    private function deleteAvatar(EditForm $editForm)
    {
        $user = GalleryUser::findOne(['id' => $editForm->userId]);
        $file = $user->name_file;
        if ($file) {
            $fileDirectory = Yii::getAlias("images/gallery/" . $user->login . '/');
            unlink($fileDirectory . $file);
        }
    }

    private function renameUserFiles(EditForm $editForm, GalleryUser $user)
    {
        $oldDirectory = Yii::getAlias('images/gallery/' . $user->login);
        $newDirectory = Yii::getAlias('images/gallery/' . $editForm->userLogin);
        rename($oldDirectory, $newDirectory);
        if ($user->name_file) {
            $oldAvatar = $newDirectory . '/' . $user->name_file;
            $newAvatar = $newDirectory . '/' . $editForm->userLogin . '_' . $user->base_name_file;
            rename($oldAvatar, $newAvatar);
        }
        $works = GalleryWork::find()->where(['id_user' => $user->id])->indexBy('id')->asArray()->all();
        if ($works) {
            foreach ($works as $work) {
                $oldFile = $newDirectory . '/' . $work['name_file'];
                $newFile = $newDirectory . '/' . $editForm->userLogin . '_' . $work['base_name_file'];
                rename($oldFile, $newFile);
            }
        }
    }

    private function checkOnCloningLoginUser(EditForm $editForm)
    {
        $user = GalleryUser::findOne(['login' => $editForm->userLogin]);
        if ($user) return '- логин занят, введите другой <br />';
    }

    /**
     * Adding a user
     * @param EditForm $editForm
     * @return string
     */
    public function addUser(EditForm $editForm)
    {

        $author = GalleryUser::findOne(['status' => 'author']);
        $addUser = new GalleryUser;
        $addUser->name = $editForm->userName;
        $addUser->login = $editForm->userLogin;
        $addUser->password = md5($editForm->userPassword);
        $addUser->status = 'friend';
        $addDirectory = \Yii::getAlias("images/gallery/$addUser->login");
        if (is_dir($addDirectory)) {
            if ($addUser->login == $author->login) {
                return [
                    'text' => $author->name . ' это же твой логин',
                    'color' => 'red'
                ];
            }
            return [
                'text' => $addUser->login . ' уже в друзьях',
                'color' => 'red'
            ];
        }
        mkdir("$addDirectory", 0777);
        chmod("$addDirectory", 0777);
        //добавление аватарки -------------------------------
        $editForm->file = UploadedFile::getInstance($editForm, 'file');
        if ($editForm->file) {
            $result = $this->addAvatar($editForm, $addUser);
            if ($result) return $result;
        }
        //-----------------------------------
        $addUser->save();
        return [
            'text' => 'Друг ' . $addUser->name . ' добавился в базу данных под логином - ' . $addUser->login,
            'color' => 'rgba(10, 10, 10, 0.7);'
        ];
    }

    private function addAvatar(EditForm $editForm, GalleryUser $user)
    {
        $check = $this->checkOnCloningAvatar($editForm);
        if ($check) return [
            'text' => 'Внимание ' . $check,
            'color' => 'red'
        ];
        if (!$check) {
            $fileDirectory = Yii::getAlias('images/gallery/' . $user->login . '/');
            $fileBaseName = $editForm->file->baseName . '.' . $editForm->file->extension;
            $fileName = $user->login . '_' . $fileBaseName;
            $file = $fileDirectory . $fileName;
            if ($file) {
                $editForm->file->saveAs($file);
                chmod("$file", 0777);
                $user->base_name_file = $fileBaseName;
                $user->name_file = $fileName;
            }

        }
    }

    private function checkOnCloningAvatar(EditForm $editForm)
    {
        $fileBaseName = $fileBaseName = $editForm->file->baseName . '.' . $editForm->file->extension;
        $file = GalleryUser::findOne(['base_name_file' => $fileBaseName]);
        if ($file) return '- такая аватарка уже есть, выберите другую <br />';
    }
}