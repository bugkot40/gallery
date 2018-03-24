<?php


namespace app\models\gallery;

use Yii;
use app\classes\gallery\EditAuthor;
use app\classes\gallery\EditFriend;
use yii\base\Model;

class EditForm extends Model
{
    public $userName;
    public $userLogin;
    public $userPassword;
    public $userStatus;
    public $userId;
    public $directionId;
    public $directionName;
    public $workId;
    public $workName;
    public $description;
    public $best;
    public $file;
    public $logo;
    public $avatar;
    public $identification;
    //-------------------- without validation
    public $sectionEdit = null;
    public $editUser;
    public $action;
    public $title;
    public $list;
    public $result;

    /**
     * Form validate
     * @return array
     */
    private function textFilter($line)
    {
        //обработка, фильтрация введенной строки в поле directionName
        $line = preg_replace("/ +/", " ", $line); //замена нескольких пробелов на один
        $firstLetter = mb_strtoupper(mb_substr($line, 0, 1, 'UTF8'), 'UTF8'); //перевод 1 буквы строки в верхний регистр
        $restLine = mb_strtolower(mb_substr($line, 1), 'UTF8'); //перевод остальной части строки в нижний регистр
        return $firstLetter . $restLine;

    }

    private function userNameFilter($line)
    {
        $line = mb_strtolower($line, 'UTF8');
        $firstLetter = mb_strtoupper(mb_substr($line, 0, 1, 'UTF8'), 'UTF8'); //перевод 1 буквы строки в верхний регистр
        $restLine = mb_strtolower(mb_substr($line, 1), 'UTF8'); //перевод остальной части строки в нижний регистр
        return $firstLetter . $restLine;
    }

    public function rules()
    {
        if ($this->sectionEdit == 'direction') {
            return [
                [['directionName'], 'required', 'message' => 'Введите название раздела'],
                [['directionName'], 'trim'], //обрезает пробелы в начале и конце строки
                ['directionName', 'match', 'pattern' => '/^[a-z0-9а-яё]{2,23}\s*([\s№_]\s*[a-z0-9а-яё]{2,23})?$/ui',
                    'message' => 'Название раздела должно быть строкой (2-23 буквы, цифры), может состоять из двух таких частей, разделенных пробелом, знаком _ , или №',
                ],
                ['directionName', 'filter', 'filter' => function ($value) { //валидатор - функция нормализации (обрабатывает значение поля прямо в браузере - js обработка)
                    return $this->textFilter($value);
                }, 'skipOnArray' => true], //устанавливает невозможность приемки массива
//                [['directionName'], 'string', 'max' => 50, 'message' => 'Строка превысила 50 символов'],
                [['directionId'], 'integer'],
                [['file'], 'image'],
            ];
        }
        if ($this->sectionEdit == 'user') {
            return [
                [['userName', 'userLogin', 'userPassword',], 'required', 'message' => 'Заполните поле'],
                [['userName', 'userLogin', 'userPassword',], 'trim'],
                ['userName', 'match', 'pattern' => '/^[a-zа-яё]{2,23}$/ui', 'message' => 'Имя должно быть строкой (2-23 буквы)'],
                ['userLogin', 'match', 'pattern' => '#^[a-z0-9]{2,23}$#ui', 'message' => 'Логин должен быть строкой (2-23 латинские буквы, цифры)'],
                ['userPassword', 'match', 'pattern' => '#^[a-z0-9]{5,20}$#ui', 'message' => ' Пароль должен быть строкой (5-20 латинских букв, цифр)'],
                ['userName', 'filter', 'filter' => function ($value) { //валидатор - функция нормализации (обрабатывает значение поля прямо в браузере - js обработка)
                    return $this->userNameFilter($value);
                }, 'skipOnArray' => true],
                ['userLogin', 'filter', 'filter' => function ($value) {
                    $value = mb_strtolower($value, 'UTF8');
                    return $value;
                }],
//                [['userName'], 'string', 'max' => 25],
//                [['userLogin'], 'string', 'max' => 25],
//                [['userPassword'], 'string', 'min' => 5, 'max' => 32],
                [['userStatus'], 'string', 'max' => 10],
                [['userId'], 'integer'],
                [['file'], 'image'],
            ];
        }
        if ($this->sectionEdit == 'work') {
            return [
                [['workName',], 'required', 'message' => 'Назовите работу'],
                [['workName', 'description'], 'trim'],
                ['workName', 'match', 'pattern' => '/^[a-z0-9а-яё]{1,22}\s*([\s_№]\s*(([a-z0-9а-яё]{1,22}\s*[\s_№]\s*){1,2})?[a-z0-9а-яё]{1,22})?$/ui',
                    'message' => 'Название работы должно быть строкой (1-22 буквы, цифры), может состоять из 4 таких частей , разделенных пробелом, знаком _, или №'],
                ['description', 'match', 'pattern' => '/^[a-z0-9а-яё]{1,22}\s*([\s,]\s*(([a-z0-9а-яё]{1,22}\s*[\s,]\s*){1,8})?[a-z0-9а-яё]{1,22})?$/ui',
                    'message' => 'Описание к работе должно быть строкой (1-22 буквы, цифры), может состоять из 10 таких частей, разделенных пробелом или запятой'],
                [['workName', 'description'], 'filter', 'filter' => function ($value) {
                    return $this->textFilter($value);
                }],
//                [['workName'], 'string', 'max' => 100, 'message' => 'Превышен размер наименования работы'],
//                [['description'], 'string', 'max' => 256, 'message' => 'Описание превышает допустимый лимит'],
                [['userStatus'], 'string', 'max' => 10],
                [['file'], 'file'],
                [['best'], 'boolean'],
                [['directionId', 'userId', 'workId'], 'integer'],
                [['file'], 'image'],
                // [['file'], 'required', 'message' => 'А что будем добавлять?']
            ];
        } else return [
            [['userLogin', 'userPassword'], 'required', 'message' => 'Заполните поле'],
            [['userLogin'], 'string', 'max' => 25],
            [['userLogin', 'userPassword'], 'trim'],
            [['userPassword'], 'string', 'max' => 50]
        ];
    }

    public function attributeLabels()
    {
        $fileLabel = '';
        switch ($this->sectionEdit) {
            case  'direction' :
                $fileLabel = 'Выбери иконку для раздела';
                break;
            case 'user' :
                $fileLabel = 'Выбери аватарку';
                break;
            case 'work' :
                $fileLabel = 'Выбери файл';
                break;
            default:
                break;
        }

        return [
            'userName' => 'Имя',
            'userLogin' => 'Логин',
            'userPassword' => 'Пароль',
            'userStatus' => 'Статус пользователя',
            'userId' => 'Выбери друга',
            'directionId' => 'Выбери направление творчества',
            'directionName' => 'Наименование творческого направления',
            'workId' => 'Выбери работу',
            'workName' => 'Наименование работы',
            'description' => 'Описание',
            'best' => 'Добавить в избранные',
            'file' => $fileLabel,
        ];
    }

    public function authorization()
    {
        $this->identification = 'check';
        $session = Yii::$app->session->get('user');
        $this->userStatus = $session['userStatus'];
        $this->userId = $session['userId'];
        $this->userLogin = $session['userLogin'];
        $this->userName = $session['userName'];
        $this->avatar = $session['avatar'];
//        $this->identification = [
//            'value' => 'check',
//            'message' => '',
//        ];
        //$session->remove('user');
        Yii::$app->session->close();
    }

    /**
     * GalleryUser identification
     * @param EditForm $editForm
     * @return bool
     */
    public function userIdentification(EditForm $editForm)
    {
        $user = GalleryUser::findOne([
            'login' => $editForm->userLogin,
            'password' => md5(
                $editForm->userPassword),
        ]);

        if ($user) {
            $this->identification = 'check';
            $this->userStatus = $user->status;
            $this->userId = $user->id;
            $this->userLogin = $user->login;
            $this->userName = $user->name;
            $this->avatar = $user->name_file;
            $session = Yii::$app->session;
            $session['user'] = [
                'userStatus' => $this->userStatus,
                'userId' => $this->userId,
                'userLogin' => $this->userLogin,
                'userName' => $this->userName,
                'avatar' => $this->avatar,
            ];
            $session->close();

        } // Скрипт под else нужен только для демонстрации работы сайта в портфолео
        else {
            $this->identification = 'notCheck';
            $this->userStatus = 'author';
            $this->userId = '1';

        }

    }


    /**
     * Generation of the object edit
     * @param EditForm $editForm
     * @return Editauthor|EditFriend
     */
    public function generateObjectEdit(EditForm $editForm)
    {
        switch ($editForm->userStatus) {
            case 'author' :
                $objectEdit = new EditAuthor;
                break;
            case 'friend' :
                $objectEdit = new EditFriend;
                break;
            default :
                break;
        }
        return $objectEdit;
    }

}