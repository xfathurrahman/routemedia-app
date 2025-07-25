<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'address', 'whatsapp_number', 'id_card_photo_path', 'package_id'];

    public function package()
    {
        return $this->belongsTo(Package::class);
    }
}
