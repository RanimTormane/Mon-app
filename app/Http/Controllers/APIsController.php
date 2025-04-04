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
       
    $apis = API::all();

   
    if (request()->wantsJson()) {
        return response()->json($apis);
    }

    
    return view('APIs.index', ['apis' => $apis]);
   
   }

   public function create ()
   {
      return view ('APIs.create');
   }

   public function store(SaveApiRequest $request)

   {
      $status = $request->has('status') ? 1 : 0;
      $api = API::create($request->validated() + ['status' => $status]);
      $response = [
         'message' => 'API created successfully!',
         'data' => $api
     ];
 
    
     if ($request->wantsJson()) {
         return response()->json($response, 201);
     }
 
    
     session()->flash('alert', 'API created successfully!');
     session()->flash('alert-type', 'success');
     
     return redirect()->route('APIs.index');
                     
   }


     
      public function show(Request $request, API $api)
      {
          
         if (!$api) {
            return response()->json(['error' => 'API not exist'], 404);
        }
    
        if ($request->wantsJson()) {
            return response()->json($api); 
        }
    
        return view('APIs.show', compact('api')); 
    }
      

   public function edit(API $api){

if (request()->wantsJson()) {
   return response()->json(['api' => $api]);
}


return view('APIs.edit', compact('api'));
}

public function update(SaveApiRequest $request, API $api)
{

$api->update($request->validated());


if (request()->wantsJson()) {
   return response()->json([
       'message' => 'API updated successfully!',
       'alert-type' => 'info',
       'api' => $api 
   ]);
}


session()->flash('alert', 'API updated successfully!');
session()->flash('alert-type', 'info');

return redirect()->route('APIs.index');
   }
   public function destroy(API $api)
{
    $api->delete();

   
    if (request()->wantsJson()) {
        return response()->json([
            'message' => 'API deleted successfully!'
        ]);
    }

   
    session()->flash('alert', 'API deleted successfully!');
    session()->flash('alert-type', 'error');
    
    return redirect()->route('APIs.index');
}

   public function updateStatus($id)
   {
      $api = Api::find($id);
      if ($api) {
         
        $api->status = $api->status === 1 ? 0 : 1;
         $api->save();
     
     if ($api->status == 1 ){
      session()->flash('alert', 'API is active now');
   
     }
     else{
      session()->flash('alert', 'API inactive');
     
     }

     return response()->json([
      'status' => $api->status,  
      'message' => $api->status == 1 ? 'API is active now' : 'API is inactive now'
  ]);
}

return response()->json(['message' => 'API not found'], 404);  
}}
   


 

#handle the requests and responses