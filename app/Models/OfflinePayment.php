<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OfflinePayment extends Model
{
    public static $waiting = 'waiting';
    public static $approved = 'approved';
    public static $reject = 'reject';

    // request types
    public static $typeCharge = 'charge';
    public static $typeCart = 'cart';

    public $timestamps = false;

    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    public function offlineBank()
    {
        return $this->belongsTo('App\Models\OfflineBank', 'offline_bank_id', 'id');
    }

    public function order()
    {
        return $this->belongsTo('App\Models\Order', 'order_id', 'id');
    }

    public function getAttachmentPath()
    {
        $attachment = $this->attachment;

        if (empty($attachment)) {
            return null;
        }

        // If already a full URL or already prefixed with /store, return as-is
        if (str_starts_with($attachment, 'http') || str_starts_with($attachment, '/store/')) {
            return $attachment;
        }

        // Default storage path used by uploadFile in AccountingController
        return '/store/' . $this->user_id . '/offlinePayments/' . ltrim($attachment, '/');
    }
}
