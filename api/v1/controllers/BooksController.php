<?php

namespace api\v1\controllers;

use api\common\controllers\BaseUserController;
use common\models\Book;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;

/**
 * REST API для работа с книгами
 *
 * @author Dmitry E. Semenov <sde.tomsk@gmail.com>
 * @copyright Self (c) 2019
 */
class BooksController extends BaseUserController
{
    /**
     * @SWG\Get(path="/books",
     *      tags = {"book"},
     *      summary = "получение списка книг",
     *      description = "Возвращает список книг с постраничной навигацие",
     *      produces = {"application/json", "application/xml"},
     *      @SWG\Parameter(
     *          name = "Authorization",
     *          in = "header",
     *          description = "JWT в формате 'Bearer token'",
     *          required = true,
     *          type = "string"
     *      ),
     *      @SWG\Response(
     *          response = 200,
     *          description = "Success"
     *      )
     *  )
     */
    public function actionIndex()
    {
        $q = Book::find();

        $result = new ActiveDataProvider([
            'query'      => $q,
            'pagination' => [
                'pageSize'     => Yii::$app->request->get('limit', 25),
                'validatePage' => false,
            ],
        ]);

        /** @var Book $book */
        foreach ($result->models as $book) {

            // @TODO .. какие действия по обработке вывода
            $items[] = [
                'id'   => (int)$book->id,
                'name' => $book->name,
                'code' => $book->code,
            ];
        }

        return [
            'message' => 'OK',
            'body'    => [
                'items'          => $items,
                'total'          => $result->pagination->totalCount,
                'items_per_page' => $result->pagination->pageSize,
                'total_pages'    => $result->pagination->pageCount
            ]
        ];
    }

    /**
     * @SWG\Get(path="/books/{id}",
     *      tags = {"book"},
     *      summary = "Получение данных книги по id",
     *      description = "Возвращает данные о книге по указанному идентификатору",
     *      produces = {"application/json", "application/xml"},
     *      @SWG\Parameter(
     *         in = "path",
     *         name = "id",
     *         description = "Идентификатор книги",
     *         required = true,
     *         type = "number"
     *      ),
     *      @SWG\Parameter(
     *          name = "Authorization",
     *          in = "header",
     *          description = "JWT в формате 'Bearer token'",
     *          required = true,
     *          type = "string"
     *      ),
     *      @SWG\Response(
     *          response = 200,
     *          description = "Success"
     *      ),
     *      @SWG\Response(
     *          response = 404,
     *          description = "Book not found"
     *      )
     *  )
     */
    public function actionView($id)
    {
        $b = Book::findOne($id);
        if (!$b) {
            throw new NotFoundHttpException(Yii::t('app', 'Книга не найдена'));
        }

        $body = [
            'id'   => (int)$b->id,
            'name' => $b->name,
            'code' => $b->code,
        ];

        return [
            'message' => 'OK',
            'body'    => $body
        ];
    }

    /**
     * @SWG\Post(path="/books",
     *      tags = {"book"},
     *      summary = "добавление книги",
     *      description = "Создание кники в базе",
     *      produces = {"application/json", "application/xml"},
     *      @SWG\Parameter(
     *          name = "Authorization",
     *          in = "header",
     *          description = "JWT в формате 'Bearer token'",
     *          required = true,
     *          type = "string"
     *      ),
     *      @SWG\Parameter(
     *          in = "body",
     *          name = "book",
     *          description = "Данные о книге",
     *          required = true,
     *          @SWG\Schema(ref="#/definitions/BookCredentials")
     *      ),
     *      @SWG\Response(
     *          response = 201,
     *          description = "Created"
     *      )
     *  )
     */
    public function actionCreate()
    {
        $post = Yii::$app->request->getBodyParams();
        $b = new Book($post);
        if ($b->save()) {
            Yii::$app->response->setStatusCode(201);

            return [
                'message' => 'OK'
            ];
        } else {
            return [
                'message' => 'ERROR',
                'errors'  => $b->errors
            ];
        }
    }

    /**
     * @SWG\Put(path="/books/{id}",
     *      tags = {"book"},
     *      summary = "обновление данных книги ",
     *      description = "Обновляет информацию о кники в базе по указанному идентификатору",
     *      produces = {"application/json", "application/xml"},
     *      @SWG\Parameter(
     *          name = "Authorization",
     *          in = "header",
     *          description = "JWT в формате 'Bearer token'",
     *          required = true,
     *          type = "string"
     *      ),
     *      @SWG\Parameter(
     *          name = "id",
     *          in = "path",
     *          description = "Идентификатор книги",
     *          required = true,
     *          type = "integer"
     *      ),
     *      @SWG\Parameter(
     *          in = "body",
     *          name = "book",
     *          description = "Данные о книге",
     *          required = true,
     *          @SWG\Schema(ref="#/definitions/BookCredentials")
     *      ),
     *      @SWG\Response(
     *          response = 200,
     *          description = "Ok"
     *      ),
     *      @SWG\Response(
     *          response = 404,
     *          description = "Not found"
     *     )
     *  )
     */
    public function actionUpdate($id)
    {
        $b = Book::findOne($id);
        if (!$b) {
            throw new NotFoundHttpException(Yii::t('app', 'Книга не найдена'));
        }

        $post = Yii::$app->request->getBodyParams();

        $b->setAttributes($post);

        if ($b->save()) {
            return [
                'message' => 'OK'
            ];
        } else {
            return [
                'message' => 'ERROR',
                'errors'  => $b->errors
            ];
        }
    }

    /**
     * @SWG\Delete(path="/books/{id}",
     *      tags = {"book"},
     *      summary = "Удаление книги",
     *      description = "Производит удаление книги из базы данных по указанному идентификатору",
     *      produces = {"application/json", "application/xml"},
     *      @SWG\Parameter(
     *          name = "Authorization",
     *          in = "header",
     *          description = "JWT в формате 'Bearer token'",
     *          required = true,
     *          type = "string"
     *      ),
     *      @SWG\Parameter(
     *          name = "id",
     *          in = "path",
     *          description = "Идентификатор книги",
     *          required = true,
     *          type = "integer"
     *      ),
     *      @SWG\Response(
     *          response = 200,
     *          description = "Ok"
     *      ),
     *      @SWG\Response(
     *          response = 404,
     *          description = "Not found"
     *     )
     *  )
     */
    public function actionDelete($id)
    {
        $b = Book::findOne($id);
        if (!$b) {
            throw new NotFoundHttpException(Yii::t('app', 'Книга не найдена'));
        }

        $b->delete();

        return [
            'message' => 'OK'
        ];
    }
}