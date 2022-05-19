<?php

namespace App\Helpers;

use File;
use Image;
use Storage;

class FileUploadHelper
{
    private static $avatar_path         = 'users/avatar/';
    private static $banner_path         = 'users/banner/';
    private static $video_path          = 'users/video/';
    private static $company_doc_path    = 'users/company/';
    private static $ustid_doc_path      = 'users/ust_doc/';
    private static $id_doc_path         = 'users/id_doc/';
    private static $commercial_doc_path = 'users/commercial_doc/';
    private static $company_logo_path   = 'company/image/';
    private static $cat_banner_path     = 'categories/banner/';
    private static $invoice_path        = 'users/invoice/';
    private static $attachment_path     = 'messages/attachments/';

    /**
     * Get S3 bucket file url
     */
    public static function getS3FileUrl($path)
    {
        $s3 = Storage::disk('s3');
        return $s3->url($path);
    }

    public static function checkFileExists($file_name, $type)
    {
        $file_path  = self::getFilePath($type);
        return Storage::disk('s3')->has($file_path.$file_name);
    }

    /**
     * Make directory on local Storage if not exists
     */
    public static function makeDirectory($directoryPath)
    {
        if(!File::isDirectory($directoryPath)) {
            File::makeDirectory($directoryPath, 0777, true, true);
        }
    }

    /**
     * Stores InterventionImage file on S3 Bucket 
     */
    public static function putToS3($path, $file_name, $file, $isInteventionImg=true)
    {
        $filePath = $path.'/'.$file_name;

                
        $s3 = Storage::disk('s3');
        if($isInteventionImg)
            $s3->put($filePath, $file->stream());
        else
            $s3->put($filePath, file_get_contents($file));
    }

    /**
     * Strores InterventionImage file on local Storage
     */
    public static function putToLocal($path, $img_name, $img)
    {
        $destinationPath    = public_path('uploads/'.$path);
        self::makeDirectory($destinationPath); // Make directory if not exists
        $img->save($destinationPath.'/'.$img_name);
    }

    /*
     * Deletes the files if exists either on s3 or local Storage
     */
    public static function deleteFile($path)
    {
        if(Storage::disk('s3')->exists($path))
            Storage::disk('s3')->delete($path); // Delete from S3
        if(file_exists(public_path('uploads/'.$path))) 
            File::delete(public_path('uploads/'.$path)); // Delete from disk
        return true;
    }

    /** Common Get/Upload/Delete File Helper */

    public static function getFilePath($type)
    {
        $type_path         = $type.'_path';
        return  self::$$type_path;
    }

    public static function getDocPath($file_name, $type)
    {
        $save_path         = self::getFilePath($type);
        return Storage::disk('s3')->url($save_path.$file_name);
    }

    public static function getMultipleDocPath($file_names, $type)
    {
        $doc_names = explode(',', $file_names);
        $doc_url = '';
        foreach ($doc_names as $doc_name) {
            $f_url = self::getDocPath($doc_name, $type);
            $doc_url .= ','.$f_url;
        }
        $doc_url = trim($doc_url, ',');
        return $doc_url;
    }

    public static function uploadDoc($file, $type)
    {
        // Generate file name
        $fileIndex  = str_slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME), '-');
        $fileExt    = pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);
        $file_name  = $fileIndex.'_'.date("YmdHis").'.'.$fileExt;
        $file_name  = preg_replace( '/\s+/', '_', $file_name);

        // Getting file storage path
        $save_path  = self::getFilePath($type);

        // Save File
        self::putToS3($save_path, $file_name, $file, false);
        return $file_name;
    }

    public function deleteDoc($file_name, $type)
    {
        $save_path  = self::getFilePath($type);
        return self::deleteFile($save_path.$file_name);
    }

    /** User Avatars */
    public static function uploadUserAvatar($inputs, $type='avatar')
    {
        if(isset($inputs['avatar']))
            $image  = $inputs['avatar'];
        else
            $image  = $inputs['file'];

        // Generate file name
        $image_name = $inputs['first_name'].'-'.$inputs['last_name'].'_'.time().'.'.$image->getClientOriginalExtension();
        $image_name = preg_replace( '/\s+/', '_', $image_name);

        // Resize Image
        $img        = Image::make($image->getRealPath());
        $img->resize(300, 300, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });

        // Getting file storage path
        $save_path  = self::getFilePath($type);

        // Save File
        self::putToS3($save_path, $image_name, $img);
        return $image_name;
    }

    public static function deleteAvatar($file_name, $type='avatar')
    {
        // Getting file storage path
        $save_path  = self::getFilePath($type);

        if($file_name=='default.jpg')
            return true;
        return self::deleteFile($save_path.$file_name);
    }

    /** User Banners */
    public static function uploadUserBanner($inputs, $type='banner')
    {
        if(isset($inputs['banner']))
            $image              = $inputs['banner'];
        else
            $image              = $inputs['file'];

        // Generate file name
        $image_name         = $inputs['first_name'].'-'.$inputs['last_name'].'_'.time().'.'.$image->getClientOriginalExtension();
        $image_name         = preg_replace( '/\s+/', '_', $image_name);

        // Resize Image
        $img                = Image::make($image->getRealPath());
        $img->resize(1920, 350, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });

        // Getting file storage path
        $save_path  = self::getFilePath($type);
        self::putToS3($save_path, $image_name, $img);
        return $image_name;
    }

    public static function deleteUserBanner($file_name, $type='banner')
    {
        // Getting file storage path
        $save_path  = self::getFilePath($type);
        
        if($file_name=='default.jpg')
            return true;
        return self::deleteFile($save_path.$file_name);
    }

    /** Categories */
    public static function uploadCategoryBanner($inputs)
    {
        $image              = $inputs['banner'];
        $image_name         = $inputs['title'].'_'.time().'.'.$image->getClientOriginalExtension();
        $image_name         = preg_replace( '/\s+/', '_', $image_name);
        $img                = Image::make($image->getRealPath());
        $img->resize(800, 520, function ($constraint) {
                $constraint->aspectRatio();
                // $constraint->upsize();
            });
        self::putToS3(self::$cat_banner_path, $image_name, $img);
        return $image_name;
    }
    
    public static function deleteCategoryBanner($file_name)
    {
        if($file_name=='default.jpg')
            return true;
        return self::deleteFile(self::$cat_banner_path.$file_name);
    }

    public static function uploadCategoryContentImage($file)
    {
        $image_name         = time().'_'.$file->getClientOriginalName();
        $image_name         = preg_replace( '/\s+/', '_', $image_name);
        $img                = Image::make($file->getRealPath());
        $save_path          = 'categories/';
        self::putToS3($save_path, $image_name, $img);
        return $image_name;
    }

    /** Companies */
    public static function uploadCompanyImage($inputs)
    {
        $image              = $inputs['image'];
        $image_name         = $inputs['name'].'_'.time().'.'.$image->getClientOriginalExtension();
        $image_name         = preg_replace( '/\s+/', '_', $image_name);
        $img                = Image::make($image->getRealPath());
        /*$img->resize(800, 520, function ($constraint) {
                //$constraint->aspectRatio();
                //$constraint->upsize();
            });*/
        $save_path = self::$company_logo_path;
        self::putToS3($save_path, $image_name, $img);
        return $image_name;
    }

    public static function deleteCompanyImage($file_name)
    {
        if($file_name=='default.jpg')
            return true;
        return self::deleteFile(self::$company_logo_path.$file_name);
    }

}