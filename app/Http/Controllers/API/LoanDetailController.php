<?php

namespace App\Http\Controllers\API;

use Exception;
use Carbon\Carbon;
use App\Models\LoanDetail;
use Illuminate\Http\Request;
use App\Models\LoanPaymentDetail;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\API\ResponseController;

class LoanDetailController extends ResponseController
{
    public function loanStore(Request $request)
    {
        try {
            $validator = \Validator::make($request->all(), [
                'amount' => 'required',
                'loan_term' => 'required',
            ]);
            if ($validator->fails()) {
                return $this->_sendErrorResponse("Please enter field is required.", $validator->messages());
            }

            $isExist = LoanDetail::where('user_id',Auth::user()->id)->first();
            if($isExist){
                $data['loan_id'] = $isExist->id;
                $data['user_id'] = $isExist->user_id;
                return $this->_sendErrorResponse("Loan already applied by this user", $data);
            }

            $loan=new LoanDetail;
            $loan->user_id=Auth::user()->id;
            $loan->amount=$request->amount;
            $loan->paid_amount=0;
            $loan->loan_term=$request->loan_term;
            $loan->status=1;
            if($loan->save()){
                $data['loan_id'] = $loan->id;
                $data['user_id'] = $loan->user_id;
                return $this->_sendResponse("Loan details has been saved successfully", $data);
            }
            else{
                return $this->_sendErrorResponse("Something went wrong");
            }

        } catch (Exception $e) {
            return $this->_sendErrorResponse("Unable to process.", $e->getMessage(), 500);
        }
    }
    
    public function approvalLoanByAdmin(Request $request)
    {
        try {
            if(Auth::user()->role_id==1){
                $validator = \Validator::make($request->all(), [
                    'loan_id' => 'required',
                ]);
                if ($validator->fails()) {
                    return $this->_sendErrorResponse("Please enter field is required.", $validator->messages());
                }
                $loan=LoanDetail::where('id',$request->loan_id)->first();
                if(!$loan){
                    $data['loan_id'] = $request->loan_id;
                    return $this->_sendErrorResponse("No data found!", $data);
                }

                if($loan->status==1){
                    // if loan status(1) is pending then approved by admin only
                    LoanDetail::where('id',$request->loan_id)->update(['status'=>3]); // status(3) means approved
                    $data['loan_details'] = $loan;
                    return $this->_sendResponse("Your loan has been approved by admin", $data);
                }else{
                    return $this->_sendErrorResponse("Your loan already approved", $request->all(), 404);
                }
            }
            else{
                return $this->_sendErrorResponse("You have not permission, Only authorized person will change");
            }
        } catch (Exception $e) {
            return $this->_sendErrorResponse("Unable to process", $e->getMessage(), 500);
        }
    }

    public function paidLoan(Request $request)
    {
        try {
            $validator = \Validator::make($request->all(), [
                'loan_id' => 'required',
                'amount' => 'required|numeric'
            ]);
            if ($validator->fails()) {
                return $this->_sendErrorResponse("Please enter field is required.", $validator->messages());
            }
            $loan=LoanDetail::where('id',$request->loan_id)->where('user_id',Auth::user()->id)->first();

            if($loan->status==2){
                return $this->_sendErrorResponse("Your loan is paid", $request->all(), 404);
            }

            if($loan->status==3){
                if(!is_null($loan)){
                    if($loan->amount<=($request->amount+$loan->paid_amount)){
                        $loan->update(['status'=>2]);
                    }
                }
                $loan=LoanDetail::where('id',$request->loan_id)->where('user_id',Auth::user()->id)
                ->increment('paid_amount',$request->amount);
                $loan=new LoanPaymentDetail;
                $loan->user_id=Auth::user()->id;
                $loan->amount=$request->amount;
                $loan->loan_id=$request->loan_id;
                $loan->status=2;
                $loan->save();
                return $this->_sendResponse("Your loan has been paid successfully");
            }else{
                return $this->_sendErrorResponse("Sorry! your loan is not approved by our authorized team", $request->all(), 404);
            }
        } catch (Exception $e) {
            return $this->_sendErrorResponse("Unable to process.", $e->getMessage(), 500);
        }
    }

    public function showLoanDetails($loan_id=null)
    {
        try {
            if(is_null($loan_id)){
                $data=LoanDetail::with('payment_details')->where('user_id',Auth::user()->id)->get();
            }
            else{
                $data=LoanDetail::with('payment_details')->where('id',$loan_id)->where('user_id',Auth::user()->id)->first();            
            }
            return $this->_sendResponse("Your loan details & loan payment details",$data);
        } catch (Exception $e) {
            return $this->_sendErrorResponse("Unable to process.", $e->getMessage(), 500);
        }
    }

}