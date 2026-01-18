<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'incident', 'user_id', 'old_status', 'new_status', 'comment'
    ];

    protected $casts = [
        'incident' => 'array',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
