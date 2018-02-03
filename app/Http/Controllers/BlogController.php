<?php
 
namespace App\Http\Controllers;

use App\Blog;
use App\Http\Controllers\Controller;

class BlogController extends Controller
{

    /**
     * Show all Blogs.
     *
     * @return Response
     */
    public function all()
    {
            return array(
              1 => "expertphp",
              2 => "demo"
            );
    }

    /**
     * Create new Blog.
     *
     * @return Response
     */
    public function create($title = null, $author = null, $content = null)
    {
            return array(
              1 => "title = ".$title,
              2 => "author = ".$author
            );
    }



    /**
     *  TO DELETE!!!
     * Show all Blogs.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        return view('user.profile', ['user' => User::findOrFail($id)]);
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