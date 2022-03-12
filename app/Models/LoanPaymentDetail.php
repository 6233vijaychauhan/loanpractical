<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LoanPaymentDetail extends Model
{
    
    use HasFactory, SoftDeletes;

    protected $table="loan_payment_details";
    protected $hidden=['deleted_at','updated_at'];

    public function loan_details()
    {
        return $this->belongsTo(LoanDetail::class,'id','loan_id');
    }
}
