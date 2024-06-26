<?php

namespace backend\models;

use yii\data\ActiveDataProvider;
use yii\db\Expression;

class UrlStatusSearch extends UrlStatus
{
    public function rules(): array
    {
        return [
            [['url', 'status_code'], 'safe']
        ];
    }

    public function search($params): ActiveDataProvider
    {
        $query = UrlStatus::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        // split status codes separated by spaces into array
        if ($this->status_code !== '') {
            $status_codes = explode(' ', $this->status_code);
            $query->andFilterWhere(['in', 'status_code', $status_codes]);
        }

        $query->andFilterWhere(['like', 'url', $this->url]);

        if (isset($params['last24Hours'])) {
            $query->andWhere(['>=', 'updated_at', new Expression('NOW() - INTERVAL 1 DAY')]);
        }
        if (isset($params['statusCodeIsNot200'])) {
            $query->andWhere(['!=', 'status_code', 200]);
        }

        return $dataProvider;
    }
}
