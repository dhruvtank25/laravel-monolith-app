<?php

use Illuminate\Database\Seeder;
use App\Models\Category;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Helpers\FileUploadHelper;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Family
        $category                      = new Category();
        $category->title               = 'Familie';
        $category->url_slug            = 'familie';
        $category->icon                = file_get_contents(asset('initial/category/family.svg'));
        $category->banner              = $this->uploadBanner('family');
        $category->short_description   = '<ul><li>&nbsp;Erfolgreich Familie gestalten</li><li>&nbsp;Kindererziehen</li><li>&nbsp;Familienkonfliktel&ouml;sen</li></ul>';
        $category->description         = file_get_contents(asset('initial/category/family_desc.txt'));
        $category->save();

        // Work And Career
        $category                      = new Category();
        $category->title               = 'Arbeit & Karriere';
        $category->url_slug            = 'arbeit-und-karriere';
        $category->icon                = file_get_contents(asset('initial/category/work_career.svg'));
        $category->banner              = $this->uploadBanner('work_career');
        $category->short_description   = '<ul><li>&nbsp;Meine St&auml;rken erkennen und einsetzen</li><li>&nbsp;Karrierewege entdecken</li><li>&nbsp;Umgang mit Kollegen</li></ul>';
        $category->description         = file_get_contents(asset('initial/category/work_career_desc.txt'));
        $category->save();

        // Love And Relationship
        $category                      = new Category();
        $category->title               = 'Liebe & Beziehung';
        $category->url_slug            = 'liebe-und-beziehung';
        $category->icon                = file_get_contents(asset('initial/category/love_relationship.svg'));
        $category->banner              = $this->uploadBanner('love_relationship');
        $category->short_description   = '<ul><li>&nbsp;Wertvolle Beziehung gestalten</li><li>&nbsp;Konflikte l&ouml;sen</li><li>&nbsp;Trennung &ndash; das Ende?</li><li>&nbsp;Umgang mit Sexualit&auml;t</li></ul>';
        $category->description         = file_get_contents(asset('initial/category/love_relationship_desc.txt'));
        $category->save();

        // Personality Development
        $category                      = new Category();
        $category->title               = 'Persönlichkeit entwickeln';
        $category->url_slug            = 'persoenlichkeit-entwickeln';
        $category->icon                = file_get_contents(asset('initial/category/personality_development.svg'));
        $category->banner              = $this->uploadBanner('personality_development');
        $category->short_description   = '<ul><li>&nbsp;Selbstbewusstsein st&auml;rken</li><li>&nbsp;Umgang mit Ver&auml;nderung und Trauer</li><li>&nbsp;Verstehe dich selbst, indem du deine Vergangenheit verstehst</li></ul>';
        $category->description         = file_get_contents(asset('initial/category/personality_development_desc.txt'));
        $category->save();
    }

    public function uploadBanner($file_name)
    {
        $inputs['banner']   = new UploadedFile(public_path('initial/category/'.$file_name.'.jpg'), $file_name.'.jpg');
        $inputs['title']  = $file_name;
        return FileUploadHelper::uploadCategoryBanner($inputs);
    }
    
}
