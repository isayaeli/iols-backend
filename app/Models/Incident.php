<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Incident extends Model
{
    protected $fillable = [
        'title', 
        'status', 
        'user_id', 
        'description',
        'assigned_to',
        
    ];

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }
}
