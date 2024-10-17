<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;

class CollectionGroup extends Model
{
    //
    protected $keyType = 'string';
    public $incrementing = false;
    protected $table = "collection_groups";

    protected $primaryKey = 'id';

    public function getRouteKeyName()
    {
        return 'id';
    }

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->id = (string) Uuid::generate(4);
        });
    }

    public function collectionGroupItems(){
        return $this->hasMany(CollectionGroupItem::class, 'collection_group_id', 'id');
    }

    public function getCollectionsAttribute(){
        $items = $this->collectionGroupItems;
        $collections = [];
        foreach($items as $item) {
            $collections[] = Collection::where('id',$item->collection_id)->first();
        }
        return $collections;
    }

    public function collections()
    {

    }
}
