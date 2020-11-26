@if(session('success'))
    <div class="mt-10 mb-5 flex flex-col items-center w-full">
        <div class="border bg-green-100 border-green-200 rounded p-5 w-full">
            {{session('success')}}
        </div>
    </div>
@endif

@if ($errors->any())
    <div class="w-full">
        @foreach ($errors->all() as $error)
            <div class="border bg-red-100 rounded p-5 border-red-200 m-2 mt-3" >{{ $error }}</div>
        @endforeach
    </div>
@endif

@if(session('error'))
    <div class="mt-10 flex flex-col items-center w-full">
        <div class="border bg-red-100 border-red-200 rounded p-5 ">
            {{session('error')}}
        </div>
    </div>
@endif
