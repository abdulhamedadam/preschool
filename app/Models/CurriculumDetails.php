<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
class CurriculumDetails extends Model implements HasMedia
{
    use InteractsWithMedia;

      protected $table = 'curriculum_details';
      protected $guarded = [];


      public function registerMediaCollections(): void
    {
        $this->addMediaCollection('curriculum_files')->useDisk('media');
    }

    public function subject()
    {
        return $this->belongsTo(Subjects::class,'subject_id');
    }

    public function creator()
    {
           return $this->belongsTo(User::class,'created_by');
    }



}
