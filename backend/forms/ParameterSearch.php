<?php
/**
 * Created by PhpStorm.
 * User: odd
 * Date: 23.08.2017
 * Time: 17:47
 */

namespace backend\forms;

use application\models\Items\Parameter;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * Class ParameterSearch
 * @package backend\forms
 */
class ParameterSearch extends Model
{
    public $id;
    public $name;
    public $type;
    public $required;

    public function rules() : array
    {
        return [
            [['id', 'type', 'required'], 'integer'],
            [['name'], 'safe'],
        ];
    }

    /**
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search(array $params) : ActiveDataProvider
    {
        $query = Parameter::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['sort' => SORT_ASC]]
        ]);

        $this->load($params);
        if (!$this->validate()) {
            $query->where('0=1');
            return $dataProvider;
        }
        $query->andFilterWhere([
            'id' => $this->id,
            'type' => $this->type,
            'required' => $this->required,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name]);
        return $dataProvider;
    }

    public function typesList() : array
    {
        return ParamHelper::typeList();
    }

    public function requiredList() : array
    {
        return [
            1 => \Yii::$app->formatter->asBoolean(true),
            0 => \Yii::$app->formatter->asBoolean(false),
        ];
    }
}