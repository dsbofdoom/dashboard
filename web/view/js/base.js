$(document).ready(function () {
    abrirMenu();

    $("[data-inputmask]").inputmask();

    var spinner = new Spinner({
        // The number of lines to draw
        lines: 13,
        // The length of each line
        length: 13,
        // The line thickness
        width: 8,
        // The radius of the inner circle
        radius: 17,
        // Scales overall size of the spinner
        scale: 1,
        // Corner roundness (0..1)
        corners: 1,
        // #rgb or #rrggbb or array of colors
        color: '#FFF',
        // Opacity of the lines
        opacity: 0.25,
        // The rotation offset
        rotate: 0,
        // 1: clockwise, -1: counterclockwise
        direction: 1,
        // Rounds per second
        speed: 1,
        // Afterglow percentage
        trail: 60,
        // Frames per second when using setTimeout() as a fallback for CSS
        fps: 20,
        // The z-index (defaults to 2000000000)
        zIndex: 2e9,
        // The CSS class to assign to the spinner
        className: 'spinner',
        // Top position relative to parent
        top: '50%',
        // Left position relative to parent
        left: '50%',
        // Whether to render a shadow
        shadow: false,
        // Whether to use hardware acceleration
        hwaccel: false,
        // Element positioning
        position: 'absolute'
    }).spin($('#spinLoading')[0]);

    $(document).bind("ajaxSend", function () {
        $("#spinLoading").show();
    }).bind("ajaxComplete", function () {
        $("#spinLoading").hide();
    });
});

function abrirMenu() {
    var url = document.URL;

    url = url.substring(url.lastIndexOf("/") + 1);

    if (url.indexOf("?") >= 0)
        url = url.substring(0, url.indexOf("?"));

    while (url.indexOf("#") >= 0)
        url = url.replace("#", "");

    if (url.length != 0) {
        var sidebar = $(".sidebar-menu");

        var todosA = sidebar.find("a");
        if (todosA.length > 0)
            for (var i = 0; i < todosA.length; i++) {
                var a = $(todosA[i]);
                var link = $(todosA[i]).attr("href");

                if (link && link.indexOf(url) >= 0) {
                    var li = a.parent("li");

                    if (li.children("a[href='#']").length == 0)
                        li = a.parent("li").parent("ul").parent("li");

                    do {
                        a = $(li.children("a[href='#']")[0]);
                        a.click();

                        li = li.parent("ul").parent("li");
                        if (li.children("a[href='#']").length == 0)
                            li = li.parent("ul").parent("li");
                    } while (li.length != 0);
                    break;
                }
            }
    }
}

function ajax(endereco, dados, success, retorno) {
    $.ajax({
        type: "POST",
        url: endereco,
        data: dados
    }).done(function (dados) {
        try {
            dados = JSON.parse(dados);
        } catch (e) {
            console.log($(dados).text());
            return;
        }

        if (dados && dados.msg) {
            if (debug) {
                if (dados.msg.texto && dados.msg.texto.length > 0) {
                    var msg = dados.msg.texto + (dados.console ? (dados.console.trace ? "<br>" + dados.console.trace : "") + (dados.console.msg ? "<br>" + dados.console.msg : "") + (dados.console.msgAnterior ? "<br>" + dados.console.msgAnterior : "") : "");

                    if (msg && msg.length > 0)
                        bootbox.alert({
                            message: msg,
                            size: 'large'
                        });
                }
            } else if (dados.msg.texto && dados.msg.texto.length > 0)
                $.notify(dados.msg.texto + (dados.msg.trace ? " " + dados.msg.trace : ""), dados.msg.tipo);

            if (dados.console) {
                console.log(dados.console.trace);
                console.log(dados.console.msg);
                if (dados.console.msgAnterior)
                    console.log(dados.console.msgAnterior);
            }

            if (dados.retorno)
                retorno();

            if (success) {
                success(dados.valores);
            }
        }
    });
}

function adicionar(campo) {
    // Busca por '"form_" + campo' e append em '"campos_" + campo'
    var campos = $("#campos_" + campo),
        id = campos.find("div").length + 1;

    $("<div/>", {
        id: campo + id
    }).appendTo(campos);

    var div = $("#" + campo + id);
    div.html($("#form_" + campo).html());

    var botao = $(div.find("#remover"));
    botao.attr("data-map", campo + id);
    botao.on("click", function () {
        $("#" + botao.attr("data-map")).remove();
    });
}

function forceRequired(atual, campos) {
    if ($(atual).val().length == 0) {
        $.each(campos, function (index, item) {
            $("#" + item).removeAttr('required');
        });
    } else {
        $.each(campos, function (index, item) {
            $("#" + item).attr('required', 'required');
        });
    }
}