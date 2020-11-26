<!DOCTYPE html>
<html>
<head>
    <link href="https://unpkg.com/tailwindcss@^1.0/dist/tailwind.min.css" rel="stylesheet">
    <title>Create Event</title>
</head>
<body class="p-12 bg-gray-100">

<div class="lg:w-1/2 xl:w-1/3 lg:mx-auto mb-4 flex">
    @include('event.partials.messages')
</div>

<div class="lg:w-1/2 xl:w-1/3 lg:mx-auto mb-4 flex">
    <h1 class="text-4xl lg:text-2xl font-bold text-purple-600">Events list</h1>
    <div class="flex-1"></div>
    <a href="/event/create" class="py-3 px-6 lg:py-2 lg:px-4 m-4 lg:m-2 rounded-full bg-purple-600 hover:bg-purple-500 text-white text-2xl lg:text-sm">New Event</a>

</div>

@foreach($events as $event)
    <div class="m-2 p-4 lg:w-1/2 xl:w-1/3 lg:mx-auto border rounded bg-white">
        <div class="flex items-center">
            <div class="flex flex-col">
                <span class="text-2xl lg:text-lg font-bold">{{ $event['name'] }}</span>
                {{ $event['date'] }}
            </div>
            <div class="flex-1"></div>
            <a href="/event/{{ $event->id }}/edit" class="py-3 px-6 lg:py-2 lg:px-4 m-4 lg:m-2 rounded-full bg-blue-600 hover:bg-blue-500 text-white text-2xl lg:text-sm">Edit</a>
        </div>



{{--        @foreach($event->shifts as $shift)--}}
{{--            {{ $shift['name'] }}--}}
{{--            <br>--}}
{{--            @foreach($shift->workerTypes as $workerType)--}}
{{--                {{ $workerType['name'] }} {{ $workerType->pivot->quantity }}--}}
{{--                <br>--}}
{{--            @endforeach--}}
{{--        @endforeach--}}

    </div>

@endforeach
</body>
</html>




