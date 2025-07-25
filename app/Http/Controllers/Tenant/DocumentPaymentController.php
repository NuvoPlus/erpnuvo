<?php
namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\DocumentPaymentRequest;
use App\Http\Requests\Tenant\DocumentRequest;
use App\Http\Resources\Tenant\DocumentPaymentCollection;
use App\Models\Tenant\Document;
use App\Models\Tenant\DocumentPayment;
use App\Models\Tenant\PaymentMethodType;
use Exception, Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade as PDF;
use Modules\Finance\Traits\FinanceTrait;
use Modules\Finance\Traits\FilePaymentTrait;

class DocumentPaymentController extends Controller
{

    use FinanceTrait, FilePaymentTrait;

    public function records($document_id)
    {
        $records = DocumentPayment::where('document_id', $document_id)->get();

        return new DocumentPaymentCollection($records);
    }

    public function tables()
    {
        return [
            'payment_method_types' => PaymentMethodType::all(),
            'payment_destinations' => $this->getPaymentDestinationsAlternate()
        ];
    }

    public function document($document_id)
    {
        $document = Document::find($document_id);

        $total_paid = collect($document->payments)->sum('payment');
        $total = $document->total;
        $total_difference = round($total - $total_paid, 2);

        return [
            'number_full' => $document->number_full,
            'total_paid' => $total_paid,
            'total' => $total,
            'total_difference' => $total_difference
        ];

    }

    public function store(DocumentPaymentRequest $request)
    {
        // dd($request->all());

        $id = $request->input('id');

        DB::connection('tenant')->transaction(function () use ($id, $request) {

            $record = DocumentPayment::firstOrNew(['id' => $id]);
            $record->fill($request->all());
            $record->save();
            $this->createGlobalPayment($record, $request->all());
            $this->saveFiles($record, $request, 'documents');

        });

        return [
            'success' => true,
            'message' => ($id)?'Pago editado con éxito':'Pago registrado con éxito'
        ];
    }

    public function destroy($id)
    {
        $item = DocumentPayment::findOrFail($id);
        $item->delete();

        return [
            'success' => true,
            'message' => 'Pago eliminado con éxito'
        ];
    }

    public function initialize_balance()
    {

        DB::connection('tenant')->transaction(function (){

            $documents = Document::get();

            foreach ($documents as $document) {

                $total_payments = $document->payments->sum('payment');

                $balance = $document->total - $total_payments;

                if($balance <= 0){

                    $document->total_canceled = true;
                    $document->update();

                }else{

                    $document->total_canceled = false;
                    $document->update();
                }

            }

        });

        return [
            'success' => true,
            'message' => 'Acción realizada con éxito'
        ];
    }

    public function  report($start, $end)
    {
        //$document = Document::select('id')->orderBy('date_of_issue', 'DESC')->take(50)->pluck('id');
        $documents = DocumentPayment::whereBetween('date_of_payment', [$start , $end])->get();

        //$customer = $document->customer;
        //$number = $document->number_full;
        $records = collect($documents)->transform(function($row){
            return [
                'id' => $row->id,
                'date_of_payment' => $row->date_of_payment->format('d/m/Y'),
                'payment_method_type_description' => $row->payment_method_type->description,
                'destination_description' => ($row->global_payment) ? $row->global_payment->destination_description:null,
                'change' => $row->change,
                'payment' => $row->payment,
                'reference' => $row->reference,
                'customer' => $row->document->customer->name,
                'number'=>  $row->document->number_full,
                'total' => $row->document->total,
            ];
        });


        /*$methods = PaymentMethodType::all();

        $methdos_sum = array();

        foreach ($methods as $item) {

            $row = [
                'name' => $item->description,
                'sum' => $documents->where('payment_method_type_id', $item->id)->sum('payment')
            ];

            array_push($methdos_sum, (object)$row);
        }*/


        $pdf = PDF::loadView('tenant.document_payments.report', compact("records"));

        $filename = "Reporte_Pagos";

        return $pdf->stream($filename.'.pdf');
    }

}
