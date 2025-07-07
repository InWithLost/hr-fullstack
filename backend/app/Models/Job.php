<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory;

    protected $table = 'domain_jobs';

    protected $fillable = [
        'company_id',
        'specialty_id',
        'title',
        'slug',
        'description',
        'salary_from',
        'salary_to',
        'status',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function specialty()
    {
        return $this->belongsTo(Specialty::class);
    }

    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}