<?php

use Illuminate\Database\Seeder;
use App\Repositories\CountryRepository;
use App\Models\Country;

class CountriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $countryRepo = new CountryRepository(new Country);
        // Delete already added records
        $countryRepo->deleteAll();
        $client      = new \GuzzleHttp\Client();
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
        $returned_result = $countryRepo->insert($countries_arr);
    }
    
}
