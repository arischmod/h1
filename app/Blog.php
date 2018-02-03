<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use File;
use Session;

class Blog extends Model
{ 
	public $timestamps = false;
	protected $fillable = ['title', 'author', 'content'];

    public function save(array $options = [])
	{		
		Session::push('blogs', $this);
		//session()->push('blogs', $this);
		return $this;
		// $data = json_encode(array($this->toJson(),$this->toJson(),$this->toJson()));		
		// return $data = $this->toJson();

	 //    //File::put(public_path('blogs.json'),$data);


	 //    return $data;
    }
}
