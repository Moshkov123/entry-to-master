<x-app-layout>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous"></script>
    <link href="{{ URL::asset('air-datepicker.css') }}" rel="stylesheet">



    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div id="calendar"></div>
                    <h4 class="text-center my-3 pb-3">Запись к мастеру</h4>

                    <form class="row row-cols-lg-auto g-3 justify-content-center align-items-center mb-4 pb-2"
                        method="POST" action="/dashboard">
                        @csrf
                        <div class="col-12">
                            <div class="form-outline">
                                <input type="text" id="airdatepicker" class="form-control" name="airdatepicker"
                                    value="{{ old('airdatepicker', session('airdatepicker')) }}">
                            </div>
                        </div>

                        <script src="{{ URL::asset('air-datepicker.js') }}">

                        </script>
                        <script>
                            new AirDatepicker('#airdatepicker', {});
                        </script>



                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">Посмотреть записи</button>
                        </div>
                    </form>
                    @if(isset($availableRecordingTimes) && !$availableRecordingTimes->isEmpty())
                    <table class="table mb-4">
                        <thead>
                            <tr>
                                <th scope="col">Свободное время</th>
                                <th scope="col">кнопка</th>
                            </tr>
                        </thead>
                        @if(isset($user))
                        @foreach($availableRecordingTimes as $time)
                        <tr>
                            <td>{{$time->time }}</td>
                            <td>
                                
                                <form action="{{ route('dashboard.delete', ['id' => $user->id]) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Отменить запись</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                        @else
                        @foreach($availableRecordingTimes as $time)
                        <tr>
                            <td>{{$time->time }}</td>
                            <td>
                                <form method="POST" action="{{ route('book-appointment') }}">
                                    @csrf
                                    <input type="hidden" name="selectedTime" value="{{ $time->id }}">
                                    <button type="submit" class="btn btn-danger">записаться</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                        @endif
                    </table>
                    @else
                    <p>Нет свободнных записей</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>