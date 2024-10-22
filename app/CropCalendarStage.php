<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Webpatser\Uuid\Uuid;

class CropCalendarStage extends Model
{
    protected $keyType = 'string';

    public $incrementing = false;
    const TYPE_CROP = 1;
    const TYPE_LIVESTOCK = 2;


    protected $primaryKey = 'id';

    protected $fillable = [
        'name','period','period_measure','cooperative_id','crop_id','livestock_id','type'
    ];


    public function getRouteKeyName(): string
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

    public function crop(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Crop::class,'crop_id');
    }

    public function livestock(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Cow::class, 'livestock_id');
    }

    public static function stages($cooperative_id, $type): array
    {

        if($type == CropCalendarStage::TYPE_CROP){
            return DB::select("
            SELECT cs.crop_id AS id, SUM(cs.period) AS period, CONCAT(p.name,' ( ',c.variety, ' ) ') AS crop
            FROM crop_calendar_stages cs
            JOIN crops c ON cs.crop_id = c.id
            JOIN products p ON c.product_id = p.id
            WHERE type = '$type' AND cs.cooperative_id = '$cooperative_id'
            GROUP BY crop_id;
        ");
        }else
            {
            return DB::select("
            SELECT cs.livestock_id AS id, SUM(cs.period) AS period, CONCAT(c.name,' ( ',b.name, ' ', c.animal_type,' ) ') AS animal
            FROM crop_calendar_stages cs
            JOIN cows c on cs.livestock_id = c.id
            JOIN breeds b on c.breed_id = b.id
             WHERE type = '$type' AND cs.cooperative_id = '$cooperative_id'
            GROUP BY livestock_id;
            ");
        }
    }

    public static function stagesByItem($type, $id): array
    {
        if($type == CropCalendarStage::TYPE_CROP){
            return DB::select("
                SELECT cs.id AS id, cs.crop_id, cs.period AS period, CONCAT(p.name,' ( ',c.variety, ' ) ') AS crop, cs.name AS name
                FROM crop_calendar_stages cs
                JOIN crops c ON cs.crop_id = c.id
                JOIN products p ON c.product_id = p.id
                WHERE cs.crop_id = '$id' ORDER BY cs.created_at
            ");
        }else{
            return DB::select("
                SELECT cs.livestock_id, cs.id AS id, cs.period AS period, CONCAT(c.name,' ( ',b.name, ' ', c.animal_type,' ) ') AS animal, cs.name AS name
                FROM crop_calendar_stages cs
                JOIN cows c on cs.livestock_id = c.id
                JOIN breeds b on c.breed_id = b.id
                 WHERE cs.livestock_id = '$id' ORDER BY cs.created_at
            ");
        }
    }
}
