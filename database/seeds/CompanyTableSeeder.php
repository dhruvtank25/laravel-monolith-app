<?php

use Illuminate\Database\Seeder;
use App\Models\Company;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Helpers\FileUploadHelper;

class CompanyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $company         = new Company();
        //$company->name   = 'Andere';
        $company->name   = 'Sonstige';
        $company->save();

        $company         = new Company();
        $company->name   = 'Ignis';
        $company->image  = $this->uploadImage('ignis');
        $company->save();

        $company         = new Company();
        $company->name   = 'ICL-Institut für Christliche Ehe-und Lebensberatunga';
        $company->image  = $this->uploadImage('icl');
        $company->save();

        $company         = new Company();
        $company->name   = 'TEAM.F';
        $company->image  = $this->uploadImage('teamf');
        $company->save();
    }

    public function uploadImage($file_name)
    {
        $inputs['image'] = new UploadedFile(public_path('initial/company/'.$file_name.'.png'), $file_name.'.png');
        $inputs['name']  = $file_name;
        return FileUploadHelper::uploadCompanyImage($inputs);
    }
    
}
