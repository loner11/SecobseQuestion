<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Tag;
use App\Question;

class TagController extends Controller
{
	public function show($id)
	{
		$tag = Tag::find($id);
		$questions = $tag->questions()->orderBy('created_at')->paginate(6);
		$popularTags = $tag->orderBy('questions_count', 'desc')->limit(4)->get();
		return view('tags.questions', compact('tag', 'questions','popularTags'));
	}
}
