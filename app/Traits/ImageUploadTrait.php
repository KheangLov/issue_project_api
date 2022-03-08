<?php

namespace App\Traits;

use Illuminate\Support\Str;
use JD\Cloudder\Facades\Cloudder;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

trait ImageUploadTrait {

    public static function BootImageUpload()
    {
        parent::boot();
        static::deleting(function ($obj) {
            Storage::disk('public')->delete($obj->{static::imageField()});
        });
    }

    public function uploadImage($value, $attributes, $return = false)
    {
        $attributeName = $attributes;
        // or use your own disk, defined in config/filesystems.php
        $disk = 'public';
        // destination path relative to the disk above
        $destinationPath = "uploads";

        // if the image was erased
        if (!$value) {
            // delete the image from disk
            Storage::disk($disk)->delete($this->{$attributeName});

            // set null in the database column
            $this->attributes[$attributeName] = null;
        }

        // if a base64 was sent, store it in the db
        if (Str::startsWith($value, 'data:image')) {
            // 0. Make the image
            $image = Image::make($value)->encode('jpg', 90);

            // 1. Generate a filename.
            $filename = time() . '.jpg';

            // 2. Store the image on disk.
            $filePath = "$destinationPath/$filename";
            Storage::disk($disk)->put($filePath, $image->stream());

            // 3. Delete the previous image, if there was one.
            Storage::disk($disk)->delete($this->{$attributeName});

            if ($return) {
                return $filePath;
            }

            $this->attributes[$attributeName] = $filePath;
        } else {

            if ($return) {
                return $value;
            }

            $this->attributes[$attributeName] = $value;
        }
    }

    public function cloudderImageUpload($value, $attributeName, $destination = 'uploads/images')
    {
        if (Str::startsWith($value, 'data:image')) {
            Cloudder::upload($value, null, [
                "folder" => $destination,
                "overwrite" => false,
                "resource_type" => "image",
                "responsive" => true,
                "transformation" => [
                    "quality" => "70",
                    "width" => "250",
                    "height" => "250",
                    "crop" => "scale"
                ]
            ]);

            $publicId = Cloudder::getPublicId();
            $imageUrl = Cloudder::show($publicId, [
                "width" => 250,
                "height" => 250,
                "crop" => "scale",
                "quality" => 70,
                "secure" => "true"
            ]);

            $this->attributes[$attributeName] = $imageUrl;
        } else {
            $this->attributes[$attributeName] = $value;
        }
    }
}
