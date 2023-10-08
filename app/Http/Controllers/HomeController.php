<?php

namespace App\Http\Controllers;

use App\images_tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\car;
use stdClass;

class HomeController extends Controller
{
    public function index()
    {
        $images = DB::table('images')->get();
        foreach ($images as  $value) {
            $temp = $this->get_tags_by_imgID($value->id);
            if ($temp != 0) {
                $value->tags_count = sizeof($temp);
                $value->related_tags = json_encode($temp);
            } else {
                $value->related_tags = 0;
                $value->tags_count = 'No tags yet';
            }
        }
        $data['images_records'] = $images;
        $tags = DB::table('images_tags')->get();
        $data['tags_records'] = $tags;
        return view('home', $data);
    }

  

    private function get_tags_by_imgID($imgID)
    {
        $tags  = DB::table('images_tags')->where('image_id', $imgID)->get();
        if (sizeof($tags) == 0) {
            $minimised_tags = 0;
        } else {
            $minimised_tags = [];
            foreach ($tags as  $value) {
                $coords = json_decode($value->coords);
                $tempTag = new stdClass();
                $tempTag->x1 = $coords->start->x;
                $tempTag->y1 = $coords->start->y;
                $tempTag->width = $coords->end->x - $coords->start->x;
                $tempTag->height = $coords->end->y - $coords->start->y;
                $tempTag->name = $value->name;
                $tempTag->id = $value->id;
                $minimised_tags[] = $tempTag;
            }
        }
        return $minimised_tags;
    }

    public function  create_tag(Request $request)
    {
        $tag = new images_tag();
        $tag->name = $request->name;
        $tag->image_id = $request->id;
        $tag->coords = $request->coords;
        $tag->save();
        return redirect('/');
    }

    public function remove_tag(Request $request)
    {
        $tagId = $request->id;
        DB::table('images_tags')->where('id', '=', $tagId)->delete();
        return redirect('/');
    }
    public function edit_tag(Request $request)
    {
        $tagId = $request->id;
        $name = $request->name;
        DB::table('images_tags')
            ->where('id', $tagId)
            ->update(['name' => $name]);
        return redirect('/');
    }
}
