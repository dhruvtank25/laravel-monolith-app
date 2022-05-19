<?php

namespace App\Repositories;

use MangoPay\MangoPayApi;
use App\Repositories\TransactionRepository;

class MangoPayRepository
{

    protected $mangopay;

    function __construct(MangoPayApi $mangopay, TransactionRepository $transactionRepo)
    {
        $this->mangopay        = $mangopay;
        $this->transactionRepo = $transactionRepo;
    }

    public function newInstance()
    {
        return $this->mangopay;
    }

    /**
     * Get MangoPay User
     * @param  Integer                $mango_user_id
     * @return UserLegal|UserNatural  User object returned from API
     */
    public function getUser($mango_user_id)
    {
        return $this->mangopay->Users->Get($mango_user_id);
    }

    /**
     * Create MangoPay Natural User
     * @param  App\Models\User  User to create MangoUser account for
     * @return App\Models\User  Updated User object
     */
    public function createNaturalUser($user)
    {
        $user_role = $user->roles->name;
        if($user_role=='user' || $user_role=='guest' || !$user->is_commercial) {
            $mangoUser = new \MangoPay\UserNatural();
            //$mangoUser->PersonType          = "NATURAL";
            $mangoUser->Email               = $user->email;
            $mangoUser->FirstName           = $user->first_name;
            $mangoUser->LastName            = $user->last_name;
            $mangoUser->Birthday            = $user->birth_date->timestamp;
            $mangoUser->Nationality         = $user->nationality;
            $mangoUser->CountryOfResidence  = $user->country_code;
        } else {
            $mangoUser = new \MangoPay\UserLegal();
            $mangoUser->Name                        = $user->coach_company;
            $mangoUser->LegalPersonType             = strtoupper($user->person_type);
            $mangoUser->Email                       = $user->email;
            $mangoUser->LegalRepresentativeFirstName= $user->first_name;
            $mangoUser->LegalRepresentativeLastName = $user->last_name;
            $mangoUser->LegalRepresentativeBirthday = $user->birth_date->timestamp;
            $mangoUser->LegalRepresentativeNationality  = $user->nationality;
            $mangoUser->LegalRepresentativeCountryOfResidence = $user->country_code;

            // Address
            $headquarterAddress = new \MangoPay\Address;
            $headquarterAddress->AddressLine1 = $user->work_street?$user->work_street:$user->street;
            //$headquarterAddress->AddressLine2 = '';
            $headquarterAddress->City = $user->work_place?$user->work_place:$user->place;
            $headquarterAddress->Region = $user->work_place?$user->work_place:$user->place;
            $headquarterAddress->PostalCode = $user->work_post_code?$user->work_post_code:$user->post_code;
            $headquarterAddress->Country = $user->work_country_code?$user->work_country_code:$user->country_code;
            $mangoUser->HeadquartersAddress = $headquarterAddress;

            if($user->person_type=='business')
                $mangoUser->CompanyNumber = $user->company_number;
        }
        $mangoUser = $this->mangopay->Users->Create($mangoUser);

        $mangouser_id = $mangoUser->Id;
        // Create new wallet for new user
        $new_wallet   = $this->createWallet($mangouser_id);

        // Save MangoUserId and WalletId to Application DB
        $user->mango_user_id   = $mangouser_id;
        $user->mango_wallet_id = $new_wallet->Id;
        $user->save();
        return $user;
    }

    /**
     * Update MangoPay Natural User
     * @param  App\Models\User  User to create MangoUser account for
     * @return App\Models\User  Updated User object
     */
    public function updateNaturalUser($user)
    {
        if(!isset($user->mango_user_id) || is_null($user->mango_user_id))
            return $this->createNaturalUser($user);
                
        $user_role = $user->roles->name;
        $mango_user_id                  = $user->mango_user_id;
        $mangoUser                      = $this->getUser($mango_user_id);
        if($user_role=='user' || $user_role=='guest' || !$user->is_commercial) {
            // $mangoUser->PersonType          = "NATURAL";
            $mangoUser->Email               = $user->email;
            $mangoUser->FirstName           = $user->first_name;
            $mangoUser->LastName            = $user->last_name;
            $mangoUser->Birthday            = $user->birth_date->timestamp;
            $mangoUser->Nationality         = $user->nationality;
            $mangoUser->CountryOfResidence  = $user->country_code;
        } else {
            $mangoUser->Name                        = $user->coach_company;
            $mangoUser->LegalPersonType             = strtoupper($user->person_type);
            $mangoUser->Email                       = $user->email;
            $mangoUser->LegalRepresentativeEmail    = $user->email;
            $mangoUser->LegalRepresentativeFirstName= $user->first_name;
            $mangoUser->LegalRepresentativeLastName = $user->last_name;
            $mangoUser->LegalRepresentativeBirthday = $user->birth_date->timestamp;
            $mangoUser->LegalRepresentativeNationality  = $user->nationality;
            $mangoUser->LegalRepresentativeCountryOfResidence = $user->country_code;

            // Legal Representative Address
            $representativeAddress = new \MangoPay\Address;
            $representativeAddress->AddressLine1 = $user->street;
            //$representativeAddress->AddressLine2 = '';
            $representativeAddress->City = $user->place;
            $representativeAddress->Region = $user->place;
            $representativeAddress->PostalCode = $user->post_code;
            $representativeAddress->Country = $user->country_code;
            $mangoUser->LegalRepresentativeAddress = $representativeAddress;

            // Headquarter Address
            $headquarterAddress = new \MangoPay\Address;
            $headquarterAddress->AddressLine1 = $user->work_street?$user->work_street:$user->street;
            //$headquarterAddress->AddressLine2 = '';
            $headquarterAddress->City = $user->work_place?$user->work_place:$user->place;
            $headquarterAddress->Region = $user->work_place?$user->work_place:$user->place;
            $headquarterAddress->PostalCode = $user->work_post_code?$user->work_post_code:$user->post_code;
            $headquarterAddress->Country = $user->work_country_code?$user->work_country_code:$user->country_code;
            $mangoUser->HeadquartersAddress = $headquarterAddress;

            if($user->person_type=='business')
                $mangoUser->CompanyNumber = $user->company_number;
        }
        $this->mangopay->Users->Update($mangoUser);
        return $user;
    }

    public function getUserActiveWallet($mango_user_id, $user='')
    {
        // Get the latest record only
        $pagination = new \MangoPay\Pagination(1, 1);
        $sorting = new \MangoPay\Sorting();
        $sorting->AddField("CreationDate", \MangoPay\SortDirection::DESC);
        $wallets   = $this->mangopay->Users->GetWallets($mango_user_id, $pagination, $sorting);
        if(count($wallets)>0)
            $wallet = $wallets[0];
        else
            $wallet = $this->createWallet($mango_user_id);
        // Set wallet id in application db if not set already
        if($user!='') {
            $user->mango_wallet_id = $wallet->Id;
            $user->save();
        }
        return $wallet;
    }

    /**
     * Get card
     * @param string $wallet_id    Wallet identifier
     * @return \MangoPay\Wallet object returned from API
     */
    public function getWallet($wallet_id)
    {
        return $this->mangopay->Wallets->get($wallet_id);
    }

    /**
     * Create new wallet
     * @param  Integer    $mango_user_id
     * @return \MangoPay\Wallet Wallet object returned from API
     */
    public function createWallet($mango_user_id)
    {
        $wallet              = new \MangoPay\Wallet();
        $wallet->Owners      = array( $mango_user_id );
        $wallet->Currency    = 'EUR';
        $wallet->Description = 'Wallet to hold user money untill session gets completed';
        return $this->mangopay->Wallets->Create($wallet);
    }

    /**
     * Create new card registration
     * @param Integer                      $mango_user_id
     * @return \MangoPay\CardRegistration  Card registration object returned from API
     */
    public function registerCard($mango_user_id, $card_type='CB_VISA_MASTERCARD')
    {
        $cardRegister           = new \MangoPay\CardRegistration();
        $cardRegister->UserId   = $mango_user_id;
        $cardRegister->Currency = "EUR";
        $cardRegister->CardType = $card_type;
        return $this->mangopay->CardRegistrations->Create($cardRegister);
    }

    /**
     * Update card registration
     * @param Integer  Card registration Id to be update
     * @param String   Card registration data to be set
     * @param Integer  Errorcode Incase of card registration failed
     * @return Array
     */
    public function updateCardRegistration($registrationId, $registrationData='', $errorCode='')
    {
        $cardRegister = $this->mangopay->CardRegistrations->Get($registrationId);
        $cardRegister->RegistrationData = isset($registrationData) ? 'data=' . $registrationData : 'errorCode=' . $errorCode;
        $updatedCardRegister = $this->mangopay->CardRegistrations->Update($cardRegister);
        if ($updatedCardRegister->Status != 'VALIDATED' || !isset($updatedCardRegister->CardId))
            return ['status' => false, 'message' => 'Cannot create card.'];

        // Disable All cards for user other than this newly created card
        $this->disableAllCard($updatedCardRegister->UserId, $updatedCardRegister->CardId);
                
        return ['status' => true, 'card_id' => $updatedCardRegister->CardId];     
    }

    /**
     * Transfer fund from card to wallet
     * @param  Integer  $mango_user_id
     * @param  integer  $amount  Last two digits represents value after decimal (111=1.11EUR)
     * @return Array
     */
    public function directCardPayIn($appointment, $mango_user_id)
    {
        $amount = $appointment->amount<1?1:$appointment->amount;
        $amount = $this->convertAmount($amount);

        // Get User Card to charge
        $card    = $this->getUserActiveCard($mango_user_id);
        if(!$card)
            return ['status' => false, 'message'=> 'No active card available for user'];

        // Get User Wallet to save money in
        $wallet    = $this->getUserActiveWallet($mango_user_id);
        $wallet_id = $wallet->Id;

        // create pay-in CARD DIRECT
        $payIn = new \MangoPay\PayIn();
        $payIn->CreditedWalletId         = $wallet_id;
        $payIn->AuthorId                 = $mango_user_id;

        $payIn->DebitedFunds             = new \MangoPay\Money();
        $payIn->DebitedFunds->Amount     = $amount;
        $payIn->DebitedFunds->Currency   = 'EUR';

        $payIn->Fees = new \MangoPay\Money();
        $payIn->Fees->Amount             = 0;
        $payIn->Fees->Currency           = 'EUR';

        // payment type as CARD
        $payIn->PaymentDetails = new \MangoPay\PayInPaymentDetailsCard();
        $payIn->PaymentDetails->CardType = $card->CardType;

        // execution type as DIRECT
        $payIn->ExecutionDetails = new \MangoPay\PayInExecutionDetailsDirect();
        $payIn->ExecutionDetails->CardId              = $card->Id;
        $payIn->ExecutionDetails->SecureModeReturnURL = route('payment-result');

        // create Pay-In
        $createdPayIn = $this->mangopay->PayIns->Create($payIn);

        // Save transaction
        $trans_id = $this->transactionRepo->saveTransaction($appointment, $createdPayIn);

        $response_arr = [
                            'status'         => true,
                            'result_status'  => $createdPayIn->Status,
                            'is_securemode'  => $createdPayIn->ExecutionDetails->SecureModeNeeded,
                            'mango_user_id'  => $mango_user_id,
                            'result_code'    => $createdPayIn->ResultCode,
                            'message'        => 'Payment successfull',
                            'trans_id'       => $trans_id,
                            'transaction_id' => $createdPayIn->Id,
                            'created_payIn'  => $createdPayIn,
                        ];
        // if created Pay-in object has status SUCCEEDED it's mean that all is fine
        if ($createdPayIn->Status == 'SUCCEEDED') {
            $response_arr['message'] = 'Payment successfull';
        }
        else if($createdPayIn->Status=='CREATED' && $createdPayIn->ExecutionDetails->SecureModeNeeded==1) {
            // If created Pay-in object require 3D-Secure Validation,
            // Return Bank's secure redirect url
            $response_arr['message']      = '3D-SecureMode Validation required.';
            $response_arr['redirect_url'] = $createdPayIn->ExecutionDetails->SecureModeRedirectURL;
        }
        else {
            // if created Pay-in object has status different than SUCCEEDED 
            // that occurred error and display error message
            $response_arr['message'] = 'Payment failed.';
        }
        return $response_arr;
    }

    /**
     * Get pay-in object
     * @param string $payInId Pay-in identifier
     * @return \MangoPay\PayIn Object returned from API
     */
    public function getPayIn($payInId)
    {
        return $this->mangopay->PayIns->Get($payInId);
    }

    /**
     * Full/Partial PayIn Refund
     * @param  Integer $appointment  Appointment that got cancelled
     * @return Array                 Refund status
     */
    public function payInRefund($appointment)
    {
        if(!$appointment->isRefundableAttribute())
            return ['status' => false, 'message' => 'invalid request'];
        
        $mango_user_id = $appointment->user->mango_user_id;
        if(!$mango_user_id)
            return ['status' => false, 'message' => 'Invalid user Id!.'];

        $trans = $this->transactionRepo->getAppmntTrans($appointment->id, 'PAYIN');
        if(!$trans || !$trans->transaction_id)
            return ['status' => false, 'message' => 'No PAYIN transaction found'];
        $coach_cal     = $appointment->getCostCalculationAttribute();
        $return_amount = $coach_cal['user_return_gross'];

        $PayInId = $trans->transaction_id;
        
        $Refund  = new \MangoPay\Refund();
        $Refund->AuthorId = $mango_user_id;

        $Refund->DebitedFunds = new \MangoPay\Money();
        $Refund->DebitedFunds->Currency = "EUR";
        //$Refund->DebitedFunds->Amount   = $this->convertAmount($appointment->amount);
        $Refund->DebitedFunds->Amount   = $this->convertAmount($return_amount);

        $Refund->Fees = new \MangoPay\Money();
        $Refund->Fees->Currency = "EUR";
        //$Refund->Fees->Amount   = $this->convertAmount($appointment->cancel_fee);
        $Refund->Fees->Amount   = 0;
        
        $refund = $this->mangopay->PayIns->CreateRefund($PayInId, $Refund);
        try {
            // Save transaction
            $this->transactionRepo->saveTransaction($appointment, $refund);
        } catch (\Exception $e) {
                        
        }

        // Update Appointment
        $appointment->cancel_fee = $coach_cal['coach_return_gross'];
        if($refund->Status=="Success") {
            $appointment->refund_status = 'paid';
            $response_arr  = ['status' => true, 'message' => 'Amount Refunded succesfully'];
        } else if($refund->Status=="FAILED") {
            $appointment->refund_status = 'failed';
            $response_arr  = ['status' => false, 'message' => $refund->ResultCode.':'.$refund->ResultMessage];
        } else {
            $appointment->refund_status = 'processing';
            $response_arr  = ['status' => false, 'message' => 'Something unexpected happened, please check mangoPay for details'];
        }
        $appointment->save();
        return $response_arr;
    }

    public function makeTransfer($appointment)
    {
        // Check if appointment is eligible for this transaction
        if(!$appointment->isTransferrableAttribute()) {
            if($appointment->isPayableAttribute())
                $this->payOut($appointment);
            return true;
        }

        // Get User and coach wallets
        $user          = $appointment->user;
        $coach         = $appointment->coach;
        $mango_user_id = $user->mango_user_id;

        if(!isset($user->mango_wallet_id))
            $this->getUserActiveWallet($mango_user_id, $user);
        if(!isset($coach->mango_wallet_id))
            $this->getUserActiveWallet($coach->mango_user_id, $coach);

        // Get appointment costs
        $coach_cal    = $appointment->getCostCalculationAttribute();
        if($coach_cal['coach_credit_per']>0) {
            $total_amount = (float) $coach_cal['coach_return_gross'];
            $commision    = (float) $coach_cal['gross_return_commission'];
        } else {
            $total_amount = (float) $coach_cal['gross_cost']; // cost to debit user
            $commision    = (float) $coach_cal['gross_commission']; // system commision
        }

        // Create Transfer object
        $Transfer = new \MangoPay\Transfer();
        $Transfer->AuthorId = $mango_user_id;
        
        // Debit amount
        $Transfer->DebitedFunds = new \MangoPay\Money();
        $Transfer->DebitedFunds->Currency = "EUR";
        $Transfer->DebitedFunds->Amount = $this->convertAmount($total_amount);
        
        // Commision (this will go to our platform)
        $Transfer->Fees = new \MangoPay\Money();
        $Transfer->Fees->Currency = "EUR";
        $Transfer->Fees->Amount   = $this->convertAmount($commision);

        // Wallet to debit and credit IN.
        $Transfer->DebitedWalletID  = $user->mango_wallet_id;
        $Transfer->CreditedWalletId = $coach->mango_wallet_id;
       
        $transfer = $this->mangopay->Transfers->Create($Transfer);
        try {
            // Save transaction
            $this->transactionRepo->saveTransaction($appointment, $transfer);
        } catch (\Exception $e) {
                        
        }

        // Update Appointment
        if($transfer->Status == 'SUCCEEDED') {
            $appointment->fee_percent     = $coach_cal['commission_percent'];
            $appointment->fee             = $commision;
            $appointment->coach_credited  = $total_amount-$commision;
            $appointment->save();
            // PayOut to Coach
            $this->payOut($appointment);
        }

        return $transfer->Status == 'SUCCEEDED'?true:false;
    }

    public function payOut($appointment)
    {
        $coach         = $appointment->coach;
        $mango_user_id = $coach->mango_user_id;

        $PayOut = new \MangoPay\PayOut();
        $PayOut->AuthorId = $mango_user_id;
        $PayOut->DebitedWalletID = $coach->mango_wallet_id;
        
        $PayOut->DebitedFunds = new \MangoPay\Money();
        $PayOut->DebitedFunds->Currency = "EUR";
        $PayOut->DebitedFunds->Amount = $this->convertAmount($appointment->coach_credited);
        //$PayOut->DebitedFunds->Amount   = 100;
        
        $PayOut->Fees = new \MangoPay\Money();
        $PayOut->Fees->Currency = "EUR";
        $PayOut->Fees->Amount   = 0;

        $PayOut->PaymentType    = "BANK_WIRE";
        
        $PayOut->MeanOfPaymentDetails = new \MangoPay\PayOutPaymentDetailsBankWire();
        $PayOut->MeanOfPaymentDetails->BankAccountId = $coach->bank_account_id;
        
        $result = $this->mangopay->PayOuts->Create($PayOut);

        // Update Appointment
        switch ($result->Status) {
            case 'SUCCEEDED':
                $payout_status  = 'paid';
                break;
            case 'FAILED':
                $payout_status  = 'failed';
                break;
            case 'CREATED':
            default:
                $payout_status  = 'processing';
                break;
        }
        $appointment->payout_status       = $payout_status;
        if(isset($result->ResultMessage))
            $appointment->payout_message  = $result->ResultMessage;
        else
            $appointment->payout_message  = NULL;
        $appointment->save();

        try {
            // Save transaction
            $this->transactionRepo->saveTransaction($appointment, $result);
        } catch (\Exception $e) {
                        
        }
        return $result->Status;
    }

    public function updatePayOutStatus($payoutId)
    {
        $payout = $this->mangopay->PayOuts->get($payoutId);
        // Get and update transaction with this payout id
        $transaction = $this->transactionRepo->getByTransactionId($payoutId, 'PAYOUT');
        $transaction->status           = $payout->Status;
        $transaction->payment_response = json_encode($payout);
        $transaction->save();

        // Get Appointment from transaction
        $appointment = $transaction->appointment;
        // Update appointment payout status
        switch ($payout->Status) {
            case 'SUCCEEDED':
                $payout_status  = 'paid';
                break;
            case 'FAILED':
                $payout_status  = 'failed';
                break;
            case 'CREATED':
            default:
                $payout_status  = 'processing';
                break;
        }
        $appointment->payout_status       = $payout_status;
        if(isset($result->ResultMessage))
            $appointment->payout_message  = $payout->ResultMessage;
        else
            $appointment->payout_message  = NULL;
        $appointment->save();
    }

    /**
     * Returns Currently active card for a User
     * @param  string $mango_user_id MangoUser Id
     * @return \MangoPay\Card        object returned from API
     */
    public function getUserActiveCard($mango_user_id)
    {
        // Get the latest record first
        $pagination = new \MangoPay\Pagination(1, 10);
        $sorting = new \MangoPay\Sorting();
        $sorting->AddField("CreationDate", \MangoPay\SortDirection::DESC);
        
        $this_card  = false;
        try {
            $cards = $this->mangopay->Users->GetCards($mango_user_id, $pagination, $sorting);
            foreach ($cards as $card) {
                if($card->Active) {
                    $this_card = $card;
                    break;
                }
            }
            return $this_card;
        } catch (\MangoPay\Libraries\ResponseException $e) {
            return $this_card;
        }
    }

    /**
     * Get card
     * @param string $cardId    Card identifier
     * @return \MangoPay\Card   object returned from API
     */
    public function getCard($card_id)
    {
        return $this->mangopay->Cards->Get($card_id);
    }

    /**
     * Create new wallet
     * @param  Integer    $mango_user_id   MangoUser Id
     * @param  Integer    $ignore_id?      Id of card that should not be blocked
     * @return Boolean
     */
    public function disableAllCard($mango_user_id, $ignore_id='')
    {
        // Get the latest record first
        $pagination = new \MangoPay\Pagination(1, 10);
        $sorting = new \MangoPay\Sorting();
        $sorting->AddField("CreationDate", \MangoPay\SortDirection::DESC);
        $cards = $this->mangopay->Users->GetCards($mango_user_id, $pagination, $sorting);
        foreach ($cards as $card) {
            if($card->Id==$ignore_id || !$card->Active)
                continue;
            // Disable all other cards
            $card->Active = false;
            $this->mangopay->Cards->Update($card);
        }
        return true;
    }

    /**
     * Get bank account for user
     * @param string $userId         Mango User Id
     * @param int    $bankAccountId  Bank account Id
     * @return \MangoPay\BankAccount Entity of bank account object
     */
    public function getBankAccount($mango_user_id, $bankAccountId)
    {
        return $this->mangopay->Users->GetBankAccount($mango_user_id, $bankAccountId);
    }

    /**
     * Create new bank account
     * @param  App\Models\User  $user
     * @return App\Models\User          Updated user object
     */
    public function createBankAccount($user)
    {
        $mango_user_id = $user->mango_user_id;
        if($mango_user_id=='' || $mango_user_id==NULL) {
            $user = $this->createNaturalUser($user);
            $mango_user_id = $user->mango_user_id;
        }

        // Update bank account if already created
        if($user->bank_account_id!='' && $user->bank_account_id!=NULL){
            // Check if bank details has changed
            $bank_account  = $this->getBankAccount($mango_user_id, $user->bank_account_id);
            $bank_details  = $bank_account->Details;
                    
            if($bank_details->IBAN==$user->iban && $bank_details->BIC==$user->bic && $bank_account->Active)
                return ['status'=>true, 'user' => $user];
            // Deactivate previous bank account
            $this->deactivateBankAccount($mango_user_id, $bank_account);
        }

        $bank_account = new \MangoPay\BankAccount();
        // Owner Address
        $bank_account->OwnerAddress = new \MangoPay\Address();
        $bank_account->OwnerAddress->AddressLine1 = $user->street;
        $bank_account->OwnerAddress->City         = $user->place;
        //$bank_account->OwnerAddress->Region       = "Berlin";
        $bank_account->OwnerAddress->PostalCode   = $user->post_code;
        $bank_account->OwnerAddress->Country      = $user->country_code;
        // Owner Name
        $bank_account->OwnerName                  = $user->owner_name;

        // Bank Details
        $details = new \MangoPay\BankAccountDetailsIBAN();
        $details->IBAN = $user->iban;
        $details->BIC  = $user->bic;
        $bank_account->Details                    = $details;
        
        try {
            $bank_account = $this->mangopay->Users->CreateBankAccount($mango_user_id, $bank_account);
            $user->bank_account_id = $bank_account->Id;
            $user->save();
            return ['status'=>true, 'user' => $user];
        } catch (\MangoPay\Libraries\ResponseException $e) {
            return ['status'=> false, 'message' => $e->getMessage(), 'errors' => $e->GetErrorDetails()];
        }
    }

    /**
     * Update bank account
     * @param string $userId         Mango User Id
     * @return App\Models\User       Updated user object
     */
    public function deactivateBankAccount($mango_user_id, $bank_account)
    {
        $bank_account->Active = 0;
        $bank_account = $this->mangopay->Users->UpdateBankAccount($mango_user_id, $bank_account);
        return true;
    }

    /**
     * Get Kyc Document Details
     * @param  Integer                  $documentID
     * @return \MangoPay\KycDocument
     */
    public function getKycDetail($documentID)
    {
        return $this->mangopay->KycDocuments->Get($documentID);
    }

    /**
     * Apply for kyc validation
     * @param  string $userId            Mango User Id
     * @return \MangoPay\KycDocument[]   Array with KYC documents entities
     */
    public function getUserKyc($mango_user_id, $status='')
    {
        // Get the latest record first
        $pagination = new \MangoPay\Pagination(1, 10);

        $sorting = new \MangoPay\Sorting();
        $sorting->AddField("CreationDate", \MangoPay\SortDirection::DESC);

        $filter = new \MangoPay\FilterKycDocuments();
        $filter->Type   = 'IDENTITY_PROOF';
        if($status!='')
            $filter->Status = $status; // CREATED, VALIDATION_ASKED, VALIDATED, REFUSED

        return $this->mangopay->Users->GetKycDocuments($mango_user_id, $pagination, $sorting, $filter);
    }
    
    /**
     * Apply for kyc validation
     * @param  Integer $mango_user_id  user id as on mongopay
     * @param  String  $file_names     comma separated list of file names
     * @param  String  $file_type      type of files
     * @param  String  $document_type  type of KYC Document as per mongopay lists
     * @return Array                   Array with result status and message
     */
    public function applyKyc($mango_user_id, $file_names, $file_type, $document_type)
    {
        // Get Id card document url
        $file_urls    = \App\Helpers\FileUploadHelper::getMultipleDocPath($file_names, $file_type);
        $file_urls    = explode(',', $file_urls);
                
        $file_string_arr = array();
        foreach ($file_urls as $file_url) {
            $file_headers = get_headers($file_url);
            if(!$file_headers || $file_headers[0] == 'HTTP/1.1 404 Not Found' ||trim($file_headers[0]) == 'HTTP/1.1 403 Forbidden')
                return ['status'=> false, 'message'=>'Kyc Document does not exists!'];
            // Get Document as base64 encoded string
            $file_string_arr[] = base64_encode(file_get_contents($file_url));
        }

        if(count($file_string_arr)==0)
            return ['status' => false, 'message' => 'No Documents available'];

        // Create kyc document
        $kycDocument = new \MangoPay\KycDocument();
        $kycDocument->Type = $document_type;
        try {
            $kycDocument = $this->mangopay->Users->CreateKycDocument($mango_user_id, $kycDocument);
        } catch (\Exception $e) {
            return ['status'=> false, 'message' => $e->getMessage()];
        }

        // Create kyc page
        foreach ($file_string_arr as $file_string) {
            $kycPage = new \MangoPay\KycPage();
            $kycPage->File = $file_string;
            try {
                $result  = $this->mangopay->Users->CreateKycPage($mango_user_id, $kycDocument->Id, $kycPage);
                if(!$result)
                    return ['status'=> false, 'message' => 'Kyc document upload failed while adding a new page!'];
            } catch (\MangoPay\Libraries\ResponseException $e) {
                return ['status'=> false, 'message' => $e->getMessage()];
            }
        }
        
        // Submit a KYC Document
        $kycDocument->Status = 'VALIDATION_ASKED';
        $kycDocument = $this->mangopay->Users->UpdateKycDocument($mango_user_id, $kycDocument);
        return ['status'=> true, 'id' => $kycDocument->Id, 'message' => 'Kyc document validation requested!'];
    }

    /**
     * Get UBO Details
     * @param  Integer                  $uboDeclartionId
     * @return \MangoPay\UboDeclaration
     */
    public function getUBODeclaration($mango_user_id, $ubo_declaration_id)
    {
        return $this->mangopay->UboDeclarations->Get($mango_user_id, $ubo_declaration_id);
    }

    public function applyUBO($mango_user_id, $ubo_arr)
    {
        // Create ubo declaration
        try {
            $uboDeclaration = $this->mangopay->UboDeclarations->Create($mango_user_id);
        } catch (\Exception $e) {
            return ['status'=> false, 'message' => $e->getMessage()];
        }

        // Declare UBOs
        foreach ($ubo_arr as $ubo_obj) {
            // Address
            $address = new \MangoPay\Address();

            $ubo = new \MangoPay\Ubo();
            $ubo->FirstName           = $ubo_obj->first_name;
            $ubo->LastName            = $ubo_obj->last_name;
            
            // Address
            $ubo->Address  = new \MangoPay\Address();
            $ubo->Address->AddressLine1 = $ubo_obj->street;
            $ubo->Address->City         = $ubo_obj->place;
            //$ubo->Address->Region     = $ubo_obj->place;
            $ubo->Address->PostalCode   = $ubo_obj->post_code;
            $ubo->Address->Country      = $ubo_obj->country;
            
            $ubo->Nationality           = $ubo_obj->nationality;
            $ubo->Birthday              = $ubo_obj->birth_date->timestamp;

            // Birthplace
            $ubo->Birthplace = new \MangoPay\Birthplace();
            $ubo->Birthplace->City      = $ubo_obj->birth_place;
            $ubo->Birthplace->Country   = $ubo_obj->birth_land;

            try {
                $result  = $this->mangopay->UboDeclarations->CreateUbo($mango_user_id, $uboDeclaration->Id, $ubo);
                if(!$result)
                    return ['status'=> false, 'message' => 'Ubo declartion failed while adding a new ubo!'];
            } catch (\MangoPay\Libraries\ResponseException $e) {
                return ['status'=> false, 'message' => $e->getMessage()];
            }
        }

        // Update UBO Declaration status to VALIDATION_ASKED
        $uboDeclaration = $this->mangopay->UboDeclarations->SubmitForValidation($mango_user_id, $uboDeclaration->Id);
        return ['status'=> true, 'id' => $uboDeclaration->Id, 'message' => 'Kyc document validation requested!'];
    }

    public function convertAmount($amount)
    {
        return number_format((float) $amount, 2, '', '');
    }

}