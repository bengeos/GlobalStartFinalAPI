<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Testimonies;
use Illuminate\Support\Facades\Validator;
use App\News;
class TestimoniesController extends Controller
{
        public function getAllTestimonies() {
        try{
            $Testimonies = Testimonies::orderBy('id', 'DESC')->paginate(10);
            return response()->json(['status'=> true, 'Testimonies'=> $Testimonies],200);
        }catch (\Exception $exception){
            return response()->json(['status'=>false, 'message'=> 'Whoops! something went wrong', 'error'=>$exception->getMessage()],500);
        }
    }


  



  public function create(Request $request) {
        try{
            $credential = request()->only('title', 'body','image','address');
        if ($request->hasFile('image')) {
            $posted_image_art =  $request->file('image');
            $filnameWithExt = $request->file('image')->getClientOriginalName();
 
            $filename = pathinfo($filnameWithExt, PATHINFO_FILENAME);

            $extension = $request->file('image')->getClientOriginalExtension();

            $fileNameToStore = $filename.'_'. time().'.'. $extension;

            $destinationPath = public_path('/news_images');
            $posted_image_art->move($destinationPath, $fileNameToStore);
            $image_art_path = '/news_images/' . $fileNameToStore;

            //$path = $request->file('image')->storeAs('\public\news_images', $fileNameToStore);
        } else {
            $fileNameToStore = "noimage.jpg";
        }

        $rules = ['title' => 'required', 'body' => 'required',];
            $validator = Validator::make($credential, $rules);
            if($validator->fails()) {
                $error = $validator->messages();
                return response()->json(['error'=> $error],500);
            }
           
                $newTestimony = new Testimonies();
                $newTestimony->type = "Testimony";
                $newTestimony->title = $credential['title'];
                $newTestimony->address = $credential['address'];
                $newTestimony->body = $credential['body'];
                $newTestimony->image = "news_images/".$fileNameToStore;

                if($newTestimony->save()){
                    return response()->json(['status'=> true, 'message'=> 'News Successfully Created', 'news'=>$newTestimony],200);
                }else {
                    return response()->json(['status'=>false, 'message'=> 'Whoops! unable to create news', 'error'=>'failed to create news'],500);
                }
            
        }catch (\Exception $exception){
            return response()->json(['status'=>false, 'message'=> 'Whoops! something went wrong', 'error'=>$exception->getMessage()],500);
        }
    }



       public function approve($id) {
           try{
            $Testimonies = Testimonies::where('id', '=', $id)->first();
            // print_r($Testimonies); exit();
            $news = new News();
            $news->title= $Testimonies['title'] ;
            $news->image= $Testimonies['image'] ;
            $news->description =$Testimonies['body'];
            $news->type = "Testimony";
            $news->save();

             $Testimonies->delete();
            return response()->json(['status'=> true, 'Testimonies'=> $news],200);
        }catch (\Exception $exception){
            return response()->json(['status'=>false, 'message'=> 'Whoops! something went wrong', 'error'=>$exception->getMessage()],500);
        }
    }
        public function delete($id) {
        try{
            $oldTestimony = Testimonies::where('id', '=', $id)->first();
            if($oldTestimony instanceof Testimonies) {
                if($oldTestimony->delete()){
                    return response()->json(['status'=> true, 'message'=> 'Testimonies Successfully Deleted'],200);
                }else {
                    return response()->json(['status'=>false, 'message'=> 'Whoops! failed to delete the Testimonies', 'error'=>'failed to delete the news'],500);
                }
            }
        }catch (\Exception $exception){
            return response()->json(['status'=>false, 'message'=> 'Whoops! something went wrong', 'error'=>$exception->getMessage()],500);
        }
    }
}
