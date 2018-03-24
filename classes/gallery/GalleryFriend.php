<?php


namespace app\classes\gallery;

use app\models\gallery\GalleryUser;
use app\models\gallery\GalleryWork;

class GalleryFriend extends Gallery
{
    /**
     * Get of the gallery with the best works of the friends
     * @param $status
     * @return mixed
     */
    private $error = 'Не существующая страница' . "\n";

    protected function getGallery($status)
    {

        // $model = Pages::find()->with('contents')->where(['id'=>$id])->one();


        // TODO: Implement getGallery() method.


        $user = GalleryUser::find()->where(['status' => $status])->one();

        if (isset($user)) {
            $gallery = GalleryWork::find()
                ->leftJoin('gallery-user', '`gallery-user`.`id` = `gallery-work`.`id_user`')
                ->where([
                    'gallery-user.status' => $status,
                    'gallery-work.best' => 1,
                ]);
            $pagination = $this->getPagination($gallery);
            $gallery = $gallery->indexBy('id')->asArray()->orderBy(['load_time' => SORT_DESC ])
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
        $gallery =[
            'gallery' => null
        ];
        return $gallery;
    }

    /**
     * Get of the galleries selected friend
     * @param $status
     * @param $galleryId
     * @return array|string|\yii\db\ActiveRecord[]
     */
    protected
    function getWorks($status, $galleryId)
    {
        // TODO: Implement getWorks() method.
        $user = GalleryUser::findOne([
            'status' => $status,
            'id' => $galleryId,
        ]);
        if ($user) {
            $users = GalleryUser::find()->indexBy('id')->asArray()->where(['status' => $status])->all();
            $works = GalleryWork::find()->where([
                'id_user' => $user->id,
            ]);
            $pagination = $this->getPagination($works);
            $works = $works->indexBy('id')->asArray()->orderBy(['load_time' => SORT_DESC])
                ->offset($pagination->offset)->limit($pagination->limit)->all();
            foreach ($works as $key => $work) {
                $works[$key]['load_time'] = date("d.m.Y", ($work['load_time'] + 25200));
            }
            return [
                'users' => $users,
                'login' => $user->login,
                'nameUser' => $user->name,
                'works' => $works,
                'pagination' => $pagination,
            ];
        }
        return $this->error;
    }
}