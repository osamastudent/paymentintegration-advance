<?php

namespace App\Http\Controllers;

use App\Mail\Orders;
use App\Models\Post;
use App\Models\User;
use Stripe\Climate\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    //
    public function createPost()
    {
        return view('post.create-post');
    }


    // store sigle post

    // public function storePost(Request $request)
    // {

    //     $file = $request->file('image');
    //     $fileName = now()->timestamp . 'rendom.png';
    //     $file->storeAs('postsImages', $fileName, 'public');

    //     Post::create([
    //         'name' => $request->name,
    //         'description' => $request->description,
    //         'image' => $fileName,
    //         'image_size' => $request->image->getSize(),
    //         'image_ext' => $request->image->getClientOriginalExtension(),
    //     ]);
    //     return back()->with('status', 'post added successfully.');
    // }

    // store multiple post

    // public function storePost(Request $request)
    // {

    //     $files = $request->file('image');
    //     $multipleFiles = [];
    //     $filelds = ['image', 'myimage'];
    //     // foreach($filelds as $fields) {}
    //     if ($files) {
    //         foreach ($files as $file) {
    //             $fileName = now()->timestamp . '_' . uniqid() . '.png'; // Create a unique name for each file
    //             $file->storeAs('postsImages', $fileName, 'public');
    //             $multipleFiles[] = $fileName;
    //             $size[] = $file->getSize();
    //             $ext[] = $file->getClientOriginalExtension();
    //         }
    //     }

    //     $implode = implode(',', $multipleFiles);
    //     $implodeSize = implode(',', $size);
    //     $implodeExt = implode(',', $ext);



    //     Post::create([
    //         'name' => $request->name,
    //         'description' => $request->description,
    //         'image' => $implode,
    //         'image_size' => $implodeSize,
    //         'image_ext' => $implodeExt,
    //     ]);
    //     return back()->with('status', 'post added successfully.');
    // }

    // store mlutiple files and fields files

    //  public function storePost(Request $request)
    // {

    //     $multipleFiles = [];
    //     $filefields = ['image', 'myimage'];
    //     foreach($filefields as $fields) {
    //     $files = $request->file($fields);

    //     if ($files) {
    //         foreach ($files as $file) {
    //             $fileName = now()->timestamp . '_' . uniqid() . '.png'; // Create a unique name for each file
    //             $file->storeAs('postsImages', $fileName, 'public');
    //             $multipleFiles[$fields][] = $fileName;
    //             $size[] = $file->getSize();
    //         }
    //     }
    //     }

    //     $implodeSize = implode(',', $size);



    //     Post::create([
    //         'name' => $request->name,
    //         'description' => $request->description,
    //         'image' => implode(',',$multipleFiles['image']),
    //         'image_size' => $implodeSize,
    //         'image_ext' => implode(',',$multipleFiles['myimage']),
    //     ]);
    //     return back()->with('status', 'post added successfully.');
    // }


    // store multiple  fields files
    public function storePost(Request $request)
    {

        $fileFileds = ['image', 'myimage'];
        $fileNames = [];
        $size = [];
        foreach ($fileFileds as $field) {
            $file = $request->file($field);
            if ($file) {
                $fileName = now()->timestamp . uniqid() . '_rendom.png';
                $file->storeAs('postsImages', $fileName, 'public');
                $fileNames[$field] = $fileName;
                $size[$field] = $file->getSize();
            }
        }

        Post::create([
            'name' => $request->name,
            'description' => $request->description,
            'image' => $fileNames['image'],
            'image_size' => $size['image'],
            'image_ext' => $fileNames['myimage'],
        ]);


        // $image=$request->file('image');
        // dd($image);
        // Mail::to('osamajanab9999@gmail.com')->send(new Orders($request));
        return back()->with('status', 'post added successfully.');
    }



    // show posts
    public function showPost()
    {
        $showPosts = Post::all();
        return view('post.show-post', compact('showPosts'));
    }

    // delete post
    public function deletePost($id)
    {
        $deletePost = Post::find($id);
        if ($deletePost) {
            $filePath = 'postsImages/' . $deletePost->image;
            if (Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }
        } else {
            return back()->with('status', "no record found.");
        }
        $deletePost->delete();
        return back()->with('status', "delete successfully.");
    }


    public function deleteAllPost()
    {
        // dd("deleteAllPost");
        $deleteAllPost = Post::all();
        foreach ($deleteAllPost as $delete) {
            $path = 'postsImages/' . $delete->image;

            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
            Post::truncate();
        }
        return back()->with('status', "delete successfully.");
    }


    // edit post
    public function editPost($id)
    {
        $editPost = Post::find($id);
        if ($editPost) {
            return view('post.edit-post', compact('editPost'));
        }
        return back()->with('status', 'no Record Found.');
    }

    public function updatePost(Request $request, $id)
    {
        $editPost = Post::find($id);
        $oldImage = $editPost->image;
        $oldImageSize = $editPost->image_size;
        $oldImageExt = $editPost->image_ext;
        // dd($oldImage);
        $file = $request->file('image');

        if ($file) {
            $fileName = now()->timestamp . '_rendom.png';
            $file->storeAs('postsImages', $fileName, 'public');

            $filePath = 'postsImages/' . $editPost->image;
            if (Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }
        }
        // $editPost->delete();

        $editPost->name = $request->name;
        $editPost->description = $request->description;
        $editPost->image = isset($fileName) ? $fileName : $oldImage;
        $editPost->image_size = $request->image ? $request->image->getSize() : $oldImageSize;
        $editPost->image_ext = $request->image ? $request->image->getClientOriginalExtension() : $oldImageExt;
        $editPost->save();
        return redirect()->route('show.post');
    }

    public function showUsers()
    {
        $showUsers = User::all();
        return view('vendor.Chatify.layouts.favorite', compact('showUsers'));
    }

    // ajax

    public function createPostAjax()
    {
        return view('post.create-post-ajax');
    }


    public function storePostAjax(Request $request)
    {
        // dd($request->all());

        // $data=$request->all();
        // return response()->json(['data',$data]);
        $file = $request->file('image');
        $fileName = now()->timestamp . "_rendom.png";
        $file->storeAs('ajaximages', $fileName, "public");

        Post::create([
            'name' => $request->name,
            'description' => $request->description,
            'image_size' => $request->image->getSize(),
            'image_ext' => $request->image->getClientOriginalExtension(),
            'image' => $fileName,
        ]);

        return response()->json(['status' => 'added successfully.']);
    }

    // show

    public function showPostAjax()
    {
        $showPosts = Post::all();
        // return response()->json(["display"=>$showPosts]);
        return view('post.create-post-ajax', compact('showPosts'));
    }



    // delete
    public function deletePostAjax(Request $request)
    {
        $deleteId = $request->id;
        $post = Post::find($deleteId);
        if ($post) {
            $post->delete();
            return response()->json(['message' => 'Post deleted successfully', 'id' => $deleteId]);
        } else {
            return response()->json(['message' => 'Post not found', 'id' => $deleteId]);
        }
    }


    // edit
    public function editPostAjax(Request $request)
    {
        $id = $request->id;
        $post = Post::find($id);
        if ($post) {
            $name = $post->name;
            return response()->json(["status" => "post here.", 'post' => $post]);
        } else {
            return response()->json(["status" => "post not found.", 'id' => $id]);
        }
    }
    // update
    public function updatePostAjax(Request $request)
    {
        
        $post = Post::find($request->id);
        $oldImage =$post->image;
        $oldImageImageSize =$post->image_size;
        $oldImageImageExt =$post->image_ext;
        $file=$request->file("image");
        if ($file) {
            $fileName=now()->timestamp ."_rendom.png";
            $file->storeAs("ajaximages",$fileName,"public");
            $filepath = "ajaximages/" . $post->image;
            if (Storage::disk('public')->exists($filepath)) {
                Storage::disk('public')->delete($filepath);
            }
        }

            $post->name = $request->name;
            $post->description = $request->description;
            $post->image =isset($fileName) ? $fileName: $oldImage;
            $post->image_size =$request->image ? $request->image->getSize() : $oldImageImageSize ;
            $post->image_ext =$request->image ? $request->image->getClientOriginalExtension() : $oldImageImageExt ;
            
            $post->save();
            return response()->json(["status" => "updated successfully.", 'name' => $request->id,'filepath'=>$filepath]);

    }
}
