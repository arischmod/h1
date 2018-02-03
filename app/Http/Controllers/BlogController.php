<?php
 
namespace App\Http\Controllers;

use App\Blog;
use App\Http\Controllers\Controller;
use Session;

class BlogController extends Controller
{


    /**
     * Init the app with Dummy data.
     * by putting dummy Blog objects in Session
     *
     * @return Response
     */
    public function init()
    {
        Session::put(['blogCount' => 0]);

        $allBlogs = array();
        $success = array_push($allBlogs, 
            Blog::create(['title' => "yesterday",'author' => "aris",'content' => "lalala"]),
            Blog::create(['title' => "today",'author' => "kasia",'content' => "kakaka"]),
            Blog::create(['title' => "tomorrow",'author' => "petros",'content' => "xoxoxo"]) 
        );
        //return Session::get('blogs');

        if ($success)
            return response(array(
                'error' => false,
                'message' => $allBlogs,
           ),200);
        else 
            return response(array(
                'error' => true,
                'message' =>'something was wrong during Initialization!',
           ),200);
    }


    /**
     * Show all Blogs.
     *
     * @return Response
     */
    public function all()
    {                
        $allBlogs = Session::get('blogs');        
     

        if ($allBlogs)
            return response(array(
                'error' => false,
                'message' => $allBlogs,
           ),200);
        else 
            return response(array(
                'error' => true,
                'message' =>'there are no Blogs yet',
           ),200);
    }

    public function blog($id)
    {                
        $allBlogs = Session::get('blogs');
        
        $blog = array();
        foreach ($allBlogs as $key => $value) {
            if ($value->id == $id){
                $blog = $value;
                break;                
            }
        }
        
        if (!empty($blog)){
            return response(array(
                'error' => false,
                'message' => $blog,
           ),200);
        } else
            return response(array(
                'error' => true,
                'message' =>'there is no Blogs with this Id!',
           ),200); 
    }

    public function delete($id)
    {                
        $allBlogs = Session::get('blogs');
        
        $blogExists = false;
        foreach ($allBlogs as $key => $value) {
            if ($value->id == $id){
                $blogExists = true;
                unset($allBlogs[$key]);
                session()->put('blogs', $allBlogs);
                break;                
            }
        }
        if ($blogExists){
            return response(array(
                'error' => false,
                'message' => 'deleted successfully',
           ),200);
        } else
            return response(array(
                'error' => true,
                'message' =>'there is no Blog with this Id!',
           ),200); 
    }

    /**
     * Create new Blog. 
     *
     * @return Response
     */
    public function create($title = null, $author = null, $content = null)
    {
        return $blog = Blog::create(['title' => $title,'author' => $author,'content' => $content]);
        //$blog = new Blog(['title' => $title,'author' => $author,'content' => $content]);
        //return $blog->save();
    }


    public function update($id, $title = null, $author = null, $content = null)
    {                
        $allBlogs = Session::get('blogs');
        
        $blogExists = false;
        foreach ($allBlogs as $key => $value) {
            if ($value->id == $id){
                $blogExists = true;                
                $value->title = $title;
                $value->author = $author;
                $value->content = $content;
                Session::put('blogs', $allBlogs);
                break;                
            }
        }
        if ($blogExists){
            return response(array(
                'error' => false,
                'message' => 'blog updated successfully',
           ),200);
        } else
            return response(array(
                'error' => true,
                'message' =>'there is no Blog with this Id!',
           ),200); 
    }
}

/**
//    use Illuminate\Http\Request;
        public function index(Request $request)
        {
            $products = Product::paginate(5);
            return response(array(
                    'error' => false,
                    'products' =>$products->toArray(),
                   ),200);       
        }
        public function store(Request $request)
        {
            Product::create($request->all());
            return response(array(
                    'error' => false,
                    'message' =>'Product created successfully',
                   ),200);
        }
        public function show($id)
        {
            $product = Product::find($id);
            return response(array(
                    'error' => false,
                    'product' =>$product,
                   ),200);
        } 
        public function update(Request $request, $id)
        {
            Product::find($id)->update($request->all());
            return response(array(
                    'error' => false,
                    'message' =>'Product updated successfully',
                   ),200);
        }
        public function destroy($id)
        {
            Product::find($id)->delete();
            return response(array(
                    'error' => false,
                    'message' =>'Product deleted successfully',
                   ),200);
        }
**/