<?php

namespace backend\components;

use backend\models\UrlStatus;
use DateTime;
use Yii;
use yii\base\Component;
use yii\httpclient\Client;

class UrlChecker extends Component
{
    /**
     * Метод проверяет каждый url из списка
     *
     * @param array $urls
     * @return array
     */
    public function check(array $urls): array
    {
        $statusCodes = [];
        foreach ($urls as $url) {
            if (filter_var($url, FILTER_VALIDATE_URL)) {
                $hash_string = md5($url);
                $urlStatus = UrlStatus::findOne($hash_string);

                if (isset($urlStatus)) {
                    $minutes = (time() - strtotime($urlStatus->updated_at)) / 60;
                    if ($minutes < 10) {
                        $statusCode = $urlStatus->status_code;
                    } else {
                        $statusCode = $this->getStatusCodeFromUrl($url);
                        $urlStatus->status_code = $statusCode;
                        $urlStatus->updated_at = Yii::$app
                            ->formatter
                            ->asDatetime(
                                new DateTime('now'),
                                'php:Y-m-d H:i:s'
                            );
                    }
                    $urlStatus->query_count += 1;
                } else {
                    $urlStatus = new UrlStatus();
                    $urlStatus->url = $url;
                    $urlStatus->hash_string = $hash_string;

                    $urlStatus->created_at = Yii::$app
                        ->formatter
                        ->asDatetime(
                            new DateTime('now'),
                            'php:Y-m-d H:i:s'
                        );
                    $urlStatus->updated_at = Yii::$app
                        ->formatter
                        ->asDatetime(
                            new DateTime('now'),
                            'php:Y-m-d H:i:s'
                        );

                    $statusCode = $this->getStatusCodeFromUrl($url);
                    $urlStatus->status_code = $statusCode;
                    $urlStatus->query_count = 1;
                }
                $isSaved = $urlStatus->save();

                if (!$isSaved) {
                    $errors = $urlStatus->errors;
                    $statusCodes['errors'] = $errors;
                    return $statusCodes;
                }

                $statusCodes[] = ["url" => $url, "code" => $statusCode];
            } else {
                $statusCodes[] = ["error" => "$url is not valid!"];
            }
        }

        return $statusCodes;
    }

    /**
     * Метод делает GET запрос к url и возвращает код ответа
     *
     * @param string $url
     * @return string
     */
    private function getStatusCodeFromUrl(string $url): string
    {
        $client = new Client();
        $responseFromUrl = $client->createRequest()
            ->setMethod('GET')
            ->setUrl($url)
            ->setOptions([
                'timeout' => 5,
            ])
            ->send();
        $statusCode = $responseFromUrl->getStatusCode();
        return $statusCode;
    }
}
