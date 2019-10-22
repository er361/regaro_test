@extends('layouts.app')
@section('content')
    <form method="post" action="/p2">
        @csrf
        <div class="form-group">
            <label for="ogrn_label">Номер ОГРН</label>
            <input type="text"
                   name="ogrn_number"
                   maxlength="13"
                   id="ogrn_input"
                   class="form-control"
                   aria-describedby="emailHelp"
                   placeholder="Введите номер ОГРН">
            <div id="validationMessage" class="invalid-feedback">
            </div>

            <div class="valid-feedback">Все верно заполнено</div>
        </div>

        <div class="form-group">
            <div class="captcha mb-3">
                <span>{!! captcha_img() !!}</span>
                <button type="button" class="btn btn-success btn-refresh"><i class="fa fa-refresh"></i>Обновить</button>
            </div>
            <input id="captcha" type="text" class="form-control" placeholder="Введите каптчу" name="captcha" required>

            @if ($errors->has('captcha'))
                <span class="help-block"><strong>{{ $errors->first('captcha') }}</strong></span>
            @endif

        </div>

        <button id="findBtn" disabled type="submit" class="btn btn-primary">Найти</button>
    </form>
@endsection

@section('scripts')
    <script>

        $(".btn-refresh").click(function () {
            $.ajax({
                type: 'GET',
                url: '/refresh_captcha',
                success: function (data) {
                    $(".captcha span").html(data.captcha);
                }
            });
        });


        function setInputFilter(textbox, inputFilter) {
            ["input", "keydown", "keyup", "mousedown", "mouseup", "select", "contextmenu", "drop"].forEach(function (event) {
                textbox.addEventListener(event, function () {
                    if (inputFilter(this.value)) {
                        this.oldValue = this.value;
                        this.oldSelectionStart = this.selectionStart;
                        this.oldSelectionEnd = this.selectionEnd;
                    } else if (this.hasOwnProperty("oldValue")) {
                        this.value = this.oldValue;
                        this.setSelectionRange(this.oldSelectionStart, this.oldSelectionEnd);
                    }
                });
            });
        }

        setInputFilter(document.getElementById("ogrn_input"), function (value) {
            return /^\d*$/.test(value);
        });

        var ostatok;

        $('#ogrn_input').keyup(function (e) {

            if (this.value.length < 13) {
                $('#validationMessage').text('Длина символов должна быть 13 цифр');
                $('#validationMessage').show();

                //valid
                $('.valid-feedback').hide();
                $('#findBtn').prop('disabled', true);
            } else
                $('.invalid-feedback').hide();

            if (this.value.length === 12) {
                ostatok = parseInt(this.value) % 11;
            }

            if (this.value.length === 13) {
                if (parseInt(e.key) !== ostatok) {
                    $('#validationMessage').text('Контрольная сумма не верна');
                    $('#validationMessage').show();
                } else {
                    $('.valid-feedback').show();
                    $('#findBtn').prop('disabled', false);
                }
            }
        });
    </script>
@endsection