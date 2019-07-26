<?php

namespace common\assets\bower;

use yii\web\AssetBundle;
use yii\web\View;

/**
 * Работа с Swagger-ui
 *
 * ```
 * Установка:
 * добавить в composer.json
 *
 *        "bower-asset/swagger-ui": "2.2.10"
 *
 * ```
 *
 * @author Dmitry E. Semenov <sde.tomsk@gmail.com>
 * @copyright Self (c) 2019
 */
class SwaggerUIAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@bower/swagger-ui/dist';

    /**
     * @inheritdoc
     */
    public $js = [
        'swagger-ui-bundle.js',
        'swagger-ui-standalone-preset.js',
    ];

    /**
     * @inheritdoc
     */

    public $jsOptions = [
        'position' => View::POS_HEAD,
    ];

    /**
     * @inheritdoc
     */
    public $css = [
        'swagger-ui.css',
    ];

    /**
     * @inheritdoc
     */
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}