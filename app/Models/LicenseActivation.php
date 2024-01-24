<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LicenseActivation extends Model
{
    protected $fillable = ['user_id', 'license_key','domain_name', 'domain_url', 'created_at', 'updated_at'];
}
