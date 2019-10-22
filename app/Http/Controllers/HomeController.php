<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use CBR\CurrencyDaily;

class HomeController extends Controller
{
    //


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function refreshCaptcha()
    {
        return response()->json(['captcha'=> captcha_img()]);
    }


    public function index()
    {
        return view('index');
    }

    public function p2(Request $request)
    {
        request()->validate([
            'ogrn_number' => 'required',
            'captcha' => 'required|captcha'
        ],['captcha.captcha'=>'Неверный код каптчи']);

        $ogrn_number = $request->post('ogrn_number');
        return view('p2',['ogrn' => $ogrn_number]);
    }

    public function curs(Request $request)
    {
        try {
            $handler = new CurrencyDaily();
            $result = $handler
                ->setDate($request->post('date')) // Опционально, дата в формате "d/m/Y"
                ->setCodes(['USD']) // Опционально, фильтр по кодам валют
                //->setCodes(['840', '978']) Или можно так
                ->request() // Выполнение запроса
                ->getResult();
            /* Вернется именованный массив
            ->getResult() - ключи по умолчанию: буквенные коды валют (USD, EUR)
            ->getResult(CurrencyDaily::KEY_NUM) - ключи: цифровые коды валют (840, 978)
            ->getResult(CurrencyDaily::KEY_ID) - ключи: уникальные ID валют в формате Банка России,
            используются для получения динамики котировок по валюте за период времени */

            /* Дата обновления ставок в результате может не совпадать с той, которую вы указали (по выходным дням,
            например, ставки не обновляются). Актуальная дата, по которой вы получили информацию
            сохраняется в хендлере после вызова getResult() и ее можно получить так */
            $date = $handler->getResultDate('Y-m-d'); // Формат по умолчанию - 'Y-m-d'
            /* Вывод в XML
            Возвращает оригинальный XML, полученный с сервера, игнорирует фильтр по кодам валют.
            Я подразумеваю, если вам нужен XML на выходе, то вы отлично знаете, что с ним делать */
            return response()->json(['data' => $result]);
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }
}
