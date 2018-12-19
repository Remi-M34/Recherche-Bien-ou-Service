$("#slider").slider({
    range: true,
    min: 0,
    max: 1440,
    step: 30,
    values: [420, 1020],
    slide: function (e, ui) {
        console.log(ui.values[0])
        var initialcombo = toClock(ui.values[0]) + '<span> à </span>' + toClock(ui.values[1]);
        $("#slider1").slider("values", [ui.values[0], ui.values[1]]);
        $("#slider2").slider("values", [ui.values[0], ui.values[1]]);
        $("#slider3").slider("values", [ui.values[0], ui.values[1]]);
        $("#slider4").slider("values", [ui.values[0], ui.values[1]]);
        $("#slider5").slider("values", [ui.values[0], ui.values[1]]);
        $("#slider6").slider("values", [ui.values[0], ui.values[1]]);
        $("#slider7").slider("values", [ui.values[0], ui.values[1]]);
        $('#amount').html(initialcombo);
        $('#amount1').html(initialcombo);
        $('#amount2').html(initialcombo);
        $('#amount3').html(initialcombo);
        $('#amount4').html(initialcombo);
        $('#amount5').html(initialcombo);
        $('#amount6').html(initialcombo);
        $('#amount7').html(initialcombo);
        document.getElementById('ph_d').value = toClock(ui.values[0]);
        document.getElementById('ph_f').value = toClock(ui.values[1]);
        document.getElementById('ph1_d').value = toClock(ui.values[0]);
        document.getElementById('ph1_f').value = toClock(ui.values[1]);
        document.getElementById('ph2_d').value = toClock(ui.values[0]);
        document.getElementById('ph2_f').value = toClock(ui.values[1]);
        document.getElementById('ph3_d').value = toClock(ui.values[0]);
        document.getElementById('ph3_f').value = toClock(ui.values[1]);
        document.getElementById('ph4_d').value = toClock(ui.values[0]);
        document.getElementById('ph4_f').value = toClock(ui.values[1]);
        document.getElementById('ph5_d').value = toClock(ui.values[0]);
        document.getElementById('ph5_f').value = toClock(ui.values[1]);
        document.getElementById('ph6_d').value = toClock(ui.values[0]);
        document.getElementById('ph6_f').value = toClock(ui.values[1]);
        document.getElementById('ph7_d').value = toClock(ui.values[0]);
        document.getElementById('ph7_f').value = toClock(ui.values[1]);

    }
});


$("#slider1").slider({
    range: true,
    min: 0,
    max: 1440,
    step: 30,
    values: [420, 1020],
    slide: function (e, ui) {
        console.log(ui.values[0])
        var initialcombo = toClock(ui.values[0]) + '<span> à </span>' + toClock(ui.values[1]);
        $('#amount1').html(initialcombo);

        document.getElementById('ph1_d').value = toClock(ui.values[0]);
        document.getElementById('ph1_f').value = toClock(ui.values[1]);

    }
});


$("#slider2").slider({
    range: true,
    min: 0,
    max: 1440,
    step: 30,
    values: [420, 1020],
    slide: function (e, ui) {
        console.log(ui.values[0])
        var initialcombo = toClock(ui.values[0]) + '<span> à </span>' + toClock(ui.values[1]);
        $('#amount2').html(initialcombo);

        document.getElementById('ph2_d').value = toClock(ui.values[0]);
        document.getElementById('ph2_f').value = toClock(ui.values[1]);
    }
});


$("#slider3").slider({
    range: true,
    min: 0,
    max: 1440,
    step: 30,
    values: [420, 1020],
    slide: function (e, ui) {
        console.log(ui.values[0])
        var initialcombo = toClock(ui.values[0]) + '<span> à </span>' + toClock(ui.values[1]);
        $('#amount3').html(initialcombo);

        document.getElementById('ph3_d').value = toClock(ui.values[0]);
        document.getElementById('ph3_f').value = toClock(ui.values[1]);
    }
});

$("#slider4").slider({
    range: true,
    min: 0,
    max: 1440,
    step: 30,
    values: [420, 1020],
    slide: function (e, ui) {
        console.log(ui.values[0])
        var initialcombo = toClock(ui.values[0]) + '<span> à </span>' + toClock(ui.values[1]);
        $('#amount4').html(initialcombo);

        document.getElementById('ph4_d').value = toClock(ui.values[0]);
        document.getElementById('ph4_f').value = toClock(ui.values[1]);
    }
});

$("#slider5").slider({
    range: true,
    min: 0,
    max: 1440,
    step: 30,
    values: [420, 1020],
    slide: function (e, ui) {
        console.log(ui.values[0])
        var initialcombo = toClock(ui.values[0]) + '<span> à </span>' + toClock(ui.values[1]);
        $('#amount5').html(initialcombo);

        document.getElementById('ph5_d').value = toClock(ui.values[0]);
        document.getElementById('ph5_f').value = toClock(ui.values[1]);
    }
});

$("#slider6").slider({
    range: true,
    min: 0,
    max: 1440,
    step: 30,
    values: [420, 1020],
    slide: function (e, ui) {
        console.log(ui.values[0])
        var initialcombo = toClock(ui.values[0]) + '<span> à </span>' + toClock(ui.values[1]);
        $('#amount6').html(initialcombo);

        document.getElementById('ph6_d').value = toClock(ui.values[0]);
        document.getElementById('ph6_f').value = toClock(ui.values[1]);
    }
});

$("#slider7").slider({
    range: true,
    min: 0,
    max: 1440,
    step: 30,
    values: [420, 1020],
    slide: function (e, ui) {
        console.log(ui.values[0])
        var initialcombo = toClock(ui.values[0]) + '<span> à </span>' + toClock(ui.values[1]);
        $('#amount7').html(initialcombo);

        document.getElementById('ph7_d').value = toClock(ui.values[0]);
        document.getElementById('ph7_f').value = toClock(ui.values[1]);
    }
});

function toClock(aa) {
    var hours = Math.floor(aa / 60);
    var minutes = aa - (hours * 60);
    // var ampm = "";

    // if (hours.length == 1) {
    //     hours = '12' + hours;
    // }
    // if (hours > 12) {
    //     ampm = "PM";
    // }
    // else if (hours == 12) {
    //     ampm = "PM";
    // }
    // else if (hours < 12) {
    //     ampm = "AM";
    //     if (hours == 0) hours = 12;
    //
    // }
    if (minutes == 0) {
        minutes = '0' + minutes;
    }
    var combo = hours + ':' + minutes;
    return combo
}

function toUnits(bb) {
    var hours = Math.floor(bb / 60);
    var minutes = bb - (hours * 60);
}

function toInitialize(a, b, c) {
    console.log(a);
    if (a == '') {
    }
    else {
        var new_start = a.split(':');
        console.log(new_start);
        $(c).slider("values", [480, 1080]);

    }
}

function updateLabels() {
    $('#amount').html(toClock($("#slider").slider("values", 0)) + '<span> à </span>' + toClock($("#slider").slider("values", 1)));
    $('#amount1').html(toClock($("#slider1").slider("values", 0)) + '<span> à </span>' + toClock($("#slider1").slider("values", 1)));
    $('#amount2').html(toClock($("#slider2").slider("values", 0)) + '<span> à </span>' + toClock($("#slider2").slider("values", 1)));
    $('#amount3').html(toClock($("#slider3").slider("values", 0)) + '<span> à </span>' + toClock($("#slider3").slider("values", 1)));
    $('#amount4').html(toClock($("#slider4").slider("values", 0)) + '<span> à </span>' + toClock($("#slider4").slider("values", 1)));
    $('#amount5').html(toClock($("#slider5").slider("values", 0)) + '<span> à </span>' + toClock($("#slider5").slider("values", 1)));
    $('#amount6').html(toClock($("#slider6").slider("values", 0)) + '<span> à </span>' + toClock($("#slider6").slider("values", 1)));
    $('#amount7').html(toClock($("#slider7").slider("values", 0)) + '<span> à </span>' + toClock($("#slider7").slider("values", 1)));
}


// get numbers to start off with
toInitialize($('#ph_d').val(), $('#ph_f').val(), "#slider");
toInitialize($('#ph1_d').val(), $('#ph1_f').val(), "#slider1");
toInitialize($('#ph2_d').val(), $('#ph2_f').val(), "#slider2");
toInitialize($('#ph3_d').val(), $('#ph3_f').val(), "#slider3");
toInitialize($('#ph4_d').val(), $('#ph4_f').val(), "#slider4");
toInitialize($('#ph5_d').val(), $('#ph5_f').val(), "#slider5");
toInitialize($('#ph6_d').val(), $('#ph6_f').val(), "#slider6");
toInitialize($('#ph7_d').val(), $('#ph7_f').val(), "#slider7");
// update the labels
updateLabels();