<?php

namespace Uspacy\IntegrationsBackendPackage\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Portal extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'token',
        'refresh_token',
        'expiry_date',
        'domain',
    ];

    /**
     * Settings associated with portal
     */
    // public function portalSettings()
    // {
    //     return $this->hasOne(PortalSettings::class);
    // }
}
