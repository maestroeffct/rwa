<?php

namespace App\Models;


use App\User;
use Illuminate\Database\Eloquent\Model;

class EventTicketSold extends Model
{
    protected $table = 'event_tickets_sold';
    protected $guarded = ['id'];
    public $timestamps = false;
    protected $dateFormat = 'U';


    /* ==========
     | Relations
     * ==========*/
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function eventTicket()
    {
        return $this->belongsTo(EventTicket::class, 'event_ticket_id', 'id');
    }

    public function sale()
    {
        return $this->belongsTo(Sale::class, 'sale_id', 'id');
    }


    /* ==========
     | Helpers
     * ==========*/

}
