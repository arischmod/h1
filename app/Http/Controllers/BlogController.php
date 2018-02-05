<?php
 
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Blog;
use Session;
use Mail;

class BlogController extends Controller
{

    public function all($order = 'asc', $published = 'all')
    {
        $allBlogs = Session::get('blogs');                
     
        // filter by tag
        $tags = array();
        foreach (app('request')->input() as $key => $value) {            
            array_push($tags, $key);
        }
        
        if (!empty($tags)) {
            foreach ($allBlogs as $key => $value) {
                $hasTag = false;
                foreach ($tags as $key2 => $value2) {                    
                    if (in_array($value2, $value['tags'])) {
                        $hasTag = true;
                        break;
                    }
                }
                if ($hasTag == false)
                    unset($allBlogs[$key]);                    
            }            
        }        

        $pubBlogs = array_where($allBlogs, function($key, $value)
        {            
            return $key['published'] == 'true';
        });
        
        $sortedPublished = array_values(array_sort($pubBlogs, function ($value) {             
            return $value['publishDate'];
        }));

        if ($order == 'desc')
            $sortedPublished = array_reverse($sortedPublished);

        if ($published == 'pub')
            return $sortedPublished;

        $unPubBlogs = array_where($allBlogs, function($key, $value)
        {            
            return $key['published'] == 'false';
        });

        $sortedUnPublished = array_values(array_sort($unPubBlogs, function ($value) {             
            return $value['id'];
        }));
        return array_merge($sortedPublished, $sortedUnPublished);
    }

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
            Blog::create(['title' => "yesterday", 'author' => "aris", 'content' => "lalala", 'published' => 'true']),
            Blog::create(['title' => "today", 'author' => "kasia", 'content' => "kakaka", 'published' => 'false']),
            Blog::create(['title' => "tomorrow", 'author' => "petros", 'content' => "xoxoxo"]) 
        );        

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
                Session::put('blogs', $allBlogs);
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
    public function create($title = null, $author = null, $content = null, $published = 'false')
    {
    
        $tags = array(); 
        foreach (app('request')->input() as $key => $value) {            
            array_push($tags, $key);
        }
        $blog = Blog::create(['title'=>$title, 'author'=>$author, 'content'=>$content, 'published'=>$published, 'tags'=>$tags]);        
        
        // send email to admin
        Mail::raw('newBlog!!', function ($message){
            $message->subject('New Blog is Created!');
            $message->from('aris.stru@gmail.com', 'Blog App');
            $message->to(env('MAIL_ADMIN', 'aris.stru@gmail.com'));
        });

        return $blog;
        //$blog = new Blog(['title' => $title,'author' => $author,'content' => $content]);
        //return $blog->save();
    }


    public function update($id, $title = null, $author = null, $content = null, $published = 'false')
    {
        $allBlogs = Session::get('blogs');
        
        $blogExists = false;
        foreach ($allBlogs as $key => $value) {
            if ($value->id == $id){
                $blogExists = true;
                unset($allBlogs[$key]);
                Session::put('blogs', $allBlogs);

                $tags = array(); 
                foreach (app('request')->input() as $key2 => $value2) {            
                    array_push($tags, $key2);
                }                

                Blog::create(['id'=>$id, 'title'=>$title, 'author'=>$author, 'content'=>$content, 'published'=>$published, 'publishDate'=>$value->publishDate, 'tags'=>$tags]);
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

    /**
     * Show all Blogs.
     *
     * @return Response
     */
    public function retrieve()
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