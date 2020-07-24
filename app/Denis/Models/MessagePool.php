<?php


namespace App\Denis\Models;


use Illuminate\Database\Eloquent\Model;

class MessagePool extends Model
{
    protected $table = 'message_pool';

    protected $fillable = [
        'conversation_id',
        'in_progress',
        'message',
    ];

    public $timestamps = false;
}