<?php
/**
 * @author Dmitry E. Semenov <sde.tomsk@gmail.com>
 * @copyright Self (c) 2019
 *
 * @SWG\Swagger(
 *     schemes={"http"},
 *     host="api-test:81",
 *     basePath="/v1",
 *     @SWG\Info(
 *         version="1.0",
 *         title="Описание API методов для тестовго задания",
 *         description="Версия: 1.0",
 *         @SWG\Contact(
 *             name = "Тестовое задание",
 *             email = "sde.tomsk@gmail.com",
 *             url = "http://api-test:81"
 *         )
 *     ),
 *  )
 * @SWG\Definition(
 *      definition="UserCredentials",
 *      required={"email", "password"},
 *      @SWG\Property(
 *          property="email",
 *          description="Email пользователя",
 *          type="string",
 *      ),
 *      @SWG\Property(
 *          property="password",
 *          description="Уникальный пароль пользователя",
 *          type="string"
 *      )
 *  )
 * @SWG\Definition(
 *      definition="BookCredentials",
 *      required={"name", "code"},
 *      @SWG\Property(
 *          property="name",
 *          description="Название книги",
 *          type="string",
 *      ),
 *      @SWG\Property(
 *          property="code",
 *          description="Уникальный код книги",
 *          type="string"
 *      )
 *  )
 */
