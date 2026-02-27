<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;

class ProductPolicy
{
    /**
     * Admin can do everything
     */
    public function before(User $user, $ability)
    {
        if ($user->role === 'admin') {
            return true;
        }
    }

    /**
     * Vendor can view only his product
     */
    public function view(User $user, Product $product)
    {
        return $user->role === 'vendor' && $product->vendor_id === $user->id;
    }

    /**
     * Vendor can update only his product
     */
    public function update(User $user, Product $product)
    {
        return $user->role === 'vendor' && $product->vendor_id === $user->id;
    }

    /**
     * Vendor can delete only his product
     */
    public function delete(User $user, Product $product)
    {
        return $user->role === 'vendor' && $product->vendor_id === $user->id;
    }

    /**
     * Vendor can create product
     */
    public function create(User $user)
    {
        return $user->role === 'vendor';
    }
}