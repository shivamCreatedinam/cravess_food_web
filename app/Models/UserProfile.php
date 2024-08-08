<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function getCoverPhotoAttribute($value)
    {
        if ($value) {
            return url('storage/app/public/' . $value);
        }

        return null;
    }
}
