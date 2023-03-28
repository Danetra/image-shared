<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Photos extends Model
{
    //
    protected $table = 'post';

    protected $fillable = [
        'images', 'description', 'likes', 'like_by', 'created_at', 'updated_at'
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];

    public static function getPost()
    {
        $get = DB::select('SELECT * FROM post');
        return $get;
    }

    public static function getDetail($id)
    {
        $detail = DB::select("SELECT * FROM post where id = $id");
        return $detail;
    }


}
