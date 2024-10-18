<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebsiteCreatorTask extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'website_link',
        'website_credentials',
        'status',
        'drop_id',
        'company_id',
        'user_id',
        'is_sent'
    ];


    public function drop() {

        return $this->belongsTo(DropManagerTask::class, 'drop_id');
    }

    public function company() {

        return $this->belongsTo(CompanyCreatorTask::class, 'company_id');
    }

    public function support() {

        return $this->hasMany(SupportTask::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
