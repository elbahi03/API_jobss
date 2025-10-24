<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jobs extends Model
{
    use HasFactory;
    protected $fillable = [ 'title', 'description', 'entreprise', 'ville', 'user_id'];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
