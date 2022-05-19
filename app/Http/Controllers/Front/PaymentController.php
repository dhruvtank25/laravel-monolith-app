<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use App\Repositories\AppointmentRepository;
use App\Repositories\MangoPayRepository;
use App\Repositories\TransactionRepository;
use App\Events\AppointmentBookedEvent;
use Carbon\Carbon;
use Auth;
use App\Helpers\FileUploadHelper;

class PaymentController extends Controller
{
    function __construct(UserRepository $userRepo, AppointmentRepository $appointmentRepo, MangoPayRepository $mangoPayRepo, TransactionRepository $transactionRepo)
    {
        $this->userRepo        = $userRepo;
        $this->appointmentRepo = $appointmentRepo;
        $this->mangoPayRepo    = $mangoPayRepo;
        $this->transactionRepo = $transactionRepo;
    }

    public function createCardRegistration(Request $request)
    {
        if(Auth::guard('user')->check())
            $user  = Auth::guard('user')->user();
        else if(Auth::guard('guest_user')->check())
            $user = Auth::guard('guest_user')->user();
        else
            return response()->json(['success'=>'false', 'message' => 'unauthorized request']);

        if($user->mango_user_id==NULL || $user->mango_user_id=='') {
            $user           = $this->mangoPayRepo->createNaturalUser($user);
        }
        $mango_user_id       = $user->mango_user_id;
        $card_type           = isset($request->card_type)?$request->card_type:'';
        $createdCardRegister = $this->mangoPayRepo->registerCard($mango_user_id, $card_type);
        $returnUrl           = url("/card-registration-response");
        return response()->json([
                                'success'       => 'true', 
                                'user'          => $user, 
                                'card_register' => $createdCardRegister,
                                'return_url'    => $returnUrl, 
                                'message'       => 'card request created'
                            ], 200);
    }

    public function cardRegistrationResponse(Request $request)
    {
        if(isset($request->data))
            return response()->json(['success' => 'true', 'registrationData' => $request->data], 200);
        else
            return response()->json(['success' => 'false', 'errorCode' => $request->errorCode], 200);
    }

    public function updateCardRegistration(Request $request)
    {
        if(Auth::guard('user')->check())
            $user  = Auth::guard('user')->user();
        else if(Auth::guard('guest_user')->check())
            $user = Auth::guard('guest_user')->user();
        else
            return response()->json(['success'=>'false', 'message' => 'unauthorized request']);
        $cardRegistrationId = $request->card_registration_id;
        $registrationData   = isset($request->registration_data)?$request->registration_data:'';
        $errorCode          = isset($request->errorCode)?$request->errorCode:'';
        $updateResult       = $this->mangoPayRepo->updateCardRegistration($cardRegistrationId, $registrationData, $errorCode);
        if($updateResult['status']==true) {
            return response()->json(['success' => 'true', 'message' => 'Card added successfully'], 200);
        } else {
            return response()->json(['success' => 'false', 'message' => $updateResult['message']], 200);
        }
    }

    public function bookingSummary($appointmentId, Request $request)
    {
        if(Auth::guard('user')->check())
            $user  = Auth::guard('user')->user();
        else if(Auth::guard('guest_user')->check())
            $user = Auth::guard('guest_user')->user();
        else
            abort(401);
        $title = 'Booking confirmation';
        $appointment = $this->appointmentRepo->get($appointmentId);
        if($appointment->user_id!=$user->id || $appointment->status!='payment pending')
            abort(401);
        return view('booking_summary', compact('title', 'appointment'));
    }

    public function initiateDirectCardPayIn(Request $request)
    {
        if(Auth::guard('user')->check())
            $user  = Auth::guard('user')->user();
        else if(Auth::guard('guest_user')->check())
            $user = Auth::guard('guest_user')->user();
        else
            return response()->json(['success'=>'false', 'message' => 'unauthorized request']);
        
        $mango_user_id  = $user->mango_user_id;
        
        // Get appointment
        if(isset($request->appointment_id)) {
            $appointment_id = $request->appointment_id;
            $appointment    = $this->appointmentRepo->getForPayment($appointment_id, $user->id);
            if(!isset($appointment))
                return response()->json(['success'=>'false', 'message' => 'Invalid request'], 200);
        }

        // Make direct payment request
        $response       = $this->mangoPayRepo->directCardPayIn($appointment, $mango_user_id);
        $transaction_id = isset($response['transaction_id'])?$response['transaction_id']:'';

        $ajax_response = [
                            'message'        => $response['message'], 
                            'transaction_id' => $transaction_id,
                            'result_url'     => route('payment-result', ['transactionId'=>$transaction_id])
                        ];
        if($response['status'] == true && $transaction_id!='') {
            if($response['result_status']=='SUCCEEDED') {
                $appointment_status             = 'scheduled';
                $ajax_response['success']       = 'true';
            } else if(isset($response['redirect_url'])) {
                $appointment_status             = 'payment processing';
                $ajax_response['success']       = 'false';
                $ajax_response['redirect_url']  = $response['redirect_url'];
            }
            else {
                $appointment_status                 = 'payment failed';
                $ajax_response['success']           = 'false';
            }
        } else {
            $appointment_status                 = 'payment failed';
            $ajax_response['success']           = 'false';
        }

        // Update appointment status
        if(isset($appointment)) {
            $appointment->status = $appointment_status;
            $appointment->save();
            
            if($appointment_status=='scheduled') {
                $trans_record = $this->transactionRepo->get($response['trans_id']);
                // Fire Appointment Booked Event
                //event(new AppointmentBookedEvent($appointment, $trans_record));
            }
        }
        return response()->json($ajax_response, 200);
    }

    public function paymentResult(Request $request)
    {
        $inputs        = $request->all();
        if(!isset($request->transactionId) || $request->transactionId=='')
            abort(404);
        $transactionId = $request->transactionId;
        $transaction   = $this->mangoPayRepo->getPayIn($transactionId);
        if($transaction->Status=='SUCCEEDED' || $transaction->Status=='FAILED') {
            // Get transaction and appointment record from DB to update status
            $trans_record = $this->transactionRepo->getByTransactionId($transaction->Id, $transaction->Type);
                    
            $appointment  = $trans_record->appointment;
            if(!isset($trans_record) || !isset($appointment))
                abort(403);

            // Update transaction Table
            $trans_record->status           = $transaction->Status;
            $trans_record->result_code      = $transaction->ResultCode;
            $trans_record->result_message   = $transaction->ResultMessage;
            $trans_record->payment_response = json_encode($transaction);
            $trans_record->save();

            if($transaction->Status=='SUCCEEDED') {
                // Update transaction status as per success
                $appointment->status = 'scheduled';
                $appointment->save();

                // Fire Appointment Booked Event
                event(new AppointmentBookedEvent($appointment, $trans_record));

                // Show Payment Success Page
                return redirect()->route('payment-success', ['appointment_id'=>$appointment->id]);
            } else if($transaction->Status=='FAILED') {
                // Update transaction status as per failed
                $appointment->status = 'payment failed';
                $appointment->save();

                // Show Payment Failed Page
                return redirect()->route('payment-failed', ['transaction_id'=>$transactionId]);
            }
        } else {
            abort(403);
        }
    }

    public function paymentSuccess(Request $request)
    {
        return view('booking_success');
    }

    public function paymentFailed(Request $request)
    {
        $transactionId = $request->transaction_id;
        $transaction   = $this->transactionRepo->getByTransactionId($transactionId);
        return view('booking_failed', compact('transaction'));
    }

    public function intiatehook(Request $request)
    {
        $type     = $request->type;
        $coach_id = $request->coach_id;
        $coach    = $this->userRepo->get($coach_id);
                
        if($type=='kyc') {
            if($coach->kyc_status!='asked') {
                echo 'Coach current kyc status is '.$coach->kyc_status;
                exit;
            }
            $kyc_details = json_decode($coach->kyc_message);
            $resource_id = '';
            foreach ($kyc_details as $kyc_detail) {
                if($kyc_detail->status=='VALIDATION_ASKED') {
                    $resource_id = $kyc_detail->id;
                    break;
                }
            }
            if($resource_id)
                return redirect('mangopay-hook?EventType=KYC_SUCCEEDED&RessourceId='.$resource_id);
            else
                echo "nothing to check for!";
        } else if($type='ubo'){
            if($coach->ubo_status!='asked') {
                echo 'Coach current Ubo status is '.$coach->ubo_status;
                exit;
            }
            $ubo_details = json_decode($coach->ubo_message);
            if($ubo_details->id)
                return redirect('mangopay-hook?EventType=UBO_DECLARATION_VALIDATED&RessourceId='.$ubo_details->id);
            else
                echo "nothing to check for!";
        }
    }

    public function hook(Request $request)
    {
        $event_type  = $request->EventType;
        if(isset($request->RessourceId))
            $resource_id = $request->RessourceId;
        else if(isset($request->ResourceId))
            $resource_id = $request->ResourceId;
        else
            return response()->json(['message' => 'Resource does not exists'], 200);
        $event_on    = $request->Date; // Timestamp ex: 1572998400
        switch ($event_type) {
            case 'PAYOUT_NORMAL_SUCCEEDED':
            case 'PAYOUT_NORMAL_FAILED':
            case 'PAYOUT_REFUND_SUCCEEDED':
                $this->mangoPayRepo->updatePayOutStatus($resource_id);
                break;

            case 'KYC_SUCCEEDED':
            case 'KYC_FAILED':
                $document = $this->mangoPayRepo->getKycDetail($resource_id);
                $coach    = $this->userRepo->getByMangoId($document->UserId);
                if(!$coach)
                    return response()->json(['message' => 'Coach not found'], 200);
                $kyc_details = json_decode($coach->kyc_message);
                $kyc_status = '';
                foreach ($kyc_details as $kyc_detail) {
                    if($kyc_detail->id==$resource_id) {
                        $kyc_detail->status         = $document->Status;
                        $kyc_detail->reason_type    = $document->RefusedReasonType;
                        $kyc_detail->reason_message = $document->RefusedReasonMessage;

                        // Delete documents from our system
                        if($event_type=='KYC_SUCCEEDED') {
                            switch ($kyc_detail->type) {
                                case 'IDENTITY_PROOF':
                                    $delete_path = FileUploadHelper::getFilePath('id_doc');
                                    break;

                                case 'REGISTRATION_PROOF':
                                    $delete_path = FileUploadHelper::getFilePath('ustid_doc');
                                    break;

                                case 'ARTICLES_OF_ASSOCIATION':
                                    $delete_path = FileUploadHelper::getFilePath('commercial_doc');
                                    break;
                                
                                default:
                                    $delete_path = '';
                                    break;
                            }
                            if($delete_path!='') {
                                $delete_path .= $kyc_detail->file_name;
                                FileUploadHelper::deleteFile($delete_path);
                            }
                        }
                    }
                    if($kyc_status!='failed') {
                        if($kyc_detail->status=='REFUSED')
                            $kyc_status = 'failed';
                        else if($kyc_detail->status=='VALIDATION_ASKED')
                            $kyc_status = 'asked';
                        else if($kyc_detail->status=='VALIDATED' && $kyc_status =='')
                            $kyc_status = 'validated';
                    }
                }
                $coach->kyc_message = json_encode($kyc_details);
                if($kyc_status!='') {
                    $coach->kyc_status  = $kyc_status;
                    if($kyc_status=='validated' && $coach->status=='kyc pending') {
                        if($coach->person_type=='business')
                            $coach->status = 'ubo pending';
                        else
                            $coach->status = 'approval';
                    }
                }
                $coach->save();
                echo "<pre>";
                print_r($document);
                echo "</pre>";
                break;

            case 'UBO_DECLARATION_VALIDATED':
            case 'UBO_DECLARATION_INCOMPLETE':
            case 'UBO_DECLARATION_REFUSED':
                $coach = $this->userRepo->getByUBODeclarationId($resource_id);
                if(!$coach)
                    return response()->json(['message' => 'Coach not found'], 200);
                $ubo_declaration = $this->mangoPayRepo->getUBODeclaration($coach->mango_user_id, $resource_id);
                if(!$ubo_declaration)
                    return response()->json(['message' => 'UBO not found'], 200);
                if($ubo_declaration->Status=='VALIDATED') {
                    $coach->ubo_status = 'validated';
                    if($coach->status=='ubo pending')
                        $coach->status = 'approval';
                } else if($ubo_declaration->Status=='INCOMPLETE') {
                    $coach->ubo_status = 'incomplete';
                } else {
                    $coach->ubo_status = 'refused';
                }
                // Update ubo declaration details
                $ubo_details = json_decode($coach->ubo_message);
                $ubo_details->reason_type = $ubo_declaration->Reason;
                $ubo_details->reason_message = $ubo_declaration->Message;
                $coach->ubo_message = json_encode($ubo_details);
                $coach->save();
                echo "<pre>";
                print_r($ubo_declaration);
                echo "</pre>";
                break;

            default:
                break;
        }
    }

}