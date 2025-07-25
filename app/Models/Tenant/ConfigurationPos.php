<?php

namespace App\Models\Tenant;

class ConfigurationPos extends ModelTenant
{
    public $timestamps = false;

    protected $fillable = [
        'prefix',
        'resolution_number',
        'resolution_date',
        'date_from',
        'date_end',
        'from',
        'to',
        'electronic',
        'type_resolution',
        'generated',
        'plate_number',
        'cash_type',
        'technical_key'
    ];

    protected $casts = [
        'resolution_date' => 'date',
        'electronic' => 'boolean',
    ];
}
