<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageIframe extends Model
{
	public $table = 'page_iframes';

	public $fillable = [
		'facade',
		'entrance',
        'lobby',
        'sponsors',
        'plenary',
		'mini_sessions'
	];
}