<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyCreatorTask extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'company_name',
        'company_address',
        'company_number',
        'company_activity',
        'company_credentials',
        'status',
        'drop_id',
        'user_id',
        'is_sent'
    ];

    public function drop() {

        return $this->belongsTo(DropManagerTask::class, 'drop_id');
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
