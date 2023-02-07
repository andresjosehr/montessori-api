<?php

namespace App\Http\Controllers;

use App\Models\PaymentControl;
use App\Models\PriceHistory;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $perPage = $request->input('perPage') ? $request->input('perPage') : 10;

        $searchString = $request->input('searchString') ? $request->input('searchString') : '';
        $searchString = $request->input('searchString') != 'null' ? $request->input('searchString') : '';
		$users = Student::with('level')
        ->when($searchString, function($q) use ($searchString) {
            $q->where('name', $searchString)
            ->orWhere('last_name', $searchString)
            ->orWhere('representative_name', $searchString)
            ->orWhere('representative_phone', $searchString)
            ->orWhere('representative_phone', $searchString);
        })
        ->paginate($perPage);

        return ApiResponseController::response('Consulta Exitosa', 200, $users);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $student                       = new Student();
        $student->name                 = $request->name;
        $student->last_name            = $request->last_name;
        $student->representative_name  = $request->representative_name;
        $student->representative_phone = $request->representative_phone;
        $student->level_id             = $request->level_id;
        $student->document             = $request->document;
        $student->save();

        return ApiResponseController::response('Usuario creado con exito', 200, $student);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if(!$student = Student::find($id)){
            return ApiResponseController::response('', 204);
        }

        return ApiResponseController::response('Consulta exitosa', 200, $student);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $student                       = Student::find($id);

        if(!$student){
            return ApiResponseController::response('Estudiante no encontrado', 404);
        }

        $student->name                 = $request->name;
        $student->last_name            = $request->last_name;
        $student->representative_name  = $request->representative_name;
        $student->representative_phone = $request->representative_phone;
        $student->level_id             = $request->level_id;
        $student->document             = $request->document;
        $student->save();

        return ApiResponseController::response('Usuario actualizado con exito', 200, $student);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Delete payments first
        PaymentControl::where('student_id', $id)->delete();

        $student = Student::find($id);
        $student->delete();

        return ApiResponseController::response('Estudiante eliminado con exito', 200);
    }

    public function registerPayment(Request $request, $id)
    {

        $bcv = ProceduresController::getDolarBCV();

        $date = explode('T', $request->payment_date)[0];

        $paymentControl                   = new PaymentControl();
        $paymentControl->student_id       = $id;
        $paymentControl->month            = $request->month+1;
        $paymentControl->year             = $request->year;
        $paymentControl->ves_amount       = $request->ves_amount;
        $paymentControl->usd_amount       = $request->usd_amount;
        $paymentControl->bcv_price        = $bcv;
        $paymentControl->full_name        = $request->full_name;
        $paymentControl->document         = $request->document;
        $paymentControl->payment_method   = $request->payment_method;
        $paymentControl->payment_date     = $date;
        $paymentControl->payer_type       = $request->payer_type;
        $paymentControl->reference_number = $request->reference_number;
        $paymentControl->payment_type     = "Mensualidad";
        $paymentControl->save();

        return ApiResponseController::response('Pago registrado con exito', 200, $paymentControl);
    }

    public function registerEnrollmentPayment(Request $request, $id)
    {

        $bcv = ProceduresController::getDolarBCV();

        $date = explode('T', $request->payment_date)[0];

        if(!$paymentControl = PaymentControl::where('student_id', $id)->where('payment_type', 'Inscripción')->first()){
            $paymentControl = new PaymentControl();
        }
        $paymentControl->student_id       = $id;
        $paymentControl->year             = date('Y');
        $paymentControl->ves_amount       = $request->ves_amount;
        $paymentControl->usd_amount       = $request->usd_amount;
        $paymentControl->bcv_price        = $bcv;
        $paymentControl->full_name        = $request->full_name;
        $paymentControl->document         = $request->document;
        $paymentControl->payment_method   = $request->payment_method;
        $paymentControl->payment_date     = $date;
        $paymentControl->payer_type       = $request->payer_type;
        $paymentControl->reference_number = $request->reference_number;
        $paymentControl->payment_type     = "Inscripción";
        $paymentControl->save();

        return ApiResponseController::response('Pago registrado con exito', 200, $paymentControl);
    }

    public function getEnrollmentPayment(Request $request)
    {
        $payments = PaymentControl::where('payment_type', 'Inscripción')->first();

        return ApiResponseController::response('Consulta exitosa', 200, $payments);
    }

    public function getPaymentControl(Request $request, $studentID, $year)
    {
        $payments = PaymentControl::where('year', $year)->where('student_id', $studentID)->get();

        return ApiResponseController::response('Consulta exitosa', 200, $payments);
    }


}
