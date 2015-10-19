// datapicker
$(function ($) {
    $.datepicker.regional['ru'] = {
        closeText: 'Закрыть',
        prevText: '&#x3c;Пред',
        nextText: 'След&#x3e;',
        currentText: 'Сегодня',
        monthNames: ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'],
        monthNamesShort: ['Янв', 'Фев', 'Мар', 'Апр', 'Май', 'Июн', 'Июл', 'Авг', 'Сен', 'Окт', 'Ноя', 'Дек'],
        dayNames: ['воскресенье', 'понедельник', 'вторник', 'среда', 'четверг', 'пятница', 'суббота'],
        dayNamesShort: ['вск', 'пнд', 'втр', 'срд', 'чтв', 'птн', 'сбт'],
        dayNamesMin: ['Вс', 'Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб'],
        weekHeader: 'Не',
        dateFormat: 'dd.mm.yy',
        firstDay: 1,
        isRTL: false,
        showMonthAfterYear: false,
        yearSuffix: ''
    };
    $.datepicker.setDefaults($.datepicker.regional['ru']);
});

function updateCoords(c) {
    $('#x').val(c.x);
    $('#y').val(c.y);
    $('#w').val(c.w);
    $('#h').val(c.h);
}

function checkCoords() {
    if (parseInt($('#w').val())) return true;
    alert('Выделите область прежде чем отправлять на сервер');
    return false;
}

$(document).ready(function () {

    // datapicker
    $('#create_date').datepicker({
        showWeek: true,
        changeMonth: true,
        changeYear: true,
        minDate: new Date(2008, 0, 1),
        maxDate: new Date()
    });

    // crop
    $('#cropbox').Jcrop({
        aspectRatio: 20 / 9,
        onSelect: updateCoords
    });

    $('#cropform').submit(function () {
        return checkCoords();
    });

    // select
    $('.chosen').chosen({
        allow_single_deselect: true
    });

    // spoiler
    $('div.spoiler_content').hide();
    $('div.spoiler a').click(function () {
        $(this).parent().children('.spoiler_content').slideToggle('slow');
    });

    // validation
    $('.validation').each(function (indx, element) {
        if ($(element).val() != '') {
            $(element).addClass('success');
        }
    });

    var elements = $('.validation').length;
    var has = $('.success').length;

    if (has == elements) {
        $('.submit').prop('disabled', false);
    }
    else {
        $('.submit').prop('disabled', true);
    }

    $('.validation').each(function (indx, element) {
        $(element).change(function () {
            var name = $(element).attr('name');
            var data = {};
            data[name] = $(this).val();

            $.ajax({
                type: 'POST',
                url: '',
                dataType: 'json',
                data: data,
                beforeSend: function () {
                    $(element).removeClass('success error');
                    $(element).parent('.control').prevAll('label').removeClass('success error');
                    $(element).nextAll('.msg').empty();
                },
                success: function (msg) {
                    $(element).parent('.control').prevAll('label').addClass(msg['status']);
                    $(element).addClass(msg['status']);

                    var message = msg['msg'];

                    for (var el in message) {
                        $(element).nextAll('.msg').append('<p class="help error">' + message[el] + '</p>');
                    }
                }
            });

            setTimeout(function () {
                has = $('.success.validation').length;

                if (has == elements) {
                    $('.submit').prop('disabled', false);
                }
                else {
                    $('.submit').prop('disabled', true);
                }
            }, 500);
        });
    });
});