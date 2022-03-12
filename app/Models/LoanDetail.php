<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LoanDetail extends Model
{
    use HasFactory, SoftDeletes;

    protected $table="loan_details";
    protected $fillable=['user_id','amount','loan_term','paid_amount','status'];
    protected $hidden=['deleted_at','updated_at'];

    public function payment_details()
    {
        return $this->hasMany(LoanPaymentDetail::class,'loan_id','id');
    }
}
