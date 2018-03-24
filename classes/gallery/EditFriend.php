<?php


namespace app\classes\gallery;

use app\models\gallery\EditForm;

class EditFriend extends Edit
{
    /**
     * Getting all lists for the friend
     * @param EditForm $editForm
     * @return array
     */
    public  function getListEdit(EditForm $editForm)
    {
        // TODO: Implement getListEdit() method.
        return [
            'direction' => $this->prepareDirectionList(),
            'work' => $this->prepareWorkList($editForm),
        ];
    }
}