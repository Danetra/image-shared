<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Photos;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PhotosController extends Controller
{
    //
    public function index()
    {
        $get = Photos::getPost();
        // dd($get);

        $data = [];
        foreach($get as $i => $value)
        {
            $array = [
                'id' => $value->id,
                'images' => url('upload/img' . '/' . $value->images),
                'description' => $value->description,
                'likes' => $value->likes,
                'like_by' => $value->like_by,
                'created_at' => $value->created_at,
                'updated_at' => $value->updated_at,
            ];
            array_push($data, $array);
            // dd($data);
        }
        if($data)
        {
            $response = [
                'code' => 200,
                'data' => $data,
            ];
            return response()->json($response, 200);
        }else{
            $response = [
                'code' => 400,
                'message' => 'Data Empty',
            ];
            return response()->json($response, 400);
        }
    }

    public function uploadPhotos(Request $request)
    {

        if($request->hasFile('images'))
        {
            $original_filename = $request->file('images')->getClientOriginalName();
            $original_filename_arr = explode('.', $original_filename);
            $file_ext = end($original_filename_arr);
            $destination = "upload/img";
            $post = 'img-' . time() . '.' . $file_ext;

            $sent = $request->file('images')->move('upload/img', $post);

            if($sent)
            {
                $data = [
                    'images' => $post,
                    'description' => $request->input('description'),
                    'likes' => $request->input('likes'),
                    'like_by' => auth()->user()->id,
                    'created_at' => date("Y-m-d H:i:s")
                ];

                $post = Photos::create($data);
                if($post)
                {
                    return response()->json([
                        'status' => 'success',
                        'data' => $post
                    ], 200);
                }else{
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Failed to Upload Data'
                    ], 400);
                }
            }else{
                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to Upload Images'
                ], 400);
            }
        }
    }

    public function detail($id)
    {
        $detail = Photos::getDetail($id);
        if($detail){
            $imagesName = $detail[0]->images;
            $destination = "upload/img";

            $data = [
                'id' => $detail[0]->id,
                'images' => url($destination . '/' . $imagesName),
                'description' => $detail[0]->description,
                'likes' => $detail[0]->likes,
                'created_at' => $detail[0]->created_at,
                'updated_at' => $detail[0]->updated_at,
            ];

            return response()->json([
                'code' => 200,
                'data' => $data
            ], 200);
        }
    }

    public function updatePhotos(Request $request, $id)
    {
        // dd($id);
        $desc = $request->input('description');
        $data = [
            'description' => $desc,
            'updated_at' => date("Y-m-d H:i:s")
        ];

        $update = Photos::whereId($id)->update($data);
        if($update)
        {
            return response()->json([
                'status' => 'success',
                'message' => 'Success Update'
            ], 200);
        }else{
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to Update'
            ], 400);
        }
    }

    public function likePhotos(Request $request, $id)
    {
        $likes = 1;
        // dd($likes);
        $data = [
            'likes' => $likes
        ];
        $likesStatus = Photos::whereId($id)->update($data);
        if($likesStatus)
        {
            return response()->json([
                'status' => 'success',
                'message' => 'Success Liked Photos'
            ], 200);
        }else{
            return response()->json([
                'status' => 'success',
                'message' => 'Failed Liked Photos'
            ], 400);
        }
    }

    public function unlikePhotos(Request $request, $id)
    {
        $likes = 0;
        // dd($likes);
        $data = [
            'likes' => $likes
        ];
        $unlikesStatus = Photos::whereId($id)->update($data);
        if($unlikesStatus)
        {
            return response()->json([
                'status' => 'success',
                'message' => 'Success Unliked Photos'
            ], 200);
        }else{
            return response()->json([
                'status' => 'success',
                'message' => 'Failed Unliked Photos'
            ], 400);
        }
    }

}
