<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupportTask extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'account_name',
        'account_credentials',
        'verification_links',
        'status',
        'is_sent',
        'user_id',
        'drop_id',
        'company_id',
        'website_id',
    ];

    public function drop() {

        return $this->belongsTo(DropManagerTask::class, 'drop_id');
    }

    public function company() {

        return $this->belongsTo(CompanyCreatorTask::class, 'company_id');
    }

    public function website() {

        return $this->belongsTo(WebsiteCreatorTask::class, 'website_id');
    }

    public function getVerificationLinksAttribute($value) {

        return json_decode($value);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
