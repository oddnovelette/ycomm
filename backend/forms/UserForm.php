<?php

namespace backend\forms;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use application\models\User;

/**
 * UserForm represents the model behind the search form about `application\models\User`.
 */
class UserForm extends User
{
    public $date_start;
    public $date_end;

    public function rules() : array
    {
        return [
            [['id', 'status', 'created_at'], 'integer'],
            [['username', 'email'], 'safe'],
            [['date_start', 'date_end'], 'date', 'format' => 'php:Y-m-d'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search(array $params) : ActiveDataProvider
    {
        $query = User::find();
        $dataProvider = new ActiveDataProvider(['query' => $query]);
        $this->load($params);

        if (!$this->validate()) {
            $query->where('0=1');
            return $dataProvider;
        }
        $query->andFilterWhere([
            'id' => $this->id,
            'status' => $this->status,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'username', $this->username])
              ->andFilterWhere(['like', 'email', $this->email])
              ->andFilterWhere(['>=', 'created_at', $this->date_start ? strtotime($this->date_start . ' 00:00:00') : null])
              ->andFilterWhere(['<=', 'created_at', $this->date_end ? strtotime($this->date_end . ' 23:59:59') : null]);

        return $dataProvider;
    }
}
