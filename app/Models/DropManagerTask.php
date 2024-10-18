<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DropManagerTask extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'fullname',
        'address',
        'contact',
        'country',
        'documents',
        'status',
        'user_id',
        'is_sent',
        'birthday'
    ];

    public function getDocumentsAttribute($documents) {

        return json_decode($documents);
    }

    public function companies() {

        return $this->hasMany(CompanyCreatorTask::class);
    }

    public function websites() {

        return $this->hasMany(WebsiteCreatorTask::class);
    }

    public function support() {

        return $this->hasMany(SupportTask::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
