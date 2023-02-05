<?php

namespace App\Http\Controllers;

use App\Http\Requests\UploadCsvRequest;
use App\Models\Customer;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;

class UploadDeliveryController extends Controller
{
    public function view(): Factory|View|Application
    {
        $customers = Customer::select([
            'customer_id',
            'name',
        ])->get();

        $action = route('upload-csv');

        return view('upload-delivery.view', [
            'customers' => $customers,
            'action' => $action,
        ]);
    }

    public function uploadCsv(UploadCsvRequest $request): Redirector|Application|RedirectResponse
    {
        $path = $request->file('customer_csv')?->storeAs('delivery-tables', $request->file('customer_csv')?->getClientOriginalName());

        return redirect('/');
    }
}
