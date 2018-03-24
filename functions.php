<?php
//функция для вывода массивов, объектов в читабельном виде
function debug($arr)
{
    echo "<br />"."<br />"."<br />" ;
    echo '<pre>' . print_r($arr, true) . '</pre>';
}

//функция для кодирования данных
function md($arg)
{
    echo '<pre>' . print_r(md5($arg), true) . '</pre>';
}

