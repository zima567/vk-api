<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'login',
        'password',
    ];

    protected $hidden = ['password'];
    protected $visible = ['id', 'login'];

    //отключить timestamps
    public $timestamps = FALSE;

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
