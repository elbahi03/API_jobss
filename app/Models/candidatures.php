<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class candidatures extends Model
{
    use HasFactory;
    protected $fillable = [ 'user_id', 'job_id', 'full_name', 'can_email', 'phone_number', 'motivation', 'status'];

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function job(){
        return $this->belongsTo(Job::class);
    }
}
