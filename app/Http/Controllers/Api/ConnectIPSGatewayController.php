<?php

namespace App\Http\Controllers\Api;

use App\Services\ConnectIpsService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
Use App\Services\EventService;
use App\Helpers\StringHelper;
use App\Models\EventRegistrationHold;
class ConnectIPSGatewayController extends Controller
{
    protected $connectIpsService;

    public function __construct(ConnectIpsService $connectIpsService, EventService $eventService)
    {
        $this->connectIpsService = $connectIpsService;
        $this->eventService = $eventService;
    }

    public function getTransactionDetails(Request $request)
    {
        $referenceId = $request->input('referenceId');
        $txnAmt = $request->input('txnAmt');
        $result = $this->connectIpsService->getTransactionDetail($referenceId, $txnAmt);

        return response()->json($result);
    }


    public function getPaymentValidation(Request $request)
    {

        $referenceId = $request->referenceId;
        $txnAmt = $request->txnAmt;
        $result = $this->connectIpsService->getPaymentValidation($referenceId, $txnAmt);

        return response()->json($result);
    }

    public function paymentInitialize(){


    }

    public function paymentConstants(){
        $fullUrl = "{$this->connectIpsService->baseUrl}/connectipswebgw/loginpage";
        $result = [
            'merchantid' => $this->connectIpsService->merchantId,
            'appid' => $this->connectIpsService->appId,
            'appname' => $this->connectIpsService->appName,
            'appname' => $this->connectIpsService->appName,
            'appname' => $this->connectIpsService->appName,
            'txncrncy' => 'NPR',
            'loginurl' => $fullUrl,

        ];
        return response()->json($result);
    }

    public function generateToken(Request $request){

        $string = "MERCHANTID=$request->merchantid,APPID=$request->appid,APPNAME=$request->appname,TXNID=$request->txnid,TXNDATE=$request->currentDate,TXNCRNCY=NPR,TXNAMT=$request->txnamt,REFERENCEID=$request->referenceid,REMARKS=$request->remarks,PARTICULARS=$request->particulars,TOKEN=TOKEN";
        return response()->json(['token'=>$this->eventService->generateHash($string)]);
    }

    public function generateTxnidReferenceId(){
        $txnid = StringHelper::generateUniqueRandomString(18, 'txnid', EventRegistrationHold::class);
        $referenceid = StringHelper::generateUniqueRandomString(18, 'referenceid', EventRegistrationHold::class);
        return response()->json([
            'txnid' => $txnid,
            'referenceid' => $referenceid
        ]);

    }


}
