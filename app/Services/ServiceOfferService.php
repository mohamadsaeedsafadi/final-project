<?php
namespace App\Services;

use App\Models\ServiceCategory;
use App\Models\ServiceOffer;
use App\Repositories\ServiceOfferRepository;
use App\Services\chat\ChatService;
use Exception;

class ServiceOfferService
{
    protected $offerRepo;

    public function __construct(ServiceOfferRepository $offerRepo ,ChatService $chatService)
    {
        $this->offerRepo = $offerRepo;
   /*      $this->ChatService=$chatService; */
    }

    public function createOffer($provider, array $data)
    {
        if ($provider->role !== 'provider') {
            throw new Exception('فقط مقدمي الخدمة يمكنهم إرسال عروض.');
        }
        $exists = ServiceOffer::where('service_request_id', $data['service_request_id'])
    ->where('provider_id', $provider->id)
    ->exists();

if ($exists) {
    throw new Exception('لقد قمت بإرسال عرض مسبقاً لهذا الطلب.');
}

        return $this->offerRepo->create([
            'service_request_id' => $data['service_request_id'],
            'provider_id' => $provider->id,
            'min_price' => $data['min_price'],
            'max_price' => $data['max_price'],
            'message' => $data['message'] ?? null
        ]);
    }
    public function acceptOffer($user, $offerId)
{
    $offer = ServiceOffer::with('request')
        ->findOrFail($offerId);

    if ($offer->request->user_id !== $user->id) {
        throw new Exception('غير مصرح لك بقبول هذا العرض.');
    }

    if ($offer->status !== 'pending') {
        throw new Exception('لا يمكن قبول هذا العرض.');
    }
/* $conversation = $this->chatService->createConversation($request); */
    return $this->offerRepo->acceptOffer($offer);
}
  public function assignCategories($provider, array $categoryIds)
{
    foreach ($categoryIds as $id) {

        $category = ServiceCategory::findOrFail($id);

        if ($category->parent_id === null) {
            throw new Exception("يجب اختيار فئة فرعية فقط.");
        }
    }

    $provider->categories()->sync($categoryIds);
}
}