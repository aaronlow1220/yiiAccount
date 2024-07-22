<?php

namespace v1\controllers;

use OpenApi\Annotations as OA;
use OpenApi\Generator;
use Yii;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\Response;

/**
 * @OA\OpenApi(
 *     security={{"BearerAuth": {}}},
 *     @OA\Server(
 *         url="http://localhost:8080/",
 *         description="[Dev] APIs server"
 *     ),
 *     @OA\Info(
 *         version="1.0.0",
 *         title="APIv1",
 *         description="APIs document of v1 that based on Swagger OpenAPI",
 *         termsOfService="http://swagger.io/terms/",
 *         @OA\Contact(name="xxxx"),
 *         @OA\License(name="MIT", identifier="MIT")
 *     ),
 * )
 *
 */
class OpenApiSpecController extends Controller
{
    /**
     * display swagger yaml.
     *
     * @param Response $response
     * @param string $format default: yaml
     * @return Response|string
     */
    public function actionIndex(Response $response, ?string $format = null): Response|string
    {
        $modulePath = Yii::getAlias('@v1');
        $modelPath = Yii::getAlias('@app/models');
        $openapi = Generator::scan([$modulePath, $modelPath]);
        if ('json' == $format) {
            $contents = $openapi->toJson();
            $response->format = Response::FORMAT_JSON;
        } elseif ('yaml' == $format) {
            $contents = $openapi->toYaml();
            $response->headers->set('Content-Type', 'application/x-yaml');
        } else {
            $viewFile = Yii::getAlias('@v1/views/view_apidoc.php');
            $yamlUri = strstr(Url::to(['/apidoc', 'format' => 'yaml'], true), '//');
            $contents = $this->view->renderFile($viewFile, ['yamlUri' => $yamlUri]);
            $response->format = Response::FORMAT_HTML;
        }

        $response->content = $contents;

        return $response;
    }
}
