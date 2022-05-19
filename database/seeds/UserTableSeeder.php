<?php

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Helpers\FileUploadHelper;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       $role_admin = Role::where('name', 'admin')->first();
       $admin = User::where('role_id', $role_admin->id)->count();
       if($admin==0) {
         // Add new Admin
         $admin = new User();
         $admin->role_id = $role_admin->id;
         $admin->first_name = 'admin';
         $admin->last_name = 'istrator';
         $admin->phone_number = '2345656464';
         $admin->email = 'admin@admin.com';
         $admin->password = bcrypt('123456');
         $admin->saveQuietly();
       }

       // Add default Avatar images
       $this->uploadDefaultImage('avatar');
       $this->uploadDefaultImage('banner');
    }

    public function uploadDefaultImage($type)
    {
        $path = FileUploadHelper::getFilePath($type);
        $file = new UploadedFile(public_path('initial/default_'.$type.'.jpg'), 'default_'.$type.'.jpg');
        return FileUploadHelper::putToS3($path, 'default.jpg', $file, false);
    }

}
