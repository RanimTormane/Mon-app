<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\API;
use App\Http\Requests\SaveApiRequest;



class APIsController extends Controller
{
   public function index()
   {
 

     #use var_dump to output the content of the variable 
     #var_dump($apis);
     #die;
     #laravel provides a helper function called DD replace var_dump and die 
     #dd($apis); ==>
    return view('APIs.index',['apis' =>  API::all()]);

   }

   public function create ()
   {
      return view ('APIs.create');
   }

   public function store(SaveApiRequest $request)

   {
     $status = $request->has('status') ? 1 : 0;
     $api = API::create($request->validated());
      
      return redirect()->route('APIs.index', $api)
                     ->with('status','API created');
   }

   public function show(API $api){
     
      return view ('APIs.show', compact('api'));
   }

   public function edit(API $api){

     return view ('APIs.edit', compact('api'));
   }

   public function update(SaveApiRequest $request, API $api){
      $api->update($request->validated());
      return redirect()->route('APIs.index', $api)
                     ->with('status','API updated');

   }
   public function destroy(API $api)
   {
      $api->delete();
      return redirect()->route('APIs.index')
                     ->with('status','API deleted');
   }
   public function updateStatus($id)
   {
      $api = Api::find($id);
      if ($api) {
         
         $api->status = !$api->status;
         $api->save();
     }

      return redirect()->route('APIs.index');
   }

}
 

#handle the requests and responses