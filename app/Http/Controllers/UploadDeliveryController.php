<?php

namespace App\Http\Controllers;

use App\Http\Requests\UploadCsvRequest;
use App\Jobs\ProcessCustomerDeliveryCsv;
use App\Models\Customer;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Storage;

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
        $file = $request->getCustomerCsv();
        $fileName = Carbon::now()->toISOString() . $file->getClientOriginalName();
        Storage::disk('deliveryTables')->putFileAs('', $file, $fileName);

        ProcessCustomerDeliveryCsv::dispatch($request->getCustomerId(), $fileName);

        return redirect('/');
    }
}
