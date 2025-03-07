<x-layout>

<h1>Edit API</h1>
<x-errors/>

 
<form method="post" action="{{ route('APIs.update',$api)}} ">
    @method('PATCH')
<!--passing data to a component -->
<x-apis.form :api="$api"/>
</form>
</x-layout>
