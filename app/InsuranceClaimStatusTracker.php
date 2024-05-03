<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InsuranceClaimStatusTracker extends Model
{

    public function claim(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(InsuranceClaim::class, 'claim_id', 'id');
    }

    public static function statusTransitions($claimId){
        $claim = InsuranceClaim::findOrFail($claimId);
        $transitions = InsuranceClaimStatusTracker::where('claim_id', $claimId)->orderBy('created_at')->get();
        return ['claim' => $claim, 'transitions' => $transitions];
    }
}
