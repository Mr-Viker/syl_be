<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Response;

class IndexController extends Controller {

  // public function index() {
  //   return view('index.index');
  // }

  public function __call($method, $args) {
    $view = 'index.' . $method;
    if (view()->exists($view)) {
      return view($view);
    }
    return Response::make('404 NOT FOUND', 404);
  }
  
}