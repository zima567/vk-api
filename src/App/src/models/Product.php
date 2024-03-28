<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'title',
        'description',
        'price',
        'pictureLink',
        'idUserFk',
    ];
    protected $visible = ['id', 'title', 'price', 'description', 'pictureLink', 'idUserFk'];

    //отключить timestamps
    public $timestamps = FALSE;

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
