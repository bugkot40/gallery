<?php


namespace app\classes\gallery;

use app\models\gallery\GalleryDirection;
use app\models\gallery\GalleryUser;
use app\models\gallery\GalleryWork;
use app\models\gallery\Work;
use yii\data\Pagination;

abstract class Gallery
{
    static private $error = 'Неизвестная страница' . "\n";

    /**
     * Request gallery depending on the status of autor
     * @param $status
     * @return array|string
     */

    protected function getPagination($gallery)
    {
        $pagination = new Pagination([
            'defaultPageSize' => 8,
            'totalCount' => $gallery->count(),
        ]);
        return $pagination;
    }
    public function requestGallery($status)
    {
        $directions = GalleryDirection::find()->indexBy('id')->asArray()->all();
        $users = GalleryUser::find()->indexBy('id')->where(['status' => $status])->asArray()->all();
        $gallery = [
            'users' => $users,
            'directions' => $directions,
            'gallery' => $this->getGallery($status),
        ];
        return $gallery;
    }

    /**
     * Request section gallery
     * @param $status
     * @param $galleryId
     * @return mixed
     */
    public function requestWorks($status, $galleryId)
    {
        return $this->getWorks($status, $galleryId);
    }

    /**
     * get of a concrete work
     * @param $workId
     * @return string|static
     */
    public static function getСoncreteWork($workId)
    {
        $work = GalleryWork::findOne(['id' => $workId]);
        $work->load_time = date("d.m.Y", ($work->load_time + 25200));
        $user = GalleryUser::find()->where(['id' => $work->id_user])->one();
        $direction = GalleryDirection::find()->where(['id' => $work->id_direction])->one();
        $galleryId = $user->status == 'autor' ? $direction->id : $user->id;
        $nameFileUser = $user->status == 'autor' ? null : $user->name_file;
        return $work ? [
            'directionName' => $direction->name,
            'galleryId' => $galleryId,
            'status' => $user->status,
            'login' => $user->login,
            'name' => $user->name,
            'nameFileUser' => $nameFileUser,
            'nameFileDirection' => $direction->name_file,
            'work' => $work,
        ] : self::$error;
    }


    protected abstract function getGallery($status);

    protected abstract function getWorks($status, $sectionId);
}