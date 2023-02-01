<?php

namespace App\Http\Controllers;

use App\Models\EnrollmentFee;
use App\Models\MonthControl;
use App\Models\PaymentControl;
use App\Models\PriceHistory;
use App\Models\Student;
use Illuminate\Http\Request;

class PaymentsController extends Controller
{
    public function updateMonthPrice(Request $request)
    {

        // Replace comma with dot and convert to float
        $requestPrice = floatval(str_replace(',', '.', $request->price));

        $price = new PriceHistory();
        $price->price = $requestPrice;
        $price->save();

        return ApiResponseController::response('Precio actualizado exitosamente', 200);
    }

    public function getYears(Request $request)
    {
        $currentYear = date('Y');

        $years = MonthControl::where('year', '<=', $currentYear)->get();

        return ApiResponseController::response('Consulta exitosa', 200, $years);
    }
    public function getMonthPrice()
    {
        $price = PriceHistory::orderBy('created_at', 'desc')->first()->price;

        return ApiResponseController::response('Consulta exitosa', 200, $price);
    }

    public function destroy($id)
    {
        $paymentControl = PaymentControl::find($id);

        if ($paymentControl) {
            $paymentControl->delete();
            return ApiResponseController::response('Eliminado exitosamente', 200);
        } else {
            return ApiResponseController::response('No encontrado', 404);
        }
    }

    public function getPaymentsSummaryByYear(Request $request, $year)
    {
        $payments = PaymentControl::where('year', $year)->get();
        $students = \DB::table('students')->get();
        $currentMothPrice = PriceHistory::orderBy('created_at', 'desc')->first()->price;

        $data = [];
        for ($i=0; $i < 12; $i++) {
            $data[$i] = [
                'month' => $i + 1,
                'abonated' => $payments->where('month', $i + 1)->sum('usd_amount'),
                'debt' => $students->count() * $currentMothPrice - $payments->where('month', $i + 1)->sum('usd_amount'),
                'debtor_students' => $students->count() - $payments->where('month', $i + 1)->groupBy('student_id')->count(),
            ];
        }

        return ApiResponseController::response('Consulta exitosa', 200, $data);
    }

    public function getPaymentByMonth(Request $request, $year, $month)
    {
        // Filter students payments by month and year
        $students = Student::with(['payments' => function ($query) use ($year, $month) {
            $query->where('year', $year)->where('month', $month);
        }])->get();

        return ApiResponseController::response('Consulta exitosa', 200, $students);
    }

    public function saveEnrollmentFee(Request $request)
    {
        $request->validate([
            'year'       => 'required|integer',
            'amount_usd' => 'required|integer',
        ]);

        if($enrEnrollmentFee = EnrollmentFee::where('year', $request->year)->first()){
            $enrEnrollmentFee->amount_usd = $request->amount_usd;
            $enrEnrollmentFee->save();
        } else {
            $enrollmentFee = new EnrollmentFee();
            $enrollmentFee->year = $request->year;
            $enrollmentFee->amount_usd = $request->amount_usd;
            $enrollmentFee->save();
        }


        return ApiResponseController::response('Guardado exitosamente', 200);
    }

    public function getEnrollmentFees()
    {
        $enrollmentFees = EnrollmentFee::all();

        return ApiResponseController::response('Consulta exitosa', 200, $enrollmentFees);
    }
}
