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
 * @property ParameterValue[] $values
 * @property Image[] $images
 * @property Overwiev[] $overviews
 *
 * Class Item
 * @package application\models\Items
 */
class Item extends ActiveRecord
{

    public $meta;

    /**
     * @param int $labelId
     * @param int $categoryId
     * @param string $code
     * @param string $name
     * @param Meta $meta
     * @return Item
     */
    public static function create(int $labelId, int $categoryId, string $code, string $name, Meta $meta) : self
    {
        $item = new self();
        $item->label_id = $labelId;
        $item->category_id = $categoryId;
        $item->code = $code;
        $item->name = $name;
        $item->meta = $meta;
        $item->created_at = time();
        return $item;
    }

    /**
     * @param int $new
     * @param int $old
     * @return void
     */
    public function setPrice(int $new, int $old) : void
    {
        $this->price_new = $new;
        $this->price_old = $old;
    }

    /**
     * @param int $labelId
     * @param string $code
     * @param string $name
     * @param Meta $meta
     */
    public function edit(int $labelId, string $code, string $name, Meta $meta) : void
    {
        $this->label_id = $labelId;
        $this->code = $code;
        $this->name = $name;
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
        foreach ($parameterValues as $val) {
            if ($val->isForParameter($id)) {
                return $val;
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


    // Overviews handling part

    public function addOverview($userId, $vote, $text) : void
    {
        $overviews = $this->overviews;
        $overviews[] = Overwiev::create($userId, $vote, $text);
        $this->updateOverviews($overviews);
    }

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
}