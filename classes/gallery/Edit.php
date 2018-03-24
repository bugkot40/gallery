<?php


namespace app\classes\gallery;

use app\models\gallery\GalleryDirection;
use app\models\gallery\EditForm;
use app\models\gallery\GalleryUser;
use app\models\gallery\GalleryWork;

use Yii;
use yii\web\UploadedFile;

abstract class Edit
{
    /**
     * Preparation of a list with directions
     * @return array|\yii\db\ActiveRecord[]
     */
    protected function prepareDirectionList()
    {
        $directions = GalleryDirection::find()->asArray()->indexBy('id')->orderBy(['name' => SORT_ASC])->all();
        if ($directions) {
            foreach ($directions as $direction) {
                $directionList[$direction['id']] = $direction['name'];
            }
            return $directionList;
        }
        return null;
    }

    /**
     * Preparation of a list with users
     * @return mixed
     */
    protected function prepareUserList()
    {
        $users = GalleryUser::find()->asArray()->indexBy('id')->orderBy(['name' => SORT_ASC])->all();
        if ($users) {
            foreach ($users as $user) {
                if (count($users) == 1 && $user['status'] == 'author') {
                    $userList = null;
                    break;
                }
                if ($user['status'] == 'author') {
                    continue;
                } elseif ($user['status'] == 'friend') {
                    $userList[$user['id']] = $user['name'] . ' , логин - ' . $user['login'];
                }
            }
            return $userList;
        }
        return null;
    }

    /**
     * Preparation of a list with works
     * @param EditForm $editForm
     * @return mixed
     */
    protected function prepareWorkList(EditForm $editForm)
    {
        if ($editForm->directionId && $editForm->userId) {
            $works = GalleryWork::find()->where([
                'id_user' => $editForm->userId,
                'id_direction' => $editForm->directionId,
            ])->asArray()->indexBy('id')->orderBy(['load_time' => SORT_ASC])->all();
            if ($works) {
                foreach ($works as $work) {
                    if ($work['best'] == 1) $best = ' "лучшая работа"';
                    else $best = '';
                    $workList[$work['id']] = 'работа: ' . $work['name_work'] . ', файл:' . $work['name_file'] . $best;
                }
                return $workList;
            }
            return null;
        }
        return null;
    }

    /**
     * Getting all lists for the abstract user
     * @param EditForm $editForm
     * @return mixed
     */

    public abstract function getListEdit(EditForm $editForm);

    /**
     * @param EditForm $editForm
     * @param GalleryUser $user
     * @return string
     */
    public function addWork(EditForm $editForm, GalleryUser $user)
    {

        // изменить размер загружаемого на сервер файла
        /*
$ratio = $size->getWidth()/$size->getHeight();

$width = 200;
$height = round($width/$ratio);

$box = new Box($width, $height);
$img->resize($box)->save($filePath.'/thumb/' . $fileName);

          */

        $editForm->file = UploadedFile::getInstance($editForm, 'file');
        if ($editForm->file) {
            $fileDirectory = Yii::getAlias("images/gallery/$user->login/");
            $fileName = $user->login . '_' . $editForm->file->baseName . '.' . $editForm->file->extension;
            $file = $fileDirectory . $fileName;
            $fileNameBase = GalleryWork::findOne(['name_file' => $fileName]);
            if (!$fileNameBase) {
                $editForm->file->saveAs($file);
                $size = getimagesize($file);
                chmod("$file", 0777);
                if ($editForm->file) {
                    $work = new GalleryWork;
                    if ($user->status == 'friend') {
                        if ($editForm->best == 1) {
                            $best = GalleryWork::findOne([
                                'id_user' => $user->id,
                                'best' => 1
                            ]);
                            if ($best) return 'blockBest';
                            $work->best = $editForm->best;
                        }
                    } else $work->best = $editForm->best;
                    $work->id_user = $editForm->userId;
                    $work->id_direction = $editForm->directionId;
                    $work->base_name_file = $editForm->file->baseName . '.' . $editForm->file->extension;
                    $work->name_file = $fileName;
                    $work->name_work = $editForm->workName;
                    $work->description = $editForm->description;
                    $work->load_time = time();
                    $work->save();
                    $editForm->file->name = $work->name_file;
                    $editForm->userName = $user->name;
                    $editForm->userLogin = $user->login;
                    return "<p class='edit-result'>" . 'Файл загружен, работа ' . $work->name_work . ' отправлена в базу данных и в папку пользователя - ' . $editForm->userName . ' (' . $editForm->userLogin . ')' . "</p>";
                }
                return "<p class='edit-result' style='background: red'>Не удалось загрузить файл </p>";
            }
            return 'cloning';
        }
        return 'Файл не выбран!';
    }

    /**
     * To remove the work
     * @param EditForm $editForm
     * @return string
     */
    public function deleteWork(EditForm $editForm)
    {
        $work = GalleryWork::findOne(['id' => $editForm->workId]);
        if ($work) {
            $user = GalleryUser::findOne(['id' => $work->id_user]);
            $editForm->workName = $work->name_work;
            $editForm->file = $work->name_file;
            $fileDirectory = Yii::getAlias("images/gallery/$user->login/");
            unlink($fileDirectory . $editForm->file);
            $work->delete();
            return "<p class='edit-result'>" . 'Работа: ' . $editForm->workName . ' удалена' . "</p>";
        }
        return '';
    }

    public function changeWork(EditForm $editForm)
    {
        $work = GalleryWork::findOne(['id' => $editForm->workId]);
        if ($work) {
            $user = GalleryUser::findOne(['id' => $work->id_user]);
            $workChangeName = $work->name_work;
            $workChangeDescription = $work->description;
            $workChangeBest = $work->best;
            if ($editForm->workName) {
                $change_1 = '';
                $change_2 = '';
                $change_3 = '';
                if ($editForm->workName != $workChangeName) {
                    $work->name_work = $editForm->workName;
                    $change_1 = "<p class='edit-result'>" . 'Работа ' . $workChangeName . ' сохранена как " ' . $work->name_work . ' "' . "</p><br />";
                }
                if ($editForm->description != $workChangeDescription) {
                    $work->description = $editForm->description;
                    $change_2 = "<p class='edit-result'>" . 'Описание к работе изменилось' . "</p><br />";
                }
                if ($editForm->best != $workChangeBest) {
                    if ($workChangeBest == 1) $changeBest = 'лучшая работа';
                    if ($workChangeBest == 0) $changeBest = 'обычная работа';
                    if ($user->status == 'friend') {
                        if ($editForm->best == 1) {
                            $best = GalleryWork::findOne([
                                'id_user' => $user->id,
                                'best' => 1
                            ]);
                            if ($best && $editForm->workId != $best->id)
                                return "<p class='edit-result' style='background: red'> К сожалению лучшая работа для демонстрации в галлерее друзей может быть только одна</p>";
                            $work->best = $editForm->best;
                        }
                    }
                    switch ($editForm->best) {
                        case 1 :
                            $work->best = 1;
                            $best = 'лучшая работа';
                            break;
                        case 0 :
                            $work->best = 0;
                            $best = 'обычная работа';
                            break;
                        default:
                            break;
                    }
                    $change_3 = "<p class='edit-result'>" . 'Статус работы поменялся c категории ' . $changeBest . ' на категорию ' . $best . "</p>";
                }
                $work->save();
                if ($editForm->workName == $workChangeName
                    && $editForm->description == $workChangeDescription
                    && $editForm->best == $workChangeBest
                ) {
                    return "<p class='edit-result' style='background: red'>Никаких изменений не выполнено</p>";
                }
                return $change_1 . $change_2 . $change_3;
            }
            if (!$editForm->workName) {
                $editForm->workName = $workChangeName;
                $editForm->description = $workChangeDescription;
                $editForm->best = $workChangeBest;
            }
        }
    }

}