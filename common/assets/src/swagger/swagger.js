/**
 * Инициализация работы Swagger-UI
 *
 * @author Dmitry E. Semenov <sde.tomsk@gmail.com>
 * @copyright Self (c) 2019
 */
jQuery(document).ready(function ($) {

    var $swagger = $('#swagger-ui');
    var key = $swagger.data('apiKey');
    var url = $swagger.data('source');

    // Build a system
    const ui = SwaggerUIBundle({
        url: url,
        dom_id: '#swagger-ui',
        deepLinking: true,
        configs: {
            preFetch: function (req) {
                if (key) {
                    req.headers["apiKey"] = key;
                }
                return req;
            }
        },
        presets: [
            SwaggerUIBundle.presets.apis,
            SwaggerUIStandalonePreset
        ],
        plugins: [
            SwaggerUIBundle.plugins.DownloadUrl
        ],
        onComplete: function () {
            console.log('SwaggerUI done');
        }
    });

    window.ui = ui;
});

