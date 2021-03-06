<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Requests;
use Auth;
use Image;
use DB;
use App\Question;
use App\Post;
use Illuminate\Support\Facades\File;

class UserController extends Controller
{
    /**
     * Show user profile.
     *
     * @return \Illuminate\Http\Response
     */
     public function profile($username) {
 		$user = DB::table('users')
 							->where('name', $username)
 							->get();
 		$questions = Question::where('username', $username)->orderBy('published_at', 'desc')->Paginate(4);
        $userPosts = Post::where('username', $username)->orderBy('created_at', 'desc')->Paginate(6);
 		return view('users.profile', ['user' => $user, 'questions' => $questions, 'userPosts' => $userPosts]);
 	}

    /**
     * Update user avatar.
     *
     * @return \Illuminate\Http\Response
     */
	public function updateAvatar(Request $request) {
		if ($request->hasFile('avatar')) {

			$avatar = $request->file('avatar');
			$user = Auth::user();

			$prevImg = $user->avatar;
			if ($prevImg != 'default.jpg') {
				$path = public_path('/uploads/avatars/');
				File::delete($path . $prevImg);
			}

			$filename = $user->name . '.' . $avatar->getClientOriginalExtension();
			Image::make($avatar)->fit(300, 300)->save(public_path('/uploads/avatars/') . $filename);


			$user->avatar = $filename;
			$user->save();
		}

		return redirect('/profile/' . Auth::user()->name);
	}
}
