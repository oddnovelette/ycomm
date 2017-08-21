<?php
/**
 * Created by PhpStorm.
 * User: odd
 * Date: 19.08.2017
 * Time: 00:23
 */

namespace application\models\Items;

/**
 * Class Meta
 * @package application\models\Items
 */
class Meta
{
    public $title;
    public $description;
    public $keywords;

    /**
     * Meta constructor.
     * @param string $title
     * @param string $description
     * @param string $keywords
     */
    public function __construct(string $title, string $description, string $keywords)
    {
        $this->title = $title;
        $this->description = $description;
        $this->keywords = $keywords;
    }
}