<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Recordingtime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RecordingtimeController extends Controller
{
    public function index()
    {

        return view('dashboard');
    }

    public function check(Request $request)
{
    $validData = $request->validate([
        'airdatepicker' => 'required|min:2|max:120',
    ]);

    $request->session()->flash('airdatepicker', $request->input('airdatepicker'));

    // Получить все контакты для выбранной даты
    $contacts = Contact::where('day', $request->input('airdatepicker'))->get();

    // Проверить, есть ли у пользователя записи на выбранную дату
    $userHasAppointments = $contacts->where('users_id', Auth::id())->isNotEmpty();

    if ($userHasAppointments) {
        // Если у пользователя есть записи, отобразить забронированные записи
        $userAppointments = $contacts->where('users_id', Auth::id());
        return view('dashboard', ['userAppointments' => $userAppointments]);
    } else {
        // Если у пользователя нет записей, отобразить доступные времена записи
        $allRecordingTimes = Recordingtime::all();
        $existingRecordingTimesIds = $contacts->pluck('recordingtimes_id')->flatten()->unique();
        $availableRecordingTimesIds = $allRecordingTimes->pluck('id')->diff($existingRecordingTimesIds);
        $availableRecordingTimes = Recordingtime::whereIn('id', $availableRecordingTimesIds)->get();
        return view('dashboard', ['availableRecordingTimes' => $availableRecordingTimes]);
    }
}


    public function bookAppointment(Request $request)
    {
        // Валидация данных
        $valid = $request->validate([
            'selectedTime' => 'required', // Добавляем валидацию выбранного времени записи
        ]);

        // Получаем ID аутентифицированного пользователя
        $userId = Auth::id();

        $airdatepicker = $request->session()->get('airdatepicker');
        // Создаем новую запись контакта
        $record = new Contact();
        $record->day = $airdatepicker; // Пример даты, которая должна быть получена из формы
        $record->recordingtimes_id = $request->input('selectedTime'); // Связываем запись контакта с выбранным временем записи
        $record->users_id = $userId;
        $record->save();

        // Получаем все контакты для выбранной даты
        $contacts = Contact::where('day', $record->day)->get();

        // Возвращаем представление с обновленным списком контактов
        return redirect()->route('dashboard')->with('contacts', $contacts);
    }



}
