<?php

namespace App\Http\Controllers;

use App\Models\MonthControl;
use App\Models\PaymentControl;
use App\Models\PriceHistory;
use Illuminate\Http\Request;

class PaymentsController extends Controller
{
    public function updateMonthPrice(Request $request)
    {
        $config = \DB::table('system_config')->where('name', 'price_per_month')->update(['value' => $request->price]);

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
}
