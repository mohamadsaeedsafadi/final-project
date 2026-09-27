<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class ServiceOffer extends Model
{
    protected $fillable = [
        'service_request_id',
        'provider_id',
        'min_price',
        'max_price',
        'message',
        'status'
    ];

    public function request()
    {
        return $this->belongsTo(ServiceRequest::class, 'service_request_id');
    }

    public function provider()
    {
        return $this->belongsTo(User::class, 'provider_id');
    }
}