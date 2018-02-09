<?php
 
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Blog;
use Session;
use Mail;

class BlogController extends Controller
{
    /**
     * Init the App 
     * flush all previous Session
     * and seed with Dummy data
     * by putting dummy Blog objects in Session
     *
     * example: http://localhost:8000/blogapi/init 
     * @return the new created Blogs 
     */
    public function init()
    {
        Session::flush();  // clear previous Data in the Session 

        Session::put(['blogCount' => 0]);  // used to make new unique blogIds

        $allBlogs = array();
        array_push($allBlogs, 
            // create published Blogs
            Blog::create(['title' => "brakingNews", 'author' => "Aris", 'content' => "lalala", 'published' => 'true']) 
        );        
        sleep(2);  // delay execution for 2 sec so the newly created Blogs get different publication time so that you can test the asc/desc retrieval of Blogs 
        array_push($allBlogs, 
            // create published Blogs
            Blog::create(['title' => "yesterDayNews", 'author' => "Max", 'content' => "lololo", 'published' => 'true']),
            // one not-published Blog
            Blog::create(['title' => "todayNews", 'author' => "Pablo", 'content' => "kakaka", 'published' => 'false']),
            // case we don't declare if it is published or not -> by default unpublished
            Blog::create(['title' => "tomorrowNews", 'author' => "Chris", 'content' => "xoxoxo"]) 
        );        

        // If the Blogs are created return the json objects otherwise return error 
        if ($allBlogs)
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
     * Create a new Blog. 
     * the 'title','author','content' vars are mandatory
     * the 'published' takes True or False values and it can be empty (by default is false)
     * 
     * example: http://localhost:8000/blogapi/create/newTitle/someone/blablabla/true?sports&fashion
     * @return the newly created Blog
     */
    public function create($title, $author, $content, $published = 'false')
    {
        // read Tags from URL vars
        $tags = array(); 
        foreach (app('request')->input() as $key => $value) {            
            array_push($tags, $key);
        }

        // Create the php Object - Blog
        $blog = Blog::create(['title'=>$title, 'author'=>$author, 'content'=>$content, 'published'=>$published, 'tags'=>$tags]);        
        
        // send email to admin
        Mail::raw('there is a new Blog Created! To see it follow the url: '.route('blog.show', ['id' => $blog ->id]), function ($message){
            $message->subject('New Blog is Created!');
            $message->from('aris.stru@gmail.com', 'Blog App');
            $message->to(env('MAIL_ADMIN', 'aris.stru@gmail.com'));
        });

        // If the Blogs are created return the new object in json otherwise return error 
        if ($blog)
            return response(array(
                'error' => false,
                'message' => $blog,
           ),200);
        else 
            return response(array(
                'error' => true,
                'message' =>'Blog could not be created!',
           ),200);
    }

    /**
     * Update a Blog by blogId. 
     * the 'title','author','content' vars are mandatory
     * the 'published' takes True or False values and it can be empty (by default is false)
     * you can also pass tags as URL vars (?tag1&tag2&tag3)
     * 
     * example:  http://localhost:8000/blogapi/update/3/aaa/bbb/ccc/true?sports
     * @return the updated Blog
     */
    public function update($id, $title, $author, $content, $published = 'false')
    {
        $allBlogs = Session::get('blogs');
        $blogExists = false;
        $blog = null;

        foreach ($allBlogs as $key => $value) {
            if ($value->id == $id){
                $blogExists = true;
                unset($allBlogs[$key]);
                Session::put('blogs', $allBlogs);

                $tags = array(); 
                foreach (app('request')->input() as $key2 => $value2) {            
                    array_push($tags, $key2);
                }

                $blog = Blog::create(['id'=>$id, 'title'=>$title, 'author'=>$author, 'content'=>$content, 'published'=>$published, 'publishDate'=>$value->publishDate, 'tags'=>$tags]);
                break;                
            }
        }
        if ($blogExists && $blog){
            return response(array(
                'error' => false,
                'message' => $blog,
           ),200);
        } else
            return response(array(
                'error' => true,
                'message' =>'there is no Blog with this Id!',
           ),200); 
    }

    /**
     * Delete a Blog by blogId. 
     * 
     * example:  http://localhost:8000/blogapi/delete/2
     * @return success or failure
     */
    public function delete($id)
    {
        $allBlogs = Session::get('blogs');
        
        $blogExists = false;
        foreach ($allBlogs as $key => $value) {
            if ($value->id == $id){
                $blogExists = true;
                unset($allBlogs[$key]);  // remove Blog from where it is stored (Session)
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
     * Get all Blogs in json format. 
     * 
     * By default this will return all the Blogs, published and unpublished shorted by ascending order of publication Date
     * We can apply a number filters on this 
     * 
     *     - to get the blogs in ASC/DESC order
     *     - to get only the published ones 
     *     - to get only the ones associated with a Tag
     * 
     * example: "http://localhost:8000/blogapi/blogs"
     * example: "http://localhost:8000/blogapi/blogs/asc"
     * example: "http://localhost:8000/blogapi/blogs/desc"
     * example: "http://localhost:8000/blogapi/blogs/asc/pub"
     * example: "http://localhost:8000/blogapi/blogs/desc/pub"
     * example: "http://localhost:8000/blogapi/blogs/desc/pub?sports&fashion"
     * 
     * @return all the Blog Objects filtered as requested
     */
    public function all($order = 'asc', $published = 'all')
    {
        $allBlogs = Session::get('blogs');                
     
        // filter by tag
        $tags = array();
        foreach (app('request')->input() as $key => $value) {            
            array_push($tags, $key);
        }
        
        if (!empty($tags)) {  // if we filter by tag
            foreach ($allBlogs as $key => $value) {
                $hasTag = false;
                if ($value['tags'] === NULL){
                    unset($allBlogs[$key]);
                    continue;
                }
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

        // get only the published Blogs
        $pubBlogs = array_where($allBlogs, function($key, $value)
        {            
            return $key['published'] == 'true';
        });
        
        // sort them by publication Date
        $sortedPublished = array_values(array_sort($pubBlogs, function ($value) {             
            return $value['publishDate'];
        }));

        // If we want them in Desc order reverse the array
        if ($order == 'desc')
            $sortedPublished = array_reverse($sortedPublished);

        // if we want only the published ones 
        if ($published == 'pub')
            return $sortedPublished;

        // if we want all the blogs not only the published ones
        $unPubBlogs = array_where($allBlogs, function($key, $value)
        {            
            return $key['published'] == 'false';
        });

        // append unpublished Blogs to the end of the array ordered by Id
        $sortedUnPublished = array_values(array_sort($unPubBlogs, function ($value) {             
            return $value['id'];
        }));

        return array_merge($sortedPublished, $sortedUnPublished);
    }

    /**
     * Get a Blog its ID in json format. 
     * 
     * example:  http://localhost:8000/blogapi/blog/2
     * @return the Blog Object or Failure
     */
    public function blog($id)
    {
        $allBlogs = Session::get('blogs');
        
        $blog = array();
        if ($allBlogs !== null) {
            foreach ($allBlogs as $key => $value) {
                if ($value->id == $id){
                    $blog = $value;
                    break;                
                }
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

}

