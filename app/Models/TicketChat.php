<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TicketChat extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = 'ticket_chat';

    public function userData() {
        return $this->hasOne(User::class, 'id', 'author');
     }

}