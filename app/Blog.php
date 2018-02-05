<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use File;
use Session;

class Blog extends Model
{ 
	public $timestamps = false;
	protected $fillable = ['id', 'title', 'author', 'content', 'published', 'publishDate', 'tags'];

    public function save(array $options = [])
	{		 
        if (is_null($this->id)) {
        	Session::put('blogCount', Session::get('blogCount')+1);
        	$this->id = Session::get('blogCount');
        }

        if (($this->published == 'true') && is_null($this->publishDate)) {
        	$this->publishDate = \Carbon\Carbon::now()->toDateTimeString();
        }else if (is_null($this->published) || ($this->published == 'false')) {        	
        	$this->published = 'false';
        	unset($this->publishDate);
        }        

		Session::push('blogs', $this);
		return $this->toJson();
    }
}
