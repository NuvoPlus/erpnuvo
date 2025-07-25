<?php

namespace App\Models\Tenant;

use Modules\Finance\Models\GlobalPayment;
use Illuminate\Database\Query\Builder;


class Cash extends ModelTenant
{
    // protected $with = ['cash_documents'];

    protected $table = 'cash';

    protected $fillable = [
        'user_id',
        'date_opening',
        'time_opening',
        'date_closed',
        'time_closed',
        'beginning_balance',
        'final_balance',
        'income',
        'state',
        'reference_number',
        'resolution_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    //obtiene documentos y notas venta
    public function cash_documents()
    {
        return $this->hasMany(CashDocument::class);
    }

    public function scopeWhereTypeUser($query)
    {
        $user = auth()->user();
        return ($user->type == 'seller') ? $query->where('user_id', $user->id) : null;
    }

    public function global_destination()
    {
        return $this->morphMany(GlobalPayment::class, 'destination');
    }

    public function resolution()
    {
        return $this->belongsTo(ConfigurationPos::class, 'resolution_id');
    }

    /**
     *
     * Retornar el balance final (total de ingresos - gastos)
     *
     * Usado en:
     * CashController - Cierre de caja chica
     *
     * @return double
     */
    public function getSumCashFinalBalance()
    {
        return $this->cash_documents->sum(function($row){
            return $row->document_pos ? $row->document_pos->getTotalCash() : 0;
        });
    }


    /**
     *
     * Filtro para obtener caja abierta del usuario en sesion
     *
     * @param  Builder $query
     * @return Builder
     */
    public function scopeGetOpenCurrentCash($query)
    {
        return $query->where('state', 1)->where('user_id', auth()->id());
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function ($cash) {
            if(env("LOG_ROUTES", false)){
                $request = request();
                $isInternal = app()->runningInConsole() || app()->runningUnitTests();

                \Log::info('Se creó un nuevo registro en la tabla cash', [
                    'id' => $cash->id,
                    'user_id' => $cash->user_id,
                    'date_opening' => $cash->date_opening,
                    'route' => $request->path(), // Ruta que hizo la solicitud
                    'method' => $request->method(), // Método HTTP (POST, PUT, etc.)
                    'controller' => optional($request->route())->getActionName(), // Controlador y método
                    'ip' => $isInternal ? '127.0.0.1' : $request->ip(),
                    'user_agent' => $request->userAgent(), // Información del navegador/cliente
                    'auth_user' => optional(auth()->user())->id, // Usuario autenticado (si aplica)
                ]);
            }
        });
    }
}
