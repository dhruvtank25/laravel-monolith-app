<?php

namespace App\Repositories;

use Webleit\RevisoApi\Reviso;
use App\Repositories\AppointmentRepository;
use App\Helpers\FileUploadHelper;

class RevisoRepository
{

    protected $reviso;
    protected $appointmentRepo;

    function __construct(Reviso $reviso, AppointmentRepository $appointmentRepo)
    {
        $this->reviso          = $reviso;
        $this->appointmentRepo = $appointmentRepo;
    }

    public function newInstance()
    {
        return $this->reviso;
    }

    protected function getUri($url)
    {
        return new \GuzzleHttp\Psr7\Uri($url);
    }

    public function getApiCall($url)
    {
        return $this->reviso->client->get($this->getUri($url));
    }

    public function getApiCollection($url, $keyname, $perPage = 20, $page=0)
    {
        $list = $this->getApiCall($url);
        $collection = new \Webleit\RevisoApi\Collection($list, $keyname);
        return $collection;
    }

    public function postApiCall($url, $data)
    {
        return $this->reviso->client->post($this->getUri($url), $data);
    }

    public function saveApiCall($data, $url, $keyname)
    {
        $newItem = new \Webleit\RevisoApi\EmptyModel($this->getUri($url), $keyname);
        return $newItem->save($data);
    }

    /**
     * Get list of all Api Endpoints
     * @return Array
     */
    public function getEndpoints()
    {
        return $this->reviso->getEndpoints();
    }

    public function getAccountCategory()
    {
        return $this->getApiCall('/account-categories')->collection[0];
    }

    public function getAccountYears()
    {
        return $this->getApiCall('/accounting-years?sort=-year')->collection[0];
    }

    public function getPurchaseVatType()
    {
        $vat_types = collect($this->getApiCall('/vat-types')->collection);
                
        $vat_collection = $vat_types->firstWhere('name', 'Sales VAT');
        if(isset($vat_collection))
            return $vat_collection;
        return $vat_types->first();
    }

    public function getVATReportSetup($vat_type_id)
    {
        return $this->getApiCall('/vat-types/'.$vat_type_id.'/vat-report-setups', 'vatTypeNumber');
    }

    public function getAccount($name='himmlisch account')
    {
        //https://rest.reviso.com/accounts/schema/post?demo=true
        $account = $this->getApiCall('/accounts?filter=name$eq:'.$name);
                
        if(count($account->collection)>0)
            $account = $account->collection[0];
        else {
            $cat   = $this->getAccountCategory();
            $years = $this->getAccountYears();
            // Need to create account here
            $data = [
                'name'            => 'himmlisch account',
                'accountNumber'   => 9949,
                'accountCategory' => $cat,
                'accountingYears' => $years,
                'accountType'     => "ProfitAndLoss",
                'contraAccount'   => '',
                'debitCredit'     => 'Credit',
            ];
            $account = $this->reviso->Accounts->create($data);
        }
        return $account;
    }

    public function getVatAccount()
    {
        $vat_account = $this->reviso->VatAccounts->get()->toArray();
        if(isset($vat_account['19vat']))
            return $vat_account['19vat'];
                    
        $account          = $this->getAccount();
        $vat_type         = $this->getPurchaseVatType();
                
        $vat_report       = $this->getVATReportSetup($vat_type->vatTypeNumber);
                
        $vat_report_collection = collect($vat_report->collection);
        $standard_report  = $vat_report_collection->firstWhere('name', 'Standard');
        if(!$standard_report)
            $standard_report = $vat_report_collection[0];
                                      
        $data = [
            'account'        => $account,
            'name'           => '19% customer VAT',
            'ratePercentage' => 19,
            'vatType'        => $vat_type,
            'vatReportSetup' => $standard_report,
            'vatCode'        => '19vat'
        ];
                
        $vat_account = $this->reviso->VatAccounts->create($data);
        return $vat_account;
    }

    public function createProductGroup()
    {
        $account  = $this->getVatAccount();
        $account_no = $account->account->accountNumber;
        
        $vat_zone = $this->getApiCall('/vat-zones?filter=name$eq:Domestic')->collection[0];
        $vat_zone_no = $vat_zone->vatZoneNumber;
        
                
        $salesAccountsList[0] = [
                                    'salesAccount' => ['accountNumber' => $account_no], 
                                    'vatZone' => ['vatZoneNumber' => $vat_zone_no]
                                ];
                
        $data = [
            'name'               => 'Customer Product Group',
            'productGroupNumber' => 49,
            'salesAccountsList'  => $salesAccountsList,
        ];
        return $this->reviso->ProductGroups->create($data);
    }

    public function createProduct()
    {
        $vat_zone = $this->getApiCall('/product-groups?filter=name$eq:Customer Product Group')->collection;
                
        if(count($vat_zone)>0)
            $pgroups = $vat_zone[0];
        else
            $pgroups = $this->createProductGroup();
        
        $data  = [
            'productNumber' => 'CP001',
            'name'          => 'Consulting Session',
            'productGroup'  => $pgroups
        ];
        return $this->reviso->Products->create($data);
    }

    public function getProduct()
    {
        $product = $this->getApiCall('/products?filter=productNumber$eq:Consulting Session');
        $product = $this->reviso->Products->get()->first();
        if(!$product)
            $product =  $this->createProduct();
        return $product;
    }

    public function getCustomerGroup()
    {
        $customer_groups = $this->reviso->customerGroups->get();
        return $customer_groups->first();        
    }

    /**
     * Get Customer list
     * @return RevisoCollection
     */
    public function getAllCustomers()
    {
        return $this->reviso->customers->get();
    }

    /**
     * Get Customer Details
     * @param  integer      $user_id  customer id
     * @return RevisoModel
     */
    public function getCustomer($user_id)
    {
        try {
            $customer = $this->reviso->customers->find($user_id);
            return $customer;
        } catch (\Webleit\RevisoApi\Exceptions\ErrorResponseException $e) {
            return false;
        }
    }

    /**
     * Create Customer
     * @param  App\Models\User $user
     * @return RevisoModel
     */
    public function createCustomer($user)
    {
        $customerGroup   = $this->getCustomerGroup();
        $customerGroupNo = $customerGroup->customerGroupNumber;
        $vatZone         = $this->reviso->vatZones->get()->first()->vatZoneNumber;
        $paymentTerms    = $this->reviso->paymentTerms->get()->first()->paymentTermsNumber;
        $data = [
            'customerNumber' => $user->id,
            'name' => $user->first_name.' '. $user->last_name,
            'currency' => 'EUR',
            'customerGroup' => [
                'customerGroupNumber' => $customerGroupNo
            ],
            'vatZone' => [
                'vatZoneNumber' => $vatZone
            ],
            'paymentTerms' => [
                'paymentTermsNumber' => $paymentTerms
            ],
            'email'   => $user->email
        ];
        if($user->house_no && $user->street)
            $data['address'] = $user->street;
        if($user->post_code)
            $data['zip']     = $user->post_code;
        if($user->place)
            $data['city']    = $user->place;
        $customer = $this->reviso->customers->create($data);
        return $customer;
    }

    /**
     * Update Customer
     * @param  App\Models\User $user
     * @return RevisoModel
     */
    public function updateCustomer($user)
    {
        $customer            = $this->getCustomer($user->id);
        if(!$customer) 
            return $this->createCustomer($user);
        $data = [
            'customerNumber' => $user->id,
            'name'           => $user->first_name.' '. $user->last_name,
            'currency'       => 'EUR',
            'address'        => $user->house_no.', '.$user->street,
            'city'           => $user->place,
            'zip'            => $user->post_code,
            'email'          => $user->email
        ];
        return $customer->save($data);
    }

    public function createSupplierGroup()
    {
        $data = [
            'account'             => $this->getAccount(),
            'name'                => 'Custom Domestic',
            'supplierGroupNumber' => 99,
        ];
        $supplier_group = $this->reviso->supplierGroups->create($data);
        return $supplier_group;
    }

    public function getSupplierGroup()
    {
        $supplier_groups = $this->getApiCall('/supplier-groups?filter=name$eq:Custom Domestic')->collection;
        if(count($supplier_groups)>0)
            $supplier_group = $supplier_groups[0];
        else
            $supplier_group = $this->createSupplierGroup();
        return $supplier_group;
    }

    /** Coaches are referred as supplier here */
    /**
     * Get Supplier list
     * @return RevisoCollection
     */
    public function getAllSuppliers()
    {
        return $this->reviso->suppliers->get();
    }

    /**
     * Get Supplier Details
     * @param  integer      $user_id  supplier id
     * @return RevisoModel
     */
    public function getSupplier($user_id)
    {
        try {
            $supplier = $this->reviso->suppliers->find($user_id);
            return $supplier;
        } catch (\Webleit\RevisoApi\Exceptions\ErrorResponseException $e) {
            return false;
        }
    }

    /**
     * Create Supplier
     * @param  App\Models\User $user
     * @return RevisoModel
     */
    public function createSupplier($user)
    {
        $supplierGroup   = $this->getSupplierGroup();
        $supplierGroupNo = $supplierGroup->supplierGroupNumber;
        $vatZone         = $this->reviso->vatZones->get()->first()->vatZoneNumber;
        $paymentTerms    = $this->reviso->paymentTerms->get()->first()->paymentTermsNumber;
        $data = [
            'supplierNumber' => $user->id,
            'name' => $user->first_name.' '. $user->last_name,
            'currency' => 'EUR',
            'supplierGroup' => [
                'supplierGroupNumber' => $supplierGroupNo
            ],
            'vatZone' => [
                'vatZoneNumber' => $vatZone
            ],
            'paymentTerms' => [
                'paymentTermsNumber' => $paymentTerms
            ],
            'address' => $user->house_no.', '.$user->street,
            'city'    => $user->place,
            'zip'     => $user->post_code,
            'email'   => $user->email
        ];
                
        $supplier = $this->reviso->suppliers->create($data);
        return $supplier;
    }

    /**
     * Update Supplier
     * @param  App\Models\User $user
     * @return RevisoModel
     */
    public function updateSupplier($user)
    {
        $supplier            = $this->getSupplier($user->id);
        if(!$supplier) 
            return $this->createSupplier($user);
        $data = [
            'supplierNumber' => $user->id,
            'name'           => $user->first_name.' '. $user->last_name,
            'currency'       => 'EUR',
            'address'        => $user->house_no.', '.$user->street,
            'city'           => $user->place,
            'zip'            => $user->post_code,
            'email'          => $user->email
        ];
        return $supplier->save($data);
    }

    // Create Invoice Schema: https://rest.reviso.com/v2/invoices/drafts/schema/post
    public function createInvoice($appointment)
    {
        if($appointment->status=='cancelled' && $appointment->cancel_fee_percent<=0)
            return false;
        
        ini_set('max_execution_time', '60');
        $coach    = $appointment->coach;
        if($this->getCustomer($coach->id)==false)
            $this->createCustomer($coach);
        $user     = $appointment->user;
        $category = $appointment->categories;

        // Get cost calculations
        $cost_calculation = $appointment->cost_calculation;
                
        //$project      = $this->reviso->project->get()->first();
        $paymentTerms = $this->reviso->paymentTerms->get()->first();
                
        $vat_account  = $this->getVatAccount();

        // Get Product  
        $product = $this->getProduct();

        $layouts = $this->getApiCall('/layouts?filter=name$eq:Customer Invoice');
        if(count($layouts->collection)>0)
            $layout = $layouts->collection[0];
        else
            $layout = $layouts->default;
                                
        $lines[0] = [
            'deliveryDate'         => $appointment->start->format('Y-m-d'),
            'description'          => $category->title,
            'discountPercentage'   => 0.00,
            /*'marginInBaseCurrency' => $cost_calculation['commission_amount'],
            'marginPercentage'     => $cost_calculation['commission_percent'],*/
            'lineNumber'           => 1,
            'marginInBaseCurrency' => 0.00,
            'marginPercentage'     => 0.00,
            'product'              => [
                'recommendedCostPrice'  => $product->recommendedCostPrice,
                'productNumber'         => $product->productNumber,
            ],
            'quantity'             => 1.00,
            'sortKey'              => 1,
            'totalNetAmount'       => $appointment->status!='cancelled'?$cost_calculation['commission_amount']:$cost_calculation['return_commission'],
            'totalVatAmount'       => $appointment->status!='cancelled'?$cost_calculation['commission_vat']:$cost_calculation['return_commission_vat'],
            'totalGrossAmount'     => $appointment->status!='cancelled'?$cost_calculation['gross_commission']:$cost_calculation['gross_return_commission'],
            /*'unit'      => [
                'name'      => 'Stck',
                'products'  => ,
                'unitNumber'=> ,
            ],*/
            'unitCostPrice'        => $appointment->status!='cancelled'?$cost_calculation['commission_amount']:$cost_calculation['return_commission'],
            'unitNetPrice'         => $appointment->status!='cancelled'?$cost_calculation['commission_amount']:$cost_calculation['return_commission'],
            //'manuallyEditedSalesPrice' => true,
            'vatInfo'              => ['vatAccount' => $vat_account, 'vatRate' => $cost_calculation['vat_percent']],
        ];
        $invoiceData  = [
            //'id'            => 1,
            //'number'        => 111,
            'currency'      => 'EUR',
            //'customer'      => ['customerNumber' => $coach->id],
            'customer'      => ['customerNumber' => $coach->id],
            'date'          => $appointment->created_at->format('Y-m-d'),
            'dueDate'       => $appointment->start->addDay()->format('Y-m-d'),
            'paymentTerms'  => $paymentTerms,
            'netAmountInBaseCurrency' => $appointment->status!='cancelled'?$cost_calculation['commission_amount']:$cost_calculation['return_commission'],
            //'project'       => $project,
            'layout'        => $layout,
            'lines'         => $lines,
            'notes'         => [
                'heading'   => $cost_calculation['duration_min'].' minutes session',
                'text1'     => 'Coach: '.$coach->first_name.' '.$coach->last_name,
                'text2'     => 'User: '.$user->first_name.' '.$user->last_name,
            ],
            'pdf'           => ['download' => asset('public/uploads/invoice.pdf')]
        ];

        // Create Draft Invoice
        $newInvoice = $this->saveApiCall($invoiceData, '/v2/invoices/drafts', 'draftInvoiceNumber');

        // Attach Pdf to Invoice
        $this->attachInvoiceAttachment($newInvoice->id, $appointment);

        // Book draft invoice
        $booked_invoice = $this->postApiCall('/v2/invoices/booked', ['id' => $newInvoice->id]);
        
        // Save new invoice Id
        $appointment->invoice_id = $booked_invoice->bookedInvoiceNumber;
        $appointment->save();

        // Add payment and mark invoice as paid
        $this->createCustomerPayment($booked_invoice->bookedInvoiceNumber);
        
        return $booked_invoice;
    }

    public function deleteInvoiceAttachment($id)
    {
        try {
            $url = $this->getUri('/v2/invoices/drafts/'.$id.'/attachment');
            $this->reviso->client->delete($url);
        } catch (\Webleit\RevisoApi\Exceptions\ErrorResponseException $e) {
            return false;
        }
        return true;
    }

    /**
     * Attaches a pdf page to invoice pdf (Only if invoice type is draft)
     * @param  Integer $id            Draft invoice id
     * @param  String  $pdf_location  Pdf absolute path
     * @return Void
     */
    public function attachInvoiceAttachment($id, $appointment)
    {
        $this->deleteInvoiceAttachment($id);

        // Create and save Invoice pdf temporarily
        $pdf = $this->appointmentRepo->createInvoice($appointment, 'coach', true);
        $temp_dir       = 'public/uploads/temp/';
        $file_name      = date('ymdhis').'.pdf';
        FileUploadHelper::makeDirectory($temp_dir);
        $pdf->save(base_path($temp_dir.$file_name));
        $temp_file_path = base_path($temp_dir.$file_name);

        // Open temp pdf file
        $file_content = fopen($temp_file_path, 'r');

        // Send file
        $data         = [['name' => 'file', 'contents' => $file_content]];
        $url          = $this->getUri('/v2/invoices/drafts/'.$id.'/attachment');
        $result       = $this->reviso->client->postFile($url, $data);

        // Delete temporary file
        unlink($temp_file_path);

        // Return result
        return $result;
    }

    public function getInvoicePdf($id, $is_download=false)
    {
        //$result = $this->getApiCall('/v2/invoices/drafts/'.$id.'/pdf');
        $result = $this->getApiCall('/v2/invoices/booked/'.$id.'/pdf');
        if(!$is_download)            
            return $result;
        $file = 'invoice.pdf';
        file_put_contents($file, $result);
        if (file_exists($file)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="'.basename($file).'"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file));
            readfile($file);
            exit;
        }
    }

    public function getCustomerPaymentNumberSeries()
    {
        $number_series = $this->getApiCall('/number-series?filter=entryType%24eq%3acustomerPayment');
        return $number_series->collection[0];
    }

    public function createCustomerPayment($invoice_id)
    {   
        $invoice  = $this->getApiCall('https://rest.reviso.com/v2/invoices/booked/'.$invoice_id);
                
        $customer = $this->getCustomer($invoice->customer->customerNumber);
                
        $account      = $this->getAccount();
        $bank_account = $this->getAccount('Bank');
                
        $numberSeries = $this->getCustomerPaymentNumberSeries();
        $voucher_data = array(
            'lines' => [
                [
                    //'contraAccount' => $bank_account,
                    'contraAccount' => [
                        'accountNumber' => $bank_account->accountNumber
                    ],
                    'amount' => -$invoice->grossAmount,
                    'amountInBaseCurrency' => -$invoice->grossAmount,
                    'booked'  => true,
                    'currency' => 'EUR',
                    'text'    => 'Payment to the coach',
                ],
                [
                    'customer'      => $customer,
                    //'contraAccount' => $account,
                    //'contraAccount' => $account,
                    'amount' => -$invoice->grossAmount,
                    'amountInBaseCurrency' => -$invoice->grossAmount,
                    'booked'  => true,
                    'currency' => 'EUR', 
                    'invoiceNumber' => $invoice_id, 
                    'text'    => 'Payment to the coach',
                ]
            ],
            'numberSeries' => $numberSeries,
            'date'         => date('Y-m-d'),
            'booked'       => true,
        );
        $voucher = $this->saveApiCall($voucher_data, '/vouchers/drafts/customer-payments', 'voucherId');
        
        // Book draft customer payment voucher
        $voucher_id = $voucher->voucherId;
        $booked_voucher = $this->postApiCall('/vouchers/booked', ['voucherId' => $voucher_id]);
        return $booked_voucher;
    }

}