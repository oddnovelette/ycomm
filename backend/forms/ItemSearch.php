<?php
/**
 * Created by PhpStorm.
 * User: odd
 * Date: 25.08.2017
 * Time: 06:19
 */

namespace backend\forms;

use application\models\Items\Category;
use application\models\Items\Item;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

/**
 * Class ItemSearch
 * @package backend\forms
 */
class ItemSearch extends Model
{
    public $id;
    public $code;
    public $name;
    public $category_id;
    public $label_id;

    public function rules() : array
    {
        return [
            [['id', 'category_id', 'label_id'], 'integer'],
            [['code', 'name'], 'safe'],
        ];
    }

    /**
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search(array $params) : ActiveDataProvider
    {
        $query = Item::find()->with('mainImage', 'category');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]]
        ]);

        $this->load($params);
        if (!$this->validate()) {
            $query->where('0=1');
            return $dataProvider;
        }
        $query->andFilterWhere([
            'id' => $this->id,
            'category_id' => $this->category_id,
            'label_id' => $this->label_id,
        ]);
        $query
            ->andFilterWhere(['like', 'code', $this->code])
            ->andFilterWhere(['like', 'name', $this->name]);
        return $dataProvider;
    }

    public function categoriesList() : array
    {
        return ArrayHelper::map(
            Category::find()
                ->andWhere(['>', 'depth', 0])
                ->orderBy('lft')
                ->asArray()
                ->all(), 'id',
            function (array $category) {
                return ($category['depth'] > 1
                        ? str_repeat('-- ', $category['depth'] - 1)
                        . ' ' : '') . $category['name'];
        });
    }
}