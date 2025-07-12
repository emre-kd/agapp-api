<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Community extends Model
{
    use HasFactory;

    protected $table = 'communities';

    protected $fillable = [
        'code',
        'name',
        'user_id'

    ];


       public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }






    public function users()
    {
        return $this->hasMany(User::class, 'community_id', 'id');
    }

      public function posts()
    {
        return $this->hasMany(Post::class, 'community_id', 'id');
    }







}
