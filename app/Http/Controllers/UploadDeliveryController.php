<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProcessFilesRequest;
use App\Jobs\ProcessCustomerDeliveryCsv;
use App\Models\Customer;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use JildertMiedema\LaravelPlupload\Facades\Plupload;

class UploadDeliveryController extends Controller
{
    public function view(): Factory|View|Application
    {
        $customers = Customer::select([
            'customer_id',
            'name',
        ])->get();

        $action = route('process-files');

        return view('upload-delivery.view', [
            'customers' => $customers,
            'action' => $action,
        ]);
    }

    public function processFiles(ProcessFilesRequest $request): Redirector|Application|RedirectResponse
    {
        foreach ($request->getFilenames() as $file) {
            ProcessCustomerDeliveryCsv::dispatch($request->getCustomerId(), $file);
        }

        return redirect('/');
    }

    public function upload(): array
    {
        return Plupload::receive('file', static function ($file)
        {
            $file->move(\Storage::disk('deliveryTables')->path(''), $file->getClientOriginalName());

            return 'ready';
        });
    }
}
