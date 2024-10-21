<?php

namespace App\Rules;

use App\BankBranch;
use App\User;
use Illuminate\Contracts\Validation\Rule;

class BankBranchRule implements Rule
{

    private $user;
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function passes($attribute, $value): bool
    {
        return BankBranch::where('name', $value)
            ->where('cooperative_id', $this->user->cooperative_id)
            ->count() > 0;
    }

    public function message(): string
    {
        return 'The :attribute selected is not register to '.$this->user->cooperative->name.' cooperative';
    }
}
