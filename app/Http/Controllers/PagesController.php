<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class PagesController extends Controller {

	public function about() {
		$name = "Kristin Dragos";
		$notEscaped = "<span color=\"red\">Kristin Dragos</span>";
		// return view('pages.about')->with("name", $name);
		return view('pages.about')->with("notEscaped", $notEscaped);
	}

}
