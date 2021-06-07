<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FollowUp extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        "agent_id",
        "customer_id",
        "title",
        "message",
        "customer_name"
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:m:s',
    ];

    public function getCustomerNameAttribute()
    {
        $customer = Customer::where("id", $this->customer_id)->first();

        return $customer->name;
    }
}
