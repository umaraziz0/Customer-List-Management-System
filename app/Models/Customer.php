<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'phone',
        'email',
        'agent',
        'status',
    ];

    public function getAgentAttribute($value)
    {
        $agent = User::find($value);

        if ($agent) {
            return $agent->name;
        }

        return null;
    }
}
