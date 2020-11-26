<!DOCTYPE html>
<html>
<head>
    <link href="https://unpkg.com/tailwindcss@^1.0/dist/tailwind.min.css" rel="stylesheet">
    <style>
        @media (min-width: 1280px) {
            .max-w-600 {
                max-width: 800px;
            }
        }
    </style>

    <title>Create Event</title>
</head>
<body class="p-6 bg-gray-200 text-gray-800">
<div id="app">
    {{--Event--}}
    <div class="w-full xl:w-3/5 max-w-600 xl:mx-auto mb-10 p-3 border rounded shadow-sm bg-white">
        <div class="flex items-center">
            <div class="flex items-center">
                <img src="{{ asset('images/event_icon.png') }}" alt="Event calendar" class="h-16 xl:h-8">
                <span class="ml-2 text-6xl lg:text-4xl xl:text-3xl text-purple-700 font-bold">Create New Event</span>
            </div>
            <div class="flex-1"></div>

            @if(gettype($event->id) === 'integer')
                <form action="/event/{{$event->id}}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="py-3 px-6 xl:py-2 xl:px-4 m-4 xl:m-2 rounded-full bg-red-600 hover:bg-red-500 text-white text-2xl lg:text-xl xl:text-sm">Delete Event</button>
                </form>
            @endif
        </div>
    </div>

    <form action="{{ $target }}" method="POST">
        @if($event->id)
            @method('PATCH')
        @endif

        @csrf

        <div class="w-full xl:w-3/5 max-w-600 xl:mx-auto my-2 p-3 border rounded shadow-sm bg-white">
            <div class="mt-2 flex flex-col xl:flex-row w-full">
                <label for="id" v-if="showIds" class="my-4 m-1 flex-1 flex flex-col text-3xl lg:text-2xl xl:text-base font-semibold">
                    ID
                    <input name="id" type="text" class="w-full py-2 px-4 mt-2 text-3xl lg:text-2xl xl:text-base border rounded" value="{{  old('id', $event->id ?? null) }}">
                </label>
                <label for="name" class="my-4 m-1 flex-1  flex flex-col text-3xl lg:text-2xl xl:text-base font-semibold">
                    Name:
                    <input required name="name" type="text" class="w-full py-2 px-4 mt-2 text-3xl lg:text-2xl xl:text-base border-2 rounded" value="{{ old('name', $event->name ?? null) }}" placeholder="Enter the event name">
                </label>
                <label for="location" class="my-4 m-1 flex-1  flex flex-col text-3xl lg:text-2xl xl:text-base font-semibold">
                    Location:
                    <input required name="location" type="text" class="w-full py-2 px-4 mt-2 text-3xl lg:text-2xl xl:text-base border-2 rounded" value="{{ old('location', $event->location ?? null) }}" placeholder="Enter the location of the event">
                </label>
                <label for="date" class="my-4 m-1 flex-1  flex flex-col text-3xl lg:text-2xl xl:text-base font-semibold">
                    Date:
                    <input required name="date" type="date" class="w-full px-4 mt-2 text-3xl lg:text-2xl xl:text-base border-2 rounded" style="padding-top: 7px; padding-bottom: 7px" value="{{ old('date', $event->date ?? null) }}">
                </label>
            </div>
            <label for="show_ids">
                [Debug: Show IDs]
                <input class="ml-2" type="checkbox" v-model="showIds">
            </label>


        </div>

        <br>
        {{-- shift--}}
        <div class="w-full xl:w-3/5 max-w-600 xl:mx-auto my-6 xl:my-3 p-3 border rounded shadow-sm bg-white " v-for="shift in shifts">
            <div class="flex">
                <div class="flex items-center">
                    <img src="{{ asset('images/shift.png') }}" alt="Event calander" class="h-12 xl:h-6">
                    <span class="ml-2 text-5xl lg:text-3xl xl:text-2xl text-purple-700 font-semibold">Shift@{{ (shift.name) === '' ? shift.name : ': ' + shift.name }} </span>
                </div>
                <div class="flex-1"></div>
                <div class="flex">
                    <button class="py-3 px-6 xl:py-2 xl:px-4 m-4 xl:m-2 rounded-full bg-purple-600 hover:bg-purple-500 text-white text-2xl lg:text-xl xl:text-sm" @click.prevent="addShift()">Add Shift</button>
                    <button class="py-3 px-6 xl:py-2 xl:px-4 m-4 xl:m-2 rounded-full bg-red-600 hover:bg-red-500 text-white text-2xl lg:text-xl xl:text-sm" @click.prevent="deleteShift(shift)">Delete Shift</button>
                </div>
            </div>

            <div class="flex flex-col">

                <div class="xl:m-0 flex flex-col w-full">
                    <label v-show="showIds"  :for="'shift[' + shift.id + '][id]'" class="my-4 xl:my-0 m-1 flex flex-col text-3xl lg:text-2xl xl:text-base font-semibold">
                        ID:
                        <input class="py-2 px-4 mt-2 text-3xl lg:text-2xl xl:text-base border-2 rounded" type="text" :name="'shift[' + shift.id + '][id]'" v-model="shift.id">
                    </label>

                    <label :for="'shift[' + shift.id + '][name]'" class="my-4 xl:my-0 m-1 flex flex-col text-3xl lg:text-2xl xl:text-base font-semibold ">
                        Name:
                        <input required class="py-2 px-4 mt-2 text-3xl lg:text-2xl xl:text-base border-2 rounded" type="text" :name="'shift[' + shift.id + '][name]'" v-model="shift.name">
                    </label>

                    <div class="mt-1 flex w-full">
                        <div class="flex flex-1 items-center pr-4">
                            <label :for="'shift[' + shift.id + '][start_time]'" class="w-1/2 m-4 flex flex-col text-3xl lg:text-2xl xl:text-base font-semibold">Start:</label>
                            <input required class="w-1/2  py-2 px-4 mt-2 text-3xl lg:text-2xl xl:text-base border-2 rounded" type="time" :name="'shift[' + shift.id + '][start_time]'" v-model="shift.start_time">
                        </div>
                        <div class="flex flex-1 items-center pr-4">
                            <label :for="'shift[' + shift.id + '][end_time]'" class="w-1/2 m-4 flex flex-col text-3xl lg:text-2xl xl:text-base font-semibold">End:</label>
                            <input required class="w-1/2 py-2 px-4 mt-2 text-3xl lg:text-2xl xl:text-base border-2 rounded" type="time" :name="'shift[' + shift.id + '][end_time]'" v-model="shift.end_time">
                        </div>
                    </div>

                    {{--Roles--}}
                    <div class="mt-4 mx-2 p-3 border rounded">
                        <table>
                            <thead>
                            <tr>
                                <th v-show="showIds"  class="text-3xl lg:text-2xl xl:text-base">ID</th>
                                <th class="text-3xl lg:text-2xl xl:text-base">Worker Type</th>
                                <th class="text-3xl lg:text-2xl xl:text-base">Quantity</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tr v-for="worker_type in shift.worker_types">
                                <td v-show="showIds"  class="text-center"> <span class="text-3xl lg:text-2xl xl:text-base">@{{ worker_type.id }}</span></td>
                                <td class="text-center">
                                    <select class="w-full py-2 xl:py-1 px-4 xl:px-2 m-2 xl:m-0 text-3xl lg:text-2xl xl:text-base border rounded" :name="'shift[' + shift.id + '][worker_types][' + worker_type.id + '][id]'"  v-model="worker_type.id" @change="workerTypeChanged(shift)">

                                        <option v-for="all_worker_type in all_worker_types" :value="all_worker_type.id"
                                                v-if="!shift.worker_types.filter(record => record.id === all_worker_type.id).length > 0 || worker_type.id === all_worker_type.id"
                                        >@{{ all_worker_type.name }}</option>
                                    </select>
                                </td>
                                <td v-show="worker_type.id !== 'x' " class="text-center">
                                    <input class="w-16 py-2 xl:py-1 px-4 xl:px-2 m-2 xl:m-0 text-3xl lg:text-2xl xl:text-base border rounded" :name="'shift[' + shift.id + '][worker_types][' + worker_type.id + '][quantity]'" type="number" min="1" v-model="worker_type.quantity">
                                </td>

                                <td v-show="worker_type.id !== 'x'" class="text-center">
                                    <button class="p-1 bg-red-600  hover:bg-red-500 text-white rounded" type="button" @click.prevent="deleteRole(shift, worker_type.id)">
                                        <svg class="h-10 xl:h-4 w-8 xl:w-3  fill-current" style="enable-background:new 0 0 24 24;" version="1.1" viewBox="0 0 24 24" xml:space="preserve">
                                            <g id="info"/><g id="icons"><g id="delete"><path d="M18.9,8H5.1c-0.6,0-1.1,0.5-1,1.1l1.6,13.1c0.1,1,1,1.7,2,1.7h8.5c1,0,1.9-0.7,2-1.7l1.6-13.1C19.9,8.5,19.5,8,18.9,8z"/><path d="M20,2h-5l0,0c0-1.1-0.9-2-2-2h-2C9.9,0,9,0.9,9,2l0,0H4C2.9,2,2,2.9,2,4v1c0,0.6,0.4,1,1,1h18c0.6,0,1-0.4,1-1V4    C22,2.9,21.1,2,20,2z"/></g></g>
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="w-full xl:w-3/5 max-w-600 xl:mx-auto  my-2 p-3 border rounded shadow-sm bg-white" >

            <div class="flex justify-between" >
                <a href="/" class="py-5 lg:py-3 xl:py-2  px-10 lg:px-5 xl:px-4 m-4 xl:m-1 rounded-full bg-red-600 hover:bg-red-500 text-white text-3xl lg:text-xl xl:text-base font-semibold">Cancel</a>
                <button class="py-5 lg:py-3 xl:py-2 px-10 lg:px-5 xl:px-4 m-4 xl:m-1 rounded-full bg-purple-600 hover:bg-purple-500 text-white text-3xl lg:text-xl xl:text-base font-semibold" @click.prevent="addShift">Add Shift</button>
                <button type="submit" class="py-5 lg:py-3 xl:py-2 px-10 lg:px-5 xl:px-4 m-4 xl:m-1 rounded-full bg-green-600 hover:bg-green-500 text-white text-3xl lg:text-xl xl:text-base font-semibold">Submit</button>
            </div>
        </div>

    </form>
</div>



<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
<script>

    const app = new Vue({
        el: '#app',
        data() {
            return {
                shifts: {!! isset($event->shifts) ? json_encode($event->shifts) : "[{
                    id: 'new',
                    worker_types: [{
                        id: 'x',
                        quantity: 1,
                    }]
                }]" !!},
                showIds: false,
                item_count: 0,
                worker_type_count: 0,
                all_worker_types:{!! json_encode($workerTypes) !!}
            }
        },

        created() {
            for (let shift of this.shifts) {
                this.addBlankWorkerTypeSelect(shift);
            }
        },
        methods: {
            addShift() {
                this.shifts.push({
                    id: 'new' + this.item_count++,
                    name: '',
                    start_time: '',
                    end_time: '',
                    worker_types: [{
                        id: 'x',
                        quantity: 1,
                    }]
                })
            },
            deleteShift(id) {
                if (this.shifts.length > 1) {
                    const i = this.shifts.findIndex(record => {
                        return record.id === id
                    });
                    this.shifts.splice(i, 1);
                }
            },
            addRole(shift) {
                if (shift.worker_types.length < this.all_worker_types.length) {
                    shift.worker_types.push({id: 'x', quantity: 1});
                }
            },
            deleteRole(shift, id) {
                if (shift.worker_types.length > 1) {
                    shift.worker_types = shift.worker_types.filter(record => record.id !== id);
                }
                this.addBlankWorkerTypeSelect(shift);
            },
            workerTypeChanged(shift) {
                this.addBlankWorkerTypeSelect(shift);
            },
            addBlankWorkerTypeSelect(shift) {
                //bug work around:
                //I could not figure out where the undefined IDs where coming from,
                //so, for now I just remove them.
                shift.worker_types = shift.worker_types.filter(record => record.id !== undefined);

                if (shift.worker_types.filter(record => record.id === 'x').length === 0) {
                    this.addRole(shift)
                }
            },



        }
    })
</script>
</body>
</html>




