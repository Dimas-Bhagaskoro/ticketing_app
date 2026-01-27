<?php

namespace App\Models;

use App\Models\Tiket;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketType extends Model
{
    use HasFactory;

    protected $fillable = ['nama'];

    public function tickets()
    {
        return $this->hasMany(Tiket::class);
    }
}
