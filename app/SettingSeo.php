<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SettingSeo extends Model
{
	protected $fillable = ['content_keyword','content_description','warung_id'];
}
