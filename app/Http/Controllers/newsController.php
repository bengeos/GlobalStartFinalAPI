<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\News;
use Carbon\Carbon;
class newsController extends Controller
{
    public function getAllNews() {
        try{
            $news = News::orderBy('id', 'DESC')->paginate(10);
            return response()->json(['status'=> true, 'news'=> $news],200);
        }catch (\Exception $exception){
            return response()->json(['status'=>false, 'message'=> 'Whoops! something went wrong', 'error'=>$exception->getMessage()],500);
        }
    }
    // public function getTodayNews() {
    //     try{
    //         $today_news = News::with('newsCategory')->wheredate('updated_at', '>=', Carbon::today())->orderBy('updated_at', 'DES')->paginate(6);
    //         return response()->json(['status'=> true, 'news'=> $today_news],200);
    //     }catch (\Exception $exception){
    //         return response()->json(['status'=>false, 'message'=> 'Whoops! something went wrong', 'error'=>$exception->getMessage()],500);
    //     }
    // }
    // public function getOldNews() {
    //     try{
    //         $today_news = News::with('newsCategory')->wheredate('updated_at', '<=', Carbon::yesterday())->orderBy('updated_at', 'DES')->paginate(8);
    //         return response()->json(['status'=> true, 'news'=> $today_news],200);
    //     }catch (\Exception $exception){
    //         return response()->json(['status'=>false, 'message'=> 'Whoops! something went wrong', 'error'=>$exception->getMessage()],500);
    //     }
    // }
    public function create() {
        try{
            $credential = request()->only('title', 'description','image');
            $rules = ['title' => 'required', 'description' => 'required',];
            $validator = Validator::make($credential, $rules);
            if($validator->fails()) {
                $error = $validator->messages();
                return response()->json(['error'=> $error],500);
            }
           
                $newNews = new News();
                $newNews->title = $credential['title'];
                $newNews->description = $credential['description'];
                $newNews->image = $credential['image'];
                if($newNews->save()){
                    return response()->json(['status'=> true, 'message'=> 'News Successfully Created', 'news'=>$newNews],200);
                }else {
                    return response()->json(['status'=>false, 'message'=> 'Whoops! unable to create news', 'error'=>'failed to create news'],500);
                }
            
        }catch (\Exception $exception){
            return response()->json(['status'=>false, 'message'=> 'Whoops! something went wrong', 'error'=>$exception->getMessage()],500);
        }
    }
    public function update() {
        try{
            $credential = request()->only('id', 'title', 'description','image', 'updated_at');
            $rules = ['id' => 'required'];
            $validator = Validator::make($credential, $rules);
            if($validator->fails()) {
                $error = $validator->messages();
                return response()->json(['error'=> $error],500);
            }
            $oldNews = News::where('id', '=', $credential['id'])->first();
            if ($oldNews instanceof News) {
                $oldNews->title = isset($credential['title'])? $credential['title']: $oldNews->title;
                $oldNews->description = isset($credential['description'])? $credential['description']: $oldNews->description;
                $oldNews->updated_at = isset($credential['updated_at'])? Carbon::parse($credential['updated_at']): $oldNews->updated_at;
                // if(isset($credential['category_id'])){
                //     $oldNewsCategory = NewsCategory::where('id', '=',$credential['category_id'])->first();
                //     if ($oldNewsCategory instanceof NewsCategory) {
                //         $oldNews->category_id = $oldNewsCategory->id;
                //     }
                // }
                if($oldNews->update()){
                    return response()->json(['status'=> true, 'message'=> 'News Successfully Updated', 'news'=>$oldNews],200);
                }else {
                    return response()->json(['status'=>false, 'message'=> 'Whoops! unable to update news', 'error'=>'failed to update news'],500);
                }
            } else {
                return response()->json(['status'=>false, 'message'=> 'Whoops! unable to find news with ID: '.$credential['id'], 'error'=>'old news not found'],500);
            }
        }catch (\Exception $exception){
            return response()->json(['status'=>false, 'message'=> 'Whoops! something went wrong', 'error'=>$exception->getMessage()],500);
        }
    }
    public function delete($id) {
        try{
            $oldNews = News::where('id', '=', $id)->first();
            if($oldNews instanceof News) {
                if($oldNews->delete()){
                    return response()->json(['status'=> true, 'message'=> 'News Successfully Deleted'],200);
                }else {
                    return response()->json(['status'=>false, 'message'=> 'Whoops! failed to delete the News', 'error'=>'failed to delete the news'],500);
                }
            }
        }catch (\Exception $exception){
            return response()->json(['status'=>false, 'message'=> 'Whoops! something went wrong', 'error'=>$exception->getMessage()],500);
        }
    }
}
