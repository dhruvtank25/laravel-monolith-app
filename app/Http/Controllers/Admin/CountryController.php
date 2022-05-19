<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\CountryRepository;
use App\Http\Requests\CountryRequest;

class CountryController extends Controller
{

    function __construct(CountryRepository $countryRepo)
    {
        $this->countryRepo = $countryRepo;
    }

    public function index()
    {
        $page_title = 'Countries';
        $countries = $this->countryRepo->get_search_custom([]);
        return view('countries.index',compact('page_title', 'countries'));
    }

    public function show(Request $request, $id)
    {
        $page_title = 'Country Details';
        $country = $this->countryRepo->get($id);
        return view('countries.show',compact('page_title', 'country'));
    }

    public function create()
    {
        $page_title = 'Add country';
        return view('countries.create', compact('page_title'));
    }

    public function store(CountryRequest $request)
    {
        $inputs = $request->all();

        $country_id = $this->countryRepo->add($inputs, false);

        $message = 'Country added successfully.';
        return redirect()->back()->withSuccess($message);
    }

    public function edit($country_id)
    {
        $page_title = 'Update country';
        $country   = $this->countryRepo->get($country_id);    
        return view('countries.edit',compact('page_title', 'country'));
    }

    public function update(CountryRequest $request, $country_id)
    {
        $inputs       = $request->all();
        $inputs['id'] = $country_id;
                
        $this->countryRepo->edit($inputs, false);

        $message = 'Country updated successfully.';
        return redirect()->back()->withSuccess($message);
    }

    public function destroy($id)
    {
        return $this->countryRepo->delete($id);
    }

    public function sync()
    {
        // Delete already added records
        $this->countryRepo->deleteAll();
        $client = new \GuzzleHttp\Client();
        $response    = $client->request('GET', 'https://restcountries.eu/rest/v2/regionalbloc/eu');
        $body        = json_decode($response->getBody()->getContents());
        $countries_arr = array();
        foreach ($body as $object) {
            $country_arr = array(
                            'name'            => $object->name,
                            'code'            => $object->alpha2Code,
                            'alpha3Code'      => $object->alpha3Code,
                            'capital'         => $object->capital,
                            'region'          => $object->region,
                            'native_name'     => $object->nativeName,
                            'currency_code'   => $object->currencies[0]->code,
                            'currency_name'   => $object->currencies[0]->name,
                            'currency_symbol' => $object->currencies[0]->symbol,
                            'created_at'      => date('Y-m-d H:i:s'),
                            'updated_at'      => date('Y-m-d H:i:s'),
                        );
            $countries_arr[] = $country_arr;
        }
        $returned_result = $this->countryRepo->insert($countries_arr);
        $total_records   = $this->countryRepo->totalRecords();
        return response()->json(['success' => 'true', 'total_countries' => $total_records], 200);
    }

}
