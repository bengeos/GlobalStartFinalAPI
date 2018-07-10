<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Testimonies;
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
