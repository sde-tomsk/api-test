<?php

namespace common\helpers;

/**
 * Вспомогательные функции для работы с текстом
 *
 * @author Dmitry E. Semenov <sde.tomsk@gmail.com>
 * @copyright Self (c) 2019
 */
class TextHelper
{
    /**
     * Нормализация перевода строк и убирание дублирующих пробелов
     *
     * @param string $str Строка, которую надо нормализовать
     * @return string Нормализованная строка
     */
    public static function normalizeWhitespace($str)
    {
        $str = trim($str);
        $str = str_replace(["\r", "\n"], '', $str);
        $str = preg_replace(array('/\n+/', '/[ \t]+/'), array("\n", ' '), $str);

        return $str;
    }
}