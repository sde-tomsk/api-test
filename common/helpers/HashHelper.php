<?php

namespace common\helpers;

/**
 * Вспомогательные функции
 *
 * @author Dmitry E. Semenov <sde.tomsk@gmail.com>
 * @copyright Self (c) 2019
 */
class HashHelper
{
    /**
     * Генерация уникального ключа
     *
     * @param string $string Исходная строка
     * @param string $salt Используемая соль
     * @return mixed
     */
    public static function generateHash($string, $salt)
    {
        return md5($string . $salt . 'Где то надо хранить эту соль');
    }
}