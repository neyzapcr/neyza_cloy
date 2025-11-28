<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Multipleuploads extends Model
{
    protected $table = 'multiuploads';
    protected $primaryKey = 'id';

    protected $fillable = [
        'filename',
        'ref_table',
        'ref_id',
    ];

    // optional: biar gampang ambil URL
    public function getUrlAttribute()
    {
        return asset('storage/' . $this->filename);
    }
}
