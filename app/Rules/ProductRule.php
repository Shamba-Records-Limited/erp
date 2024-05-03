<?php

namespace App\Rules;

use App\Product;
use App\User;
use Illuminate\Contracts\Validation\Rule;

class ProductRule implements Rule
{
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }


    public function passes($attribute, $value): bool
    {
        $products = array_map('trim', explode(',', trim($value)));
        if ($products) {
            return Product::select('name')
                    ->where('cooperative_id', $this->user->cooperative_id)
                    ->whereIn('name', $products)
                    ->count() == count($products);
        }
        return false;
    }

    public function message(): string
    {
        return 'All the :attribute provided  may have not been registered by  '
            . $this->user->cooperative->name
            . ' cooperative. Please refer to the selected products';
    }
}
