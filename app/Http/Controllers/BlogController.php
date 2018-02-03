<?php
 
namespace App\Http\Controllers;

use App\Blog;
use App\Http\Controllers\Controller;
use Session;

class BlogController extends Controller
{


    /**
     * Init the app with Dummy data.
     * by puting dummy Blog objects in Session
     *
     * @return Response
     */
    public function init()
    {
        $allBlogs = array();
        $success = array_push($allBlogs, 
            new Blog(['title' => "yesterday",'author' => "aris",'content' => "lalala"]),
            new Blog(['title' => "today",'author' => "kasia",'content' => "kakaka"]),
            new Blog(['title' => "tomorow",'author' => "petros",'content' => "xoxoxo"]) 
        );

        Session::put(['blogs' => $allBlogs]);

        if ($success)
            return response(array(
                'error' => false,
                'message' => $allBlogs,
           ),200);
        else 
            return response(array(
                'error' => true,
                'message' =>'something was wrong during Ititialisation!',
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

        // $collection = collect(json_decode($json_here, true));
        // $data = $collection->where('id', 1)->data;
        // return json_decode(file_get_contents(public_path('blogs.json')), true);
    }

    /**
     * Create new Blog. 
     *
     * @return Response
     */
    public function create($title = null, $author = null, $content = null)
    {
        $blog = new Blog(['title' => $title,'author' => $author,'content' => $content]);

        //File::put(public_path('blogs.json'),$blog->toJson());
        
        return $blog->save();
        return $blog;
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