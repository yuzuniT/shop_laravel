<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $table = 'contacts';

    protected $fillable=[
        'user_id',
        'family_name',
        'last_name',
        'email',
        'phone_number',
        'contact_type',
        'contact_title',
        'message',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
