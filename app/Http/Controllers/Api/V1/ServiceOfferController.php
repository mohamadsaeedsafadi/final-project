<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\ServiceOfferService;
use Illuminate\Http\Request;
class ServiceOfferController extends Controller
{
    protected $service;

    public function __construct(ServiceOfferService $service)
    {
        $this->service = $service;
    }

    public function store(Request $request)
    {
        $request->validate([
            'service_request_id' => 'required|exists:service_requests,id',
            'min_price' => 'required|numeric',
            'max_price' => 'required|numeric|gte:min_price',
            'message' => 'nullable|string'
        ]);

        $offer = $this->service->createOffer(
            $request->user(),
            $request->all()
        );

        return response()->json($offer);
    }
    public function accept(Request $request, $id)
{
    $offer = $this->service->acceptOffer(
        $request->user(),
        $id
    );

    return response()->json($offer);
}
public function updateCategories(Request $request)
{
    $request->validate([
        'categories' => 'required|array',
        'categories.*' => 'exists:service_categories,id'
    ]);

    $this->service->assignCategories(
        $request->user(),
        $request->categories
    );

    return response()->json(['message' => 'تم تحديث المجالات']);
}
}