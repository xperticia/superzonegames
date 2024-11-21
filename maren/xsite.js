/* Script XPANEL 3     */
/* Update: 29/06/2013  */
/* ------------------- */

$(function() {

    //$(".tab-content > .tab-pane").css('min-height', "400px");

    if ($("#btEliminarBusqueda").length) {
        $("#btEliminarBusqueda").click(function(e) {
            var vURL = window.location.href.split('?');
            var vParametros = "";
            if (getUrlVars()["menu"]) {
                vParametros += vURL[0] + "?menu=" + getUrlVars()["menu"];
            } else {
                vParametros += vURL[0] + "?menu=productos";
            }

            /*
            $("input[name='q']").val("");
            $("select[name='c'] option[value='']").attr("selected", true).end().change();
            $("select[name='g'] option[value='']").attr("selected", true).end().change();
            $("input[name='q']").focus();
            */

            vParametros += "&q=" + $("input[name='q']").val();
            vParametros += "&c=";
            vParametros += "&g=";

            location = vParametros;
        })
    }

    if ($("#myCarousel").length) {
        $('#myCarousel').carousel();
    };

    if ($("#myCarouselPortada").length) {
        $('#myCarouselPortada').carousel({
            interval: 10000
        });
    };

    $('[type="submit"]').click(function() {
        if ($(".ckeditor").length) {
            UpdateCKEditors();
        }
    });

    if ($("a#btClickComprar").length) {
        $("a#btClickComprar").click(function(e) {
            $("a[href='#tab03']").trigger('click');
            e.preventDefault();
            id = $(this).attr("data-id");
            $.each($("#publicacion option"), function(key, val) {
                if (val.value == id) {
                    val.selected = true;
                } else {
                    val.selected = false;
                }
            })
        })
    }

    if ($("a#btClickInscribirse").length) {
        $("a#btClickInscribirse").click(function(e) {
            $("a[href='#tab03']").trigger('click');
            e.preventDefault();
            id = $(this).attr("data-id");
            $.each($("#curso option"), function(key, val) {
                if (val.value == id) {
                    val.selected = true;
                } else {
                    val.selected = false;
                }
            })
        })
    }

    if ($("input[name='q']").length) {
        $("input[name='q']").bind('keypress', function(e) {
            if (($(this).val() != "") && ($("input#i").val() > 1)) {
                $("select#categoria option").eq(0).prop("selected", true);
                $("select#genero option").eq(0).prop("selected", true);
            }
        })
    }

    if ($("#categoria").length) {
        $("#categoria").bind('change', function() {
            if ($(this).val() != "") {
                $('[type="submit"]').trigger("click");
            }
        })
    }

    if ($("#genero").length) {
        $("#genero").bind('change', function() {
            if ($(this).val() != "") {
                $('[type="submit"]').trigger("click");
            }
        })
    }

    if ($("a#btClickEditarEnlace").length) {
        $("a#btClickEditarEnlace").click(function(e) {
            e.preventDefault();
            $("tr[id^='divEnlace']").hide();
            if ($("#" + $(this).attr("data-id")).is(':visible')) {
                $("#" + $(this).attr("data-id")).hide();
            } else {
                $("#" + $(this).attr("data-id")).show();
            }
        })
    }

    if ($("input#btClickCancelarEditarEnlace").length) {
        $("input#btClickCancelarEditarEnlace").click(function(e) {
            if ($("#" + $(this).attr("data-id")).is(':visible')) {
                $("#" + $(this).attr("data-id")).hide();
            } else {
                $("#" + $(this).attr("data-id")).show();
            }
        })
    }

    if ($("a#btClickEliminarEnlace").length) {
        $("a#btClickEliminarEnlace").click(function(e) {
            //e.preventDefault();
            titulo = $(this).attr("data-titulo");
            id = $(this).attr("data-id");
            if (confirm("Se va a ELIMINAR el registro: \n\n[" + id + "] " + titulo + ".\n\nEsta usted seguro?")) {
                var vURL = window.location.href;
                vURL = vURL + "&accion=delete&id=" + id;
                window.location = vURL;
            }
        })
    }

    if ($("a[rel=colorbox]").length) {
        $.getScript("xpanel/scripts/colorbox/jquery.colorbox.js", function() {
            $("a[rel=colorbox]").each(function(idx, el) {
                if ($(el).attr("class") == "iframe") {
                    $("." + $(el).attr("class")).colorbox({ iframe: true, width: "80%", height: "80%" });
                } else {
                    if (($(el).attr("class") == "thumbnail archiveros") && $(el).attr("href")) {
                        $("a.archiveros").colorbox({ rel: "archiveros", slideshow: true, slideshowSpeed: 5000, transition: "fade", width: "75%", height: "75%" });
                    } else {
                        if (($(el).attr("class") == "thumbnail foto") && $(el).attr("href")) {
                            $("a.foto").colorbox({ rel: "foto", slideshow: true, slideshowSpeed: 5000, transition: "fade", width: "75%", height: "75%" });
                        } else {
                            $("." + $(el).attr("class")).colorbox({ rel: $(el).attr("class"), slideshow: true, slideshowSpeed: 5000, transition: "fade", width: "75%", height: "75%" });
                        }
                    }
                }
            })
        });
    };

    if ($("a#btnEliminarArchivo").length) {
        $("a#btnEliminarArchivo").click(function(e) {
            e.preventDefault();
            if ($(this).attr("data-texto") == "") {
                var vMsj = "Se va a eliminar el archivo: \n\n- [" + $(this).attr("data-nombre-archivo") + "]\n\nEsta usted seguro?";
            } else {
                var vMsj = "Se va a eliminar el archivo: \n\n- [" + $(this).attr("data-nombre-archivo") + "] " + $(this).attr("data-texto") + "\n\nEsta usted seguro?";
            }
            if (confirm(vMsj)) {
                var vURL = window.location.href.split('?');
                var fields = {};
                fields["idc"] = $(this).attr("data-contenido");
                fields["ida"] = $(this).attr("data-archivo");
                fields["tipo"] = $(this).attr("data-tipo");

                $.ajax({
                    url: vURL[0] + '?menu=' + getUrlVars()["menu"] + '&accion=deleteimagen',
                    data: fields,
                    type: 'POST',
                    dataType: 'text',
                    success: function(response) {
                        location.reload(true);
                    },
                    error: function(xhr, status) {
                        alert('Disculpe, existió un problema');
                    },
                    complete: function(xhr, status) {
                        //alert('Petición realizada');
                    }
                });
            }
        })
    }

    if ($("#map_canvas").length) {
        $.getScript("xpanel/scripts/googlemaps/ubicacion.js", function() {
            initialize();
        })
    }

    //	Procesamiento de los MENSAJES
    var tipo, titulo, id, contenido, url;
    if ($(".modal").length) {
        $("a[data-toggle='modal']").click(function(e) {
            $domModal = $($(this).attr("data-target"));
            tipo = $(this).attr("data-tipo");
            titulo = $(this).attr("title");
            switch (tipo) {
                case "Adjuntos":
                    contenido = $(this).attr("data-contenido");
                    id = $(this).attr("data-id");
                    url = 'html/formAdjuntos.php?menu=' + contenido + '&id=' + id;
                    break;
                case "Autor":
                    contenido = $(this).attr("data-contenido");
                    id = $(this).attr("data-id");
                    url = 'html/formAutor.php?id=' + id;
                    break;
                case "Suscripcion":
                    contenido = getUrlVars()["menu"];
                    id = getUrlVars()["id"];
                    url = 'html/formSuscripcion.html?nocache=' + (new Date()).getTime();
                    break;
                case "Registro":
                    contenido = $(this).attr("data-contenido");
                    id = $(this).attr("data-id");
                    url = 'html/formRegistro.php?t=' + tipo + '&id=' + id + '&nocache=' + (new Date()).getTime();
                    break;
                case "Producto":
                    titulo = "[" + $(this).attr("data-codigo") + "] " + $(this).attr("title");
                    contenido = getUrlVars()["menu"];
                    id = $(this).attr("data-id");
                    url = 'html/formProducto.php?t=' + tipo + '&id=' + id + '&nocache=' + (new Date()).getTime();
                    $.get("html/buscarDatos.php?t=click&menu=" + tipo + "&id=" + id);
                    break;
                default:
                    contenido = $(this).attr("data-contenido");
                    id = $(this).attr("data-id");
                    url = 'html/formMensajes.html?nocache=' + (new Date()).getTime();
                    break;
            }
        })
        $(".modal").on('show', function(e) {
            if ($(this).attr("id") == "ModalMensajes") {
                $("#ModalMensajes").html("<p id=\"vMsjCargando\" style=\"display: block;left: " + (($(".modal").outerWidth() - 250) / 2) + "px;right: 0;\"><img src=\"images/preloader.gif\" /> <span>obteniendo datos ...</span></p>");
                //	obtengo el contenido HTML
                $.ajax({
                    url: url,
                    success: function(data) {
                        $(".modal").html(data);

                        if ($("#formMensajes").length) {
                            $("#formMensajes").find(".modal-header").find("h3").text(titulo);
                            //	cargo el script de validacion
                            $.getScript("xpanel/scripts/h5f/h5f.js", function() {
                                H5F.setup($("#formMensajes"));
                            });
                            if ($("#codigo_pais").length) {
                                /*
                                $.getJSON('http://api.wipmania.com/jsonp?callback=?', function(data) {
                                    $("#codigo_pais option[value='" + data.address.country_code + "']").attr("selected", true);
                                });
                                */
                                if ($("#codigo_pais option:selected").val() == "") {
                                    $("#codigo_pais option[value='BO']").attr("selected", true);
                                }
                            }
                            $("#tipo_mensaje").val(tipo);
                            $("#tipo_cabeza").val(contenido);
                            $("#id_cabeza").val(id);
                            $("#asunto").val(tipo + ' desde "' + document.title + '"');

                            $("#formMensajes input:visible:enabled:first").focus();

                            switch (tipo) {
                                case "Comentarios":
                                case "Consultas":
                                    $(".modal").css("width", 560).css('margin-left', ($(".modal").outerWidth() / 2) * -1);
                                    $(".modal .modal-body").css("max-height", 400).css("overflow", "auto");
                                    break;
                                case "Registro":
                                    //$(".modal").css("width", 850).css('margin-left', ($(".modal").outerWidth() / 2) * -1);
                                    //$(".modal .modal-body").css("max-height", 400).css("overflow", "auto");
                                    break;
                            }
                        }

                        if ($("#formProducto").length) {

                            $('#myTabProducto a[data-toggle="tab"]').on('click', function(e) {
                                e.preventDefault();
                                //e.stopPropagation();
                                $("#myTabProducto li").removeClass("active");
                                $(this).closest("li").addClass("active");
                                $(".modal-body .tab-pane").removeClass("active");
                                $(".modal-body " + $(this).attr('href')).addClass("active");
                            });
                            if ($("#myCarousel").length) {
                                $('#myCarousel').carousel();
                            };

                            $(".modal-body .tab-content > .tab-pane").css('min-height', 330);

                            $("#formProducto").find(".modal-header").find("h3").text(titulo);
                            $("#tipo_mensaje").val(tipo);
                            $("#tipo_cabeza").val(contenido);
                            $("#id_cabeza").val(id);

                            if ($("a[rel=colorbox]").length) {
                                $.getScript("xpanel/scripts/colorbox/jquery.colorbox.js", function() {
                                    $("a[rel=colorbox]").each(function(idx, el) {
                                        if (($(el).attr("class") == "thumbnail foto") && $(el).attr("href")) {
                                            $("a.foto").colorbox({ rel: "foto", slideshow: true, slideshowSpeed: 5000, transition: "fade", width: "75%", height: "75%" });
                                        } else {
                                            $("." + $(el).attr("class")).colorbox({ rel: $(el).attr("class"), slideshow: true, slideshowSpeed: 5000, transition: "fade", width: "75%", height: "75%" });
                                        }
                                    })
                                });
                            };

                            $(".modal").css("width", 850)
                                .css('margin-left', ($(".modal").outerWidth() / 2) * -1);
                            $(".modal .modal-body").css("min-height", 420).css("max-height", 440).css("overflow", "auto");
                        }

                        if ($("#btGuardarComentario").length) {
                            $('#btGuardarComentario').click(function(e) {

                                //	verifico si esta el formulario completo
                                if (document.getElementById("formMensajes").checkValidity() === true) {
                                    //	Guardo los datos
                                    $.ajax({
                                        url: 'html/guardarMensajes.php',
                                        data: $('#formMensajes').serialize(),
                                        type: 'POST',
                                        success: function(request) {
                                            $("#formMensajes").find(".modal-body").html(request);
                                            $("#formMensajes").find(".modal-footer").html("<button type='button' class='btn btn-primary' id='btCerrarComentario' data-dismiss='modal'>Cerrar</button>");

                                            if ($("#btCerrarComentario").length) {
                                                $('#btCerrarComentario').click(function() {
                                                    $(".modal").hide();
                                                    if (tipo == "Comentarios") {
                                                        location.reload();
                                                    }
                                                })
                                            }
                                        },
                                        error: function(xhr, status) {
                                            alert('Disculpe, existió un problema');
                                        }
                                    });

                                    e.preventDefault();
                                }
                            });
                        }

                        if ($("#btGuardarSuscripcion").length) {
                            $('#btGuardarSuscripcion').click(function(e) {
                                //	me aseguro que el CAPTCHA sea completado
                                if ($("#recaptcha_div").length) {
                                    $("#recaptcha_response_field").attr("required", true);
                                }

                                //	verifico si esta el formulario completo
                                if (document.getElementById("formMensajes").checkValidity() === true) {

                                    //	verifico si el CAPTCHA es correcto
                                    var fields = {};
                                    fields["g-recaptcha-response"] = $('#g-recaptcha-response').val();

                                    $.ajax({
                                        url: "xpanel/scripts/obtenercaptcha.php?t=verificar",
                                        data: fields,
                                        type: 'POST',
                                        dataType: 'json',
                                        success: function(json) {
                                            if (json.label == "OK") {
                                                //	Guardo los datos
                                                $.ajax({
                                                    url: 'html/guardarSuscripcion.php',
                                                    data: $('#formMensajes').serialize(),
                                                    type: 'POST',
                                                    success: function(request) {
                                                        $("#formMensajes").find(".modal-body").html(request);

                                                        if ($("#btCerrarSuscripcion").length) {
                                                            $('#btCerrarSuscripcion').click(function() {
                                                                $(".modal").hide();
                                                            })
                                                        }
                                                    },
                                                    error: function(xhr, status) {
                                                        alert('Disculpe, existió un problema');
                                                    }
                                                });

                                            } else {
                                                switch (json.value) {
                                                    case "invalid-site-private-key":
                                                        alert("ERROR: No se ha podido verificar la clave privada.");
                                                        break;
                                                    case "invalid-request-cookie":
                                                        alert("ERROR: El parámetro challenge de la secuencia de comandos de verificación es incorrecta.");
                                                        break;
                                                    case "incorrect-captcha-sol":
                                                        alert("ERROR: El Código de verificación incorrecto.\nVuelva a intentarlo u obtenga un nuevo código.");
                                                        break;
                                                    case "recaptcha-not-reachable":
                                                        alert("ERROR: No se puede obtener el código de error.");
                                                        break;
                                                    default:
                                                        alert("ERROR: Obtenga un nuevo código de verificación.");
                                                        break;
                                                }
                                            }
                                        },
                                        error: function(xhr, status) {
                                            alert('Disculpe, existió un problema');
                                        }
                                    });

                                    e.preventDefault();
                                }
                            });
                        }

                        if ($("#btGuardarRegistro").length) {
                            $("#btGuardarRegistro").click(function(e) {
                                //	verifico si esta el formulario completo
                                if (document.getElementById("formMensajes").checkValidity() === true) {
                                    //	Guardo los datos
                                    $.ajax({
                                        url: 'html/guardarRegistro.php',
                                        data: $('#formMensajes').serialize(),
                                        type: 'POST',
                                        success: function(request) {
                                            $("#formMensajes").find(".modal-body").html(request);
                                            $("#formMensajes").find(".modal-footer").html("<button type='button' class='btn btn-primary' id='btCerrarRegistro' data-dismiss='modal'>Cerrar</button>");

                                            if ($("#btCerrarRegistro").length) {
                                                $("#btCerrarRegistro").click(function() {
                                                    $(".modal").hide();
                                                })
                                            }
                                        },
                                        error: function(xhr, status) {
                                            alert('Disculpe, existió un problema');
                                        }
                                    });
                                    e.preventDefault();
                                }
                            });
                        }

                    }
                });
            };
        });
        $(".modal").on('hidden', function() {
            $(this).empty();
            $("iframe[src='about:blank']").remove();
            $(this).removeAttr("style");
            $(this).hide();
        })

    }

    //	contador de click
    if ($("#btClick").length) {
        $("#btClick").click(function(e) {
            //e.preventDefault();
            contenido = $(this).attr("data-contenido");
            id = $(this).attr("data-id");
            $.get("html/buscarDatos.php?t=click&menu=" + contenido + "&id=" + id);
        })
    }

    //	centro las imagenes de la FOTOGALERIA
    $(window).resize(function() {
        if ($(".thumbnail a img").length) {
            $.each($(".thumbnail a img"), function(key, val) {
                //if ($(this).width() <= $(this).closest("div.thumbnail").width()) {
                $(this).css("margin-left", (($(this).closest("div.thumbnail").width() - $(this).width()) / 2));
                //}
            })
        }
    })
    $(window).trigger('resize');

    //	actualizo la ubicacion actual
    if ($("#path").length) {
        $("#path").hide();
        $("body header div.row:last div.span12 span").html(" &raquo; " + $("#path").text());
        $("title").text($("#path").text() + " | " + $("title").text());
    }

});

function showRecaptcha(element) {
    element = grecaptcha.render(document.getElementById('recaptcha_div'), { 'sitekey': '6Ld5ZWgUAAAAAJqTUmvORdua-Ztm8ILxSjxAYuuI' });
}

function getUrlVars() {
    var vars = [],
        hash;
    var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
    for (var i = 0; i < hashes.length; i++) {
        hash = hashes[i].split('=');
        vars.push(hash[0]);
        vars[hash[0]] = hash[1];
    }
    return vars;
}

function AbrirURL(t) {
    if ($(t).val()) {
        location = $(t).val();
    }
}

function UpdateCKEditors() {
    for (var i in CKEDITOR.instances) {
        CKEDITOR.instances[i].updateElement();
    }
}

/*
if($("#filter").length) {
	$("#filter").bind('keyup',function () {
		$(this).css('background-image','url(images/preloader.gif)').css('background-position','right').css('background-repeat','no-repeat');
		buscarEnTabla(this.value,"#listaProductos");
		$(this).css('background-image','').css('background-position','').css('background-repeat','');
		$("#resultadosFilter").html(($("#listaProductos tr:visible").length-1)+" resultados");
	});
}

function buscarEnTabla (texto,tabla) {
	var table = $(tabla);
	var value = texto;
	table.find("tr").each(function(index, row) {
		var allCells = $(row).find("td");
		$("#resultadosFilter").html(allCells.text());
		if(allCells.length > 0) {
			var found = false;
			allCells.each(function(index, td) {
				var regExp = new RegExp(value, 'i');
				if(regExp.test($(td).text())) {
					found = true;
					return false;
				}
			});
			if (found == true) $(row).show();
			else $(row).hide();
		}
	});
}
*/