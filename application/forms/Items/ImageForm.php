<?php
/**
 * Created by PhpStorm.
 * User: odd
 * Date: 19.08.2017
 * Time: 19:15
 */

namespace application\forms\Items;

use yii\base\Model;
use yii\web\UploadedFile;

class ImageForm extends Model
{
    /**
     * @var UploadedFile[]
     */
    public $files;

    public function rules() : array
    {
        return [
            ['files', 'each', 'rule' => ['image']],
        ];
    }

    /**
     * Loads images before validate
     * @return bool
     */
    public function beforeValidate() : bool
    {
        if (parent::beforeValidate()) {
            $this->files = UploadedFile::getInstances($this, 'files');
            return true;
        }
        return false;
    }
}