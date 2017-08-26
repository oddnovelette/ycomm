<?php
/**
 * Created by PhpStorm.
 * User: odd
 * Date: 20.08.2017
 * Time: 16:39
 */

namespace application\models\Items;

use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\Json;
use yii\web\UploadedFile;
/**
 * @property integer $id
 * @property integer $created_at
 * @property string $code
 * @property string $name
 * @property string $text
 * @property integer $category_id
 * @property integer $label_id
 * @property integer $price_old
 * @property integer $price_new
 * @property integer $rating
 *
 * @property Meta $meta
 * @property Label $label
 * @property Category $category
 * @property CategoryAttachment[] $categoryAttachments
 * @property TagAttachment[] $tagAttachments
 * @property RelatedAttachment[] $relatedAttachments
 * @property Variation[] $variations
 * @property ParameterValue[] $values
 * @property Image[] $images
 * @property Overwiev[] $overviews
 *
 * Class Item
 * @package application\models\Items
 */
class Item extends ActiveRecord
{
    const STATUS_DRAFT = 0;
    const STATUS_ACTIVE = 1;

    public $meta;

    /**
     * @param int $labelId
     * @param int $categoryId
     * @param string $code
     * @param string $name
     * @param string $text
     * @param Meta $meta
     * @return Item
     */
    public static function create(int $labelId, int $categoryId, string $code, string $name, string $text, Meta $meta) : self
    {
        $item = new self();
        $item->label_id = $labelId;
        $item->category_id = $categoryId;
        $item->code = $code;
        $item->name = $name;
        $item->text = $text;
        $item->meta = $meta;
        $item->created_at = time();
        return $item;
    }

    /**
     * @param string $new
     * @param string $old
     * @return void
     */
    public function setPrice(string $new, string $old) : void
    {
        $this->price_new = $new;
        $this->price_old = $old;
    }

    /**
     * @param int $labelId
     * @param string $code
     * @param string $name
     * @param string $text
     * @param Meta $meta
     */
    public function edit(int $labelId, string $code, string $name, string $text, Meta $meta) : void
    {
        $this->label_id = $labelId;
        $this->code = $code;
        $this->name = $name;
        $this->text = $text;
        $this->meta = $meta;
    }

    /**
     * @param int $categoryId
     * @return void
     */
    public function changeMainCategory(int $categoryId) : void
    {
        $this->category_id = $categoryId;
    }

    public function setParameterValue($id, $value) : void
    {
        $values = $this->values;
        foreach ($values as $val) {
            if ($val->isForParameter($id)) {
                $val->change($value);
                $this->values = $values;
                return;
            }
        }
        $values[] = ParameterValue::create($id, $value);
        $this->values = $values;
    }

    public function getParameterValue($id) : ParameterValue
    {
        $parameterValues = $this->values;
        foreach ($parameterValues as $value) {
            if ($value->isForParameter($id)) {
                return $value;
            }
        }
        return ParameterValue::create($id, null);
    }

    // Categories handlers

    public function attachCategory($id) : void
    {
        $attachments = $this->categoryAttachments;
        foreach ($attachments as $attachment) {
            if ($attachment->isForCategory($id)) {
                return;
            }
        }
        $attachments[] = CategoryAttachment::create($id);
        $this->categoryAttachments = $attachments;
    }

    public function detachCategory($id) : void
    {
        $attachments = $this->categoryAttachments;
        foreach ($attachments as $i => $attachment) {
            if ($attachment->isForCategory($id)) {
                unset($attachments[$i]);
                $this->categoryAttachments = $attachments;
                return;
            }
        }
        throw new \DomainException('Attachment not found.');
    }

    public function detachItemCategories() : void
    {
        $this->categoryAttachments = null;
    }

    // Tag handlers

    public function attachTag($id) : void
    {
        $attachments = $this->tagAttachments;
        foreach ($attachments as $attachment) {
            if ($attachment->isForTag($id)) {
                return;
            }
        }
        $attachments[] = TagAttachment::create($id);
        $this->tagAttachments = $attachments;
    }

    public function detachTag($id) : void
    {
        $attachments = $this->tagAttachments;
        foreach ($attachments as $i => $attachment) {
            if ($attachment->isForTag($id)) {
                unset($attachments[$i]);
                $this->tagAttachments = $attachment;
                return;
            }
        }
        throw new \DomainException('Attachment not found.');
    }

    public function detachItemTags() : void
    {
        $this->tagAttachments = null;
    }

    // Image handlers

    public function uploadImage(UploadedFile $file) : void
    {
        $images = $this->images;
        $images[] = Image::create($file);
        $this->updateImages($images);
    }

    public function deleteImage($id) : void
    {
        $images = $this->images;
        foreach ($images as $i => $image) {
            if ($image->isIdEqualTo($id)) {
                unset($images[$i]);
                $this->updateImages($images);
                return;
            }
        }
        throw new \DomainException('Image not found.');
    }

    public function deleteItemImages() : void
    {
        $this->updateImages([]);
    }

    public function moveImageUp($id) : void
    {
        $images = $this->images;
        foreach ($images as $i => $image) {
            if ($image->isIdEqualTo($id)) {
                if ($prev = $images[$i - 1] ?? null) {
                    $images[$i - 1] = $image;
                    $images[$i] = $prev;
                    $this->updateImages($images);
                }
                return;
            }
        }
        throw new \DomainException('Image not found.');
    }

    public function moveImageDown($id) : void
    {
        $images = $this->images;
        foreach ($images as $i => $image) {
            if ($image->isIdEqualTo($id)) {
                if ($next = $images[$i + 1] ?? null) {
                    $images[$i] = $next;
                    $images[$i + 1] = $image;
                    $this->updateImages($images);
                }
                return;
            }
        }
        throw new \DomainException('Image not found.');
    }

    private function updateImages(array $images) : void
    {
        foreach ($images as $i => $image) {
            $image->setSort($i);
        }
        $this->images = $images;
        $this->populateRelation('mainImage', reset($images));
    }

    // Related items methods

    public function attachRelatedItem($id) : void
    {
        $attachments = $this->relatedAttachments;
        foreach ($attachments as $attachment) {
            if ($attachment->isForItem($id)) {
                return;
            }
        }
        $attachments[] = CategoryAttachment::create($id);
        $this->relatedAttachments = $attachments;
    }

    public function revokeRelatedItem($id) : void
    {
        $attachments = $this->relatedAttachments;
        foreach ($attachments as $i => $attachment) {
            if ($attachment->isForItem($id)) {
                unset($attachments[$i]);
                $this->relatedAttachments = $attachments;
                return;
            }
        }
        throw new \DomainException('Attachment not found.');
    }

    // Variations handlers

    /**
     * @param $id
     * @return Variation
     */
    public function getVariation($id) : Variation
    {
        foreach ($this->variations as $variation) {
            if ($variation->isIdEqualTo($id)) {
                return $variation;
            }
        }
        throw new \DomainException('Variation not found.');
    }

    /**
     * @param $code
     * @param $name
     * @param $price
     */
    public function addVariation($code, $name, $price) : void
    {
        $variations = $this->variations;
        foreach ($variations as $variation) {
            if ($variation->isCodeEqualTo($code)) {
                throw new \DomainException('Variation already exists.');
            }
        }
        $variations[] = Variation::create($code, $name, $price);
        $this->variations = $variations;
    }

    /**
     * @param $id
     * @param $code
     * @param $name
     * @param $price
     * @return void
     */
    public function editVariation($id, $code, $name, $price) : void
    {
        $variations = $this->variations;
        foreach ($variations as $i => $variation) {
            if ($variation->isIdEqualTo($id)) {
                $variation->edit($code, $name, $price);
                $this->variations = $variations;
                return;
            }
        }
        throw new \DomainException('Variation not found.');
    }

    /**
     * @param $id
     * @return void
     */
    public function removeVariation($id) : void
    {
        $variations = $this->variations;
        foreach ($variations as $i => $variation) {
            if ($variation->isIdEqualTo($id)) {
                unset($variations[$i]);
                $this->variations = $variations;
                return;
            }
        }
        throw new \DomainException('Variation not found.');
    }


    // Overviews handlers

    /**
     * @param $userId
     * @param $vote
     * @param $text
     * @return void
     */
    public function addOverview($userId, $vote, $text) : void
    {
        $overviews = $this->overviews;
        $overviews[] = Overwiev::create($userId, $vote, $text);
        $this->updateOverviews($overviews);
    }

    /**
     * @param $id
     * @param $vote
     * @param $text
     * @return void
     */
    public function editOverview($id, $vote, $text) : void
    {
        $overviews = $this->overviews;
        foreach ($overviews as $i => $overview) {
            if ($overview->isIdEqualTo($id)) {
                $overview->edit($vote, $text);
                $this->updateOverviews($overviews);
                return;
            }
        }
        throw new \DomainException('Overview not found.');
    }

    public function acceptOverview($id) : void
    {
        $overviews = $this->overviews;
        foreach ($overviews as $i => $overview) {
            if ($overview->isIdEqualTo($id)) {
                $overview->accept();
                $this->updateOverviews($overviews);
                return;
            }
        }
        throw new \DomainException('Overview not found.');
    }

    public function declineOverview($id) : void
    {
        $overviews = $this->overviews;
        foreach ($overviews as $i => $overview) {
            if ($overview->isIdEqualTo($id)) {
                $overview->decline();
                $this->updateOverviews($overviews);
                return;
            }
        }
        throw new \DomainException('Overwiev not found.');
    }

    public function deleteOverview($id) : void
    {
        $overviews = $this->overviews;
        foreach ($overviews as $i => $overview) {
            if ($overview->isIdEqualTo($id)) {
                unset($overviews[$i]);
                $this->updateOverviews($overviews);
                return;
            }
        }
        throw new \DomainException('Overview not found.');
    }

    /**
     * @param array $overviews
     * @return void
     */
    private function updateOverviews(array $overviews) : void
    {
        $amount = 0;
        $total = 0;
        foreach ($overviews as $overview) {
            if ($overview->isActive()) {
                $amount++;
                $total += $overview->getRating();
            }
        }
        $this->overviews = $overviews;
        $this->rating = $amount ? $total / $amount : null;
    }


    ########### Item Attachments ##############################

    public function getLabel() : ActiveQuery
    {
        return $this->hasOne(Label::class, ['id' => 'label_id']);
    }
    public function getCategory() : ActiveQuery
    {
        return $this->hasOne(Category::class, ['id' => 'category_id']);
    }
    public function getCategories() : ActiveQuery
    {
        return $this->hasMany(Category::class, ['id' => 'category_id'])->via('categoryAttachments');
    }
    public function getCategoryAttachments() : ActiveQuery
    {
        return $this->hasMany(CategoryAttachment::class, ['item_id' => 'id']);
    }
    public function getTags() : ActiveQuery
    {
        return $this->hasMany(Tag::class, ['id' => 'tag_id'])->via('tagAttachments');
    }
    public function getTagAttachments() : ActiveQuery
    {
        return $this->hasMany(TagAttachment::class, ['item_id' => 'id']);
    }
    public function getValues() : ActiveQuery
    {
        return $this->hasMany(ParameterValue::class, ['item_id' => 'id']);
    }
    public function getImages() : ActiveQuery
    {
        return $this->hasMany(Image::class, ['item_id' => 'id'])->orderBy('sort');
    }
    public function getRelateds() : ActiveQuery
    {
        return $this->hasMany(Item::class, ['id' => 'related_id'])->via('relatedAttachments');
    }
    public function getRelatedAttachments() : ActiveQuery
    {
        return $this->hasMany(RelatedAttachment::class, ['item_id' => 'id']);
    }
    public function getOverviews() : ActiveQuery
    {
        return $this->hasMany(Overwiev::class, ['item_id' => 'id']);
    }
    public function getMainImage() : ActiveQuery
    {
        return $this->hasOne(Image::class, ['id' => 'main_image_id']);
    }
    public function getVariations() : ActiveQuery
    {
      return $this->hasMany(Variation::class, ['item_id' => 'id']);
    }
    #######################################################

    public static function tableName() : string
    {
        return '{{%app_items}}';
    }

    public function behaviors() : array
    {
        return [
            [
                'class' => SaveRelationsBehavior::className(),
                'relations' => [
                    'categoryAttachments',
                    'tagAttachments',
                    'relatedAttachments',
                    'variations',
                    'values',
                    'images',
                    'overviews'
                ],
            ],
        ];
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert) : bool
    {
        $this->setAttribute('meta_json', Json::encode([
            'title' => $this->meta->title,
            'description' => $this->meta->description,
            'keywords' => $this->meta->keywords
        ]));
        return parent::beforeSave($insert);
    }

    /**
     * @return bool
     */
    public function beforeDelete() : bool
    {
        if (parent::beforeDelete()) {
            foreach ($this->images as $image) {
                $image->delete();
            }
            return true;
        }
        return false;
    }

    public function afterFind() : void
    {
        $meta = Json::decode($this->getAttribute('meta_json'));
        $this->meta = new Meta(
            $meta['title'] ?? null,
            $meta['description'] ?? null,
            $meta['keywords'] ?? null
        );
        parent::afterFind();
    }

    public function transactions() : array
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    /**
     * @param bool $insert
     * @param array $changedAttributes
     */
    public function afterSave($insert, $changedAttributes) : void
    {
         $related = $this->getRelatedRecords();
         if (array_key_exists('mainImage', $related)) {
             $this->updateAttributes([
                 'main_image_id' => $related['mainImage']
                 ? $related['mainImage']->id
                 : null
             ]);
         }
         parent::afterSave($insert, $changedAttributes);
    }
}