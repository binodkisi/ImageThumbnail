<?php
 
namespace App\Http\Controllers;
 
use Illuminate\Http\Request;
use Validator,Redirect,Response,File;
Use Image;
Use App\Models\Photo;
use Intervention\Image\Exception\NotReadableException;
 
 
class ImageController extends Controller
{
 
public function index()
{
  return view('image');
}
 
public function save(Request $request)
{
 request()->validate([
      'photo_name' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
 ]);
 
 if ($files = $request->file('photo_name')) {
     
    // for save original image
    $ImageUpload = Image::make($files);
    $originalPath = 'images/';
    $ImageUpload->save($originalPath.time().$files->getClientOriginalName());
     
    // for save thumnail image1
    $thumbnailPath = 'thumbnail1/';
    $ImageUpload->resize(800,600);
    $ImageUpload = $ImageUpload->save($thumbnailPath.time().$files->getClientOriginalName());

    $thumbnailPath = 'thumbnail2/';
    $ImageUpload->resize(400,300);
    $ImageUpload = $ImageUpload->save($thumbnailPath.time().$files->getClientOriginalName());
 
  $photo = new Photo();
  $photo->photo_name = time().$files->getClientOriginalName();
  $photo->save();
  }
 
  $image = Photo::latest()->first(['photo_name']);
  return Response()->json($image);
 
 
}
}