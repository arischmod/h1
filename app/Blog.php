<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use File;
use Session;

class Blog extends Model
{ 
	public $timestamps = false;
	protected $fillable = ['id', 'title', 'author', 'content'];

    public function save(array $options = [])
	{		 
        if (is_null($this->id)) {
        	Session::put('blogCount', Session::get('blogCount')+1);
        	$this->id = Session::get('blogCount');
        }

		Session::push('blogs', $this);
		return $this->toJson();
    }
}
