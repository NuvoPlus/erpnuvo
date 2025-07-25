<?php

namespace App\Models\Tenant;

use App\Models\Tenant\Catalogs\AffectationIgvType;
use App\Models\Tenant\Catalogs\PriceType;
use App\Models\Tenant\Catalogs\SystemIscType;
use Illuminate\Support\Facades\DB;
use Modules\Factcolombia1\Models\Tenant\TypeUnit;

class DocumentItem extends ModelTenant
{
    // protected $with = ['affectation_igv_type', 'system_isc_type', 'price_type'];
    public $timestamps = false;

    protected $fillable = [
        'document_id',
        'item_id',
        'item',
        'quantity',
        'unit_price',
        'total',
        'total_plastic_bag_taxes',
        'warehouse_id',
        'unit_type_id',
        'tax_id',
        'tax',
        'total_tax',
        'subtotal',
        'discount',
    ];


    protected $casts = [
        'tax' => 'object'
    ];


    public function unit_type()
    {
        return $this->belongsTo(TypeUnit::class, 'unit_type_id');
    }

    public function getItemAttribute($value)
    {
        return (is_null($value))?null:(object) json_decode($value);
    }

    public function setItemAttribute($value)
    {
        $this->attributes['item'] = (is_null($value))?null:json_encode($value);
    }


    // public function affectation_igv_type()
    // {
    //     return $this->belongsTo(AffectationIgvType::class, 'affectation_igv_type_id');
    // }

    // public function system_isc_type()
    // {
    //     return $this->belongsTo(SystemIscType::class, 'system_isc_type_id');
    // }

    // public function price_type()
    // {
    //     return $this->belongsTo(PriceType::class, 'price_type_id');
    // }

    public function m_item()
    {
        return $this->belongsTo(Item::class,'item_id');
    }

    public function document()
    {
        return $this->belongsTo(Document::class);
    }

    public function relation_item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }



    public function scopeWhereDefaultDocumentType($query, $params)
    {

        $db_raw = DB::raw("document_items.id as id, documents.series as series, documents.number as number,
                            document_items.item as item, document_items.quantity as quantity,
                            documents.date_of_issue as date_of_issue");

        if($params['person_id']){

            return $query->whereHas('document', function($q) use($params){
                            $q->whereBetween($params['date_range_type_id'], [$params['date_start'], $params['date_end']])
                                ->where('customer_id', $params['person_id'])
                                ->whereTypeUser();
                        })
                        ->join('documents', 'document_items.document_id', '=', 'documents.id')
                        ->select($db_raw)
                        ->latest('id');

        }


        return $query->whereHas('document', function($q) use($params){
                    $q->whereBetween($params['date_range_type_id'], [$params['date_start'], $params['date_end']])
                        ->where('user_id', $params['seller_id'])
                        ->whereTypeUser();
                })
                ->join('documents', 'document_items.document_id', '=', 'documents.id')
                ->select($db_raw)
                ->latest('id');

    }


    /**
     * Valor neto sin impuestos
     *
     * @return float
     */
    public function getNetValueAttribute()
    {
        return $this->generalApplyNumberFormat($this->quantity * $this->unit_price);
    }


    /**
     *
     * Datos para pdf reporte articulos vendidos
     *
     * @return array
     */
    public function getDataReportSoldItems()
    {
        $cost = $this->generalApplyNumberFormat($this->relation_item->purchase_unit_price * $this->quantity);

        return [
            'type_name' => 'FE',
            'internal_id' => $this->item->internal_id,
            'name' => $this->item->name,
            'quantity' => (float) $this->quantity,
            'cost' => $cost,
            'net_value' => $this->net_value,
            'discount' => $this->discount,
            'utility' => $this->generalApplyNumberFormat($this->net_value - $cost),
            'total_tax' => $this->total_tax,
            'total' => $this->total,
        ];
    }


    /**
     *
     * Filtros para reporte articulos vendidos
     *
     * @param  Builder $query
     * @param  Request $request
     * @return Builder
     */
    public function scopeFilterReportSoldItems($query, $request)
    {
        $brand_id = $request->brand_id ?? null;
        $item_id = $request->item_id ?? null;

        return $query->with([
                    'document' => function($query){
                        return $query->select([
                            'id',
                            'prefix',
                            'number'
                        ]);
                    },
                    'relation_item' => function($query){
                        return $query->select([
                            'id',
                            'purchase_unit_price'
                        ]);
                    },
                ])
                ->filterSoldItemsDocument($request)
                ->filterByItem($item_id)
                ->filterByBrand($brand_id)
                ->latest('id');
    }


    /**
     *
     * Filtrar por producto
     *
     * @param  Builder $query
     * @param  int $item_id
     * @return Builder
     */
    public function scopeFilterByItem($query, $item_id)
    {
        if($item_id) $query->where('item_id', $item_id);

        return $query;
    }


    /**
     *
     * Filtrar por marca
     *
     * @param  Builder $query
     * @param  int $brand_id
     * @return Builder
     */
    public function scopeFilterByBrand($query, $brand_id)
    {
        if($brand_id)
        {
            $query->whereHas('relation_item', function($query) use($brand_id){
                return $query->where('brand_id', $brand_id);
            });
        }

        return $query;
    }


    /**
     *
     * Filtros para reporte articulos vendidos en el documento
     *
     * @param  Builder $query
     * @param  Request $request
     * @return Builder
     */
    public function scopeFilterSoldItemsDocument($query, $request)
    {
        $customer_id = $request->customer_id ?? null;
        $user_id = $request->user_id ?? null;
        $start_date = $request->start_date ?? null;
        $end_date = $request->end_date ?? null;
        $start_time = $request->start_time ?? null;
        $end_time = $request->end_time ?? null;

        return $query->whereHas('document', function($document) use($customer_id, $user_id, $start_date, $end_date, $start_time, $end_time){

            return $document->filterByCustomer($customer_id)
                            ->filterByUser($user_id)
                            ->filterByRangeTimeOfIssue($start_time, $end_time)
                            ->filterByRangeDateOfIssue($start_date, $end_date)
                            ->filterInvoiceDocument();
        });
    }

}
