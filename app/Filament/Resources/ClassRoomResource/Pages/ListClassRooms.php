<?php

namespace App\Filament\Resources\ClassRoomResource\Pages;

use App\Filament\Resources\ClassRoomResource;
use App\Models\ClassRoom;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListClassRooms extends ListRecords
{
     protected static string $resource = ClassRoomResource::class;
     protected static string $view = 'filament.class_rooms.index';

     public $class_rooms;


     public function mount(): void
     {
         $this->class_rooms = ClassRoom::all();
     }
}
