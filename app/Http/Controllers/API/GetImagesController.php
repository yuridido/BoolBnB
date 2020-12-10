<?php

namespace App\Http\Controllers\API;
use App\Image;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
class GetImagesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $id = $request->id;
        $images = Image::where('apartment_id','=',$id)->get();
        $data = [];
        foreach($images as $image){
           $data[] = [
               'path' => Storage::url($image->path),
               'apartment_id'=> $image->apartment_id
           ];
        }
        return response()->json($data);
    }

    


}