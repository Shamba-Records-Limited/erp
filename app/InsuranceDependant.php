<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Webpatser\Uuid\Uuid;

class InsuranceDependant extends Model
{

    const RELATIONSHIP_SPOUSE = 1;
    const RELATIONSHIP_CHILD = 2;

    protected $keyType = 'string';
    public $incrementing = false;
    protected $primaryKey = 'id';
    protected $fillable = [
        'subscription_id',
        'name',
        'relationship',
        'idno',
        'dob',
        'no',
        'cooperative_id'
    ];

    public function getRouteKeyName()
    {
        return 'id';
    }

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->id = (string)Uuid::generate(4);
        });
    }

    public function subscription(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(InsuranceSubscriber::class, 'subscription_id', 'id');
    }

    /**
     * @param Request $request
     * @param $subscriptionId
     * @param User $user
     * @return void
     */
    public static function addDependant(Request $request, $subscriptionId, User $user){
        $dependantNumber = (InsuranceSubscriber::findOrFail($subscriptionId)->dependants()->count()) + 1;

        $dependant = new InsuranceDependant();
        $dependant->cooperative_id = $user->cooperative_id;
        $dependant->name = $request->name;
        $dependant->idno = $request->idno;
        $dependant->relationship = $request->relationship;
        $dependant->dob = $request->dob;
        $dependant->no = $dependantNumber;
        $dependant->subscription_id = $subscriptionId;
        $dependant->save();
    }
}
