<?php


namespace app\models\gallery;

use app\classes\gallery\Gallery;

use app\classes\gallery\GalleryAuthor;
use app\classes\gallery\GalleryFriend;
use yii\base\Model;


class GalleryForm extends Model
{
    const AUTHOR_STATUS = 'author';
    const FRIEND_STATUS = 'friend';
    private $error = 'Неизвестный запрос' . "\n";

    /**
     * The output of the gallery to a GalleryForm
     * @param $status
     * @return array|string
     */
    public function getGalleryForm($status)
    {
        $galleryObject = $this->statusIdentification($status);
        $gallery = $galleryObject ? $galleryObject->requestGallery($status) : $this->error;
        return $gallery;
    }

    /**
     * The output of the works to a GalleryForm
     * @param $status
     * @param $galleryId
     * @return string
     */
    public function getWorksForm($status, $galleryId)
    {
        $galleryObject = $this->statusIdentification($status);
        $works = $galleryObject ? $galleryObject->requestWorks($status, $galleryId) : $this->error;
        return $works;
    }

    /**
     * The output of the concrete work to a GalleryForm
     * @param $workId
     * @return string|static
     */
    public function getConcreteWorkForm($workId)
    {
        $concreteWork = Gallery::getСoncreteWork($workId);
        return $concreteWork;
    }

    private function statusIdentification($status)
    {
        $gallery = null;
        switch ($status) {
            case self::AUTHOR_STATUS:
                $gallery = new GalleryAuthor();
            break;
            case self::FRIEND_STATUS:
                $gallery = new GalleryFriend();
            break;
            default:
                null;
            break;
        }

        return $gallery;
    }

    public function getAdvertising()
    {
        $advertising = GalleryAdvertising::find()->asArray()->all();
        return $advertising;
    }
}