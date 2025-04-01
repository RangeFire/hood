<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ticket extends Model
{
   use HasFactory;

   protected $guarded = [];

   public function userData() {
      return $this->hasOne(User::class, 'id', 'creator');
   }

   public function ticketChat() {
      return $this->hasMany(TicketChat::class, 'ticket_id')->orderBy('created_at', 'asc');
   }

   public function leadingOperator() {
      return $this->hasOne(User::class, 'id', 'leading_operator');
   }

   public function project() {
      return $this->belongsTo(Project::class, 'project_id');
   }

}