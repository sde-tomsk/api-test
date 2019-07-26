<?php

namespace common\assets;

use yii\web\AssetBundle;

/**
 * Работа с Swagger
 *
 * @author Dmitry E. Semenov <sde.tomsk@gmail.com>
 * @copyright Self (c) 2019
 */
class SwaggerAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@common/assets/src/swagger';

    /**
     * @inheritdoc
     */
    public $js = [
        'swagger.js',
    ];

    /**
     * @inheritdoc
     */
    public $depends = [
        'common\assets\bower\SwaggerUIAsset'
    ];
}