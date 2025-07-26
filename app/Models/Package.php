<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'price', 'speed'];

    protected $appends = ['clients_count'];

    public function getClientsCountAttribute()
    {
        return $this->clients()->count();
    }

    public function clients()
    {
        return $this->hasMany(Client::class);
    }
}
