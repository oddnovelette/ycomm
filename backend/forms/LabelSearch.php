<?php
/**
 * Created by PhpStorm.
 * User: odd
 * Date: 22.08.2017
 * Time: 01:00
 */

namespace backend\forms;

use application\models\Items\Label;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * Class LabelSearch
 * @package forms
 */
class LabelSearch extends Model
{
    public $id;
    public $name;
    public $slug;

    public function rules() : array
    {
        return [
            [['id'], 'integer'],
            [['name', 'slug'], 'safe'],
        ];
    }

    /**
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search(array $params) : ActiveDataProvider
    {
        $query = Label::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['name' => SORT_ASC]
            ]
        ]);

        $this->load($params);
        if (!$this->validate()) {
            $query->where('0=1');
            return $dataProvider;
        }
        $query->andFilterWhere(['id' => $this->id]);
        $query
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'slug', $this->slug]);

        return $dataProvider;
    }
}