<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model as BaseModel;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class FileModel extends BaseModel implements HasMedia
{
    use InteractsWithMedia;
    protected $guarded = ['id', 'created_at', 'updated_at'];
}
