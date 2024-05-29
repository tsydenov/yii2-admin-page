<?php

namespace backend\components;

use backend\models\UrlStatus;
use Yii;
use yii\base\Component;
use yii\httpclient\Client;

class UrlChecker extends Component
{
    /**
     * Creates and/or updates rows in url_status with urls from array
     *
     * @param array $urls
     * @return array
     */
    public function check(array $urls): array
    {
        $statusCodes = [];
        foreach ($urls as $url) {
            if (!filter_var($url, FILTER_VALIDATE_URL)) {
                $statusCodes[] = ["error" => "$url is not valid!"];
                continue;
            }

            $hash_string = md5($url);
            if ($urlStatus = UrlStatus::findOne($hash_string)) {
                $minutes = (time() - strtotime($urlStatus->updated_at)) / 60;
                if ($minutes < 10) {
                    $statusCode = $urlStatus->status_code;
                } else {
                    $statusCode = $this->getStatusCodeFromUrl($url);
                    $urlStatus->status_code = $statusCode;
                    $urlStatus->touch('updated_at');
                }
                $urlStatus->query_count++;
            } else {
                $urlStatus = new UrlStatus();
                $urlStatus->url = $url;
                $urlStatus->hash_string = $hash_string;
                $statusCode = $this->getStatusCodeFromUrl($url);
                $urlStatus->status_code = $statusCode;
                $urlStatus->query_count = 1;
            }
            $isSaved = $urlStatus->save();

            if (!$isSaved) {
                $errors = $urlStatus->errors;
            }

            $statusCodes[] = ["url" => $url, "code" => $statusCode];
        }

        if (isset($errors)) {
            $statusCodes['errors'] = $errors;
        }
        return $statusCodes;
    }

    /**
     * Sends GET request to provided url and returns status code
     *
     * @param string $url
     * @return string
     */
    public function getStatusCodeFromUrl(string $url): string
    {
        $client = new Client();
        try {
            $responseFromUrl = $client->createRequest()
                ->setMethod('GET')
                ->setUrl($url)
                ->setOptions([
                    'timeout' => 5,
                ])
                ->send();
            $statusCode = $responseFromUrl->getStatusCode();
        } catch (yii\httpclient\Exception $e) {
            $statusCode = '0';
        }
        return $statusCode;
    }
}
