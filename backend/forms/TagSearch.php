<?php
/**
 * Created by PhpStorm.
 * User: odd
 * Date: 23.08.2017
 * Time: 04:26
 */

namespace backend\forms;

use application\models\Items\Tag;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * Class TagSearch
 * @package backend\forms
 */
class TagSearch extends Model
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
        $query = Tag::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['name' => SORT_ASC]]
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