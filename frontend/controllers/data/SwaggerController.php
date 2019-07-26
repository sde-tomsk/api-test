<?php

namespace frontend\controllers\data;

use Yii;
use yii\caching\Cache;
use yii\filters\ContentNegotiator;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\Response;

/**
 * Генерация документации для Swagger
 *
 * @author Dmitry E. Semenov <sde.tomsk@gmail.com>
 * @copyright Self (c) 2019
 */
class SwaggerController extends Controller
{
    /**
     * @var string|array|\Symfony\Component\Finder\Finder The directory(s) or filename(s).
     * If you configured the directory must be full path of the directory.
     */
    public $scanDir;

    /**
     * @var array The options passed to `Swagger`, Please refer the `Swagger\scan` function for more information.
     */
    public $scanOptions = [];

    /**
     * @var Cache|string|null the cache object or the ID of the cache application component that is used to store
     * Cache the \Swagger\Scan
     */
    public $cache = 'swagger';

    /**
     * @var string Cache key
     * [[cache]] must not be null
     */
    public $cacheKey = 'api-swagger-cache';

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'contentNegotiator' => [
                'class'   => ContentNegotiator::class,
                'formats' => [
                    'application/json' => Response::FORMAT_JSON,
                    'application/xml'  => Response::FORMAT_XML,
                ],
            ],
        ]);
    }

    /**
     * Получить данные для документации
     *
     * @return \Swagger\Annotations\Swagger
     * @throws \yii\base\ExitException
     * @throws \yii\base\InvalidConfigException
     */
    public function actionData()
    {
        Yii::$app->getResponse()->format = Response::FORMAT_JSON;
        $headers = Yii::$app->getResponse()->getHeaders();

        $headers->set('Access-Control-Allow-Headers', 'Content-Type, apiKey, Authorization');
        $headers->set('Access-Control-Allow-Methods', 'GET, POST, DELETE, PUT');
        $headers->set('Access-Control-Allow-Origin', '*');
        $headers->set('Allow', 'OPTIONS,HEAD,GET');

        $this->clearCache();

//        if ($this->cache !== null) {
//            $cache = $this->getCache();
//            if (($swagger = $cache->get($this->cacheKey)) === false) {
//                $swagger = $this->getSwagger();
//                $cache->set($this->cacheKey, $swagger);
//            }
//        } else {
//            $swagger = $this->getSwagger();
//        }
        $swagger = $this->getSwagger();

        return $swagger;
    }

    /**
     * Удаление кэша
     * @throws \yii\base\ExitException
     * @throws \yii\base\InvalidConfigException
     */
    protected function clearCache()
    {
        $clearCache = Yii::$app->getRequest()->get('clear-cache', false);
        if ($clearCache !== false) {
            $this->getCache()->delete($this->cacheKey);

            Yii::$app->response->content = 'Succeed clear swagger api cache.';
            Yii::$app->end();
        }
    }

    /**
     * Получение кэша
     * @return Cache
     * @throws \yii\base\InvalidConfigException
     */
    protected function getCache()
    {
        return is_string($this->cache) ? Yii::$app->get($this->cache, false) : $this->cache;
    }

    /**
     * Get swagger object
     * @return \Swagger\Annotations\Swagger
     */
    protected function getSwagger()
    {
        return \Swagger\scan($this->scanDir, $this->scanOptions);
    }

    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        $parent = parent::beforeAction($action);

        $this->scanDir = [
            Yii::getAlias('@api/v1/controllers'),
            Yii::getAlias('@api/v1/swagger'),
        ];

        return $parent;
    }
}