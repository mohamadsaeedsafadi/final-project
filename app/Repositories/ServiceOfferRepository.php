<?php
namespace App\Repositories;

use App\Models\ServiceOffer;

class ServiceOfferRepository
{
    public function create(array $data)
    {
        return ServiceOffer::create($data);
    }

    public function getOffersForRequest($requestId)
    {
        return ServiceOffer::with('provider')
            ->where('service_request_id', $requestId)
            ->get();
    }
    public function acceptOffer($offer)
{
    $offer->update(['status' => 'accepted']);

    ServiceOffer::where('service_request_id', $offer->service_request_id)
        ->where('id', '!=', $offer->id)
        ->update(['status' => 'rejected']);

    $offer->request->update(['status' => 'in_progress']);

    return $offer;
}
}