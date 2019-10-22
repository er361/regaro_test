@extends('layouts.app')
@section('css')
    <link rel="stylesheet" href="css/jquery-ui.min.css">
@endsection
@section('content')
    <h2>Ваш номер ОГРН - {{$ogrn}}</h2>

    <div class="form-group">
        <label for="datePicker">Выберите дату</label>
        <input id="datePicker" class="form-control" >
    </div>

    <div id="USD">Доллар США $ — <strong id="USD_VAL">00,0000</strong> руб.</div>



@endsection
@section('scripts')
    <script src="js/jquery-ui.min.js"></script>
    <script>
        $('#datePicker').datepicker({
            onSelect: function(dateText) {
                $.post('/curs',{date: dateText,"_token": "{{ csrf_token() }}",},function (json) {

                    let usd = json.data.USD;


                    $('#USD_VAL').text(usd.Value);
                });
                // console.log("Selected date: " + dateText + "; input's current value: " + this.value);
            },
            dateFormat: 'dd-mm-yy'
        });
    </script>
@endsection