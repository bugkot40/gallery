<?php


namespace app\classes\gallery;


use app\models\gallery\GalleryDirection;
use app\models\gallery\GalleryUser;
use app\models\gallery\GalleryWork;
use yii\data\Pagination;

class GalleryAuthor extends Gallery
{
    /**
     * Get of the gallery with the best works of the author
     * @param $status
     * @return array|\yii\db\ActiveRecord[]
     */
    private $error = 'Несуществующая страница' . "\n";

    protected function getGallery($status)
    {
        // TODO: Implement getGallery() method.

        $user = $this->getUser($status);
        if (isset($user)) {
            $gallery = GalleryWork::find()->where([
                'id_user' => $user->id,
                'best' => 1,
            ]);
            $pagination = $this->getPagination($gallery);
            $gallery = $gallery->indexBy('id')->asArray()->orderBy(['load_time' => SORT_DESC])
                ->offset($pagination->offset)->limit($pagination->limit)->all();

            foreach ($gallery as $key => $work) {
                $gallery[$key]['load_time'] = date("d.m.Y", ($work['load_time'] + 25200)); //25200-это 7 часов, т.е перевел гринвич на новокузнецкое время
            }
            $gallery = [
                'gallery' => $gallery,
                'pagination' => $pagination
            ];
            return $gallery ? $gallery : null;
        }
        return $this->error;
    }

    /**
     * Get of the galleries selected direction
     * @param $status
     * @param $galleryId
     * @return array|string|\yii\db\ActiveRecord[]
     */
    protected function getWorks($status, $galleryId)
    {
        // TODO: Implement getWorks() method.
        $user = $this->getUser($status);
        if (isset($user)) {
            $direction = GalleryDirection::findOne(['id' => $galleryId]);
            if (isset($direction)) {
                $directions = GalleryDirection::find()->indexBy('id')->asArray()->all();
                $works = GalleryWork::find()->where([
                    'id_user' => $user->id,
                    'id_direction' => $direction->id,
                ]);
                $pagination = $this->getPagination($works);
                $works = $works->indexBy('id')->asArray()->orderBy(['load_time' => SORT_DESC])
                    ->offset($pagination->offset)->limit($pagination->limit)->all();
                foreach ($works as $key => $work) {
                    $works[$key]['load_time'] = date("d.m.Y", ($work['load_time'] + 25200));
                }
                return [
                    'directions' => $directions,
                    'direction' => $direction->name,
                    'works' => $works,
                    'pagination' => $pagination,
                ];
            }
            return $this->error;
        }
        return $this->error;
    }

    /**
     * Identifying the user corresponding to the status
     * @param $status
     * @return static
     */
    private function getUser($status)
    {
        return GalleryUser::findOne(['status' => $status]);
    }


}