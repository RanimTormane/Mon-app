@csrf
    <label for="name">Name</label>
    <input type="text" name="name" id ="name" 
            value="{{old('name',$api->name ?? '')}}">
    <label for="description">Description</label>
    <textarea name="description" id="description">{{old('description',$api->description ?? '')}}</textarea>
    <label for="token">Token</label>
    <input type="text" name="token" id ="token" 
            value="{{old('token',$api->token ?? '')}}">
    <label for="status">Active</label>

    <input type="hidden" name="status" value="0">
    <input type="checkbox" name="status" id="status" value="1" {{ old('status', $api->status ?? 1) == 1 ? 'checked' : '' }}>

     
    <button>Save</button>


    <!-- old :it's a helper function that make the data allowing  in the form
        ?? '' : it's a null coalescing operator return a default value if the model variable isn't defined   -->