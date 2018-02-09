<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Session;

/**
 * the php Blog Object declaration 
 */
class Blog extends Model
{ 
	public $timestamps = false;    // don't use Laravel auto-created timestamps
	protected $fillable = ['id', 'title', 'author', 'content', 'published', 'publishDate', 'tags'];  // Blog attributes that can be changed

    // this is Triggered during the save() action
    // here we declare what will happen when we store an Blog Object 
    public function save(array $options = [])   
	{		 
        // set an uniqueId if is not already seated (case create or update)
        if (is_null($this->id)) {
        	Session::put('blogCount', Session::get('blogCount')+1);
        	$this->id = Session::get('blogCount');
        }

        // if published attach the publication date
        // if publish=false or null the blog is stored as notPablished so it do not have a publicationDate 
        if (($this->published == 'true') && is_null($this->publishDate)) {
        	$this->publishDate = \Carbon\Carbon::now()->toDateTimeString();
        }else if (is_null($this->published) || ($this->published == 'false')) {        	
        	$this->published = 'false';
        	unset($this->publishDate);
        }        

        // Store Object in Session
		Session::push('blogs', $this);
		
        return $this->toJson();   // return Object in json format
    }
}
