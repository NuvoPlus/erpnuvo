<?php

namespace App\Models\Tenant;

use App\Models\Tenant\Catalogs\IdentityDocumentType;

class Company extends ModelTenant
{
    protected $with = ['identity_document_type'];
    protected $fillable = [
        'user_id',
        'identity_document_type_id',
        'number',
        'name',
        'trade_name',
        'soap_send_id',
        'soap_type_id',
        'soap_username',
        'soap_password',
        'soap_url',
        'certificate',
        'logo',
        'app_name',
        'app_owner_name',
        'app_business_name',
        'jpg_firma_facturas',
        'detraction_account',
        'operation_amazonia',
    ];

    public function identity_document_type()
    {
        return $this->belongsTo(IdentityDocumentType::class, 'identity_document_type_id');
    }

    public static function active()
    {
        return Company::first();
    }
}
