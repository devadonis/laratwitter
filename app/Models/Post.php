<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
		use HasFactory;

		protected $fillable = ['user_id', 'body'];
		protected $appends = ['createdDate'];

		public function user()
		{
			return $this->belongsTo(User::class);
		}

		public function getCreatedDateAttribute()
		{
			return $this->created_at->diffForHumans();
		}
}
