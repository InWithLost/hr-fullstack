<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'logo',
        'inn',
        'description',
        'balance',
    ];

    public function jobs()
    {
        return $this->hasMany(Job::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class)->withPivot('is_owner')->withTimestamps();
    }
}