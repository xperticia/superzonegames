/* Script XPANEL 3.1	*/
/* Fecha: 03/08/2023	*/
/* --------------------	*/

$(function () {

	$(window).resize(function () {
		//$("title").text($(window).width()+" / "+$(window).height());
		$("body > .container-fluid:first").css('min-height', ($(window).height() - 135) + "px");
		$(".tab-content > .tab-pane").css('min-height', ($(window).height() - 355) + "px");
	})
	$(window).trigger('resize');

	if ($("a[rel=colorbox]").length) {
		$.getScript("../scripts/colorbox/jquery.colorbox.js", function () {
			$("a[rel=colorbox]").each(function (idx, el) {
				if ($(el).attr("class") == "iframe") {
					$("." + $(el).attr("class")).colorbox({ iframe: true, width: "80%", height: "80%" });
				} else {
					$("." + $(el).attr("class")).colorbox({ rel: $(el).attr("class"), width: "75%", height: "75%" });
				}
			})
		});
	};

	if ($("#imagenContador").length) {
		setInterval(function () {
			$("#imagenContador").attr("src", "../images/preloader.gif");
			$("#imagenContador").attr("src", "contador.png?" + (new Date().getTime()));
		}, 5000);

	};

	if ($("#signup").length) {
		$.getScript("../scripts/h5f/h5f.js", function () {
			H5F.setup(document.getElementById("signup"));
		});
	};

	if ($("#listado").length) {
		$.getScript("../scripts/jquery.stickytableheaders.js", function () {
			$("#listado").stickyTableHeaders();
		});
	};

	if ($("#dropbox").length) {
		$.getScript("../scripts/html5-file-upload/js/jquery.filedrop.js", function () {
			$.getScript("../scripts/html5-file-upload/js/script.js", function () { });
		});
	};

	$('[type="submit"]').click(function (e) {
		if (getUrlVars()["menu"] == "productos") {
			$("#nro_serie option").attr("selected", true);
		}
		if (((getUrlVars()["menu"] == "ventas") || (getUrlVars()["menu"] == "servicios")) && $("#id_cliente").length) {
			if ($("#id_cliente").val() == "") {
				alert("Recuerde seleccionar o buscar un CLIENTE para asignar.");
				return false;
			}
		}
		if ((getUrlVars()["menu"] == "ventas") && $("#recibido").val() < $("#total").val()) {
			alert('El monto RECIBIDO es menor al monto TOTAL');
			return false;
		}
		if ($(".ckeditor").length) {
			UpdateCKEditors();
		}
		if($(this).attr('name') == 'guadartipomoneda'){			
			var form = $(this).closest('form');
			const selectTipoMoneda = form.find('select[name="tipo_moneda"]')
			const inputTipoCambio = form.find('input[name="tipo_cambio_actual"]')
			var vImporte = inputTipoCambio.val().replace('.', '').replace(',', '.');
			if((selectTipoMoneda.val() !== 'Bs') && (Number(vImporte) == 0)){
				e.preventDefault()
				inputTipoCambio.focus()
				alert("Debe ingresar el valor del tipo de cambio en Bs de un "+selectTipoMoneda.val()+".");
			} else {
				if(selectTipoMoneda.val() == 'Bs'){
					inputTipoCambio.val(0).trigger('change')
				}
			}
		}
	});

	if ($(".tooltip-demo").length) {
		$('.tooltip-demo').tooltip({
			selector: "a[data-toggle=tooltip]"
		})
	}

	if ($("a[rel=popover]").length) {
		$("a[rel=popover]")
			.popover({
				offset: 10
			})
			.click(function (e) {
				e.preventDefault();
			})
	}

	if ($("#codigo_pais").length) {
		if ($('#codigo_pais option:selected').val() == "") {
			if (getUrlVars()["accion"] != "editar") {
				$("#codigo_pais option[value='BO']").attr("selected", true);
			}
		}
	}

	if ((getUrlVars()["menu"] == "clientes") || (getUrlVars()["menu"] == "productos") || (getUrlVars()["menu"] == "usuarios")) {
		if ($("input#categoria").length) {
			$("input#categoria").keyup(function () {
				$(this).val($(this).val().toUpperCase());
			});
		}
		if ($("input#nombre").length) {
			$("input#nombre").keyup(function () {
				$(this).val($(this).val().toUpperCase());
			});
		}
		if ($("input#apellidos").length) {
			$("input#apellidos").keyup(function () {
				$(this).val($(this).val().toUpperCase());
			});
		}
	}

	if ($("input#nro_doc").length || $("input#email").length) {
		var formatearTexto = function () {
			$(this).val($(this).val().toLowerCase());

			// asigno el nombre de usuario
			if (getUrlVars()["accion"] == "nuevo") {
				$("#usuario").val($.trim($("input#email").val().toLowerCase()));
				$("#clave").val($.trim($("input#nro_doc").val().toLowerCase()));
			}
		};
		$("input#nro_doc").bind('change', formatearTexto);
		$("input#nro_doc").bind('keypress', formatearTexto);

		$("input#email").bind('change', formatearTexto);
		$("input#email").bind('keypress', formatearTexto);
	}

	if ($("button#btAumentarCampo").length) {
		$("button#btAumentarCampo").click(function () {
			if ($("#" + $(this).attr("data-campo")).val() == "") {
				$("#" + $(this).attr("data-campo")).val("0");
			}
			var vValor = Number($("#" + $(this).attr("data-campo")).val());
			if ($(this).attr("data-tipo") == "-") {
				if ($(this).attr("data-limite") !== undefined) {
					if (vValor > $(this).attr("data-limite")) {
						$("#" + $(this).attr("data-campo")).val(vValor - 1);
					} else {
						$("#" + $(this).attr("data-campo")).val($(this).attr("data-limite"));
					}
				} else {
					$("#" + $(this).attr("data-campo")).val(vValor - 1);
				}
			}
			if ($(this).attr("data-tipo") == "+") {
				if ($(this).attr("data-limite") !== undefined) {
					if (vValor < $(this).attr("data-limite")) {
						$("#" + $(this).attr("data-campo")).val(vValor + 1);
					} else {
						$("#" + $(this).attr("data-campo")).val($(this).attr("data-limite"));
					}
				} else {
					$("#" + $(this).attr("data-campo")).val(vValor + 1);
				}
			}
			if (($(this).attr("data-origen") !== undefined) && ($(this).attr("data-destino") !== undefined)) {
				$("#" + $(this).attr("data-destino")).val(Number($("#" + $(this).attr("data-origen")).val()) + Number($("#" + $(this).attr("data-campo")).val()));
			}
		})
	}

	if ($("#creditos_venta").length) {
		$("#creditos_venta").bind('keypress', function (e) {
			if (e.which == 13) {
				if ($(this).siblings("#btAumentarCampo[data-tipo='+']").prop("disabled") == false) {
					$(this).val((Number($(this).val()) - 1));
					$(this).siblings("#btAumentarCampo[data-tipo='+']").trigger("click");
				}
				$(this).focus();
				return false;
			}
		})
	}

	if ($("#gratis_venta").length) {
		$("#gratis_venta").bind('keypress', function (e) {
			if (e.which == 13) {
				if ($(this).siblings("#btAumentarCampo[data-tipo='+']").prop("disabled") == false) {
					$(this).val((Number($(this).val()) - 1));
					$(this).siblings("#btAumentarCampo[data-tipo='+']").trigger("click");
				}
				$(this).focus();
				return false;
			}
		})
	}

	/* BUSCAR CLIENTES */
	if ($("#listaClientes").length) {
		$.getScript("../scripts/stupidtable.js?dev", function () {
			$("#listaClientes").stupidtable();
		});
	}

	if ($("button#btBuscarClienteVentana").length) {
		$("button#btBuscarClienteVentana").click(function (e) {
			e.preventDefault();

			$domModal = $($(this).attr("data-target"));
			$domModal.find('input[name="origen"]').val(e.target.id);
			$domModal.find(".modal-header h3").text("Buscar cliente");
			$domModal.find(".modal-header #codigoCliente").val("");
			$domModal.find(".modal-header #documentoCliente").val("");
			$domModal.find(".modal-header #nombreCliente").val("");
			$domModal.find(".modal-header #telefonosCliente").val("");
			$domModal.find(".modal-header #queryCliente").val("");

			$domModal.find(".modal-body #listaClientes").children("tbody").html("");

			$domModal.find(".modal-body").css('height', 350);
			$domModal.css('width', 830);

			$("input#btnModalCancelar").click(function () {
				$domModal.modal('hide');
			});

		})
	}

	const array1 = ['ventas', 'egresos', 'traspasos', 'cotizaciones', 'inventarios'];
	if ((array1.includes(getUrlVars()["menu"]) == true) && $("#queryCliente").length) {
		$("#queryCliente").bind('keydown', function (e) {
			if (e.which == 13) {
				$("button#btnModalBuscarCliente").trigger("click");
			}
		});
	}

	if (((getUrlVars()["menu"] == "ventas") || (getUrlVars()["menu"] == "egresos") || (getUrlVars()["menu"] == "traspasos") || (getUrlVars()["menu"] == "cotizaciones") || (getUrlVars()["menu"] == "inventarios")) && $("#codigoCliente").length) {
		$("#codigoCliente").bind('keydown', function (e) {
			if (e.which == 13) {
				$("button#btnModalBuscarCliente").trigger("click");
			}
		});
	}

	if (((getUrlVars()["menu"] == "ventas") || (getUrlVars()["menu"] == "egresos") || (getUrlVars()["menu"] == "traspasos") || (getUrlVars()["menu"] == "cotizaciones") || (getUrlVars()["menu"] == "inventarios")) && $("#documentoCliente").length) {
		$("#documentoCliente").bind('keydown', function (e) {
			if (e.which == 13) {
				$("button#btnModalBuscarCliente").trigger("click");
			}
		});
	}

	if (((getUrlVars()["menu"] == "ventas") || (getUrlVars()["menu"] == "egresos") || (getUrlVars()["menu"] == "traspasos") || (getUrlVars()["menu"] == "cotizaciones") || (getUrlVars()["menu"] == "inventarios")) && $("#nombreCliente").length) {
		$("#nombreCliente").bind('keydown', function (e) {
			if (e.which == 13) {
				$("button#btnModalBuscarCliente").trigger("click");
			}
		});
	}

	if (((getUrlVars()["menu"] == "ventas") || (getUrlVars()["menu"] == "egresos") || (getUrlVars()["menu"] == "traspasos") || (getUrlVars()["menu"] == "cotizaciones") || (getUrlVars()["menu"] == "inventarios")) && $("#telefonosCliente").length) {
		$("#telefonosCliente").bind('keydown', function (e) {
			if (e.which == 13) {
				$("button#btnModalBuscarCliente").trigger("click");
			}
		});
	}

	$("button#btnModalBuscarCliente").click(function (e) {
		e.preventDefault();

		$domModal = $($(this).attr("data-target"));
		$domModal.find(".modal-body #listaClientes").children("tbody").html("");
		$domModal.find(".modal-body #listaClientes").children("tbody:first").append("<tr><td colspan='7' style='text-align: center;background: #ffffcc;color: #808080;'><img src='../images/preloader.gif' /> cargando datos ...</td></tr>");

		vParametros = "";
		vParametros = vParametros + "&s=" + $("#sucursal").val();
		if ($domModal.find(".modal-header #codigoCliente").length) {
			vParametros = vParametros + "&cc=" + $domModal.find(".modal-header #codigoCliente").val();
		}
		if ($domModal.find(".modal-header #documentoCliente").length) {
			vParametros = vParametros + "&d=" + unescape(encodeURIComponent($domModal.find(".modal-header #documentoCliente").val()));
		}
		if ($domModal.find(".modal-header #nombreCliente").length) {
			vParametros = vParametros + "&n=" + unescape(encodeURIComponent($domModal.find(".modal-header #nombreCliente").val()));
		}
		if ($domModal.find(".modal-header #telefonosCliente").length) {
			vParametros = vParametros + "&tc=" + unescape(encodeURIComponent($domModal.find(".modal-header #telefonosCliente").val()));
		}
		if ($domModal.find(".modal-header #queryCliente").length) {
			vParametros = vParametros + "&q=" + unescape(encodeURIComponent($domModal.find(".modal-header #queryCliente").val()));
		}

		$.ajax({
			url: 'buscarDatos.php?t=cliente' + vParametros,
			dataType: 'json',
			timeout: 1000,
			async: false,
			success: function (data) {
				$domModal.find(".modal-body #listaClientes").children("tbody").html("");
				if (data.length) {
					$.each(data, function (key, elemento) {
						if ($("#id_cliente[value='" + elemento.codigo + "']").length > 0) {
							$domModal.find(".modal-body #listaClientes").children("tbody:first").append("<tr class=\"unChecked\" style=\"color: #dddddd;\"><td><input type=\"radio\" name=\"registros\" value=\"" + elemento.value + "\" disabled=\"disabled\" /></td><td>" + elemento.codigo + "</td><td>" + elemento.documento + "</td><td>" + elemento.label + "</td><td>" + elemento.telefonos + "</td><td style=\"text-align: right;\">" + elemento.creditos + "<input type=\"hidden\" name=\"domicilio\" value=\"" + elemento.domicilio + "\" /></td><td style=\"text-align: right;\">" + elemento.gratis + "<input type=\"hidden\" name=\"categoria_usuario\" value=\"" + elemento.categoria_usuario + "\" /><input type=\"hidden\" name=\"comentarios\" value=\"" + elemento.comentarios + "\" /></td></tr>");
						} else {
							$domModal.find(".modal-body #listaClientes").children("tbody:first").append("<tr class=\"unChecked\"><td><input type=\"radio\" name=\"registros\" value=\"" + elemento.value + "\" /></td><td>" + elemento.codigo + "</td><td>" + elemento.documento + "</td><td>" + elemento.label + "</td><td>" + elemento.telefonos + "</td><td style=\"text-align: right;\">" + elemento.creditos + "<input type=\"hidden\" name=\"domicilio\" value=\"" + elemento.domicilio + "\" /></td><td style=\"text-align: right;\">" + elemento.gratis + "<input type=\"hidden\" name=\"categoria_usuario\" value=\"" + elemento.categoria_usuario + "\" /><input type=\"hidden\" name=\"comentarios\" value=\"" + elemento.comentarios + "\" /></td></tr>");
						}
					});

					if ($("input[name='registros']").length) { //	marco la fila seleccionada
						$("input[name='registros']").click(function () {

							$domModal.find(".modal-footer #btnModalGuardarCliente").focus();

							$("#listaClientes > tbody:first").find("input[name='registros']").each(function () {
								if ($(this).prop("checked")) {
									$(this).closest("tr").attr("class", "Checked");
								} else {
									$(this).closest("tr").attr("class", "unChecked");
								}
							});

						})
					}

					$("table#listaClientes tbody tr").click(function () {
						if ($(this).find("td").eq(0).find("input[name='registros']").prop('disabled') == false) {
							$(this).find("td").eq(0).find("input[name='registros']").prop('checked', true);

							$domModal.find(".modal-footer #btnModalGuardarCliente").focus();

							$("#listaClientes > tbody:first").find("input[name='registros']").each(function () {
								if ($(this).prop("checked")) {
									$(this).closest("tr").attr("class", "Checked");
								} else {
									$(this).closest("tr").attr("class", "unChecked");
								}
							});
						}

					});

				} else {
					$domModal.find(".modal-body #listaClientes").children("tbody:first").append("<tr><td colspan='7' style='text-align: center;background: #ffffcc;color: #808080;'> No hay coincidencias encontradas</td></tr>");
				}
			}
		})

	});

	$("input#btnModalGuardarCliente").click(function (e) {
		//e.stopPropagation();
		e.preventDefault();

		$domModal = $($(this).attr("data-target"));

		if ($("#listaClientes > tbody:first").find("tr").length == 0) {
			alert(htmlDecode("Debe buscar un CLIENTE para seleccionar, ya sea por su C&oacute;digo, Tipo y N&deg; de Documento, Nombres y Apellidos o su N&deg; de Tel&eacute;fono."));
			$("#nombreCliente").focus();
		} else {

			if ($("#listaClientes > tbody:first").find("input[name='registros']:checked").length == 0) {
				alert("Debe seleccionar un CLIENTE.");
			} else {
				var vId = "";
				var vCodigo = "";
				var vDocumento = "";
				var vNombre = "";
				var vTelefonos = "";
				var vCreditos = 0;
				var vGratis = 0;
				var vDomicilio = "";
				var vComentarios = "";
				var vCategoria_Usuario = "";

				//	obtengo todos los ELEMENTOS seleccionados
				$("#listaClientes > tbody:first").find("input[name='registros']:checked").each(function () {
					vId = $(this).val();
					vCodigo = $(this).closest("tr").find("td").eq(1).text();
					vDocumento = $(this).closest("tr").find("td").eq(2).text();
					vNombre = $(this).closest("tr").find("td").eq(3).text();
					vTelefonos = $(this).closest("tr").find("td").eq(4).text();
					vCreditos = $(this).closest("tr").find("td").eq(5).text();
					vDomicilio = $(this).closest("tr").find("td").eq(5).find("input[name='domicilio']").val();
					vGratis = $(this).closest("tr").find("td").eq(6).text();
					vCategoria_Usuario = $(this).closest("tr").find("td").eq(6).find("input[name='categoria_usuario']").val();
					vComentarios = $(this).closest("tr").find("td").eq(6).find("input[name='comentarios']").val();

					if (confirm(htmlDecode("Se va a seleccionar el siguiente CLIENTE:\n\C&oacute;digo: " + vCodigo + "\nDocumento: " + vDocumento + "\nNombres y Apellidos: " + vNombre + "\n\nEsta usted seguro?"))) {

						$("#id_cliente").val(vId);
						$("#codigo").val(vCodigo);
						$("#nombre_completo").val(vNombre);
						$("#domicilio").val(vDomicilio);
						$("#telefonos").val(vTelefonos);
						$("#categoria_usuario").val(vCategoria_Usuario);
						$("#creditos").val(vCreditos);
						if ($("button#btAumentarCampo[data-tipo='-'][data-campo='creditos_venta']").attr("data-limite") !== undefined) {
							$("button#btAumentarCampo[data-tipo='-'][data-campo='creditos_venta']").attr("data-limite", (vCreditos * (-1)));
						}
						$("#gratis").val(vGratis);
						if ($("button#btAumentarCampo[data-tipo='-'][data-campo='gratis_venta']").attr("data-limite") !== undefined) {
							$("button#btAumentarCampo[data-tipo='-'][data-campo='gratis_venta']").attr("data-limite", (vGratis * (-1)));
						}
						$("#divComentarios").html(vComentarios);

						$("button#btAumentarCampo").attr("disabled", false);

						$("#btNuevoDetalle").attr("disabled", false);
						$("#btNuevoDetalle").attr("data-cliente", vId);
						$("#btNuevoDetalle").focus();

						$domModal.modal('hide');
					}

				})

			}

		}

	});

	/* AUMENTAR STOCK */
	if ($("#producto").length) {
		$("#producto").autocomplete({
			source: function (request, response) {
				$("#id_producto").val("");
				$("#codigo").val("");
				$("#campo_categoria").val("");
				$("#campo_genero").val("");
				$("#stock").val("");
				$("button#btAumentarCampo").attr("disabled", true);
				$("#stock_aumentar").val("0");
				$("#stock_final").val("");

				$("#vMsjCargando").find("span").text("buscando PRODUCTO...");
				$("#vMsjCargando").show();

				$('input[type="submit"]').attr('disabled', true);

				$.getJSON("buscarDatos.php", {
					t: "productos",
					term: request.term,
					s: $("#sucursal").val(),
					m: getUrlVars()["menu"]
				}, response);
			},
			minLength: 0,
			select: function (event, ui) {
				event.preventDefault();

				$("#vMsjCargando").hide();

				$(this).val(ui.item.label);
				$("#id_producto").val(ui.item.value);
				$("#codigo").val(ui.item.codigo);
				$("#campo_categoria").val(ui.item.categoria);
				$("#campo_genero").val(ui.item.genero);
				$("#stock").val(ui.item.stock);
				$("button#btAumentarCampo[data-tipo='-'][data-campo='stock_aumentar']").attr("data-limite", 0);
				/*if ($("button#btAumentarCampo[data-tipo='-'][data-campo='stock_aumentar']").attr("data-limite") !== undefined) {
					$("button#btAumentarCampo[data-tipo='-'][data-campo='stock_aumentar']").attr("data-limite",(ui.item.stock*(-1)));
				}*/
				$("button#btAumentarCampo").attr("disabled", false);

				$('input[type="submit"]').attr('disabled', false);
			},
			focus: function (event, ui) {
				//$(this).val(ui.item.label);
				event.preventDefault();
			},
			close: function (event, ui) {
				$("#vMsjCargando").hide();
			},
			response: function (event, ui) {
				if (ui.content.length === 0) {
					$("#vMsjCargando").hide();
				}
			}
		}).focus(function () {
			if ($(this).val() != "") {
				return false;
			} else {
				$(this).autocomplete("search", $(this).val());
			}
		});
	}

	if (document.querySelector('input[name="stock_aumentar"]')) {
		const inputID = document.querySelector('input[name="id_producto"]')
		const inputStockAumentar = document.querySelector('input[name="stock_aumentar"]')
		const inputStockActual = document.querySelector('input[name="stock"]')
		const inputStockFinal = document.querySelector('input[name="stock_final"]')

		inputStockAumentar.addEventListener('change', e => {
			e.preventDefault();

			if (inputID.value != "") {
				const vStockActual = Number(inputStockActual.value)
				const vValor = Number(inputStockAumentar.value)
				const vValorFinal = vStockActual + vValor

				inputStockFinal.value = vValorFinal

			}

		})
		inputStockAumentar.addEventListener('keydown', e => {
			const form = e.target.closest('form')
			const inputSubmit = form.querySelector('input[type="submit"]')

			if (e.which == 13 || e.keyCode == 13) {
				e.preventDefault();
				inputStockAumentar.dispatchEvent(new Event('change', { bubbles: true, cancelable: true }))
				inputSubmit.focus();
			}

		})

	}

	/*	TRASPASOS	*/
	/*
	if ($("#sucursalorigen").length && $("#sucursaldestino").length) {
		var verificarSucursal = function () {
			if ($(this).attr("name") == "sucursalorigen") {
				$("#sucursaldestino option").attr("disabled", false);
				$("#sucursaldestino option[value='" + $("#sucursalorigen option:selected").val() + "']").attr("disabled", true);
				$("#sucursaldestino").get(0).selectedIndex = 0;
			}

			if (($("#sucursalorigen option:selected").val() != "") && ($("#sucursaldestino option:selected").val() != "") && ($("#sucursalorigen option:selected").val() != $("#sucursaldestino option:selected").val())) {
				$("#btNuevoDetalle").attr("disabled", false);
			} else {
				$("#btNuevoDetalle").attr("disabled", true);
			}
		};

		$("#sucursalorigen").bind('change', verificarSucursal);
		$("#sucursaldestino").bind('change', verificarSucursal);
	}

	if ($("#usuarioorigen").length && $("#usuariodestino").length) {
		var verificarUsuario = function () {
			if ($(this).attr("name") == "usuarioorigen") {
				$("#usuariodestino option").attr("disabled", false);
				$("#usuariodestino option[value='" + $("#usuarioorigen option:selected").val() + "']").attr("disabled", true);
				$("#usuariodestino").get(0).selectedIndex = 0;
			}
		};

		$("#usuarioorigen").bind('change', verificarUsuario);
		$("#usuariodestino").bind('change', verificarUsuario);
	}
	*/

	/*	COTIZACIONES	*/
	if ((getUrlVars()["menu"] == "cotizaciones")) {
		if (document.querySelector('input[name="fecha_alta"]')) {
			document.querySelector('input[name="fecha_alta"]').addEventListener('change', e => {
				if (e.target.value != "") {
					const vFecha = e.target.value.split('-');

					const objFecha = new Date(vFecha[0], (vFecha[1] - 1), vFecha[2]);
					const tiempo = objFecha.getTime();
					const milisegundos = parseInt(15 * 24 * 60 * 60 * 1000);
					objFecha.setTime(tiempo + milisegundos);

					document.querySelector('input[name="fecha_vencimiento"]').value = objFecha.getFullYear() + "-" + sprintf("%02s", (objFecha.getMonth() + 1)) + "-" + sprintf("%02s", objFecha.getDate())

				}
			})
		}
	}

	/*	EGRESOS	*/
	if ($("#tipo_egreso").length) {
		$("#tipo_egreso").autocomplete({
			source: function (request, response) {
				$("#totalEgreso").val("");

				$.getJSON("buscarDatos.php", {
					t: "tipo_egreso",
					term: request.term,
					m: getUrlVars()["menu"],
					ids: $("#sucursal").val()
				}, response);
			},
			minLength: 0,
			select: function (event, ui) {
				$(this).val(ui.item.label);
				event.preventDefault();

				var vCadena = ui.item.label;
				if (vCadena.toUpperCase() == "COMPRAS") {
					$("#divEgresosTotal").hide();
					$("#divEgresosProductos").show();

					$("#totalEgreso").removeAttr("required");

					$("#listaDetalles > tbody:first").html("");
					if ($("#id_cliente").val() != "") {
						$("#btNuevoDetalle").attr("disabled", false);
						$("#btNuevoDetalle").attr("data-cliente", $("#id_cliente").val());
						$("#btnCalcularSubtotal").trigger("click");
					}
					$('input[type="submit"]').attr('disabled', true);
				} else {
					$("#divEgresosTotal").show();
					$("#divEgresosProductos").hide();

					$("#totalEgreso").attr("required", "");

					$('input[type="submit"]').attr('disabled', false);
				}

			},
			focus: function (event, ui) {
				$(this).val(ui.item.label);
				event.preventDefault();
			}
		}).focus(function () {
			if ($(this).val() != "") {
				return false;
			} else {
				$(this).autocomplete("search", $(this).val());
			}
		});
	}

	$("body").on("change blur", "input#tipo_egreso", function (e) {
		var vCadena = $(this).val();
		if (vCadena.toUpperCase() == "COMPRAS") {
			$("#divEgresosTotal").hide();
			$("#divEgresosProductos").show();

			$("#totalEgreso").removeAttr("required");

			$("#listaDetalles > tbody:first").html("");
			if ($("#id_cliente").val() != "") {
				$("#btNuevoDetalle").attr("disabled", false);
				$("#btNuevoDetalle").attr("data-cliente", $("#id_cliente").val());
				$("#btnCalcularSubtotal").trigger("click");
			}
			$('input[type="submit"]').attr('disabled', true);
		} else {
			$("#divEgresosTotal").show();
			$("#divEgresosProductos").hide();

			$("#totalEgreso").attr("required", "");

			$('input[type="submit"]').attr('disabled', false);
		}

		if ((vCadena != "") && ($("#id_cliente").val() != "")) {
			$('input[type="submit"]').attr('disabled', false);
		} else {
			$('input[type="submit"]').attr('disabled', true);
		}

	})

	function formatearNumeroConDecimales(thisObj) {
		var vImporte = thisObj.val().replace(/\./g, "").replace(/\,/g, ".");
		if (vImporte == null || !isFinite(vImporte)) {
			vImporte = 0;
		}
		thisObj.val(number_format(vImporte, 2, ",", "."));
	}

	$("body").on("change paste", "input#totalEgreso", function (e) {
		e.preventDefault();
		formatearNumeroConDecimales($(this));
	})

	$("body").on("keypress", "input#totalEgreso", function (e) {
		if (e.which == 13) {
			$("#usuario").focus();
			return false;
		}
	})

	/*	SERVICIOS TECNICOS	*/
	if ($("select#estado").length) {
		$("select#estado").change(function () {
			var vValor = $(this).find("option:selected").val();
			if (vValor != "") {
				if (vValor == "Entregado") {
					$("#divEntregado").show();
					$("#tecnico").attr("required", true);
					$("#fecha_entrega").attr("required", true);
					$("#hora_entrega").attr("required", true);
				} else {
					$("#divEntregado").hide();
					$("#tecnico").attr("required", false);
					$("#fecha_entrega").attr("required", false);
					$("#hora_entrega").attr("required", false);
				}
			}
		})
	}

	if ($("#consola").length) {
		$("#consola").autocomplete({
			source: function (request, response) {
				$("#modelo").val("");
				$.getJSON("buscarDatos.php", {
					t: "consola",
					term: request.term,
					m: getUrlVars()["menu"],
					ids: $("#sucursal").val()
				}, response);
			},
			minLength: 0,
			select: function (event, ui) {
				$(this).val(ui.item.label);
				event.preventDefault();
			},
			focus: function (event, ui) {
				$(this).val(ui.item.label);
				event.preventDefault();
			}
		}).focus(function () {
			if ($(this).val() != "") {
				return false;
			} else {
				$(this).autocomplete("search", $(this).val());
			}
		});
	}

	if ($("#modelo").length) {
		$("#modelo").autocomplete({
			source: function (request, response) {
				$.getJSON("buscarDatos.php", {
					t: "modelo",
					term: request.term,
					m: getUrlVars()["menu"],
					c: $("#consola").val(),
					ids: $("#sucursal").val()
				}, response);
			},
			minLength: 0,
			select: function (event, ui) {
				$(this).val(ui.item.label);
				event.preventDefault();
			},
			focus: function (event, ui) {
				$(this).val(ui.item.label);
				event.preventDefault();
			}
		}).focus(function () {
			if ($(this).val() != "") {
				return false;
			} else {
				$(this).autocomplete("search", $(this).val());
			}
		});
	}

	/*	VENTAS	*/
	if ($("#btnAsignarFactura").length) {
		$("#btnAsignarFactura").click(function (e) {
			e.preventDefault();
			$("#vMsjCargando").find("span").text("buscando NRO. de FACTURA...");
			$("#vMsjCargando").show();

			$.getJSON('buscarDatos.php?t=obtenerNroFactura', function (data) {
				$("#vMsjCargando").hide();

				if (data.length) {
					$.each(data, function (key, elemento) {
						$("#btnAsignarFactura").closest(".controls").append("<input type=\"text\" id=\"nro_factura\" name=\"nro_factura\" value=\"" + elemento.factura + "\" size=\"50\" maxlength=\"50\" title=\"N&deg; de Factura\" class=\"input-small\" style=\"text-align: right;font-size: 1.5em;\" />");
						$("#btnAsignarFactura").hide();
					})

					$("#nro_factura").dblclick(function () {
						$(this).remove();
						$("#btnAsignarFactura").show();
					})
				}
			})
		})
	}

	if ($("#sucursal").length) {
		$("#sucursal").change(function () {
			if (getUrlVars()["menu"] == "productos") {
				switch (getUrlVars()["accion"]) {
					case "aumentarStock":
						$("#id_producto").val("");
						$("#codigo").val("");
						$("#producto").val("");
						$("#categoria").val("");
						$("#genero").val("");
						$("#stock").val("");
						$("button#btAumentarCampo").attr("disabled", true);
						$("#stock_aumentar").val("0");
						$("#stock_final").val("");

						$('input[type="submit"]').attr('disabled', true);
						break;
					default:
						if ($("#id_producto").length) {
							$.getJSON('buscarDatos.php?t=obtenerStock&p=' + $("#id_producto").val() + '&s=' + $("#sucursal").find("option:selected").val(), function (data) {
								if (data.length) {
									$.each(data, function (key, elemento) {
										$("#stock").val(elemento.stock);
									})
								} else {
									$("#stock").val("0");
								}
							})
						}
						break;
				}
			}

			if ((getUrlVars()["menu"] == "ventas") || (getUrlVars()["menu"] == "egresos") || (getUrlVars()["menu"] == "cotizaciones")) {
				if ($(this).find("option:selected").val() != "") {
					$("#codigo").attr("disabled", false);
					$("#cliente").attr("disabled", false);
					if (($("#id_cliente").val() != "") && ($("#codigo").val() != "") && ($("#cliente").val() != "")) {
						$("#btNuevoDetalle").attr("disabled", false);
						$("#btNuevoDetalle").attr("data-cliente", $("#id_cliente").val());
					}
					$("#codigo").focus();
				} else {
					$("#codigo").attr("disabled", true);
					$("#cliente").attr("disabled", true);
					$("#btNuevoDetalle").attr("disabled", true);
					$("#btNuevoDetalle").attr("data-cliente", "");
					$("#listaDetalles > tbody:first").html("");
					$("#btnCalcularSubtotal").trigger("click");
					$('input[type="submit"]').attr('disabled', false);
				}
			}

			if (getUrlVars()["menu"] == "inventarios") {
				if ($(this).find("option:selected").val() != "") {
					$("#btNuevoDetalle").attr("disabled", false);
					$("#btNuevoDetalle").attr("data-cliente", "");
				} else {
					$("#btNuevoDetalle").attr("disabled", true);
					$("#btNuevoDetalle").attr("data-cliente", "");
					$("#listaDetalles > tbody:first").html("");
					$('input[type="submit"]').attr('disabled', true);
				}
			}

		})
	}

	if ($("#codigo").length) {
		if ((getUrlVars()["menu"] == "ventas") || (getUrlVars()["menu"] == "servicios") || (getUrlVars()["menu"] == "pedidos") || (getUrlVars()["menu"] == "cotizaciones")) {
			$("#btBuscarCliente").bind('click', function (e) {
				e.preventDefault();
				var valor = $("#codigo").val();
				if ((valor != "")) {
					$("#id_cliente").val("");
					$("#codigo").val("");
					$("#cliente").val("");
					$("#documento").val("");
					$("#domicilio").val("");
					$("#telefonos").val("");
					$("#categoria_usuario").val("");
					if (getUrlVars()["menu"] == "ventas") {
						$("#creditos").val("");
						$("#gratis").val("");
						$("button#btAumentarCampo").attr("disabled", true);
						if (getUrlVars()["accion"] == "nuevo") {
							$("#creditos_venta").val("0");
							$("#gratis_venta").val("0");
						}
						if (getUrlVars()["accion"] == "editar") {
							$("#creditos_venta").val($("#old_creditos_venta").val());
							$("#gratis_venta").val($("#old_gratis_venta").val());
						}
						$("#creditos_final").val("");
						$("#gratis_final").val("");
						$("#divComentarios").html("");
					}

					$("#vMsjCargando").find("span").text("buscando CODIGO...");
					$("#vMsjCargando").show();
					$("#documento").css('background-image', 'url(../images/preloader.gif)').css('background-position', 'right').css('background-repeat', 'no-repeat');
					$("#domicilio").css('background-image', 'url(../images/preloader.gif)').css('background-position', 'right').css('background-repeat', 'no-repeat');
					$("#telefonos").css('background-image', 'url(../images/preloader.gif)').css('background-position', 'right').css('background-repeat', 'no-repeat');
					$("#categoria_usuario").css('background-image', 'url(../images/preloader.gif)').css('background-position', 'right').css('background-repeat', 'no-repeat');

					if (getUrlVars()["accion"] == "nuevo") {
						$("#listaDetalles > tbody:first").html("");
					}

					$.getJSON('buscarDatos.php?t=verificarCliente&c=' + valor + '&m=' + getUrlVars()["menu"] + '&s=' + $("#sucursal").val(), function (data) {
						$("#vMsjCargando").hide();
						$("#documento").css('background-image', '').css('background-position', '').css('background-repeat', '');
						$("#domicilio").css('background-image', '').css('background-position', '').css('background-repeat', '');
						$("#telefonos").css('background-image', '').css('background-position', '').css('background-repeat', '');
						$("#categoria_usuario").css('background-image', '').css('background-position', '').css('background-repeat', '');
						if (data.length) {
							$.each(data, function (key, elemento) {
								$("#id_cliente").val(elemento.id);
								$("#codigo").val(sprintf("%04s", elemento.codigo));
								$("#cliente").val(elemento.nombre);
								$("#documento").val(elemento.documento);
								$("#domicilio").val(elemento.domicilio);
								$("#telefonos").val(elemento.telefonos);
								$("#categoria_usuario").val(elemento.categoria_usuario);
								if (getUrlVars()["menu"] == "ventas") {
									if (getUrlVars()["accion"] == "nuevo") {
										$("#creditos").val(elemento.creditos);
										if ($("button#btAumentarCampo[data-tipo='-'][data-campo='creditos_venta']").attr("data-limite") !== undefined) {
											$("button#btAumentarCampo[data-tipo='-'][data-campo='creditos_venta']").attr("data-limite", (elemento.creditos * (-1)));
										}
										$("#gratis").val(elemento.gratis);
										if ($("button#btAumentarCampo[data-tipo='-'][data-campo='gratis_venta']").attr("data-limite") !== undefined) {
											$("button#btAumentarCampo[data-tipo='-'][data-campo='gratis_venta']").attr("data-limite", (elemento.gratis * (-1)));
										}
									}
									if (getUrlVars()["accion"] == "editar") {
										$("#creditos").val(elemento.creditos - ($("#creditos_venta").val()));
										if ($("button#btAumentarCampo[data-tipo='-'][data-campo='creditos_venta']").attr("data-limite") !== undefined) {
											$("button#btAumentarCampo[data-tipo='-'][data-campo='creditos_venta']").attr("data-limite", ($("#creditos").val() * (-1)));
										}
										$("#creditos_final").val(elemento.creditos);

										$("#gratis").val(elemento.gratis - ($("#gratis_venta").val()));
										if ($("button#btAumentarCampo[data-tipo='-'][data-campo='gratis_venta']").attr("data-limite") !== undefined) {
											$("button#btAumentarCampo[data-tipo='-'][data-campo='gratis_venta']").attr("data-limite", ($("#gratis").val() * (-1)));
										}
										$("#gratis_final").val(elemento.gratis);
									}
									if ((elemento.creditos == 0) || (elemento.gratis == 0)) {
										$("#divComentarios").html(elemento.comentarios);
									}
									$("button#btAumentarCampo").attr("disabled", false);
								}

								$("#btNuevoDetalle").attr("disabled", false);
								$("#btNuevoDetalle").attr("data-cliente", elemento.id);
								//$("#codigo").focus();
								$('input[type="submit"]').attr('disabled', true);
							})

							$("#btnCalcularSubtotal").trigger("click");
						} else {
							$("#id_cliente").val("");
							$("#cliente").val("");
							$("#documento").val("");
							$("#domicilio").val("");
							$("#telefonos").val("");
							$("#categoria_usuario").val("");
							if (getUrlVars()["menu"] == "ventas") {
								$("#creditos").val("");
								$("#gratis").val("");
								$("button#btAumentarCampo").attr("disabled", true);
							}

							$("#btNuevoDetalle").attr("disabled", true);
							$("#btNuevoDetalle").attr("data-cliente", "");
							if (getUrlVars()["accion"] == "nuevo") {
								$("#listaDetalles > tbody:first").html("");
							}
							$("#btnCalcularSubtotal").trigger("click");
							$("#codigo").focus();
							alert(htmlDecode('El C&oacute;digo de Cliente: ' + valor + ' no existe.'));
							$("#codigo").val("");
							$('input[type="submit"]').attr('disabled', false);
						}
					})

				} else {
					$("#id_cliente").val("");
					$("#codigo").val("");
					$("#cliente").val("");
					$("#documento").val("");
					$("#domicilio").val("");
					$("#telefonos").val("");
					$("#categoria_usuario").val("");
					if (getUrlVars()["menu"] == "ventas") {
						$("#creditos").val("");
						$("#gratis").val("");
						$("button#btAumentarCampo").attr("disabled", true);
						if (getUrlVars()["accion"] == "nuevo") {
							$("#creditos_venta").val("0");
							$("#gratis_venta").val("0");
						}
						$("#creditos_final").val("");
						$("#gratis_final").val("");
						$("#divComentarios").html("");
					}

					$("#btNuevoDetalle").attr("disabled", true);
					$("#btNuevoDetalle").attr("data-cliente", "");
					if (getUrlVars()["accion"] == "nuevo") {
						$("#listaDetalles > tbody:first").html("");
					}
					$("#btnCalcularSubtotal").trigger("click");
					$("#codigo").focus();
					$('input[type="submit"]').attr('disabled', false);
				}
			})
			$("#codigo").bind('keydown', function (e) {
				if (e.which == 13) {
					$("#btBuscarCliente").trigger("click");
					//$("#codigo").focus();
					//return false;
				}
			})
			$("#codigo").bind('keyup', function (e) {
				e.preventDefault();
				var valor = $("#codigo").val();
				if (valor == "") {
					$("#id_cliente").val("");
					$("#codigo").val("");
					$("#cliente").val("");
					$("#documento").val("");
					$("#domicilio").val("");
					$("#telefonos").val("");
					$("#categoria_usuario").val("");
					if (getUrlVars()["menu"] == "ventas") {
						$("#creditos").val("");
						$("#gratis").val("");
						$("button#btAumentarCampo").attr("disabled", true);
						if (getUrlVars()["accion"] == "nuevo") {
							$("#creditos_venta").val("0");
							$("#gratis_venta").val("0");
						}
						$("#creditos_final").val("");
						$("#gratis_final").val("");
						$("#divComentarios").html("");
					}

					$("#btNuevoDetalle").attr("disabled", true);
					$("#btNuevoDetalle").attr("data-cliente", "");
					if (getUrlVars()["accion"] == "nuevo") {
						$("#listaDetalles > tbody:first").html("");
					}
					$("#btnCalcularSubtotal").trigger("click");
					//$("#codigo").focus();
					$('input[type="submit"]').attr('disabled', false);
				}
			})
		}

		if (getUrlVars()["menu"] == "egresos") {
			$("#btBuscarCliente").bind('click', function (e) {
				e.preventDefault();
				var valor = $("#codigo").val();
				if ((valor != "")) {
					$("#id_cliente").val("");
					$("#cliente").val("");
					$("#domicilio").val("");
					$("#telefonos").val("");
					$("#categoria_usuario").val("");
					$("#totalEgreso").val("");

					$("#domicilio").css('background-image', 'url(../images/preloader.gif)').css('background-position', 'right').css('background-repeat', 'no-repeat');
					$("#telefonos").css('background-image', 'url(../images/preloader.gif)').css('background-position', 'right').css('background-repeat', 'no-repeat');
					$("#categoria_usuario").css('background-image', 'url(../images/preloader.gif)').css('background-position', 'right').css('background-repeat', 'no-repeat');

					if ($("#tipo_egreso").val().toUpperCase() == "COMPRAS") {
						$("#listaDetalles > tbody:first").html("");
					}

					$.getJSON('buscarDatos.php?t=verificarCliente&c=' + valor + '&m=' + getUrlVars()["menu"] + '&s=' + $("#sucursal").val(), function (data) {
						$("#domicilio").css('background-image', '').css('background-position', '').css('background-repeat', '');
						$("#telefonos").css('background-image', '').css('background-position', '').css('background-repeat', '');
						$("#categoria_usuario").css('background-image', '').css('background-position', '').css('background-repeat', '');
						if (data.length) {
							$.each(data, function (key, elemento) {
								$("#id_cliente").val(elemento.id);
								$("#codigo").val(sprintf("%04s", elemento.codigo));
								$("#cliente").val(elemento.nombre);
								$("#domicilio").val(elemento.domicilio);
								$("#telefonos").val(elemento.telefonos);
								$("#categoria_usuario").val(elemento.categoria_usuario);

								if ($("#tipo_egreso").val().toUpperCase() == "COMPRAS") {
									$("#btNuevoDetalle").attr("disabled", false);
									$("#btNuevoDetalle").attr("data-cliente", elemento.id);
								}
								//$("#codigo").focus();
								$('input[type="submit"]').attr('disabled', $("#tipo_egreso").val() == "");
							})

							if ($("#tipo_egreso").val().toUpperCase() == "COMPRAS") {
								$("#btnCalcularSubtotal").trigger("click");
							}
						} else {
							$("#id_cliente").val("");
							$("#cliente").val("");
							$("#domicilio").val("");
							$("#telefonos").val("");
							$("#categoria_usuario").val("");

							if ($("#tipo_egreso").val().toUpperCase() == "COMPRAS") {
								$("#btNuevoDetalle").attr("disabled", true);
								$("#btNuevoDetalle").attr("data-cliente", "");
								$("#listaDetalles > tbody:first").html("");
								$("#btnCalcularSubtotal").trigger("click");
							}
							$("#codigo").focus();
							alert(htmlDecode('El C&oacute;digo de Cliente: ' + $("#codigo").val() + ' no existe.'));
							$("#codigo").val("");
							$('input[type="submit"]').attr('disabled', false);
						}
					})

				} else {
					$("#id_cliente").val("");
					$("#codigo").val("");
					$("#cliente").val("");
					$("#domicilio").val("");
					$("#telefonos").val("");
					$("#categoria_usuario").val("");
					$("#totalEgreso").val("");

					if ($("#tipo_egreso").val().toUpperCase() == "COMPRAS") {
						$("#btNuevoDetalle").attr("disabled", true);
						$("#btNuevoDetalle").attr("data-cliente", "");
						$("#listaDetalles > tbody:first").html("");
						$("#btnCalcularSubtotal").trigger("click");
					}
					$("#codigo").focus();
					$('input[type="submit"]').attr('disabled', false);
				}
			})
			$("#codigo").bind('keydown', function (e) {
				if (e.which == 13) {
					$("#btBuscarCliente").trigger("click");
					//$("#codigo").focus();
					//return false;
				}
			})
			$("#codigo").bind('keyup', function (e) {
				e.preventDefault();
				var valor = $("#codigo").val();
				if (valor == "") {
					$("#id_cliente").val("");
					$("#codigo").val("");
					$("#cliente").val("");
					$("#domicilio").val("");
					$("#telefonos").val("");
					$("#categoria_usuario").val("");
					$("#totalEgreso").val("");

					if ($("#tipo_egreso").val().toUpperCase() == "COMPRAS") {
						$("#btNuevoDetalle").attr("disabled", true);
						$("#btNuevoDetalle").attr("data-cliente", "");
						$("#listaDetalles > tbody:first").html("");
						$("#btnCalcularSubtotal").trigger("click");
					}
					//$("#codigo").focus();
					$('input[type="submit"]').attr('disabled', false);
				}
			})
		}

		if ((getUrlVars()["menu"] == "productos") && (getUrlVars()["accion"] == "aumentarStock")) {
			$("#btBuscarProducto").bind('click', function (e) {
				e.preventDefault();
				var valor = $("#codigo").val();
				if ((valor != "")) {
					$("#id_producto").val("");
					$("#producto").val("");
					$("#campo_categoria").val("");
					$("#campo_genero").val("");
					$("#stock").val("");
					$("#stock_aumentar").val("");
					$("#stock_final").val("");
					$("button#btAumentarCampo").attr("disabled", true);

					$("#vMsjCargando").find("span").text("buscando CODIGO...");
					$("#vMsjCargando").show();

					$.getJSON('buscarDatos.php?t=verificarProducto&c=' + valor + '&m=' + getUrlVars()["menu"] + '&s=' + $("#sucursal").val(), function (data) {
						$("#vMsjCargando").hide();
						if (data.length) {
							$.each(data, function (key, elemento) {
								$("#id_producto").val(elemento.id);
								$("#codigo").val(elemento.codigo);
								$("#producto").val(elemento.nombre);
								$("#campo_categoria").val(elemento.categoria);
								$("#campo_genero").val(elemento.genero);
								$("#stock").val(elemento.stock);
								$("button#btAumentarCampo[data-tipo='-'][data-campo='stock_aumentar']").attr("data-limite", 0);
								$("button#btAumentarCampo").attr("disabled", false);

								$("#codigo").focus();
								$('input[type="submit"]').attr('disabled', false);
							})
						} else {
							$("#id_producto").val("");
							$("#producto").val("");
							$("#campo_categoria").val("");
							$("#campo_genero").val("");
							$("#stock").val("");
							$("#stock_aumentar").val("0");
							$("#stock_final").val("");
							$("button#btAumentarCampo").attr("disabled", true);

							$("#codigo").focus();
							alert(htmlDecode('El C&oacute;digo de Producto: ' + $("#codigo").val() + ' no existe.'));
							$("#codigo").val("");
							$('input[type="submit"]').attr('disabled', false);
						}
					})
				} else {
					$("#id_producto").val("");
					$("#producto").val("");
					$("#campo_categoria").val("");
					$("#campo_genero").val("");
					$("#stock").val("");
					$("#stock_aumentar").val("0");
					$("#stock_final").val("");
					$("button#btAumentarCampo").attr("disabled", true);

					$("#codigo").focus();
					$('input[type="submit"]').attr('disabled', false);
				}
			})
			$("#codigo").bind('keypress', function (e) {
				if (e.which == 13) {
					$("#btBuscarProducto").trigger("click");
					$("#codigo").focus();
					return false;
				}
			})
			$("#codigo").bind('keyup', function (e) {
				e.preventDefault();
				var valor = $("#codigo").val();
				if (valor == "") {
					$("#id_producto").val("");
					$("#codigo").val("");
					$("#producto").val("");
					$("#campo_categoria").val("");
					$("#campo_genero").val("");
					$("button#btAumentarCampo").attr("disabled", true);
					$("#stock").val("");
					$("#stock_aumentar").val("0");
					$("#stock_final").val("");

					$("#codigo").focus();
					$('input[type="submit"]').attr('disabled', false);
				}
			})
		}
	}

	if ($("#cliente").length) {
		$("#cliente").autocomplete({
			source: function (request, response) {
				$("#id_cliente").val("");
				$("#codigo").val("");
				$("#domicilio").val("");
				$("#telefonos").val("");
				$("#categoria_usuario").val("");
				if (getUrlVars()["menu"] == "ventas") {
					$("#creditos").val("");
					$("#gratis").val("");
					$("button#btAumentarCampo").attr("disabled", true);
					if (getUrlVars()["accion"] == "nuevo") {
						$("#creditos_venta").val("0");
						$("#gratis_venta").val("0");
					}
					$("#creditos_final").val("");
					$("#gratis_final").val("");
					$("#divComentarios").html("");
				}
				$("#totalEgreso").val("");

				$("#vMsjCargando").find("span").text("buscando CLIENTE...");
				$("#vMsjCargando").show();
				$("#domicilio").css('background-image', 'url(../images/preloader.gif)').css('background-position', 'right').css('background-repeat', 'no-repeat');
				$("#telefonos").css('background-image', 'url(../images/preloader.gif)').css('background-position', 'right').css('background-repeat', 'no-repeat');
				$("#categoria_usuario").css('background-image', 'url(../images/preloader.gif)').css('background-position', 'right').css('background-repeat', 'no-repeat');

				if ((getUrlVars()["menu"] == "ventas") || (getUrlVars()["menu"] == "cotizaciones")) {
					$("#btNuevoDetalle").attr("disabled", true);
					$("#btNuevoDetalle").attr("data-cliente", "");
					if (getUrlVars()["accion"] == "nuevo") {
						$("#listaDetalles > tbody:first").html("");
					}
					$("#btnCalcularSubtotal").trigger("click");
				}
				if (((getUrlVars()["menu"] == "egresos") && ($("#tipo_egreso").val().toUpperCase() == "COMPRAS"))) {
					$("#btNuevoDetalle").attr("disabled", true);
					$("#btNuevoDetalle").attr("data-cliente", "");
					$("#listaDetalles > tbody:first").html("");
					$("#btnCalcularSubtotal").trigger("click");
				}
				$('input[type="submit"]').attr('disabled', true);

				$.getJSON("buscarDatos.php", {
					t: "cliente",
					term: request.term,
					m: getUrlVars()["menu"],
					s: $('#sucursal').val()
				}, response);
			},
			minLength: 0,
			select: function (event, ui) {
				event.preventDefault();

				$("#vMsjCargando").hide();
				$("#domicilio").css('background-image', '').css('background-position', '').css('background-repeat', '');
				$("#telefonos").css('background-image', '').css('background-position', '').css('background-repeat', '');
				$("#categoria_usuario").css('background-image', '').css('background-position', '').css('background-repeat', '');

				$(this).val(ui.item.label);
				$("#id_cliente").val(ui.item.value);
				$("#codigo").val(sprintf("%04s", ui.item.codigo));
				$("#domicilio").val(ui.item.domicilio);
				$("#telefonos").val(ui.item.telefonos);
				$("#categoria_usuario").val(ui.item.categoria_usuario);
				if (getUrlVars()["menu"] == "ventas") {
					$("#creditos").val(ui.item.creditos);
					if ($("button#btAumentarCampo[data-tipo='-'][data-campo='creditos_venta']").attr("data-limite") !== undefined) {
						$("button#btAumentarCampo[data-tipo='-'][data-campo='creditos_venta']").attr("data-limite", (ui.item.creditos * (-1)));
					}
					$("#gratis").val(ui.item.gratis);
					if ($("button#btAumentarCampo[data-tipo='-'][data-campo='gratis_venta']").attr("data-limite") !== undefined) {
						$("button#btAumentarCampo[data-tipo='-'][data-campo='gratis_venta']").attr("data-limite", (ui.item.gratis * (-1)));
					}
					$("#divComentarios").html(ui.item.comentarios);
					$("button#btAumentarCampo").attr("disabled", false);

					$('input[type="submit"]').attr('disabled', false);
				}

				if ((getUrlVars()["menu"] == "servicios") || (getUrlVars()["menu"] == "pedidos")) {
					$('input[type="submit"]').attr('disabled', false);
				}

				if ((getUrlVars()["menu"] == "ventas") || (getUrlVars()["menu"] == "cotizaciones")) {
					$("#btNuevoDetalle").attr("disabled", false);
					$("#btNuevoDetalle").attr("data-cliente", ui.item.value);
				}
				if (((getUrlVars()["menu"] == "egresos") && ($("#tipo_egreso").val().toUpperCase() == "COMPRAS"))) {
					$("#btNuevoDetalle").attr("disabled", false);
					$("#btNuevoDetalle").attr("data-cliente", ui.item.value);
				}

			},
			focus: function (event, ui) {
				event.preventDefault();
				//$(this).val(ui.item.label);
			},
			close: function (event, ui) {
				$("#vMsjCargando").hide();
				$("#domicilio").css('background-image', '').css('background-position', '').css('background-repeat', '');
				$("#telefonos").css('background-image', '').css('background-position', '').css('background-repeat', '');
				$("#categoria_usuario").css('background-image', '').css('background-position', '').css('background-repeat', '');
			}
		}).focus(function () {
			if ($(this).val() != "") {
				return false;
			} else {
				$(this).autocomplete("search", $(this).val());
			}
		});
	}

	if ($("#btnCalcularSubtotal").length) {
		$("#btnCalcularSubtotal").click(function (e) {
			e.preventDefault();

			if (getUrlVars()["menu"] == "ventas") {

				var vImporte = 0;
				$("#listaDetalles > tbody:first").find("input[name='detalle']").each(function () {
					vImporteDetalle = $(this).closest("tr").find("td").eq(6).find("input[name='detalleTotal[]']").val();
					var vImporteDetalleAux = vImporteDetalle.replace('.', '').replace(',', '.');
					vImporte += Number(vImporteDetalleAux);
				});
				$("#importe").val(vImporte);
				$("#importe").val(number_format($("#importe").val(), 2, ',', '.'));

				var vIva = Number($("#iva").val().replace('.', '').replace(',', '.'));
				$("#iva").val(number_format(vIva, 2, ',', '.'));

				var vSubtotal = vImporte + vIva;
				$("#subtotal").val(vSubtotal);
				$("#subtotal").val(number_format($("#subtotal").val(), 2, ',', '.'));

				var vDescuento = Number($("#descuento").val().replace('.', '').replace(',', '.'));
				$("#descuento").val(number_format(vDescuento, 2, ',', '.'));

				var vTotal = vSubtotal - vDescuento;
				$("#total").val(vTotal);
				$("#total").val(number_format($("#total").val(), 2, ',', '.'));

				var vMultiplo = $("input[name='tipo_cambio_multiplo']").val()
				var vTotal_ME = vTotal * vMultiplo
				$("#total_me").val(number_format(vTotal_ME, 2, ',', '.'));

				var vRecibido = Number($("#recibido").val().replace('.', '').replace(',', '.'));
				$("#recibido").val(number_format(vRecibido, 2, ',', '.'));

				var vSaldo = vTotal - vRecibido;
				$("#saldo").val(vSaldo);
				$("#saldo").val(number_format($("#saldo").val(), 2, ',', '.'));

				if (vSaldo == 0) {
					$("#msjSaldo").text("Saldo")
					$("#msjSaldo").css("color", "#000000");
					if (vTotal > 0) {
						$("#pagado[value='1']").prop("checked", true);
					}
				} else {
					if (vSaldo > 0) {
						$("#msjSaldo").text("Saldo que adeuda el Cliente")
						$("#msjSaldo").css("color", "#ff0000");
						if (vTotal > 0) {
							$("#pagado[value='0']").prop("checked", true);
						}
					} else {
						$("#msjSaldo").text("Saldo a favor del Cliente");
						$("#msjSaldo").css("color", "#000000");
						if (vTotal > 0) {
							$("#pagado[value='1']").prop("checked", true);
						}
					}
				}
			}

			if ((getUrlVars()["menu"] == "servicios") || (getUrlVars()["menu"] == "pedidos")) {
				var vImporte = Number($("#importe").val().replace('.', '').replace(',', '.'));
				$("#importe").val(number_format(vImporte, 2, ',', '.'));

				var vIva = Number($("#iva").val().replace('.', '').replace(',', '.'));
				$("#iva").val(number_format(vIva, 2, ',', '.'));

				var vSubtotal = vImporte + vIva;
				$("#subtotal").val(vSubtotal);
				$("#subtotal").val(number_format($("#subtotal").val(), 2, ',', '.'));

				var vDescuento = Number($("#descuento").val().replace('.', '').replace(',', '.'));
				$("#descuento").val(number_format(vDescuento, 2, ',', '.'));

				var vTotal = vSubtotal - vDescuento;
				$("#total").val(vTotal);
				$("#total").val(number_format($("#total").val(), 2, ',', '.'));

				var vRecibido = Number($("#recibido").val().replace('.', '').replace(',', '.'));
				$("#recibido").val(number_format(vRecibido, 2, ',', '.'));

				var vSaldo = vTotal - vRecibido;
				$("#saldo").val(vSaldo);
				$("#saldo").val(number_format($("#saldo").val(), 2, ',', '.'));

				if (vSaldo == 0) {
					$("#msjSaldo").text("Saldo")
					$("#msjSaldo").css("color", "#000000");
					if (vTotal > 0) {
						$("#pagado[value='1']").prop("checked", true);
					}
				} else {
					if (vSaldo > 0) {
						$("#msjSaldo").text("Saldo que adeuda el Cliente")
						$("#msjSaldo").css("color", "#ff0000");
						if (vTotal > 0) {
							$("#pagado[value='0']").prop("checked", true);
						}
					} else {
						$("#msjSaldo").text("Saldo a favor del Cliente");
						$("#msjSaldo").css("color", "#000000");
						if (vTotal > 0) {
							$("#pagado[value='1']").prop("checked", true);
						}
					}
				}
				$('input[type="submit"]').attr('disabled', false);
			}

			if (getUrlVars()["menu"] == "egresos") {
				var vImporte = 0;
				$("#listaDetalles > tbody:first").find("input[name='detalle']").each(function () {
					vImporteDetalle = $(this).closest("tr").find("td").eq(5).find("input[name='detalleTotal[]']").val();
					var vImporteDetalleAux = vImporteDetalle.replace('.', '').replace(',', '.');
					vImporte += Number(vImporteDetalleAux);
				});
				$("#total").val(vImporte);
				$("#total").val(number_format($("#total").val(), 2, ',', '.'));
			}

			if (getUrlVars()["menu"] == "cotizaciones") {
				var vImporte = 0;
				$("#listaDetalles > tbody:first").find("input[name='detalle']").each(function () {
					vImporteDetalle = $(this).closest("tr").find("td").eq(6).find("input[name='detalleTotal[]']").val();
					var vImporteDetalleAux = vImporteDetalle.replace('.', '').replace(',', '.');
					vImporte += Number(vImporteDetalleAux);
				});
				$("#total").val(vImporte);
				$("#total").val(number_format($("#total").val(), 2, ',', '.'));
			}

		})
	}

	if ($("#listaProductos").length) {
		$.getScript("../scripts/stupidtable.js?dev", function () {
			$("#listaProductos").stupidtable();
		});
	}

	if ($("button#btNuevoDetalle").length) {
		$("button#btNuevoDetalle").click(function (e) {
			e.preventDefault();

			$domModal = $($(this).attr("data-target"));
			$domModal.find('input[name="origen"]').val(e.target.id);
			$domModal.find(".modal-header h3").text("Buscar producto");
			$domModal.find(".modal-header #q").val("");
			$domModal.find(".modal-header #listaCategoria").get(0).selectedIndex = 0;
			$domModal.find(".modal-header #listaGenero").empty();
			$domModal.find(".modal-header #listaGenero").append("<option value=''>Género</option>");

			var vParametros = "";
			if (getUrlVars()["menu"] == "traspasos") {
				if (getUrlVars()["accion"] == "nuevo") {				
					vParametros = vParametros + "&ids=" + $("#sucursalorigen").val();
				} else {
					vParametros = vParametros + "&ids=" + $("#sucursalorigen option:selected").val();
				}
			} else {
				vParametros += "&ids=" + $("#sucursal").val();
			}
			$.getJSON('buscarDatos.php?t=listaGeneros' + vParametros, function (data) {
				if (data.length) {
					$.each(data, function (key, elemento) {
						$domModal.find(".modal-header #listaGenero").append('<option value="' + elemento.label + '">' + elemento.label + '</option>');
					})
				}
			})

			$domModal.find(".modal-header #listaGenero").get(0).selectedIndex = 0;
			$domModal.find(".modal-body #listaProductos").children("tbody").html("");
			$domModal.find(".modal-footer #detalleCantidad").val("");
			$domModal.find(".modal-footer #detalleDescuento").val("");
			$domModal.find(".modal-footer #detalleTotal").val("");
			if (getUrlVars()["menu"] == "traspasos") {
				$domModal.find(".modal-footer #detalleDescuento").closest("div.span3").html("");
				$domModal.find(".modal-footer #detalleTotal").closest("div.span3").html("");
			}
			if (getUrlVars()["menu"] == "egresos") {
				$domModal.find(".modal-footer #detalleDescuento").closest("div.span3").find("strong").text("Precio: ");
				$domModal.find(".modal-footer #detalleDescuento").attr("title", "Precio");
			}
			if (getUrlVars()["menu"] == "inventarios") {
				$domModal.find(".modal-footer #detalleCantidad").closest("div.span3").html("");
				$domModal.find(".modal-footer #detalleDescuento").closest("div.span3").html("");
				$domModal.find(".modal-footer #detalleTotal").closest("div.span3").html("");
			}

			$domModal.find(".modal-body").css('height', 350);
			$domModal.css('width', 830);

			$("input#btnModalCancelar").click(function () {
				$domModal.modal('hide');
			});

		})
	}

	if (((getUrlVars()["menu"] == "ventas") || (getUrlVars()["menu"] == "traspasos") || (getUrlVars()["menu"] == "cotizaciones") || (getUrlVars()["menu"] == "inventarios")) && $("#q").length) {
		$("#q").bind('keydown', function (e) {
			if (e.which == 13) {
				$("button#btnModalBuscar").trigger("click");
			}
		});
	}

	if (((getUrlVars()["menu"] == "ventas") || (getUrlVars()["menu"] == "traspasos") || (getUrlVars()["menu"] == "cotizaciones") || (getUrlVars()["menu"] == "inventarios")) && $("#listaCategoria").length) {
		$("#listaCategoria").bind('change', function () {
			$("#listaGenero").empty();
			$("#listaGenero").append("<option value=''>Género</option>");

			var vParametros = "";
			vParametros += "&c=" + $("#listaCategoria option:selected").val();
			if (getUrlVars()["menu"] == "traspasos") {
				if (getUrlVars()["accion"] == "nuevo") {				
					vParametros = vParametros + "&ids=" + $("#sucursalorigen").val();
				} else {
					vParametros = vParametros + "&ids=" + $("#sucursalorigen option:selected").val();
				}
			} else {
				vParametros += "&ids=" + $("#sucursal").val();
			}

			$.getJSON('buscarDatos.php?t=listaGeneros' + vParametros, function (data) {
				if (data.length) {
					$.each(data, function (key, elemento) {
						$("#listaGenero").append('<option value="' + elemento.label + '">' + elemento.label + '</option>');
					})
				}
			})

			$("button#btnModalBuscar").trigger("click");
			$("#nombre").focus();
		});
	}

	if (((getUrlVars()["menu"] == "ventas") || (getUrlVars()["menu"] == "traspasos") || (getUrlVars()["menu"] == "cotizaciones") || (getUrlVars()["menu"] == "inventarios")) && $("#listaGenero").length) {
		$("#listaGenero").bind('change', function () {
			$("button#btnModalBuscar").trigger("click");
			$("#nombre").focus();
		});
	}

	$("button#btnModalBuscar").click(function () {
		$domModal = $($(this).attr("data-target"));
		$domModal.find(".modal-body #listaProductos").children("tbody").html("");
		$domModal.find(".modal-body #listaProductos").children("tbody:first").append("<tr><td colspan='7' style='text-align: center;background: #ffffcc;color: #808080;'><img src='../images/preloader.gif' /> cargando datos ...</td></tr>");

		vParametros = "";
		if (getUrlVars()["menu"] == "traspasos") {
			if (getUrlVars()["accion"] == "nuevo") {				
				vParametros = vParametros + "&s=" + $("#sucursalorigen").val();
			} else {
				vParametros = vParametros + "&s=" + $("#sucursalorigen option:selected").val();
			}
		} else {
			vParametros = vParametros + "&s=" + $("#sucursal").val();
		}
		vParametros = vParametros + "&q=" + $domModal.find(".modal-header #q").val();
		vParametros = vParametros + "&c=" + unescape(encodeURIComponent($domModal.find(".modal-header #listaCategoria option:selected").val()));
		vParametros = vParametros + "&g=" + unescape(encodeURIComponent($domModal.find(".modal-header #listaGenero option:selected").val()));

		$.ajax({
			url: 'buscarDatos.php?t=productos' + vParametros,
			dataType: 'json',
			timeout: 1000,
			async: false,
			success: function (data) {
				$domModal.find(".modal-body #listaProductos").children("tbody").html("");
				$domModal.find(".modal-footer #detalleCantidad").val("");
				$domModal.find(".modal-footer #detalleDescuento").val("");
				$domModal.find(".modal-footer #detalleTotal").val("");

				if (data.length) {
					$.each(data, function (key, elemento) {
						var vEstilo = "";
						switch (elemento.categoria) {
							case "PC-DVD":
								vEstilo = "background: rgb(255,0,0);color: #ffffff;";
								break;
							case "PS2":
								vEstilo = "background: rgb(51,102,255);color: rgb(255,255,0);";
								break;
							case "PS3":
								vEstilo = "background: rgb(0,0,0);color: rgb(0,176,240);";
								break;
							case "PS4":
								vEstilo = "background: rgb(155,187,89);color: #ffffff;";
								break;
							case "Wii":
								vEstilo = "background: rgb(0,255,255);color: rgb(0,0,255);";
								break;
							case "XBOX360":
								vEstilo = "background: rgb(0,255,0);color: rgb(0,0,0);";
								break;
						}

						if ($("#listaDetalles > tbody:first").find("input[name='detalle']").filter("input[value='" + elemento.id + "']").length > 0) {
							var vFila = $("<tr class=\"unChecked\" style=\"color: #dddddd;\" />");
							vFila.append("<td><input type=\"radio\" name=\"productos\" value=\"" + elemento.id + "\" disabled=\"disabled\" /></td>");
						} else {
							var vFila = $("<tr class=\"unChecked\" />");
							vFila.append("<td><input type=\"radio\" name=\"productos\" value=\"" + elemento.id + "\" /></td>");
						}
						vFila.append("<td style='" + vEstilo + "'>" + elemento.codigo + "</td>");
						if (elemento.foto != "") {
							path_url_foto = "../../images/productos/" + elemento.foto;
							vFila.append("<td>" + elemento.nombre + " <a href=\"" + path_url_foto + "\" class=\"foto\" rel=\"colorbox\" title=\"[" + elemento.codigo + "] " + elemento.nombre + "\"><i class=\"icon-picture\"></i></a>" + "</td>");
						} else {
							vFila.append("<td>" + elemento.nombre + "</td>");
						}
						vFila.append("<td>" + elemento.categoria + "</td>");
						vFila.append("<td>" + elemento.genero + "</td>");
						vFila.append("<td style='text-align: right;'>" + elemento.precio_venta + "<input type='hidden' name='pcosto' value='" + elemento.precio_costo + "' /><input type='hidden' name='pventa' value='" + elemento.precio_venta + "' /></td>");
						vFila.append("<td style='text-align: right;'>" + elemento.stock + "</td>");

						$domModal.find(".modal-body #listaProductos").children("tbody:first").append(vFila);
					});

					if ($domModal.find(".modal-body #listaProductos tbody:first").find("a[rel=colorbox]").length) {
						$.getScript("../scripts/colorbox/jquery.colorbox.js", function () {
							$domModal.find(".modal-body #listaProductos tbody:first").find("a[rel=colorbox]").each(function (idx, el) {
								$("." + $(el).attr("class")).colorbox({ rel: $(el).attr("class"), width: "75%", height: "75%" });
							})
						});
					};

					if ($("input[name='productos']").length) { //	marco la fila seleccionada
						$("input[name='productos']").click(function () {
							$domModal.find(".modal-footer #detalleCantidad").val("1");
							$domModal.find(".modal-footer #detalleCantidad").trigger('change');
							$domModal.find(".modal-footer #detalleCantidad").focus();
							$domModal.find(".modal-footer #detalleCantidad").select();

							$("#detallePrecio").val($(this).closest("tr").find("td").eq(5).text());
							if (getUrlVars()["menu"] == "egresos") {
								$("#detalleDescuento").val($(this).closest("tr").find("td").eq(5).text());
							}

							$("#listaProductos > tbody:first").find("input[name='productos']").each(function () {
								if ($(this).prop("checked")) {
									$(this).closest("tr").attr("class", "Checked");
								} else {
									$(this).closest("tr").attr("class", "unChecked");
								}
							});

						})
					}

					$("table#listaProductos tbody tr").click(function () {
						if ($(this).find("td").eq(0).find("input[name='productos']").prop('disabled') == false) {
							$(this).find("td").eq(0).find("input[name='productos']").prop('checked', true);

							$domModal.find(".modal-footer #detalleCantidad").val("1");
							$domModal.find(".modal-footer #detalleCantidad").trigger('change');
							$domModal.find(".modal-footer #detalleCantidad").focus();
							$domModal.find(".modal-footer #detalleCantidad").select();

							$("#detallePrecio").val($(this).find("td").eq(5).text());

							if (getUrlVars()["menu"] == "egresos") {
								$("#detalleDescuento").val($(this).find("td").eq(5).text());
							} else {
								$("#detalleDescuento").val("0");
							}

							$("#listaProductos > tbody:first").find("input[name='productos']").each(function () {
								if ($(this).prop("checked")) {
									$(this).closest("tr").attr("class", "Checked");
								} else {
									$(this).closest("tr").attr("class", "unChecked");
								}
							});
						}

					});

				} else {
					$domModal.find(".modal-body #listaProductos").children("tbody:first").append("<tr><td colspan='7' style='text-align: center;background: #ffffcc;color: #808080;'> No hay coincidencias encontradas</td></tr>");
				}

			}
		})

	});

	if ($("#detalleCantidad").length) {
		$("#detalleCantidad").change(function () {
			var vImporte = $(this).val().replace('.', '').replace(',', '.');
			$(this).val(number_format(vImporte, 0, ',', '.'));

			if ((getUrlVars()["menu"] == "ventas") || (getUrlVars()["menu"] == "cotizaciones")) {
				var vCantidad = $("#detalleCantidad").val().replace('.', '').replace(',', '.');
				var vPrecio = $("#detallePrecio").val().replace('.', '').replace(',', '.');
				var vDescuento = $("#detalleDescuento").val().replace('.', '').replace(',', '.');
				var vTotal = (vPrecio - vDescuento) * vCantidad;
				$("#detalleTotal").val(number_format(vTotal, 2, ',', '.'));
			}
			if (getUrlVars()["menu"] == "egresos") {
				var vCantidad = $("#detalleCantidad").val().replace('.', '').replace(',', '.');
				var vPrecio = $("#detalleDescuento").val().replace('.', '').replace(',', '.');
				var vTotal = vPrecio * vCantidad;
				$("#detalleTotal").val(number_format(vTotal, 2, ',', '.'));
			}
		})

		$("#detalleCantidad2").bind('keydown', function (e) {
			if (e.which == 13) {
				if (getUrlVars()["menu"] == "traspasos") {
					$("input#btnModalGuardar").focus();
				} else {
					if (getUrlVars()["menu"] != "egresos") {
						$("#detalleDescuento").val("0");
					}
					$("#detalleCantidad").trigger('change');
					$("input#btnModalGuardar").focus();
				}
				return false;
			}
		})

	}

	if ($("#detalleDescuento").length) {
		$("#detalleDescuento").change(function () {
			var vImporte = $(this).val().replace('.', '').replace(',', '.');
			$(this).val(number_format(vImporte, 2, ',', '.'));

			if ((getUrlVars()["menu"] == "ventas") || (getUrlVars()["menu"] == "cotizaciones")) {
				var vCantidad = $("#detalleCantidad").val().replace('.', '').replace(',', '.');
				var vPrecio = $("#detallePrecio").val().replace('.', '').replace(',', '.');
				var vDescuento = $("#detalleDescuento").val().replace('.', '').replace(',', '.');
				var vTotal = (vPrecio - vDescuento) * vCantidad;
				$("#detalleTotal").val(number_format(vTotal, 2, ',', '.'));
			}
			if (getUrlVars()["menu"] == "egresos") {
				var vCantidad = $("#detalleCantidad").val().replace('.', '').replace(',', '.');
				var vPrecio = $("#detalleDescuento").val().replace('.', '').replace(',', '.');
				var vTotal = vPrecio * vCantidad;
				$("#detalleTotal").val(number_format(vTotal, 2, ',', '.'));
			}

		})

		$("#detalleDescuento2").bind('keydown', function (e) {
			if (e.which == 13) {
				$("input#btnModalGuardar").focus();
				return false;
			}
		})
	}

	$("input#btnModalGuardar").click(function () {
		$domModal = $($(this).attr("data-target"));

		if ($("#detalleCantidad").length) {
			$("#detalleCantidad").trigger('change');
		}
		if ($("#detalleDescuento").length) {
			$("#detalleDescuento").trigger('change');
		}

		if ($("#listaProductos > tbody:first").find("tr").length == 0) {
			alert(htmlDecode("Debe buscar el PRODUCTO a agregar, ya sea por su C&oacute;digo, Nombre, C&oacute;digo de Barra, Categoria o G&eacute;nero."));
			$("#nombre").focus();
		} else {

			if ($("#listaProductos > tbody:first").find("input[name='productos']:checked").length == 0) {
				alert("Debe seleccionar un PRODUCTO.");
			} else {
				var vId = "";
				var vCodigo = "";
				var vNombre = "";
				var vCategoria = "";
				var vPrecioCosto = 0;
				var vPrecioVenta = 0;
				var vStock = 0;
				var vValor = 0;
				var vStockDestino = 0;

				switch (getUrlVars()["menu"]) {
					case "cotizaciones":
						vPrecio = Number($("#listaProductos > tbody:first").find("input[name='productos']:checked").closest("tr").find("td").eq(5).text().replace('.', '').replace(',', '.'));
						if (vPrecio == 0) {
							alert("El PRODUCTO seleccionado no posee PRECIO.");
						} else {

							vValor = Number($("#detalleCantidad").val().replace('.', '').replace(',', '.'));
							if (vValor <= 0) {
								alert("La CANTIDAD debe tener un valor numerico positivo.");
								$("#detalleCantidad").focus();
								$("#detalleCantidad").select();
							} else {
								vValor = Number($("#detalleDescuento").val().replace('.', '').replace(',', '.'));
								if (vValor < 0) {
									alert("El DESCUENTO debe tener un valor numerico positivo.");
									$("#detalleDescuento").focus();
								} else {
									vValor = Number($("#detalleTotal").val().replace('.', '').replace(',', '.'));
									if (vValor <= 0) {
										//$("#detalleTotal").focus();
										alert("El TOTAL debe tener un valor numerico positivo.\n");
									} else {
										//	obtengo todos los ELEMENTOS seleccionados
										$("#listaProductos > tbody:first").find("input[name='productos']:checked").each(function () {
											vId = $(this).val();
											vCodigo = $(this).closest("tr").find("td").eq(1).text();
											vNombre = $(this).closest("tr").find("td").eq(2).text();
											vCategoria = $(this).closest("tr").find("td").eq(3).text();
											vPrecioCosto = $(this).closest("tr").find("td").eq(5).find("input[name='pcosto']").val();
											vPrecioVenta = $(this).closest("tr").find("td").eq(5).find("input[name='pventa']").val();

											//	verifico si ya esta en el detalle
											if ($("#listaDetalles > tbody:first").find("input[name='detalle']").filter("input[value='" + vId + "']").length > 0) {
												alert("El PRODUCTO seleccionado ya se encuentra en el DETALLE actual.");
											} else {
												if (confirm(htmlDecode("Se va a agregar el siguiente PRODUCTO:\n\C&oacute;digo: " + vCodigo + "\nDetalle:" + vNombre + " [" + vCategoria + "]\nCantidad: " + $("#detalleCantidad").val() + "\nPrecio: " + vPrecioVenta + "\nDescuento: " + $("#detalleDescuento").val() + "\nTotal: " + $("#detalleTotal").val() + "\n\nEsta usted seguro?"))) {

													$("#listaDetalles > tbody:first").append("<tr class='unChecked'><td><input type='checkbox' name='detalle' value='" + vId + "' /><input type='hidden' name='id_detalle[]' value='" + vId + "' /></td><td>" + vCodigo + "<input type='hidden' name='detalleCodigo[]' value='" + vCodigo + "' /></td><td>" + vNombre + " [" + vCategoria + "]" + "<input type='hidden' name='detalle[]' value='" + vNombre + " [" + vCategoria + "]" + "' /></td><td style='text-align: right;'>" + $("#detalleCantidad").val() + "<input type='hidden' name='detalleCantidad[]' value='" + $("#detalleCantidad").val() + "' /></td><td style='text-align: right;'>" + vPrecioVenta + "<input type='hidden' name='detallePrecioCosto[]' value='" + vPrecioCosto + "' /><input type='hidden' name='detallePrecioVenta[]' value='" + vPrecioVenta + "' /></td><td style='text-align: right;padding-right: 10px;'>" + $("#detalleDescuento").val() + "<input type='hidden' name='detalleDescuento[]' value='" + $("#detalleDescuento").val() + "' /></td><td style='text-align: right;padding-right: 10px;'>" + $("#detalleTotal").val() + "<input type='hidden' name='detalleTotal[]' value='" + $("#detalleTotal").val() + "' /></td></tr>");

													$("input[name='detalle']").click(function () {
														if ($(this).prop("checked")) {
															$(this).closest("tr").attr("class", "Checked");
														} else {
															$(this).closest("tr").attr("class", "unChecked");
														}
														var $filas = $("input[name='detalle']:checked");
														if ($filas.length > 0) {
															if ($("#btEliminarDetalle").length) { $("#btEliminarDetalle").attr("disabled", false); }
														} else {
															if ($("#btEliminarDetalle").length) { $("#btEliminarDetalle").attr("disabled", true); }
														}
													})

													$("#btnCalcularSubtotal").trigger("click");

													$domModal.modal('hide');
												}
											}
										})

										if ($("#listaDetalles > tbody:first").find("tr").length > 0) {
											$('input[type="submit"]').attr('disabled', false);
										}

									}

								}

							}

						}
						break;
					case "inventarios":
						//	obtengo todos los ELEMENTOS seleccionados
						$("#listaProductos > tbody:first").find("input[name='productos']:checked").each(function () {
							vId = $(this).val();
							vCodigo = $(this).closest("tr").find("td").eq(1).text();
							vNombre = $(this).closest("tr").find("td").eq(2).text();
							vCategoria = $(this).closest("tr").find("td").eq(3).text();
							vPrecioCosto = $(this).closest("tr").find("td").eq(5).find("input[name='pcosto']").val();
							vPrecioVenta = $(this).closest("tr").find("td").eq(5).find("input[name='pventa']").val();
							vStock = $(this).closest("tr").find("td").eq(6).text();

							//	verifico si ya esta en el detalle
							if ($("#listaDetalles > tbody:first").find("input[name='detalle']").filter("input[value='" + vId + "']").length > 0) {
								alert("El PRODUCTO seleccionado ya se encuentra en el DETALLE actual.");
							} else {

								if (confirm(htmlDecode("Se va a agregar el siguiente PRODUCTO:\n\C&oacute;digo: " + vCodigo + "\nDetalle:" + vNombre + " [" + vCategoria + "]" + "\nStock: " + vStock + "\n\nEsta usted seguro?"))) {

									$("#listaDetalles > tbody:first").append("<tr class='unChecked'><td><input type='checkbox' name='detalle' value='" + vId + "' /><input type='hidden' name='id_detalle[]' value='" + vId + "' /></td><td>" + vCodigo + "<input type='hidden' name='detalleCodigo[]' value='" + vCodigo + "' /></td><td>" + vNombre + " [" + vCategoria + "]" + "<input type='hidden' name='detalle[]' value='" + vNombre + " [" + vCategoria + "]" + "' /></td><td style='text-align: right;padding-right: 10px;'>" + vStock + "<input type='hidden' name='detalleStock[]' value='" + vStock + "' /></td><td style='text-align: right;'><input type='text' name='detalleCantidad[]' value='' class='input-small' placeholder='0' style='text-align: right;' required /></td><td style='text-align: right;padding-right: 10px;'><input type='text' name='detalleDiferencia[]' value='' class='input-small' placeholder='0' style='text-align: right;' required /></td></tr>");

									$("input[name='detalle']").click(function () {
										if ($(this).prop("checked")) {
											$(this).closest("tr").attr("class", "Checked");
										} else {
											$(this).closest("tr").attr("class", "unChecked");
										}
										var $filas = $("input[name='detalle']:checked");
										if ($filas.length > 0) {
											if ($("#btEliminarDetalle").length) { $("#btEliminarDetalle").attr("disabled", false); }
										} else {
											if ($("#btEliminarDetalle").length) { $("#btEliminarDetalle").attr("disabled", true); }
										}
									})

									$domModal.modal('hide');
								}

							}

						})

						if ($("#listaDetalles > tbody:first").find("tr").length > 0) {
							$('input[type="submit"]').attr('disabled', false);
						}
						break;
					case "traspasos":
						// verifico si el producto seleccionado tiene STOCK
						vStock = Number($("#listaProductos > tbody:first").find("input[name='productos']:checked").closest("tr").find("td").eq(6).text().replace('.', '').replace(',', '.'));
						if (vStock == 0) {
							alert("El PRODUCTO seleccionado no posee STOCK.");
						} else {
							// verifico la CANTIDAD ingresada
							vValor = Number($("#detalleCantidad").val().replace('.', '').replace(',', '.'));
							if (vValor <= 0) {
								alert("La CANTIDAD debe tener un valor numerico positivo.");
								$("#detalleCantidad").focus();
								$("#detalleCantidad").select();
							} else {
								// verifico que la CANTIDAD ingresada sea menor o igual al STOCK
								if (vValor > vStock) {
									alert("La CANTIDAD debe tener un valor numerico positivo,\nmenor o igual al STOCK del Producto (" + vStock + ").");
									$("#detalleCantidad").focus();
									$("#detalleCantidad").select();
								} else {
									//	obtengo todos los ELEMENTOS seleccionados
									$("#listaProductos > tbody:first").find("input[name='productos']:checked").each(function () {
										vId = $(this).val();
										vCodigo = $(this).closest("tr").find("td").eq(1).text();
										vNombre = $(this).closest("tr").find("td").eq(2).text();
										vCategoria = $(this).closest("tr").find("td").eq(3).text();
										vPrecioCosto = $(this).closest("tr").find("td").eq(5).find("input[name='pcosto']").val();
										vPrecioVenta = $(this).closest("tr").find("td").eq(5).find("input[name='pventa']").val();
										vStock = $(this).closest("tr").find("td").eq(6).text();

										//	verifico si ya esta en el detalle
										if ($("#listaDetalles > tbody:first").find("input[name='detalle']").filter("input[value='" + vId + "']").length > 0) {
											alert("El PRODUCTO seleccionado ya se encuentra en el DETALLE actual.");
										} else {

											//	obtengo el STOCK en SUCURSAL DESTINO
											$.getJSON('buscarDatos.php?t=productos&s=' + $("#sucursaldestino option:selected").val() + "&id=" + vId, function (data) {
												if (data.length) {
													$.each(data, function (key, elemento) {
														vStockDestino = elemento.stock;
													})
												}
											})

											if (confirm(htmlDecode("Se va a agregar el siguiente PRODUCTO:\n\C&oacute;digo: " + vCodigo + "\nDetalle: " + vNombre + " [" + vCategoria + "]\nCantidad: " + $("#detalleCantidad").val() + "\n\nEsta usted seguro?"))) {

												$("#listaDetalles > tbody:first").append("<tr class='unChecked'><td><input type='checkbox' name='detalle' value='" + vId + "' /><input type='hidden' name='id_detalle[]' value='" + vId + "' /></td><td>" + vCodigo + "<input type='hidden' name='detalleCodigo[]' value='" + vCodigo + "' /></td><td>" + vNombre + " [" + vCategoria + "]" + "<input type='hidden' name='detalle[]' value='" + vNombre + " [" + vCategoria + "]" + "' /></td><td style='text-align: right;'>" + vStock + "<input type='hidden' name='detalleStockOrigen[]' value='" + vStock + "' /></td><td style='text-align: right;'>" + vStockDestino + "<input type='hidden' name='detalleStockDestino[]' value='" + vStockDestino + "' /></td><td style='text-align: right;'>" + $("#detalleCantidad").val() + "<input type='hidden' name='detalleCantidad[]' value='" + $("#detalleCantidad").val() + "' /></td></tr>");

												$("input[name='detalle']").click(function () {
													if ($(this).prop("checked")) {
														$(this).closest("tr").attr("class", "Checked");
													} else {
														$(this).closest("tr").attr("class", "unChecked");
													}
													var $filas = $("input[name='detalle']:checked");
													if ($filas.length > 0) {
														if ($("#btEliminarDetalle").length) { $("#btEliminarDetalle").attr("disabled", false); }
													} else {
														if ($("#btEliminarDetalle").length) { $("#btEliminarDetalle").attr("disabled", true); }
													}
												})

												//$("#btnCalcularSubtotal").trigger("click");

												$domModal.modal('hide');
											}
										}

									})

									if ($("#listaDetalles > tbody:first").find("tr").length > 0) {
										$('input[type="submit"]').attr('disabled', false);
									}
								}

							}

						}
						break;
					default:
						vStock = Number($("#listaProductos > tbody:first").find("input[name='productos']:checked").closest("tr").find("td").eq(6).text().replace('.', '').replace(',', '.'));
						if ((vStock == 0) && (getUrlVars()["menu"] == "ventas")) {
							alert("El PRODUCTO seleccionado no posee STOCK.");
						} else {

							vValor = Number($("#detalleCantidad").val().replace('.', '').replace(',', '.'));
							if (vValor <= 0) {
								alert("La CANTIDAD debe tener un valor numerico positivo.");
								$("#detalleCantidad").focus();
								$("#detalleCantidad").select();
							} else {
								vValor = Number($("#detalleDescuento").val().replace('.', '').replace(',', '.'));
								if (vValor < 0) {
									if ((getUrlVars()["menu"] == "ventas") || (getUrlVars()["menu"] == "cotizaciones")) {
										alert("El DESCUENTO debe tener un valor numerico positivo.");
									}
									if (getUrlVars()["menu"] == "egresos") {
										alert("El PRECIO debe tener un valor numerico positivo.");
									}
									$("#detalleDescuento").focus();
								} else {
									vValor = Number($("#detalleTotal").val().replace('.', '').replace(',', '.'));
									if (vValor <= 0) {
										alert("El TOTAL debe tener un valor numerico positivo.\n");
									} else {
										//	obtengo todos los ELEMENTOS seleccionados
										$("#listaProductos > tbody:first").find("input[name='productos']:checked").each(function () {
											vId = $(this).val();
											vCodigo = $(this).closest("tr").find("td").eq(1).text();
											vNombre = $(this).closest("tr").find("td").eq(2).text();
											vCategoria = $(this).closest("tr").find("td").eq(3).text();
											vPrecioCosto = $(this).closest("tr").find("td").eq(5).find("input[name='pcosto']").val();
											vPrecioVenta = $(this).closest("tr").find("td").eq(5).find("input[name='pventa']").val();

											//	verifico si ya esta en el detalle
											if ($("#listaDetalles > tbody:first").find("input[name='detalle']").filter("input[value='" + vId + "']").length > 0) {
												alert("El PRODUCTO seleccionado ya se encuentra en el DETALLE actual.");
											} else {

												if ((getUrlVars()["menu"] == "ventas") || (getUrlVars()["menu"] == "cotizaciones")) {
													if (confirm(htmlDecode("Se va a agregar el siguiente PRODUCTO:\n\C&oacute;digo: " + vCodigo + "\nDetalle:" + vNombre + " [" + vCategoria + "]\nCantidad: " + $("#detalleCantidad").val() + "\nPrecio: " + vPrecioVenta + "\nDescuento: " + $("#detalleDescuento").val() + "\nTotal: " + $("#detalleTotal").val() + "\n\nEsta usted seguro?"))) {

														$("#listaDetalles > tbody:first").append("<tr class='unChecked'><td><input type='checkbox' name='detalle' value='" + vId + "' /><input type='hidden' name='id_detalle[]' value='" + vId + "' /></td><td>" + vCodigo + "<input type='hidden' name='detalleCodigo[]' value='" + vCodigo + "' /></td><td>" + vNombre + " [" + vCategoria + "]" + "<input type='hidden' name='detalle[]' value='" + vNombre + " [" + vCategoria + "]" + "' /></td><td style='text-align: right;'>" + $("#detalleCantidad").val() + "<input type='hidden' name='detalleCantidad[]' value='" + $("#detalleCantidad").val() + "' /></td><td style='text-align: right;'>" + vPrecioVenta + "<input type='hidden' name='detallePrecioCosto[]' value='" + vPrecioCosto + "' /><input type='hidden' name='detallePrecioVenta[]' value='" + vPrecioVenta + "' /></td><td style='text-align: right;padding-right: 10px;'>" + $("#detalleDescuento").val() + "<input type='hidden' name='detalleDescuento[]' value='" + $("#detalleDescuento").val() + "' /></td><td style='text-align: right;padding-right: 10px;'>" + $("#detalleTotal").val() + "<input type='hidden' name='detalleTotal[]' value='" + $("#detalleTotal").val() + "' /></td></tr>");

														$("input[name='detalle']").click(function () {
															if ($(this).prop("checked")) {
																$(this).closest("tr").attr("class", "Checked");
															} else {
																$(this).closest("tr").attr("class", "unChecked");
															}
															var $filas = $("input[name='detalle']:checked");
															if ($filas.length > 0) {
																if ($("#btEliminarDetalle").length) { $("#btEliminarDetalle").attr("disabled", false); }
															} else {
																if ($("#btEliminarDetalle").length) { $("#btEliminarDetalle").attr("disabled", true); }
															}
														})

														$("#btnCalcularSubtotal").trigger("click");

														$domModal.modal('hide');
													}

												}

												if (getUrlVars()["menu"] == "egresos") {
													if (confirm(htmlDecode("Se va a agregar el siguiente PRODUCTO:\n\C&oacute;digo: " + vCodigo + "\nDetalle:" + vNombre + " [" + vCategoria + "]" + "\nPrecio: " + $("#detalleDescuento").val() + "\nCantidad: " + $("#detalleCantidad").val() + "\nTotal: " + $("#detalleTotal").val() + "\n\nEsta usted seguro?"))) {

														$("#listaDetalles > tbody:first").append("<tr class='unChecked'><td><input type='checkbox' name='detalle' value='" + vId + "' /><input type='hidden' name='id_detalle[]' value='" + vId + "' /></td><td>" + vCodigo + "<input type='hidden' name='detalleCodigo[]' value='" + vCodigo + "' /></td><td>" + vNombre + " [" + vCategoria + "]" + "<input type='hidden' name='detalle[]' value='" + vNombre + " [" + vCategoria + "]" + "' /></td><td style='text-align: right;padding-right: 10px;'>" + $("#detalleDescuento").val() + "<input type='hidden' name='detallePrecio[]' value='" + $("#detalleDescuento").val() + "' /></td><td style='text-align: right;'>" + $("#detalleCantidad").val() + "<input type='hidden' name='detalleCantidad[]' value='" + $("#detalleCantidad").val() + "' /></td><td style='text-align: right;padding-right: 10px;'>" + $("#detalleTotal").val() + "<input type='hidden' name='detalleTotal[]' value='" + $("#detalleTotal").val() + "' /></td></tr>");

														$("input[name='detalle']").click(function () {
															if ($(this).prop("checked")) {
																$(this).closest("tr").attr("class", "Checked");
															} else {
																$(this).closest("tr").attr("class", "unChecked");
															}
															var $filas = $("input[name='detalle']:checked");
															if ($filas.length > 0) {
																if ($("#btEliminarDetalle").length) { $("#btEliminarDetalle").attr("disabled", false); }
															} else {
																if ($("#btEliminarDetalle").length) { $("#btEliminarDetalle").attr("disabled", true); }
															}
														})

														$("#btnCalcularSubtotal").trigger("click");

														$domModal.modal('hide');
													}
												}

											}

										})

										if ($("#listaDetalles > tbody:first").find("tr").length > 0) {
											$('input[type="submit"]').attr('disabled', false);
										}

									}

								}

							}

						}
						break;
				}

			}

		}

	});

	if ($("button#btEliminarDetalle").length) {
		$("button#btEliminarDetalle").click(function (e) {
			e.preventDefault();
			$("#vMsjCargando").find("span").text("eliminando Detalle...");
			$("#vMsjCargando").show();

			$("#listaDetalles > tbody:first").find("input[name='detalle']:checked").each(function () {

				if ((getUrlVars()["menu"] == "ventas") && (getUrlVars()["accion"] == "editar")) {
					var vFila = $(this);
					//console.log("Debo actualizar el STOCK del PRODUCTO: "+$(this).val()+" en la VENTA: "+$('#id_venta').val()+" de la SUCURSAL: "+$('#sucursal').val());
					$.getJSON('eliminarDatos.php?menu=' + getUrlVars()["menu"] + '&accion=eliminarDetalle&idv=' + $('#id_venta').val() + '&idp=' + $(this).val() + '&ids=' + $('#sucursal').val(), function (data) {
						if (data.length) {
							$.each(data, function (key, elemento) {
								if (elemento.estado == "OK") {
									vFila.closest("tr").remove();
									$("#btnCalcularSubtotal").trigger("click");
								}
							})
						}
					})

				} else {
					$(this).closest("tr").remove();
				}
			});
			$("#btEliminarDetalle").attr("disabled", true);

			$("#btnCalcularSubtotal").trigger("click");

			$("#vMsjCargando").hide();
		})
	}

	if ($("input[name='detalle']").length) {
		$("input[name='detalle']").click(function () {
			if ($(this).prop("checked")) {
				$(this).closest("tr").attr("class", "Checked");
			} else {
				$(this).closest("tr").attr("class", "unChecked");
			}
			var $filas = $("input[name='detalle']:checked");
			if ($filas.length > 0) {
				if ($("#btEliminarDetalle").length) { $("#btEliminarDetalle").attr("disabled", false); }
			} else {
				if ($("#btEliminarDetalle").length) { $("#btEliminarDetalle").attr("disabled", true); }
			}
		})
	}

	if ($("button#btIva").length) {
		$("button#btIva").click(function (e) {
			e.preventDefault();
			var vImporte = Number($("#importe").val().replace('.', '').replace(',', '.'));
			var vIva = (Number($(this).attr("data-porcentaje")) * vImporte) / 100;
			$("#iva").val(number_format(vIva, 2, ',', '.'));
			$("#btnCalcularSubtotal").trigger("click");
			$("#iva").focus();
			$("#iva").select();
		})
	}

	if ($("#importe").length || $("#iva").length || $("#subtotal").length || $("#descuento").length || $("#total").length || $("#recibido").length || $("#saldo").length) {
		var formatearImporte = function () {
			var vImporte = $(this).val().replace('.', '').replace(',', '.');
			$(this).val(number_format(vImporte, 2, ',', '.'));
			$("#btnCalcularSubtotal").trigger("click");
		};

		var recalcularImporte = function (e) {
			if (e.which == 13) {
				$("#btnCalcularSubtotal").trigger("click");
				if ((getUrlVars()["menu"] == "ventas") || (getUrlVars()["menu"] == "servicios") || (getUrlVars()["menu"] == "pedidos")) {
					switch ($(this).attr("name")) {
						case "importe":
							$("#iva").focus();
							$("#iva").select();
							break;
						case "iva":
							$("#descuento").focus();
							$("#descuento").select();
							break;
						case "descuento":
							$("#recibido").focus();
							$("#recibido").select();
							break;
						case "recibido":
							$('[type="submit"]').focus();
							break;
						default:
							$(this).focus();
							break;
					}
				} else {
					$(this).focus();
				}
				return false;
			}
		};

		var seleccionarImporte = function () {
			$(this).select();
			return false;
		};

		$("#importe").bind('change', formatearImporte);
		$("#importe").bind('keypress', recalcularImporte);
		$("#iva").bind('change', formatearImporte);
		$("#iva").bind('keypress', recalcularImporte);
		$("#subtotal").bind('change', formatearImporte);
		$("#descuento").bind('change', formatearImporte);
		$("#descuento").bind('keypress', recalcularImporte);
		$("#total").bind('change', formatearImporte);
		$("#recibido").bind('change', formatearImporte);
		$("#recibido").bind('keypress', recalcularImporte);
		$("#saldo").bind('change', formatearImporte);

		if ((getUrlVars()["menu"] == "ventas") || (getUrlVars()["menu"] == "servicios") || (getUrlVars()["menu"] == "pedidos")) {
			$("#importe").bind('click', seleccionarImporte);
			$("#iva").bind('click', seleccionarImporte);
			$("#descuento").bind('click', seleccionarImporte);
			$("#recibido").bind('click', seleccionarImporte);
		}
	}

	/*	PRODUCTOS	*/
	if ($("#tipo_producto").length) {
		$("#tipo_producto").autocomplete({
			source: function (request, response) {
				$.getJSON("buscarDatos.php", {
					t: "tipo_producto",
					term: request.term,
					m: getUrlVars()["menu"],
					ids: $("#sucursal").val()
				}, response);
			},
			minLength: 0,
			select: function (event, ui) {
				$(this).val(ui.item.label);
				event.preventDefault();
			},
			focus: function (event, ui) {
				$(this).val(ui.item.label);
				event.preventDefault();
			}
		}).focus(function () {
			if ($(this).val() != "") {
				return false;
			} else {
				$(this).autocomplete("search", $(this).val());
			}
		});
	}

	if ($("#item_serie").length) {
		$("#item_serie").bind('keypress', function (e) {
			if ((e.which == 13) && ($(this).val() != "")) {
				$valor = $(this).val();
				// verifico si el valor ya esta en el elemento
				if ($("#nro_serie").find("option[value='" + $valor + "']").length > 0) {
					$(this).focus().select();
					alert("El valor \"" + $valor + "\" ya esta agregado.");
				} else {
					// verifico si el valor ya esta registrado en el sistema
					var vParametros = "";
					vParametros += "&c=" + $valor + "&m=" + getUrlVars()["menu"];
					if ($("#sucursal").length) { vParametros += "&s=" + $("#sucursal").val(); }

					$.getJSON('buscarDatos.php?t=verificarNroSerie' + vParametros, function (data) {
						if (data.length) {
							vMsj = "El Numero de Serie esta asignado a:\n\n";
							$.each(data, function (key, elemento) {
								vMsj += "- [" + elemento.codigo + "] " + elemento.nombre + " de la Sucursal: " + elemento.sucursal + "\n";
							})
							vMsj += "\nNo se pueden registrar Productos con un Numero de\nSerie existente.";
							alert(vMsj);
							$("#item_serie").focus();
						} else {
							$("#nro_serie").append("<option value='" + $valor + "'>" + $valor + "</option>");
							$("#item_serie").val("").focus();
						}
					});
				}
				return false;
			} else {
				if ((e.which == 13) && ($(this).val() == "")) {
					$(this).focus();
					return false;
				}
			}
		})
	}
	if ($("#btnAgregaSerie").length) {
		$("#btnAgregaSerie").bind('click', function (e) {
			$valor = $("#item_serie").val();
			if($valor != ""){
				// verifico si el valor ya esta en el elemento
				if ($("#nro_serie").find("option[value='" + $valor + "']").length > 0) {
					$("#item_serie").focus().select();
					alert("El valor \"" + $valor + "\" ya esta agregado.");
				} else {
					// verifico si el valor ya esta registrado en el sistema
					var vParametros = "";
					vParametros += "&c=" + $valor + "&m=" + getUrlVars()["menu"];
					if ($("#sucursal").length) { vParametros += "&s=" + $("#sucursal").val(); }

					$.getJSON('buscarDatos.php?t=verificarNroSerie' + vParametros, function (data) {
						if (data.length) {
							vMsj = "El Numero de Serie esta asignado a:\n\n";
							$.each(data, function (key, elemento) {
								vMsj += "- [" + elemento.codigo + "] " + elemento.nombre + " de la Sucursal: " + elemento.sucursal + "\n";
							})
							vMsj += "\nNo se pueden registrar Productos con un Numero de\nSerie existente.";
							alert(vMsj);
							$("#item_serie").focus();
						} else {
							$("#nro_serie").append("<option value='" + $valor + "'>" + $valor + "</option>");
							$("#item_serie").val("").focus();
						}
					});
				}
				return false;
			} else {
				$("#item_serie").focus();
				return false;
			}
		})
	}

	if ($("#nro_serie").length) {
		$("#nro_serie").bind('dblclick', function (e) {
			if (confirm("Se va a ELIMINAR el valor: \"" + $(this).val() + "\".\nEsta usted seguro?")) {
				$("#nro_serie option[value='" + $(this).val() + "']").remove();
			}
		})
	}

	if ($("#numero").length && $("#letra").length) {
		var verificarNumeroyLetra = function () {
			if ($(this).attr("name") == "numero") {
				$(this).val(sprintf("%04s", $(this).val()));
			}
			if ($(this).attr("name") == "letra") {
				$(this).val($(this).val().toUpperCase());
			}

			if (($("#numero").val() != "") && ($("#letra").val() != "")) {
				var vParametros = "";
				vParametros += "&n=" + $("#numero").val();
				vParametros += "&l=" + $("#letra").val();
				if ($("#sucursal").length) { vParametros += "&s=" + $("#sucursal").val(); }

				$.getJSON('buscarDatos.php?t=verificarCodigo' + vParametros, function (data) {
					if (data.length) {
						vMsj = "El Código de Producto: \"" + $("#numero").val() + "-" + $("#letra").val() + "\" ya ha sido asignado a:\n\n";
						$.each(data, function (key, elemento) {
							vMsj += "- \"" + elemento.nombre + "\" en la Categoría: \"" + elemento.categoria + "\"\n";
						})
						vMsj += "\nNo se pueden registrar nuevos Productos con un Código existente.";
						alert(vMsj);
						$("#numero").focus();
						$('input[type="submit"]').attr('disabled', true);
					} else {
						$('input[type="submit"]').attr('disabled', false);
					}
				});
			}
		}

		var obtenerEnter = function (e) {
			if (e.which == 13) {
				$(this).trigger("click");
				$(this).focus();
				return false;
			}
		};

		$("#numero").bind('change', verificarNumeroyLetra);
		$("#numero").bind('keypress', obtenerEnter);
		$("#letra").bind('change', verificarNumeroyLetra);
		$("#letra").bind('keypress', obtenerEnter);
	}

	if ($("#precio_costo").length || $("#precio_venta").length ||
		$("#cantidad_pieza_caja").length || $("#precio_costo_unitario").length || $("#precio_venta_caja").length || $("#precio_total_venta_caja").length ||
		$("#precio_venta_docena").length || $("#precio_total_venta_docena").length) {
		var formatearImporte = function () {
			if ($(this).attr('id') != 'cantidad_pieza_caja') {
				var vImporte = $(this).val().replace('.', '').replace(',', '.');
				$(this).val(number_format(vImporte, 2, ',', '.'));
			}

			if (($(this).attr('id') == 'precio_costo') || ($(this).attr('id') == 'cantidad_pieza_caja')) {
				var vPrecioCosto = Number($("#precio_costo").val().replace('.', '').replace(',', '.'));
				var vCantidad_pieza_caja = Number($("#cantidad_pieza_caja").val().replace('.', '').replace(',', '.'));

				const vImporte = vPrecioCosto / vCantidad_pieza_caja
				$("#precio_costo_unitario").val(vImporte);
				$("#precio_costo_unitario").val(number_format($("#precio_costo_unitario").val(), 2, ',', '.'));

				if (($(this).attr('id') == 'cantidad_pieza_caja') && (getUrlVars()["menu"] == "productos") && getUrlVars()["accion"] == "nuevo") {
					$("#stock").val($(this).val());
				}

			}

			if (($(this).attr('id') == 'precio_venta_caja') || ($(this).attr('id') == 'cantidad_pieza_caja')) {
				var vCantidad_pieza_caja = Number($("#cantidad_pieza_caja").val().replace('.', '').replace(',', '.'));
				var vPrecio_venta_caja = Number($("#precio_venta_caja").val().replace('.', '').replace(',', '.'));

				var vImporte = vCantidad_pieza_caja * vPrecio_venta_caja;
				$("#precio_total_venta_caja").val(vImporte);
				$("#precio_total_venta_caja").val(number_format($("#precio_total_venta_caja").val(), 2, ',', '.'));
			}
			if ($(this).attr('id') == 'precio_venta_docena') {
				var vPrecio_venta_docena = Number($("#precio_venta_docena").val().replace('.', '').replace(',', '.'));

				var vImporte = 12 * vPrecio_venta_docena;
				$("#precio_total_venta_docena").val(vImporte);
				$("#precio_total_venta_docena").val(number_format($("#precio_total_venta_docena").val(), 2, ',', '.'));
			}
		};

		$("#precio_costo").bind('change', formatearImporte);
		$("#precio_costo_unitario").bind('change', formatearImporte);
		$("#precio_venta").bind('change', formatearImporte);
		$("#precio_venta_caja").bind('change', formatearImporte);
		$("#precio_total_venta_caja").bind('change', formatearImporte);
		$("#precio_venta_docena").bind('change', formatearImporte);
		$("#precio_total_venta_docena").bind('change', formatearImporte);
		$("#cantidad_pieza_caja").bind('change', formatearImporte);
	}

	if($("#tipo_cambio_actual").length || $("#tipo_cambio").length){
		var formatearImporteTipoCambio = function () {
			if ($(this).attr('id') != 'cantidad_pieza_caja') {
				var vImporte = $(this).val().replace('.', '').replace(',', '.');
				$(this).val(number_format(vImporte, 4, ',', '.'));
			}
		}
		$("#tipo_cambio_actual").bind('change', formatearImporteTipoCambio);
		$("#tipo_cambio").bind('change', formatearImporteTipoCambio);
	}

	if ($("#genero").length) {
		$("#genero").autocomplete({
			source: function (request, response) {
				$.getJSON("buscarDatos.php", {
					t: "genero",
					term: request.term,
					m: getUrlVars()["menu"],
					c: $("#categoria").val(),
					ids: $("#sucursal").val()
				}, response);
			},
			minLength: 0,
			select: function (event, ui) {
				$(this).val(ui.item.label);
				event.preventDefault();
			},
			focus: function (event, ui) {
				$(this).val(ui.item.label);
				event.preventDefault();
			}
		}).focus(function () {
			if ($(this).val() != "") {
				return false;
			} else {
				$(this).autocomplete("search", $(this).val());
			}
		});
	}

	if ($("#marca").length) {
		$("#marca").autocomplete({
			source: function (request, response) {
				$.getJSON("buscarDatos.php", {
					t: "marca",
					term: request.term,
					m: getUrlVars()["menu"],
					ids: $("#sucursal").val()
				}, response);
			},
			minLength: 0,
			select: function (event, ui) {
				$(this).val(ui.item.label);
				event.preventDefault();
			},
			focus: function (event, ui) {
				$(this).val(ui.item.label);
				event.preventDefault();
			}
		}).focus(function () {
			if ($(this).val() != "") {
				return false;
			} else {
				$(this).autocomplete("search", $(this).val());
			}
		});
	}

	if ($("#idioma").length) {
		$("#idioma").autocomplete({
			source: function (request, response) {
				$.getJSON("buscarDatos.php", {
					t: "idioma",
					term: request.term,
					m: getUrlVars()["menu"],
					ids: $("#sucursal").val()
				}, response);
			},
			minLength: 0,
			select: function (event, ui) {
				$(this).val(ui.item.label);
				event.preventDefault();
			},
			focus: function (event, ui) {
				$(this).val(ui.item.label);
				event.preventDefault();
			}
		}).focus(function () {
			if ($(this).val() != "") {
				return false;
			} else {
				$(this).autocomplete("search", $(this).val());
			}
		});
	}

	if ($("#categoria").length) {
		$("#categoria").autocomplete({
			source: function (request, response) {
				switch (getUrlVars()["menu"]) {
					case "productos":
						$("#numero").val("");
						$("#letra").val("");

						$.getJSON("buscarDatos.php", {
							t: "categoria",
							term: request.term,
							m: getUrlVars()["menu"],
							ids: $("#sucursal").val()
						}, response);
						break;
					case "preguntas":
						$.getJSON("buscarDatos.php", {
							t: "categoria",
							term: request.term,
							m: getUrlVars()["menu"],
							e: getUrlVars()["id"]
						}, response);
						break;
					default:
						$.getJSON("buscarDatos.php", {
							t: "categoria",
							term: request.term,
							m: getUrlVars()["menu"]
						}, response);
						break;
				}
			},
			minLength: 0,
			select: function (event, ui) {
				$(this).val(ui.item.label);
				event.preventDefault();
			},
			focus: function (event, ui) {
				$(this).val(ui.item.label);
				event.preventDefault();
			},
			close: function (event, ui) {
				if (getUrlVars()["menu"] == "productos") {
					$.getJSON('buscarDatos.php?t=obtenerCodigo&c=' + $(this).val() + '&s=' + $("#sucursal").val(), function (data) {
						if (data.length) {
							$.each(data, function (key, elemento) {
								$("#numero").val(elemento.numero);
								$("#letra").val(elemento.letra);
							});
						}
					})
				}
			},
			response: function (event, ui) {
				if (ui.content.length === 0) {
					if (getUrlVars()["menu"] == "productos") {
						$("#numero").val(sprintf("%06s", 1));
						$("#letra").val($(this).val().substring(0, 4));
					}
				}
			}
		}).focus(function () {
			if ($(this).val() != "") {
				return false;
			} else {
				$(this).autocomplete("search", $(this).val());
			}
		});
	}

	if ($("#subcategoria").length) {
		$("#subcategoria").autocomplete({
			source: function (request, response) {
				$.getJSON("buscarDatos.php", {
					t: "subcategoria",
					term: request.term,
					m: getUrlVars()["menu"],
					e: getUrlVars()["id"]
				}, response);
			},
			minLength: 0,
			select: function (event, ui) {
				$(this).val(ui.item.label);
				event.preventDefault()
			},
			focus: function (event, ui) {
				$(this).val(ui.item.label);
				event.preventDefault();
			}
		}).focus(function () {
			if ($(this).val() != "") {
				return false;
			} else {
				$(this).autocomplete("search", $(this).val());
			}
		});
	}

	if ($("#tipo_respuesta").length) {
		$("#tipo_respuesta").autocomplete({
			source: function (request, response) {
				$.getJSON("buscarDatos.php", {
					t: "tipo_respuesta",
					term: request.term,
					m: getUrlVars()["menu"],
					e: getUrlVars()["id"]
				}, response);
			},
			minLength: 0,
			select: function (event, ui) {
				$(this).val(ui.item.label);
				event.preventDefault()
			},
			focus: function (event, ui) {
				$(this).val(ui.item.label);
				event.preventDefault();
			}
		}).focus(function () {
			if ($(this).val() != "") {
				return false;
			} else {
				$(this).autocomplete("search", $(this).val());
			}
		});
	}

	if ($("#grupo").length) {
		$("#grupo").autocomplete({
			source: function (request, response) {
				$.getJSON("buscarDatos.php", {
					t: "grupo",
					term: request.term,
					m: getUrlVars()["menu"]
				}, response);
			},
			minLength: 0,
			select: function (event, ui) {
				$(this).val(ui.item.label);
				event.preventDefault()
			},
			focus: function (event, ui) {
				$(this).val(ui.item.label);
				event.preventDefault();
			}
		}).focus(function () {
			if ($(this).val() != "") {
				return false;
			} else {
				$(this).autocomplete("search", $(this).val());
			}
		});
	}

	/*	USUARIOS	*/
	if ($("#ciudad").length) {
		$("#ciudad").autocomplete({
			source: function (request, response) {
				$.getJSON("buscarDatos.php", {
					t: "ciudad",
					term: request.term,
					m: getUrlVars()["menu"]
				}, response);
			},
			minLength: 0,
			select: function (event, ui) {
				$(this).val(ui.item.label);
				event.preventDefault()
			},
			focus: function (event, ui) {
				$(this).val(ui.item.label);
				event.preventDefault();
			}
		}).focus(function () {
			if ($(this).val() != "") {
				return false;
			} else {
				$(this).autocomplete("search", $(this).val());
			}
		});
	}

	if ($("#profesion").length) {
		$("#profesion").autocomplete({
			source: function (request, response) {
				$.getJSON("buscarDatos.php", {
					t: "profesion",
					term: request.term,
					m: getUrlVars()["menu"]
				}, response);
			},
			minLength: 0,
			select: function (event, ui) {
				$(this).val(ui.item.label);
				event.preventDefault()
			},
			focus: function (event, ui) {
				$(this).val(ui.item.label);
				event.preventDefault();
			}
		}).focus(function () {
			if ($(this).val() != "") {
				return false;
			} else {
				$(this).autocomplete("search", $(this).val());
			}
		});
	}

	if ($("#nivel_academico").length) {
		$("#nivel_academico").autocomplete({
			source: function (request, response) {
				$.getJSON("buscarDatos.php", {
					t: "nivel_academico",
					term: request.term,
					m: getUrlVars()["menu"]
				}, response);
			},
			minLength: 0,
			select: function (event, ui) {
				$(this).val(ui.item.label);
				event.preventDefault()
			},
			focus: function (event, ui) {
				$(this).val(ui.item.label);
				event.preventDefault();
			}
		}).focus(function () {
			if ($(this).val() != "") {
				return false;
			} else {
				$(this).autocomplete("search", $(this).val());
			}
		});
	}

	if ($("#categoria_usuario").length) {
		$("#categoria_usuario").autocomplete({
			source: function (request, response) {
				$.getJSON("buscarDatos.php", {
					t: "categoria_usuario",
					term: request.term,
					m: getUrlVars()["menu"]
				}, response);
			},
			minLength: 0,
			select: function (event, ui) {
				$(this).val(ui.item.label);
				event.preventDefault()
			},
			focus: function (event, ui) {
				$(this).val(ui.item.label);
				event.preventDefault();
			}
		}).focus(function () {
			if ($(this).val() != "") {
				return false;
			} else {
				$(this).autocomplete("search", $(this).val());
			}
		});
	}

	if (document.querySelector('input[name="usuario"]')) {
		const usuario = document.querySelector('input[name="usuario"]')
		const id = document.querySelector('input[name="id_usuario"]')

		usuario.addEventListener('blur', e => {

			if (usuario.value != '') {

				let parametros = ''
				parametros += '?t=verificarUsuario'
				parametros += '&u=' + usuario.value
				parametros += id ? '&id=' + id.value : ''

				fetch('buscarDatos.php' + parametros)
					.then(response => response.json())
					.then(result => {
						if (result.length) {
							usuario.value = ''
							let msj = ''
							result.forEach(item => {
								msj += '- Nombre: ' + item.nombre + ' - Tipo: ' + item.tipo + ' - Sucursal: ' + item.sucursal + '\n'
							})
							alert('El nombre de usuario: "' + usuario.value + '" ya se ha asignado al siguiente usuario: \n' + msj)
						}
					})
					.catch(error => console.log('Hubo un problema con la petición Fetch: ' + error.message))

			}

		})

	}

	/*	BOTONES DE IMPRESION	*/
	if ($("#printButton").length) {
		$("#printButton").click(function (e) {
			e.preventDefault();

			switch ($(this).attr("data-contenido")) {
				case ".imprimible":
					$($(this).attr("data-contenido")).printArea({ mode: "iframe", popTitle: $("head title").html() });
					break;
				default:
					var divContenido = $("<div/>");
					divContenido.addClass("imprimible");
					if (getUrlVars()["menu"] == "reportes") {
						divContenido.append("<h1>Reportes</h1>");
						divContenido.append("<h2>" + $("h3:first").text() + "</h2>");
						vParrafo = "<p style='padding: 0;margin: 0 auto 5px;'><strong>Sucursal:</strong> " + $("#s option:selected").text();
						if ($("#c").length) {
							vParrafo += " / <strong>Categor&iacute;a:</strong> " + $("#c option:selected").text();
						}
						if ($("#g").length) {
							vParrafo += " / <strong>G&eacute;nero:</strong> " + $("#g option:selected").text();
						}
						if ($("#fd").length && $("#fh").length) {
							vParrafo += " / <strong>Periodo:</strong> " + $("#fd").val() + " al " + $("#fh").val();
						}
						if ($("#e").length) {
							vParrafo += " / <strong>Estado:</strong> " + $("#e option:selected").text();
						}
						vParrafo += "</p>";
						divContenido.append(vParrafo);
					}
					if ($(this).attr("data-contenido") == "#divListado") {
						divContenido.append("<h1>" + $("h1:first").text().replace("Lista de registros", "") + "</h1>");
						divContenido.append("<h2>Lista de registros</h2>");
					}
					if (getUrlVars()["menu"] == "hojaruta") {
						divContenido.append("<h1>Hoja de Rutas</h1>");
						divContenido.append("<h2>Datos de la Hoja de Ruta: " + $("#nro_orden").val() + "</h2>");
					}
					divContenido.append($($(this).attr("data-contenido")).html());
					divContenido.find("div.well").remove();
					divContenido.find("a").attr("href", "#");

					if (getUrlVars()["menu"] != "reportes") {
						divContenido.find("input[name='CheckTodo']").closest("th").remove();
						if (divContenido.find("table#listado thead tr:first th:first").html() == "&nbsp;") {
							divContenido.find("table#listado thead tr:first th:first").remove();
						}
						divContenido.find("input[name='registro[]']").closest("td").remove();
						divContenido.find(".btn-toolbar").closest("td").remove();
						divContenido.find(".btn-toolbar").remove();
						divContenido.find("#vMsjCargando").remove();
						divContenido.find("footer").remove();
						divContenido.find("table#listado tbody tr").each(function () {
							if ($(this).find("td:first").html() == "&nbsp;") {
								$(this).remove();
							}
						})

						divContenido.find("table#listado tfoot tr:first td:first").prop("colspan", (Number(divContenido.find("table#listado tfoot tr:first td:first").prop("colspan")) - 2));
					}

					divContenido.find("input[name='estado']").each(function () {
						if ($(this).prop("checked") == true) {
							$(this).closest("td").html("SI");
						} else {
							$(this).closest("td").html("NO");
						}
					})

					$(divContenido).printArea({ mode: "iframe", popTitle: $("head title").html() });
					break;

			}
		})
	}
	if ($("#closeButton").length) {
		$("#closeButton").click(function () {
			if (confirm("Se ha cerrar la ventana actual.\nEsta usted seguro?\n")) {
				window.close();
			}
		})
	}

	/*	PREFERENCIAS	*/
	if ($("#publicidad_portada_mostrar").length) {
		$("#publicidad_portada_mostrar").click(function () {
			$("#publicidad_portada_archivo").attr("disabled", !$(this).prop("checked"));
			$("#publicidad_portada_file").attr("disabled", !$(this).prop("checked"));
			$("#publicidad_portada_enlace").attr("disabled", !$(this).prop("checked"));
		})
	}

	if ($("#publicidad_h_mostrar").length) {
		$("#publicidad_h_mostrar").click(function () {
			$("#publicidad_h_archivo").attr("disabled", !$(this).prop("checked"));
			$("#publicidad_h_file").attr("disabled", !$(this).prop("checked"));
			$("#publicidad_h_enlace").attr("disabled", !$(this).prop("checked"));
		})
	}

	if ($("#publicidad_v_mostrar").length) {
		$("#publicidad_v_mostrar").click(function () {
			$("#publicidad_v_archivo").attr("disabled", !$(this).prop("checked"));
			$("#publicidad_v_file").attr("disabled", !$(this).prop("checked"));
			$("#publicidad_v_enlace").attr("disabled", !$(this).prop("checked"));
		})
	}

	if ($("#sitio_plantilla").length) {
		$('#sitio_plantilla').change(function () {
			var valor = $(this).val();
			$('#sitio_estilo').empty();
			$('#sitio_estilo').append("<option value=''>&laquo;-- seleccionar plantilla</option>");

			$.getJSON('buscarDatos.php?t=estilos&valor=' + valor, function (data) {
				$.each(data, function (key, val) {
					if ($("#old_sitio_estilo").val() == val.value) {
						$('#sitio_estilo').append("<option value='" + val.value + "' selected>" + val.label + "</option>");
					} else {
						$('#sitio_estilo').append("<option value='" + val.value + "'>" + val.label + "</option>");
					}
				});
			});
		});
	}
	if (getUrlVars()["menu"] == "config") {
		$('#sitio_plantilla').change();
	}

	//	Listado de registros
	if ($("table#listado").length) {
		switch (getUrlVars()["menu"]) {
			case "imgportada":
			case "fotogaleria":
				$("table#listado tbody tr td").click(function () {
					if ($(this).find("input[name='registro[]']").prop('disabled') == false) {
						$(this).find("input[name='registro[]']").trigger('click');

						$("table#listado > tbody:first").find("input[name='registro[]']").each(function () {
							if ($(this).prop("checked")) {
								$(this).closest("td").attr("class", "Checked");
							} else {
								$(this).closest("td").attr("class", "unChecked");
							}
						});
					}
				});
				break;
			default:
				$("table#listado tbody tr").click(function () {
					if ($(this).find("td").eq(0).find("input[name='registro[]']").prop('disabled') == false) {
						$(this).find("td").eq(0).find("input[name='registro[]']").trigger('click');

						$("table#listado > tbody:first").find("input[name='registro[]']").each(function () {
							if ($(this).prop("checked")) {
								$(this).closest("tr").attr("class", "Checked");
							} else {
								$(this).closest("tr").attr("class", "unChecked");
							}
						});
					}
				});
				break;
		}
	}

	if ($("table#listado tbody tr td input[name='registro[]']").length) {
		$("table#listado tbody tr td input[name='registro[]']").click(function () {
			if ($(this).prop('disabled') == false) {
				$(this).trigger('click');
			}
		});
	}

	if ($("#CheckTodo").length) { //	selecciono todos los registros
		$('#CheckTodo').click(function () {
			var $filas = $("input[name='registro[]']");
			var $btVer = $("#btVerRegistro");
			var $btEditar = $("#btEditarRegistro");
			var $btEliminar = $("#btEliminarRegistro");

			if ($("#CheckTodo").prop("checked")) {
				$filas.prop("checked", true);
				if ((getUrlVars()["menu"] == "fotogaleria") || (getUrlVars()["menu"] == "imgportada")) {
					$filas.closest("td").attr("class", "Checked");
				} else {
					$filas.closest("tr").attr("class", "Checked");
				}
				if ($filas.length > 0) {
					if ($btVer.length) { $btVer.attr("disabled", false); }
					if ($btEditar.length) { $btEditar.attr("disabled", false); }
					if ($btEliminar.length) { $btEliminar.attr("disabled", false); }
				}
			} else {
				$filas.prop("checked", false);
				if ((getUrlVars()["menu"] == "fotogaleria") || (getUrlVars()["menu"] == "imgportada")) {
					$filas.closest("td").attr("class", "unChecked");
				} else {
					$filas.closest("tr").attr("class", "unChecked");
				}
				if ($btVer.length) { $btVer.attr("disabled", true); }
				if ($btEditar.length) { $btEditar.attr("disabled", true); }
				if ($btEliminar.length) { $btEliminar.attr("disabled", true); }
			}
		});
	};
	if ($("input[name='registro[]']").length) { //	marco el registro seleccionado
		$("input[name='registro[]']").click(function () {
			if ($(this).prop("checked")) {
				if ((getUrlVars()["menu"] == "fotogaleria") || (getUrlVars()["menu"] == "imgportada")) {
					$(this).closest("td").attr("class", "Checked");
				} else {
					$(this).closest("tr").attr("class", "Checked");
				}
			} else {
				if ((getUrlVars()["menu"] == "fotogaleria") || (getUrlVars()["menu"] == "imgportada")) {
					$(this).closest("td").attr("class", "unChecked");
				} else {
					$(this).closest("tr").attr("class", "unChecked");
				}
				if ($("#CheckTodo").length) {
					if ($("#CheckTodo").prop("checked")) {
						$("#CheckTodo").attr("checked", false);
					}
				}
			}
			var $filas = $("input[name='registro[]']:checked");
			var $btVer = $("#btVerRegistro");
			var $btEditar = $("#btEditarRegistro");
			var $btEliminar = $("#btEliminarRegistro");

			if ($filas.length > 0) {
				if ($btVer.length) { $btVer.attr("disabled", false); }
				if ($btEditar.length) { $btEditar.attr("disabled", false); }
				if ($btEliminar.length) { $btEliminar.attr("disabled", false); }
			} else {
				if ($btVer.length) { $btVer.attr("disabled", true); }
				if ($btEditar.length) { $btEditar.attr("disabled", true); }
				if ($btEliminar.length) { $btEliminar.attr("disabled", true); }
			}
		});
	};
	if ($("input[name='estado']").length) { //	cambio el ESTADO del registro seleccionado
		$("input[name='estado']").click(function () {
			var vURL = window.location.href.split('?');
			var $fila = $(this).parent().parent().find("td").eq(0).children();
			var fields = {};
			fields["registro[]"] = $fila.val();

			$.ajax({
				url: vURL[0] + '?menu=' + getUrlVars()["menu"] + '&accion=cambiarestado',
				data: fields,
				type: 'POST',
				dataType: 'text',
				success: function (response) {
					//$fila.attr("title",json.estado);
					//location.reload(true);
				},
				error: function (xhr, status) {
					alert('Disculpe, existió un problema');
				},
				complete: function (xhr, status) {
					//alert('Petición realizada');
				}
			});
		});
	}

	//	Botones de acciones
	if ($("#btNuevoRegistro").length) {
		$("#btNuevoRegistro").click(function (e) {
			e.preventDefault();
			var vURL = window.location.href.split('?');

			var vParametros = "";
			vParametros += vURL[0] + "?menu=" + getUrlVars()["menu"] + "&accion=nuevo";
			if (getUrlVars()["tipo"]) { vParametros += "&tipo=" + getUrlVars()["tipo"]; }
			if ($("#id").val()) { vParametros += "&id=" + $("#id").val(); }
			if (getUrlVars()["vienede"]) { vParametros += "&vienede=" + getUrlVars()["vienede"]; }

			location = vParametros;
		});
	}

	if ($("#btVerRegistro").length) {
		$("#btVerRegistro").click(function () {
			var vURL = window.location.href.split('?');

			var vParametros = "";
			vParametros += vURL[0] + "?menu=" + getUrlVars()["menu"] + "&accion=ver";
			if (getUrlVars()["tipo"]) { vParametros += "&tipo=" + getUrlVars()["tipo"]; }
			if ($("#id").val()) { vParametros += "&id=" + $("#id").val(); }
			if (getUrlVars()["vienede"]) { vParametros += "&vienede=" + getUrlVars()["vienede"]; }
			var $filas = $("input[name='registro[]']:checked");
			vParametros += "&registro[]=" + $filas.val();

			location = vParametros;
		});
	}

	if ($("a#mnFilaVerRegistro").length) {
		$("a#mnFilaVerRegistro").click(function (e) {
			e.preventDefault();
			$('#CheckTodo').prop("checked", true);
			$('#CheckTodo').trigger('click');
			if ((getUrlVars()["menu"] == "fotogaleria") || (getUrlVars()["menu"] == "imgportada")) {
				var $fila = $(this).closest("div.btn-toolbar").find("label").find("input[name='registro[]']");
			} else {
				var $fila = $(this).closest("tr").find("td").find("input[name='registro[]']");
			}
			$fila.prop("checked", false);
			$fila.trigger('click');
			//$('#CheckTodo').prop("checked",false);

			if ((getUrlVars()["menu"] == "fotogaleria") || (getUrlVars()["menu"] == "imgportada")) {
				$fila.closest("td").attr("class", "Checked");
			} else {
				$fila.closest("tr").attr("class", "Checked");
			}

			var $filas = $("input[name='registro[]']:checked");
			var $btVer = $("#btVerRegistro");
			var $btEditar = $("#btEditarRegistro");
			var $btEliminar = $("#btEliminarRegistro");

			if ($filas.length > 0) {
				if ($btVer.length) { $btVer.attr("disabled", false); }
				if ($btEditar.length) { $btEditar.attr("disabled", false); }
				if ($btEliminar.length) { $btEliminar.attr("disabled", false); }
			} else {
				if ($btVer.length) { $btVer.attr("disabled", true); }
				if ($btEditar.length) { $btEditar.attr("disabled", true); }
				if ($btEliminar.length) { $btEliminar.attr("disabled", true); }
			}
			$("#btVerRegistro").click();
		})
	}

	if ($("#btEditarRegistro").length) {
		$("#btEditarRegistro").click(function () {
			var vURL = window.location.href.split('?');

			var vParametros = "";
			vParametros += vURL[0] + "?menu=" + getUrlVars()["menu"] + "&accion=editar";
			if (getUrlVars()["tipo"]) { vParametros += "&tipo=" + getUrlVars()["tipo"]; }
			if ($("#id").val()) { vParametros += "&id=" + $("#id").val(); }
			if (getUrlVars()["vienede"]) { vParametros += "&vienede=" + getUrlVars()["vienede"]; }
			var $filas = $("input[name='registro[]']:checked");
			vParametros += "&registro[]=" + $filas.val();

			location = vParametros;
		});
	}

	if ($("a#mnFilaEditarRegistro").length) {
		$("a#mnFilaEditarRegistro").click(function (e) {
			e.preventDefault();
			$('#CheckTodo').prop("checked", true);
			$('#CheckTodo').trigger('click');
			if ((getUrlVars()["menu"] == "fotogaleria") || (getUrlVars()["menu"] == "imgportada")) {
				var $fila = $(this).closest("div.btn-toolbar").find("label").find("input[name='registro[]']");
			} else {
				var $fila = $(this).closest("tr").find("td").find("input[name='registro[]']");
			}
			$fila.prop("checked", false);
			$fila.trigger('click');
			//$('#CheckTodo').attr("checked",false);

			if ((getUrlVars()["menu"] == "fotogaleria") || (getUrlVars()["menu"] == "imgportada")) {
				$fila.closest("td").attr("class", "Checked");
			} else {
				$fila.closest("tr").attr("class", "Checked");
			}

			var $filas = $("input[name='registro[]']:checked");
			var $btVer = $("#btVerRegistro");
			var $btEditar = $("#btEditarRegistro");
			var $btEliminar = $("#btEliminarRegistro");

			if ($filas.length > 0) {
				if ($btVer.length) { $btVer.attr("disabled", false); }
				if ($btEditar.length) { $btEditar.attr("disabled", false); }
				if ($btEliminar.length) { $btEliminar.attr("disabled", false); }
			} else {
				if ($btVer.length) { $btVer.attr("disabled", true); }
				if ($btEditar.length) { $btEditar.attr("disabled", true); }
				if ($btEliminar.length) { $btEliminar.attr("disabled", true); }
			}
			$("#btEditarRegistro").click();
		})
	}

	if ($("#btEliminarRegistro").length) {
		$("#btEliminarRegistro").click(function (e) {
			$("#vMsjCargando").find("span").text("eliminando " + getUrlVars()["menu"].toUpperCase() + "...");
			$("#vMsjCargando").show();

			var $filas = $("input[name='registro[]']:checked");
			if ($filas.length > 1) {
				var vMsj = "Se van a ELIMINAR los " + $filas.length + " registros seleccionados:\n\n";
			} else {
				var vMsj = "Se va a ELIMINAR el siguiente registro seleccionado:\n\n";
			}
			var vURL = window.location.href.split('?');

			var fields = {};
			fields = $("input[name='registro[]']:checked").serializeArray();
			if ($("#id").length) {
				var id = new Object();
				id = { name: "id", value: $("#id").val() };
				fields.push(id);
			}
			const user = e.target.getAttribute('data-user')
			if (user) {
				let campo = new Object();
				campo = { name: "idu", value: user };
				fields.push(campo);
			}
	
			switch (getUrlVars()["menu"]) {
				case "fotogaleria":
				case "imgportada":
					$filas.each(function () {
						var vIdImagen = $(this).closest("td").find("a.fotogaleria").attr("href");
						vMsj += "- [" + $.trim(vIdImagen.substring((vIdImagen.length - 12), vIdImagen.length)) + "] " + $.trim($(this).closest("td").find("a.fotogaleria").attr("title")) + "\n";
					});
					break;
				case "tipos":
					$filas.each(function () {
						vMsj += "- [" + $.trim($(this).closest("tr").find("td").eq(0).children().val()) + "] " + $.trim($(this).closest("tr").find("td").eq(2).html()) + "\n";
					});
					break;
				case "clientes":
					$filas.each(function () {
						vMsj += "- [" + $.trim($(this).closest("tr").find("td").eq(0).children().val()) + "] " + $.trim($(this).closest("tr").find("td").eq(3).html()) + " - " + $.trim($(this).closest("tr").find("td").eq(4).html()) + "\n";
					});
					break;
				case "productos":
					$filas.each(function () {
						vMsj += "- [" + $.trim($(this).closest("tr").find("td").eq(0).children().val()) + "] " + $.trim($(this).closest("tr").find("td").eq(2).html()) + " - " + $.trim($(this).closest("tr").find("td").eq(3).find("a").text()) + "\n";
					});
					break;
				case "ventas":
				case "servicios":
				case "pedidos":
					$filas.each(function () {
						vMsj += "- [" + $.trim($(this).closest("tr").find("td").eq(0).children().val()) + "] " + $.trim($(this).closest("tr").find("td").eq(2).html()) + " - " + $.trim($(this).closest("tr").find("td").eq(3).html()) + "\n";
					});
					break;
				default:
					$filas.each(function () {
						vMsj += "- [" + $.trim($(this).closest("tr").find("td").eq(0).children().val()) + "] " + $.trim($(this).closest("tr").find("td").eq(3).html()) + "\n";
					});
					break;
			}
			vMsj += "\nEsta usted seguro?";
			if (confirm(vMsj)) {
				$.ajax({
					url: 'eliminarDatos.php?menu=' + getUrlVars()["menu"] + '&accion=eliminar',
					data: fields,
					type: 'POST',
					dataType: 'json',
					success: function (response) {
						if (response.length) {
							var vMsj = "";
							$.each(response, function (key, elemento) {
								if (elemento.estado != "OK") {
									$.each(elemento.errores, function (key, errores) {
										vMsj += errores.error + "\n";
									})
								}
							})
							if (vMsj != "") {
								alert("Se ha obtenido los siguientes mensajes de error\nal intentar eliminar algunos de los registros seleccionados:\n\n" + vMsj + "\n");
							} else {
								location.reload(true);
							}
							$("#vMsjCargando").hide();
						}
					},
					error: function (xhr, status) {
						$("#vMsjCargando").hide();
						alert('Disculpe, existió un problema: ' + status);
					},
					complete: function (xhr, status) {
						//alert('Petición realizada');
					}
				});
			} else {
				$("#vMsjCargando").hide();
			};
		});
	};

	if ($("a#mnFilaEliminarRegistro").length) {
		$("a#mnFilaEliminarRegistro").click(function (e) {
			e.preventDefault();
			$('#CheckTodo').prop("checked", true);
			$('#CheckTodo').trigger('click');
			switch (getUrlVars()["menu"]) {
				case "fotogaleria":
				case "imgportada":
					var $fila = $(this).closest("div.btn-toolbar").find("label").find("input[name='registro[]']");
					break;
				default:
					var $fila = $(this).closest("tr").find("td").find("input[name='registro[]']");
					break;
			}
			$fila.prop("checked", false);
			$fila.trigger('click');
			//$('#CheckTodo').attr("checked",false);

			switch (getUrlVars()["menu"]) {
				case "fotogaleria":
				case "imgportada":
					$fila.closest("td").attr("class", "Checked");
					break;
				default:
					$fila.closest("tr").attr("class", "Checked");
					break;
			}
			var $filas = $("input[name='registro[]']:checked");
			var $btVer = $("#btVerRegistro");
			var $btEditar = $("#btEditarRegistro");
			var $btEliminar = $("#btEliminarRegistro");

			if ($filas.length > 0) {
				if ($btVer.length) { $btVer.attr("disabled", false); }
				if ($btEditar.length) { $btEditar.attr("disabled", false); }
				if ($btEliminar.length) { $btEliminar.attr("disabled", false); }
			} else {
				if ($btVer.length) { $btVer.attr("disabled", true); }
				if ($btEditar.length) { $btEditar.attr("disabled", true); }
				if ($btEliminar.length) { $btEliminar.attr("disabled", true); }
			}
			$("#btEliminarRegistro").click();
		})
	}

	if ($("#btEliminarRegistroFisico").length) {
		$("#btEliminarRegistroFisico").click(function (e) {
			e.preventDefault();
			$("#vMsjCargando").find("span").text("eliminando " + getUrlVars()["menu"].toUpperCase() + "...");
			$("#vMsjCargando").show();

			var $fila = $(this).closest("tr").find("td").find("input[name='registro[]']");
			$fila.prop("checked", false);
			$fila.trigger('click');

			var $filas = $("input[name='registro[]']:checked");
			if ($filas.length > 1) {
				var vMsj = "Se van a ELIMINAR fisicamente los " + $filas.length + " registros seleccionados:\n\n";
			} else {
				var vMsj = "Se va a ELIMINAR fisicamente el siguiente registro seleccionado:\n\n";
			}
			var vURL = window.location.href.split('?');

			var fields = {};
			fields = $("input[name='registro[]']:checked").serializeArray();
			if ($("#id").length) {
				var id = new Object();
				id = { name: "id", value: $("#id").val() };
				fields.push(id);
			}
			switch (getUrlVars()["menu"]) {
				case "fotogaleria":
					$filas.each(function () {
						var vIdImagen = $(this).closest("td").find("a.fotogaleria").attr("href");
						vMsj += "- [" + $.trim(vIdImagen.substring((vIdImagen.length - 12), vIdImagen.length)) + "] " + $.trim($(this).closest("td").find("a.fotogaleria").attr("title")) + "\n";
					});
					break;
				case "tipos":
					$filas.each(function () {
						vMsj += "- [" + $.trim($(this).closest("tr").find("td").eq(0).children().val()) + "] " + $.trim($(this).closest("tr").find("td").eq(2).html()) + "\n";
					});
					break;
				case "clientes":
					$filas.each(function () {
						vMsj += "- [" + $.trim($(this).closest("tr").find("td").eq(0).children().val()) + "] " + $.trim($(this).closest("tr").find("td").eq(3).html()) + " - " + $.trim($(this).closest("tr").find("td").eq(4).html()) + "\n";
					});
					break;
				case "productos":
					$filas.each(function () {
						vMsj += "- [" + $.trim($(this).closest("tr").find("td").eq(0).children().val()) + "] " + $.trim($(this).closest("tr").find("td").eq(2).html()) + " - " + $.trim($(this).closest("tr").find("td").eq(3).text()) + "\n";
					});
					break;
				case "ventas":
				case "servicios":
				case "pedidos":
					$filas.each(function () {
						vMsj += "- [" + $.trim($(this).closest("tr").find("td").eq(0).children().val()) + "] " + $.trim($(this).closest("tr").find("td").eq(2).html()) + " - " + $.trim($(this).closest("tr").find("td").eq(3).html()) + "\n";
					});
					break;
				default:
					$filas.each(function () {
						vMsj += "- [" + $.trim($(this).closest("tr").find("td").eq(0).children().val()) + "] " + $.trim($(this).closest("tr").find("td").eq(3).html()) + "\n";
					});
					break;
			}
			vMsj += "\nEsta usted seguro?";
			if (confirm(vMsj)) {
				$.ajax({
					url: 'eliminarDatos.php?menu=' + getUrlVars()["menu"] + '&accion=eliminarfisico',
					data: fields,
					type: 'POST',
					dataType: 'json',
					success: function (response) {
						if (response.length) {
							var vMsj = "";
							$.each(response, function (key, elemento) {
								if (elemento.estado != "OK") {
									$.each(elemento.errores, function (key, errores) {
										vMsj += errores.error + "\n";
									})
								}
							})
							if (vMsj != "") {
								alert("Se ha obtenido los siguientes mensajes de error\nal intentar eliminar algunos de los registros seleccionados:\n\n" + vMsj + "\n");
							} else {
								location.reload(true);
							}
							$("#vMsjCargando").hide();
						}
					},
					error: function (xhr, status) {
						$("#vMsjCargando").hide();
						alert('Disculpe, existio un problema: ' + status);
					},
					complete: function (xhr, status) {
						//alert('Petición realizada');
					}
				});
			} else {
				$("#vMsjCargando").hide();
			};
		});
	};

	if ($("a#btExportarDatos").length) {
		let sheetJsLoaded = false;

		$("a#btExportarDatos").click(function (e) {
			var valor = $(this).attr("data-tipo");
			e.preventDefault();

			var divContenido = $("<div/>");
			if ($("#divListado").find("#tab01.active").length) {
				divContenido.append($("#tab01").html());
				divContenido.find(".well").remove();
			} else {
				if ($("#divListado").find("#tab02.active").length) {
					divContenido.append("<h1>Reportes</h1>\n");
					divContenido.append("<h2>" + $("h3:first").text() + "</h2>\n");
					vParrafo = "<p><strong>Sucursal:</strong> " + $("#s option:selected").text();
					if ($("#c").length) {
						vParrafo += " / <strong>Categor&iacute;a:</strong> " + $("#c option:selected").text();
					}
					if ($("#g").length) {
						vParrafo += " / <strong>G&eacute;nero:</strong> " + $("#g option:selected").text();
					}
					if ($("#fd").length && $("#fh").length) {
						vParrafo += " / <strong>Periodo:</strong> " + $("#fd").val() + " al " + $("#fh").val();
					}
					if ($("#e").length) {
						vParrafo += " / <strong>Estado:</strong> " + $("#e option:selected").text();
					}
					vParrafo += "</p>\n";
					divContenido.append(vParrafo);
					divContenido.append($("#tab02").html());
					divContenido.find(".well").remove();
					divContenido.find("#cantidad_registros").remove();
				} else {
					divContenido.append("<h1>" + $("h1:first").text().replace("Lista de registros", "") + "</h1>\n");
					divContenido.append("<h2>Lista de registros</h2>\n");
					divContenido.append($("#divListado").html());
				}
			}

			divContenido.find("table#listado").attr("border", "1");
			divContenido.find("table#listado").removeAttr("class");
			divContenido.find("thead").removeAttr("class");
			divContenido.find("thead:first").remove();
			divContenido.find("tr").removeAttr("class");
			divContenido.find("th").removeAttr("class");
			divContenido.find("table#listado").removeAttr("style");
			divContenido.find("thead").removeAttr("style");
			divContenido.find("th").removeAttr("style");
			divContenido.find("a").each(function () {
				$(this).html($(this).html().replace("<br>", " "));
				$(this).closest("th").html($(this).text());
			})
			if (divContenido.find("input[name='CheckTodo']").length) {
				divContenido.find("input[name='CheckTodo']").closest("th").remove();
				if (divContenido.find("table#listado thead tr:first th:first").html() == "&nbsp;") {
					divContenido.find("table#listado thead tr:first th:first").remove();
				}
				divContenido.find("input[name='registro[]']").closest("td").remove();
				divContenido.find(".btn-toolbar").closest("td").remove();
				divContenido.find("#vMsjCargando").remove();
				divContenido.find("table#listado tfoot tr:first td:first").prop("colspan", (Number(divContenido.find("table#listado tfoot tr:first td:first").prop("colspan")) - 2));
			}
			divContenido.find(".btn-toolbar").remove();
			divContenido.find("table#listado tbody tr").each(function () {
				if ($(this).find("td:first").html() == "&nbsp;") {
					$(this).remove();
				}
			})
			divContenido.find("input[name='estado']").each(function () {
				if ($(this).prop("checked") == true) {
					$(this).closest("td").html("SI");
				} else {
					$(this).closest("td").html("NO");
				}
			})
			divContenido.find("footer").remove();
			vParrafo = "<p><strong>Fecha:</strong> "+(new Date().toLocaleDateString('es-ES'))+" | <strong>Horas:</strong> "+(new Date().toLocaleTimeString('es-ES', { hour12: false }))+" </p>";
			divContenido.append(vParrafo);

			if(valor == 'xlsx'){
				console.log('btExportarDatos',valor);

				if (!window.ExcelJS) {
					// Si ExcelJS no está cargada, cargarla dinámicamente
					const script = document.createElement('script');
					script.src = 'https://cdn.jsdelivr.net/npm/exceljs/dist/exceljs.min.js';
					script.onload = function() {
					  // Ahora que ExcelJS está cargada, puedes utilizarla
					  generarXLSX();
					};
					document.head.appendChild(script);
				} else {
					// Si ExcelJS ya está cargada, simplemente utilizarla
					generarXLSX();
				}				

				function generarXLSX() {
					const contenidoHTML = document.getElementById('listado').innerHTML;

					const wb = new ExcelJS.Workbook();
					const ws = wb.addWorksheet('Hoja1');
			  
					// Crear una tabla
					const table = document.createElement('table');
					table.innerHTML = contenidoHTML;
			  
					// Obtener todas las filas de la tabla
					const rows = table.getElementsByTagName('tr');
					
					// Iterar sobre las filas y agregar celdas al archivo XLSX
					for (const row of rows) {
					  const rowCells = row.getElementsByTagName('td');
					  const xlsxRow = ws.addRow();
					  for (const cell of rowCells) {
						xlsxRow.getCell(cell.cellIndex + 1).value = cell.textContent;
					  }
					}
			  
					// Cargar la imagen y agregarla al archivo
					const image = new Image();
					image.src = '../images/confirmacion.jpg'; // Reemplazar con la ruta correcta
					image.onload = function() {
					  const imageId = wb.addImage({
						base64: getImageDataUri(image),
						extension: 'png', // Asegúrate de ajustar la extensión según el tipo de imagen
					  });
			  
					  // Agregar la imagen
					  ws.addImage(imageId, {
						tl: { col: 1, row: 1 }, // Coordenadas superiores izquierdas
						ext: { width: image.width, height: image.height } // Tamaño de la imagen
					  });
			  
					  // Ajustar el ancho y alto de la celda
					  const cell = ws.getCell(1, 1);
					  cell.width = image.width / 6; // Ajusta según tus necesidades
					  cell.height = image.height / 1.5; // Ajusta según tus necesidades
			  
					  wb.xlsx.writeBuffer().then(function(buffer) {
						const blob = new Blob([buffer], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
						const url = URL.createObjectURL(blob);
						const a = document.createElement('a');
						a.href = url;
						a.download = 'archivo.xlsx';
						a.click();
					  });
					};

				  }
			  
				// Función para obtener el URI de datos de una imagen
				function getImageDataUri(img) {
					const canvas = document.createElement('canvas');
					canvas.width = img.width;
					canvas.height = img.height;
					const context = canvas.getContext('2d');
					context.drawImage(img, 0, 0);
					return canvas.toDataURL('image/png'); // Cambiar a 'image/jpeg' si es necesario
				}


			} else {
				console.log(divContenido[0]);
				var formContenido = $("#formDescargar");
				formContenido.attr("action", "exportarDatos.php");
				formContenido.append("<input type='hidden' name='tipo' value='" + valor + "' />");
				formContenido.append("<input type='hidden' name='menu' value='" + getUrlVars()["menu"] + "' />");
				formContenido.append("<input type='hidden' name='contenido' value='" + $(divContenido).html().replace(/'/gi, "&apos;") + "' />");
				$("form#formDescargar").submit();
			}

		})
	}

	if ($("a#btExportarDatos2").length) {
		$("a#btExportarDatos2").click(function (e) {
			var valor = $(this).attr("data-tipo");
			e.preventDefault();

			var formContenido = $("#formDescargar");
			formContenido.html('')
			formContenido.attr("action", "exportarDatos_productos.php");
			formContenido.append("<input type='hidden' name='tipo' value='" + valor + "' />");
			formContenido.append("<input type='hidden' name='menu' value='" + getUrlVars()["menu"] + "' />");
			formContenido.append("<input type='hidden' name='accion' value='" + getUrlVars()["accion"] + "' />");
			formContenido.append("<input type='hidden' name='s' value='" + $("#s").val() + "' />");
			formContenido.append("<input type='hidden' name='ns' value='" + $("#ns").val() + "' />");

			if ($("#tp").length) {
				var vDatos = $("#tp option:selected").val().split('&tp=');
				if (vDatos[1] != undefined) {
					formContenido.append("<input type='hidden' name='tp' value='" + vDatos[1] + "' />");
				} else {
					formContenido.append("<input type='hidden' name='tp' value='' />");
				}
			}

			if ($("#c").length) {
				var vDatos = $("#c option:selected").val().split('&c=');
				if (vDatos[1] != undefined) {
					formContenido.append("<input type='hidden' name='c' value='" + vDatos[1] + "' />");
				} else {
					formContenido.append("<input type='hidden' name='c' value='' />");
				}
			}

			if ($("#g").length) {
				var vDatos = $("#g option:selected").val().split('&g=');
				if (vDatos[1] != undefined) {
					formContenido.append("<input type='hidden' name='g' value='" + vDatos[1] + "' />");
				} else {
					formContenido.append("<input type='hidden' name='g' value='' />");
				}
			}

			if (getUrlVars()["tc"] != undefined) {
				formContenido.append("<input type='hidden' name='tc' value='" + getUrlVars()["tc"] + "' />");
			} else {
				formContenido.append("<input type='hidden' name='tc' value='' />");
			}

			if (getUrlVars()["to"] != undefined) {
				formContenido.append("<input type='hidden' name='to' value='" + getUrlVars()["to"] + "' />");
			} else {
				formContenido.append("<input type='hidden' name='to' value='' />");
			}

			$("form#formDescargar").submit();

		})
	}

	if ($("#btEliminarBusqueda").length) {
		$("#btEliminarBusqueda").click(function () {
			var vURL = window.location.href.split('?');

			var vParametros = "";
			vParametros += vURL[0] + "?menu=" + getUrlVars()["menu"];
			if (getUrlVars()["tipo"]) { vParametros += "&tipo=" + getUrlVars()["tipo"]; }
			if ($("#id").val()) { vParametros += "&id=" + $("#id").val(); }
			if (getUrlVars()["vienede"]) { vParametros += "&vienede=" + getUrlVars()["vienede"]; }
			if (getUrlVars()["registro%5B%5D"]) { vParametros += "&registro[]=" + getUrlVars()["registro%5B%5D"]; }

			location = vParametros;
		})
	}
	//	Opciones para agregar IMAGENES o ARCHIVOS adjuntos
	if ($("input#btnAgregarCampos").length) {
		$("input#btnAgregarCampos").click(function () {
			var vIndicador = $(this).siblings().closest('.controls').find('input[type="hidden"]:first').val();
			var vNombre_clase_clone = $(this).siblings().closest('.controls').find('div:first').attr('class');
			var vIndicador_label = vNombre_clase_clone.substr(5, vNombre_clase_clone.length).toUpperCase();
			var vCantidadClones = $(this).siblings().closest('.controls').find('.' + vNombre_clase_clone).length;
			if ((vNombre_clase_clone == "cloneCategoria") || (vNombre_clase_clone == "cloneEstado")) {
				var vNuevoIndicador = Number(vCantidadClones) + 1;
				var vContenedor = $(this).siblings().closest('.controls').find('div:first').attr('id').substr(0, $(this).siblings().closest('.controls').find('div:first').attr('id').indexOf('1'));
			} else {
				var vNuevoIndicador = Number(vIndicador) + Number(vCantidadClones);
				var vContenedor = $(this).siblings().closest('.controls').find('div:first').attr('id').substr(0, $(this).siblings().closest('.controls').find('div:first').attr('id').indexOf(vIndicador));
			}

			var nuevoElemento = $(this).siblings().closest('.controls').find('div:first').clone().attr('id', vContenedor + vNuevoIndicador);
			nuevoElemento.find('.controls input[type="checkbox"]').val("");
			nuevoElemento.find('.controls input[type="checkbox"]').attr("checked", false);
			nuevoElemento.find('.controls input[type="text"]').val("");
			nuevoElemento.find('.controls input[type="file"]').val("");
			nuevoElemento.find('.controls input[type="color"]').val("");
			nuevoElemento.find('.controls input#btnEliminarClone').remove();
			if (vNuevoIndicador > 9) {
				nuevoElemento.children('label').html('[' + vIndicador_label + vNuevoIndicador + ']');
			} else {
				nuevoElemento.children('label').html('[' + vIndicador_label + '0' + vNuevoIndicador + ']');
			}
			var botonEliminar = $("<input />", {
				type: "button",
				"class": "btn btn-danger",
				id: "btnEliminarClone",
				style: "margin-left: 4px;",
				value: "Eliminar",
				click: function () {
					var vControls = $(this).closest('.' + vNombre_clase_clone).closest('.controls');
					if ((vNombre_clase_clone == "cloneCategoria") || (vNombre_clase_clone == "cloneEstado")) {
						var vIndicador = $(this).closest('.controls').siblings().closest('div').siblings().closest('.controls').find('input[type="hidden"]:first');
						if (vControls.find('.' + vNombre_clase_clone).length > 1) {
							$(this).closest('.' + vNombre_clase_clone).remove();
							vIndicador.val(vControls.find('.' + vNombre_clase_clone).length);

							vControls.find('.' + vNombre_clase_clone).each(function (idx, elemento) {
								var indice = idx + 1;
								$(elemento).attr("id", vContenedor + indice);
								if (indice > 9) {
									$(elemento).children('label').html('[' + vIndicador_label + indice + ']');
								} else {
									$(elemento).children('label').html('[' + vIndicador_label + '0' + indice + ']');
								}
							})
						} else {
							// es el ultimo clone
							vControls.find('.' + vNombre_clase_clone).find(vNombre_clase_clone).attr('id', vContenedor + '1');
							vControls.find('.' + vNombre_clase_clone).children('label').html('[' + vIndicador_label + '01]');
							vControls.find('.' + vNombre_clase_clone).children('.controls').find('input[type="checkbox"]').attr("value", "");
							vControls.find('.' + vNombre_clase_clone).children('.controls').find('input[type="checkbox"]').attr("checked", false);
							vControls.find('.' + vNombre_clase_clone).children('.controls').find('input[type="text"]').val("");
							vControls.find('.' + vNombre_clase_clone).children('.controls').find('input[type="text"]').attr("value", "");
							vControls.find('.' + vNombre_clase_clone).children('.controls').find('input[type="file"]').attr("value", "");
							vControls.find('.' + vNombre_clase_clone).children('.controls').find('input[type="color"]').attr("value", "");
							vControls.find('.' + vNombre_clase_clone).children('.controls').find('input#btnEliminarClone').remove();
						}
					} else {
						var vIndicador = $(this).closest('.controls').siblings().closest('div').siblings().closest('.controls').find('input[type="hidden"]:first').val();
						$(this).closest('.' + vNombre_clase_clone).remove();
						if (vControls.find('.' + vNombre_clase_clone).length > 1) {
							vControls.find('.' + vNombre_clase_clone).each(function (idx, elemento) {
								var indice = Number(vIndicador) + idx;
								$(elemento).attr("id", vContenedor + indice);
								if (indice > 9) {
									$(elemento).children('label').html('[' + vIndicador_label + indice + ']');
								} else {
									$(elemento).children('label').html('[' + vIndicador_label + '0' + indice + ']');
								}
							})
						}
					}
				}
			});
			nuevoElemento.find('.controls').children(':last').after(botonEliminar);
			var vControls = $(this).siblings().closest('.controls');
			vControls.find('#' + vContenedor + (vNuevoIndicador - 1)).after(nuevoElemento);
		})
	}

	if ($("input#btnEliminarClone").length) {
		$("input#btnEliminarClone").click(function () {
			var vIndicador = $(this).closest('.controls').siblings().closest('div').siblings().closest('.controls').find('input[type="hidden"]:first');
			var vNombre_clase_clone = $(this).closest('.controls').siblings().closest('div').attr('class');
			var vIndicador_label = vNombre_clase_clone.substr(5, vNombre_clase_clone.length).toUpperCase();
			var vContenedor = $(this).attr('data-contenedor');

			var vControls = $(this).closest('.' + vNombre_clase_clone).closest('.controls');
			if (vControls.find('.' + vNombre_clase_clone).length > 1) {
				$(this).closest('.' + vNombre_clase_clone).remove();
				vIndicador.val(vControls.find('.' + vNombre_clase_clone).length);

				vControls.find('.' + vNombre_clase_clone).each(function (idx, elemento) {
					var indice = idx + 1;
					$(elemento).attr("id", vContenedor + indice);
					if (indice > 9) {
						$(elemento).children('label').html('[' + vIndicador_label + indice + ']');
					} else {
						$(elemento).children('label').html('[' + vIndicador_label + '0' + indice + ']');
					}
				})
			} else {
				// es el ultimo clone
				vControls.find('.' + vNombre_clase_clone).find(vNombre_clase_clone).attr('id', vContenedor + '1');
				vControls.find('.' + vNombre_clase_clone).children('label').html('[' + vIndicador_label + '01]');
				vControls.find('.' + vNombre_clase_clone).children('.controls').find('input[type="checkbox"]').attr("value", "");
				vControls.find('.' + vNombre_clase_clone).children('.controls').find('input[type="checkbox"]').attr("checked", false);
				vControls.find('.' + vNombre_clase_clone).children('.controls').find('input[type="text"]').attr("value", "");
				vControls.find('.' + vNombre_clase_clone).children('.controls').find('input[type="file"]').attr("value", "");
				vControls.find('.' + vNombre_clase_clone).children('.controls').find('input[type="color"]').attr("value", "");
				vControls.find('.' + vNombre_clase_clone).children('.controls').find('input#btnEliminarClone').remove();
			}
		})
	}

	//	Opciones para editar archivos adjuntos
	if ($("a#btnVerArchivo").length) {
		$("a#btnVerArchivo").click(function (e) {
			e.preventDefault();
			alert("a#btnVerArchivo: " + $(this).attr("data-archivo"));
		});
	};

	if ($("a#btnEditarComentarioArchivo").length) {
		$("a#btnEditarComentarioArchivo").click(function (e) {
			e.preventDefault();
			$domModal = $($(this).attr("data-target"));
			$($(this).attr("data-target")).children(".modal-header").children("h3").text("Editar comentario");
			$("#idc").val($(this).attr("data-contenido"));
			$("#ida").val($(this).attr("data-archivo"));
			$("#tipo").val($(this).attr("data-tipo"));
			if ($("#tipo").val() == "Imagen") {
				$("#opciones_imagen").show();
			} else {
				$("#opciones_imagen").hide();
			}
			$("#archivo_adjunto_nombre").val($(this).attr("data-nombre-archivo"));
			$("#archivo_adjunto_comentario").val($(this).attr("data-texto"));

			$("#btnModalGuardar").click(function () {
				var vURL = window.location.href.split('?');
				var fields = $("#modalEditarArchivo .modal-body form").serializeArray();

				$.ajax({
					url: vURL[0] + '?menu=' + getUrlVars()["menu"] + '&accion=updateimagen',
					data: fields,
					type: 'POST',
					dataType: 'text',
					success: function (response) {
						$domModal.modal('hide');
						location.reload(true);
					},
					error: function (xhr, status) {
						alert('Disculpe, existió un problema');
					},
					complete: function (xhr, status) {
						//alert('Petición realizada');
					}
				});
			});
			$("#btnModalCancelar").click(function () {
				$domModal.modal('hide');
			});
		})
	}
	if ($("a#btnEliminarArchivo").length) {
		$("a#btnEliminarArchivo").click(function (e) {
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
					success: function (response) {
						location.reload(true);
					},
					error: function (xhr, status) {
						alert('Disculpe, existió un problema');
					},
					complete: function (xhr, status) {
						//alert('Petición realizada');
					}
				});
			}
		})
	}

	if ($("a#btEliminarAsignacion").length) {
		$("a#btEliminarAsignacion").click(function (e) {
			e.preventDefault();
			//alert($(this).attr("data-grupo")+" | "+$(this).attr("data-contacto"));

			var vURL = window.location.href.split('?');
			var fields = {};
			fields["idg"] = $(this).attr("data-grupo");
			fields["idc"] = $(this).attr("data-contacto");

			if (confirm("Se va a eliminar la asignacion del contacto seleccionado.\nEsta usted seguro?")) {
				$.ajax({
					url: vURL[0] + '?menu=' + getUrlVars()["menu"] + '&accion=deleteasignacion',
					data: fields,
					type: 'POST',
					dataType: 'text',
					success: function (response) {
						//$fila.attr("title",json.estado);
						location.reload(true);
					},
					error: function (xhr, status) {
						alert('Disculpe, existió un problema');
					},
					complete: function (xhr, status) {
						//alert('Petición realizada');
					}
				});
			}
		})
	}

	// cambio la altura de las filas en el modulo SERVICIOS TECNICOS y PEDIDOS
	if (((getUrlVars()["menu"] == "servicios") || (getUrlVars()["menu"] == "pedidos")) && $("table#listado").length) {
		$("table#listado tbody tr").each(function () {
			$(this).css({ "height": "60px", "max-height": "60px", "min-height": "60px" });
		})
	}

	$("body").on("shown", ".modal", function (e) {
		setFirstFocusableElements(e.target);
	})
	$("body").on("hidden", ".modal", function (e) {
		const origen = e.target.querySelector('input[name="origen"]')
		if(origen){
			const boton = document.querySelector('#' + origen.value)
			boton.focus();
		}
	})

	$("#hidepage").remove();
});

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
};

function UpdateCKEditors() {
	for (var i in CKEDITOR.instances) {
		CKEDITOR.instances[i].updateElement();
	}
}

function SeleccionarColumnaPrivilegiosjQuery(t) {
	var vCheckSeleccionado = $(t).attr("id").slice(9, $(t).attr("id").length).toLowerCase();
	if ($(t).prop("checked")) {
		$("input[title='" + vCheckSeleccionado + "']:enabled").prop("checked", true);
	} else {
		$("input[title='" + vCheckSeleccionado + "']:enabled").prop("checked", false);
	}
}

function AbrirURL(t) {
	if ($(t).val()) {
		if (getUrlVars()["menu"] == "reportes") {
			var vFechas = "";
			if ($("#fd").length) {
				vFechas += "&fd=" + $("#fd").val();
			}
			if ($("#fh").length) {
				vFechas += "&fh=" + $("#fh").val();
			}
			window.location = $(t).val() + vFechas;
		} else {
			window.location = $(t).val();
		}
	}
}

function redondear(sVal, nDec) {
	var n = parseFloat(sVal);
	var s = "0.00";
	if (!isNaN(n)) {
		n = Math.round(n * Math.pow(10, nDec)) / Math.pow(10, nDec);
		s = String(n);
		s += (s.indexOf(".") == -1 ? "." : "") + String(Math.pow(10, nDec)).substr(1);
		s = s.substr(0, s.indexOf(".") + nDec + 1);
	}
	return s;
}

function number_format(number, decimals, dec_point, thousands_sep) {
	number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
	var n = !isFinite(+number) ? 0 : +number,
		prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
		sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
		dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
		s = '',
		toFixedFix = function (n, prec) {
			var k = Math.pow(10, prec);
			return '' + Math.round(n * k) / k;
		};
	// Fix for IE parseFloat(0.55).toFixed(0) = 0;
	s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
	if (s[0].length > 3) {
		s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
	}
	if ((s[1] || '').length < prec) {
		s[1] = s[1] || '';
		s[1] += new Array(prec - s[1].length + 1).join('0');
	}
	return s.join(dec);
}

function htmlEncode(value) {
	return $('<div />').text(value).html();
}

function htmlDecode(value) {
	return $('<div />').html(value).text();
}

// -------------------------------------------------------------
let app = document.querySelector('body')
const DECIMALES = 2

document.addEventListener('DOMContentLoaded', function () {
	console.log("DOMContentLoaded");

    app.onchange = handleChange.bind(this)
    app.onclick = handleClick.bind(this)
    app.ondblclick = handleDblClick.bind(this)

	if (document.querySelector('input[name="fd"]')) {
		document.querySelector('input[name="fd"]').addEventListener('change', e => {
			document.querySelector('input[name="fh"]').setAttribute('min', e.target.value)
		})
	}

	if (document.querySelector('button#editarCoordenadas')) {
		document.querySelector('button#editarCoordenadas').addEventListener('click', event => {
			event.preventDefault();

			let target = event.target.getAttribute('data-target')
			let modalRegistro = document.querySelector(target)

			let vCampoLatitud = event.target.closest('.control-group').querySelector('input[type="text"]#latitud');
			let vCampoLongitud = event.target.closest('.control-group').querySelector('input[type="text"]#longitud');

			modalRegistro.querySelector('input[name="origen"]').value = event.target.id;
			modalRegistro.querySelector('input[type="text"]#vLatitud').value = vCampoLatitud.value;
			modalRegistro.querySelector('input[type="text"]#vLongitud').value = vCampoLongitud.value;

			const typeMapa = modalRegistro.querySelector('.modal-body').getAttribute('data-apimapa')

			modalRegistro.querySelector('.modal-body').innerHTML = '<div id="mapa"></div>';
			switch (typeMapa) {
				case "leaflet":
					initializarMapaUbicacion(modalRegistro.querySelector('input[type="text"]#vLatitud'), modalRegistro.querySelector('input[type="text"]#vLongitud'));
					break;
				case "googlemaps":
					initializarMapaUbicacionGoogle(modalRegistro.querySelector('input[type="text"]#vLatitud'), modalRegistro.querySelector('input[type="text"]#vLongitud'));
					break;
			}

			modalRegistro.querySelector('button#btnModalGuardarMapa').addEventListener('click', event => {
				modalRegistro.querySelector('.modal-body').innerHTML = '';
				vCampoLatitud.value = event.target.closest('.modal-footer').querySelector('input[type="text"]#vLatitud').value;
				vCampoLongitud.value = event.target.closest('.modal-footer').querySelector('input[type="text"]#vLongitud').value;

			}, { once: true })

			modalRegistro.querySelector('button#btnModalCancelar').addEventListener('click', event => {
				modalRegistro.querySelector('.modal-body').innerHTML = '';
			}, { once: true })

		})
	}

	setFirstFocusableElements(document.querySelector('body > .container-fluid'));

})

function handleClick(event) {
    //console.log('handleClick',event.target);

	/*
    if ((event.target.getAttribute('data-evento') == 'click') && (event.target.getAttribute('data-metodo') != '')) {
        const parametro = event.target.getAttribute('data-parametro') ? event.target.getAttribute('data-parametro') : ''
        const metodos = event.target.getAttribute("data-metodo").split(",")
		
        metodos.forEach((nombreMetodo) => {
            if (typeof window[nombreMetodo] === 'function') {
                window[nombreMetodo](event.target.innerText, parametro, event.target);
            }
        })
    }
		*/

    const target = event.target.nodeName == 'BUTTON' ? event.target : (event.target.nodeName == 'A' ? event.target : event.target.closest('BUTTON'))

    if (!target) { return }
    if (target.disabled) { return }

    if ((target.getAttribute('data-evento') == 'click') && (target.getAttribute('data-metodo') != '')) {
        const parametro = target.getAttribute('data-parametro') ? target.getAttribute('data-parametro') : ''
        const metodos = target.getAttribute("data-metodo").split(",")
		
        metodos.forEach((nombreMetodo) => {
            if (typeof window[nombreMetodo] === 'function') {
                window[nombreMetodo](target.innerText, parametro, target);
            }
        })
    }

	if (target.id == 'btnModalGuardarDetalleTraspaso') {
        event.preventDefault()
        const formRegistro = event.target.closest('form')
		if (validForm(formRegistro, true)) {
			let data = new FormData(formRegistro)
			
			let object = {}, url;
			data.forEach(function(value, key){
				object[key] = value;
			});
			//console.log(object);

			let body = document.querySelector('table#listaDetalles tbody')
			
			let filas = body.querySelectorAll('tr');
			let existeCoincidencia = Array.from(filas).some((fila) => {
				let inputOrigen = fila.querySelector('input[name="id_detalleorigen[]"]')
				let inputDestino = fila.querySelector('input[name="id_detalledestino[]"]')
			  
				return (
				  inputOrigen &&
				  inputDestino &&
				  inputOrigen.value === object.productoorigen.toString() &&
				  inputDestino.value === object.productodestino.toString()
				);
			});
				
			if(existeCoincidencia){
				modalMessage({type: 'alert', title: 'Atención', message: "El producto de origen y destino seleccionado, ya se encuentra en el detalla actual", target: ''},(response) => { return false })
			} else {
				const botonCerrar = formRegistro.querySelector('button[id="btnModalCancelar"]')

				modalMessage({type: 'confirm', title: 'Confirmación', message: "Esta seguro de agregar el producto de origen y destino seleccionado, con la cantidad asignada?", target: ''},(response) => { 
					if (response == true) {
						const selectProductoOrigen = formRegistro.querySelector('select[name="productoorigen"]')
						const valoresProductoOrigen =  selectProductoOrigen.querySelector('option[value="'+object.productoorigen+'"]').text.split('| Stock: ')
						const selectProductoDestino = formRegistro.querySelector('select[name="productodestino"]')
						const valoresProductoDestino =  selectProductoDestino.querySelector('option[value="'+object.productodestino+'"]').text.split('| Stock: ')

						const tr = document.createElement('tr')
						tr.classList.add('unChecked')
						tr.innerHTML = `
							<td>
								<input type='checkbox' name='detalle' value='${(body.querySelectorAll('tr').length+1)}' data-evento="click" data-metodo="checkClickRow" />
								<input type='hidden' name='id_detalleorigen[]' value='${object.productoorigen}' />
								<input type='hidden' name='id_detalledestino[]' value='${object.productodestino}' />
							</td>
							<td>${valoresProductoOrigen[0]}</td>
							<td>${valoresProductoDestino[0]}</td>
							<td style='text-align: right;'>${object.detalleCantidadOrigen}<input type='hidden' name='detalleCantidadOrigen[]' value='${object.detalleCantidadOrigen}' /></td>
							<td style='text-align: right;'>${object.detalleCantidadDestino}<input type='hidden' name='detalleCantidadDestino[]' value='${object.detalleCantidadDestino}' /></td>
							<td style='text-align: right;'>${object.detalleCantidad}<input type='hidden' name='detalleCantidad[]' value='${object.detalleCantidad}' /></td>
						`
						body.append(tr)
						
						botonCerrar.dispatchEvent(new Event('click', { bubbles: true, cancelable: true }))
					}
				})
					
			}

	
		}
	}

	if(target.id == 'btnEliminarDetalle'){
        event.preventDefault()
		const formRegistro = target.closest('form')
		const destino = target.getAttribute('data-target')
		const elemento = target.getAttribute('data-item')
		const user = target.getAttribute('data-user')

		modalMessage({type: 'confirm', title: 'Confirmación', message: "Esta seguro que desea eliminar el detalle seleccionado?", target: ''},(response) => { 
			if(response == true){
				let body = formRegistro.querySelector(destino+' tbody')
				body.querySelectorAll(elemento+':checked').forEach(item => {
					if(getUrlVars()["accion"] == "editar"){

						let vParametros = '';
						vParametros = vParametros + "?menu=" + getUrlVars()["menu"];
						vParametros = vParametros + "&accion=eliminarDetalle";
						switch (getUrlVars()["menu"]) {
							case 'traspasos':
								const master = formRegistro.querySelector('input[name="id_traspaso"]')
								const id = item.value

								vParametros = vParametros + "&master=" + master.value;
								vParametros = vParametros + "&id=" + id;
								vParametros = vParametros + "&idu=" + user;
								break;
						
							default:
								break;
						}

						fetch('eliminarDatos.php'+vParametros, { method: 'GET' })
							.then(response => response.json())
							.then(result => {
								//if(result[0].estado == 'OK'){
									item.closest('tr').remove()
								//}
							})
							.catch(error => console.log('Hubo un problema con la petición Fetch: ' + error.message))

					} else {
						item.closest('tr').remove()
					}
				})
				target.setAttribute('disabled','disabled')
			}
			return false 
		})
	}

	if (target.id == 'btnModalGuardarBuscarProducto') {
        event.preventDefault()
        const formRegistro = event.target.closest('form')
		if (validForm(formRegistro, true)) {
			let data = new FormData(formRegistro)
			
			let object = {}, url;
			data.forEach(function(value, key){
				object[key] = value;
			});
			//console.log(object.registros);

			if(object.registros != undefined){
				
				const elemento = event.target.tagName == 'I' ? event.target.closest('button') : event.target
				const domModal = app.querySelector(elemento.getAttribute('data-target'))
				const botonGuardar = app.querySelector('input[type="submit"]')
				const botonCerrar = domModal.querySelector('button[id="btnModalCancelar"]')

				const sucursalOrigen = app.querySelector('[name="sucursal"]')

				let vParametros = ''
				vParametros = vParametros + '&s='+sucursalOrigen.value
				vParametros = vParametros + '&id='+object.registros
			
				fetch('buscarDatos.php?t=productos'+vParametros, { method: 'GET' })
					.then(response => response.json())
					.then(result => {

						const id = app.querySelector('input[name="id_producto"]')
						const codigo = app.querySelector('input[name="codigo"]')
						const nombre = app.querySelector('input[name="producto"]')
						const categoria = app.querySelector('input[name="campo_categoria"]')
						const genero = app.querySelector('input[name="campo_genero"]')
						const stock = app.querySelector('input[name="stock"]')
						const stockAumentar = app.querySelector('input[name="stock_aumentar"]')
						const stockFinal = app.querySelector('input[name="stock_final"]')

						const botonAumentarCampoRestar = app.querySelector('button#btAumentarCampo[data-tipo="-"][data-campo="stock_aumentar"]')
						botonAumentarCampoRestar.setAttribute('data-limite',0)
						botonAumentarCampoRestar.removeAttribute('disabled')
						const botonAumentarCampoSumar = app.querySelector('button#btAumentarCampo[data-tipo="+"][data-campo="stock_aumentar"]')
						botonAumentarCampoSumar.removeAttribute('disabled')
						botonGuardar.removeAttribute('disabled')

						id.value = result[0].id
						codigo.value = result[0].codigo
						nombre.value = result[0].nombre
						categoria.value = result[0].categoria
						genero.value = result[0].genero
						stock.value = result[0].stock
						stockAumentar.value = 0
						stockFinal.value = ''

						botonCerrar.dispatchEvent(new Event('click', { bubbles: true, cancelable: true }))
					})
					.catch(error => console.log('Hubo un problema con la petición Fetch: ' + error.message))
			
			} else {
				modalMessage({type: 'alert', title: 'Atención', message: "Debe seleccionar un producto.", target: ''},(response) => { return false })
			}
	
		}
	}

}

function handleChange(event) {
    const target = event.target

    if (!target) return

    //console.log('handleChange',target.name);

    if ((target.getAttribute('data-evento') == 'change') && (target.getAttribute('data-metodo') != '')) {
        const master = target.getAttribute('data-id-master') ? target.getAttribute('data-id-master') : ''
        const metodos = target.getAttribute("data-metodo").split(",")
        metodos.forEach((nombreMetodo) => {
            if (typeof window[nombreMetodo] === 'function') {
                window[nombreMetodo](target.value, master, target);
            }
        })
    }

    if (target.classList.contains('uppercase')) {
        target.value = target.value.toUpperCase()
    }

    if (target.classList.contains('lowercase')) {
        target.value = target.value.toLowerCase()
    }

	if (target.classList.contains('moneda')) {
		if (target.value != '') {
			const decimales = target.getAttribute('data-decimales') ? target.getAttribute('data-decimales') : DECIMALES			
			target.value = number_format(target.value.replace('.', '').replace(',', '.'), decimales, ',', '.')
		}
	}

}

function handleDblClick(event) {
    console.log('handleDblClick');
    const target = event.target

    if (!target) return

}

// BUSCADOR STOCK EN OTRAS SUCURSALES DE UN PRODUCTO ----------
function getData_ModalStockSucursalesAsociadas(valorSeleccionado, master, objeto) {

	const titulo = objeto.getAttribute('title')
	const sucursal = objeto.getAttribute('data-sucursal')
	const codigo = objeto.getAttribute('data-producto')
	
	let vParametros = ''
	vParametros = vParametros + '&s='+sucursal
	vParametros = vParametros + '&q='+codigo

	fetch('buscarDatos.php?t=obtenerStockSucursalesAsociadas'+vParametros, { method: 'GET' })
		.then(response => response.json())
		.then(result => {

			const domModal = app.querySelector(objeto.getAttribute('data-target'))

			domModal.querySelector('.modal-header h4').innerText = titulo
			//domModal.querySelector('.modal-body').style.height = '350px'
			domModal.style.width = '830px'
					
			const body = domModal.querySelector('.modal-body table tbody')
			body.innerHTML = ''

			if(result[0].message == 'OK'){
				result[0].records.forEach(item => {
					body.innerHTML += `<tr ${item.ID_SUCURSAL == sucursal ? 'class="info"' : ''}>
						<td>${item.SUCURSAL}</td>
						<td>${item.CODIGO}</td>
						<td>${item.PRODUCTO}</td>
						<td class="text-right">${item.STOCK}</td>
					</tr>`	
				})
			} else {
				body.innerHTML = `<tr><td colspan="4">${result[0].error}</td></tr>`
			}
		
		})
		.catch(error => console.log('Hubo un problema con la petición Fetch: ' + error.message))

}

// BUSCADOR DE PRODUCTOS ----------

function showModal_BuscarProducto(valorSeleccionado, master, objeto) {
	console.log('showModal_BuscarProducto');
	
	const domModal = app.querySelector(objeto.getAttribute('data-target'))

	domModal.querySelector('.modal-header h3').innerText = 'Buscar producto'
	domModal.querySelector('.modal-body').style.height = '350px'
	domModal.style.width = '830px'

	domModal.querySelector('input[name="origen"]').value = objeto.id;
	domModal.querySelector('.modal-header input[name="q"]').value = ''
	domModal.querySelector('.modal-header select[name="listaCategoria"]').value = ''
	domModal.querySelector('.modal-header select[name="listaGenero"]').value = ''
	
	const body = domModal.querySelector('.modal-body table tbody')
	body.innerHTML = ''
}

function getData_ModalBuscarProducto(valorSeleccionado, master, objeto) {

	const elemento = objeto.tagName == 'I' ? objeto.closest('button') : objeto
	const domModal = app.querySelector(elemento.getAttribute('data-target'))

	const head = domModal.querySelector('.modal-body table thead')
	const body = domModal.querySelector('.modal-body table tbody')
	body.innerHTML = ''
	const columnas = head.querySelectorAll('th').length
	let tr = document.createElement('tr')
	tr.innerHTML = `
		<td colspan="${columnas}" class="text-danger text-center"><img src="../images/preloader.gif" /> obteniendo datos..</td>
	`
	body.append(tr)

	const sucursalOrigen = app.querySelector('[name="sucursal"]').value
	const texto = domModal.querySelector('input[name="q"]').value
	const categoria = domModal.querySelector('select[name="listaCategoria"]').value
	const genero = domModal.querySelector('select[name="listaGenero"]').value

	let vParametros = ''
	vParametros = vParametros + '&s='+sucursalOrigen
	vParametros = vParametros + '&q='+texto
	vParametros = vParametros + '&c='+categoria
	vParametros = vParametros + '&g='+genero

	fetch('buscarDatos.php?t=productos'+vParametros, { method: 'GET' })
		.then(response => response.json())
		.then(result => {
			body.innerHTML = ''
			if(result.length){
				tr.classList.add('unChecked')			
				result.forEach(item => {
					tr = document.createElement('tr')
					tr.classList.add('unChecked')
					tr.innerHTML = `
						<td><input type="radio" name="registros" value="${item.id}" data-evento="click" data-metodo="clickFila" /></td>
						<td data-evento="click" data-metodo="clickFila">${item.codigo}</td>
						<td data-evento="click" data-metodo="clickFila">${item.nombre}</td>
						<td data-evento="click" data-metodo="clickFila">${item.categoria}</td>
						<td data-evento="click" data-metodo="clickFila">${item.genero}</td>
						<td class="text-right" data-evento="click" data-metodo="clickFila">
							${item.precio_venta}
							<input type="hidden" name="pcosto" value="${item.precio_costo}" />
							<input type="hidden" name="pventa" value="${item.precio_venta}" />
						</td>
						<td class="text-right" data-evento="click" data-metodo="clickFila">${item.stock}</td>
					`
					body.append(tr)
				})
			} else {
				tr.innerHTML = `
					<td colspan="${columnas}" class="text-danger text-center">No hay coincidencias encontradas</td>
				`
				body.append(tr)
			}
		})
		.catch(error => console.log('Hubo un problema con la petición Fetch: ' + error.message))

}

function getData_ModalBuscarProductoGeneros(valorSeleccionado, master, objeto) {

	const elemento = objeto.tagName == 'I' ? objeto.closest('button') : objeto
	const domModal = app.querySelector(elemento.getAttribute('data-target'))

	const sucursalOrigen = app.querySelector('[name="sucursal"]').value
	const categoria = domModal.querySelector('select[name="listaCategoria"]').value

	const listaGenero = domModal.querySelector('select[name="listaGenero"]')
	listaGenero.innerHTML = '<option value="">' + listaGenero.getAttribute('title') + ' (0)</option>'

	let vParametros = ''
	vParametros = vParametros + '&c='+categoria
	vParametros = vParametros + '&ids='+sucursalOrigen

	fetch('buscarDatos.php?t=listaGeneros'+vParametros, { method: 'GET' })
		.then(response => response.json())
		.then(result => {
			listaGenero.innerHTML = '<option value="">' + listaGenero.getAttribute('title') + ' (' + result.length + ')</option>'
			let opt = ''
			result.forEach(item => {
				opt += `<option value="${item.label}">${item.label}</option>`
			})
			listaGenero.innerHTML += opt
		})
		.catch(error => console.log('Hubo un problema con la petición Fetch: ' + error.message))

}

function clickFila(valorSeleccionado, master, objeto) {

	if(objeto.tagName == 'TD'){
		const fila = objeto.closest('tr')
		const check = fila.querySelector('input[name="registros"]')
		check.checked = true
	}

	const body = objeto.closest('tbody')
	const filas = body.querySelectorAll('tr')
	filas.forEach(fila => {
		const check = fila.querySelector('input[name="registros"]')
		if(check.checked){
			fila.classList.add('Checked')
			fila.classList.remove('unChecked')
		} else {
			fila.classList.add('unChecked')
			fila.classList.remove('Checked')
		}
	})

}

// TRASPASOS ----------

function getData_SucursalDestino(valorSeleccionado, master, objeto) {
	console.log('getData_SucursalDestino');
    const container = objeto.closest('form')

	const boton = container.querySelector('button[id="btNuevoDetalleTraspaso"]')
	const sucursalOrigen = container.querySelector('[name="sucursalorigen"]')
	const categoriaDestino = app.querySelector('select[name="listaCategoriaDestino"]')
	const generoDestino = app.querySelector('select[name="listaGeneroDestino"]')

	if(sucursalOrigen.value != '' && valorSeleccionado != '' && sucursalOrigen.value != valorSeleccionado){
		boton.removeAttribute('disabled')
	} else {
		boton.setAttribute('disabled','disabled')
	}
	categoriaDestino.innerHTML = '<option value="">' + categoriaDestino.getAttribute('title') + '</option>'
	generoDestino.innerHTML = '<option value="">' + generoDestino.getAttribute('title') + '</option>'

	if(valorSeleccionado != ''){

		let vParametros = '';
		vParametros = vParametros + "&m=" + getUrlVars()["menu"];
		vParametros = vParametros + "&ids=" + valorSeleccionado;

		fetch('buscarDatos.php?t=categoria'+vParametros, { method: 'GET' })
			.then(response => response.json())
			.then(result => {
				let opt = ''
				result.forEach(item => {
					opt += `<option value="${item.label}">${item.label}</option>`
				})
				categoriaDestino.innerHTML += opt
			})
			.catch(error => console.log('Hubo un problema con la petición Fetch: ' + error.message))

		fetch('buscarDatos.php?t=listaGeneros'+vParametros, { method: 'GET' })
			.then(response => response.json())
			.then(result => {
				let opt = ''
				result.forEach(item => {
					opt += `<option value="${item.label}">${item.label}</option>`
				})
				generoDestino.innerHTML += opt
			})
			.catch(error => console.log('Hubo un problema con la petición Fetch: ' + error.message))

	}

}
function showModal_DetalleTraspaso(valorSeleccionado, master, objeto) {
	console.log('showModal_DetalleTraspaso');
	
	const domModal = app.querySelector(objeto.getAttribute('data-target'))

	domModal.querySelector('.modal-header h3').innerText = 'Buscar producto de origen y destino'
	domModal.querySelector('.modal-body').style.height = '350px'
	domModal.style.width = '830px'

	// origen
	domModal.querySelector('.modal-body input[name="q"]').value = ''
	domModal.querySelector('.modal-body select[name="listaCategoria"]').value = ''
	domModal.querySelector('.modal-body select[name="listaGenero"]').value = ''
	const productosOrigen = domModal.querySelector('select[name="productoorigen"]')
	productosOrigen.innerHTML = '<option value="">' + productosOrigen.getAttribute('title') + ' (0)</option>'
	domModal.querySelector('.modal-body input[name="detalleCantidadOrigen"]').value = ''
	
	// destino
	const tituloSucursalDestino = domModal.querySelector('h5.tituloProductoDestino')
	const sucursalDestino = app.querySelector('select[name="sucursaldestino"]')
	if(sucursalDestino.value != ''){
		tituloSucursalDestino.innerText = 'Producto de Destino en ' + sucursalDestino.querySelector('option[value="'+sucursalDestino.value+'"]').innerText
	} else {
		tituloSucursalDestino.innerText = 'Producto de Destino'
	}
	domModal.querySelector('.modal-body input[name="qDestino"]').value = ''
	domModal.querySelector('.modal-body select[name="listaCategoriaDestino"]').value = ''
	domModal.querySelector('.modal-body select[name="listaGeneroDestino"]').value = ''
	const productosDestino = domModal.querySelector('select[name="productodestino"]')
	productosDestino.innerHTML = '<option value="">' + productosDestino.getAttribute('title') + ' (0)</option>'
	domModal.querySelector('.modal-body input[name="detalleCantidadDestino"]').value = ''
	
	domModal.querySelector('input[name="detalleCantidad"]').value = ''

}
function getData_ModalBuscarOrigen(valorSeleccionado, master, objeto) {
	console.log('getData_ModalBuscarOrigen');

	const elemento = objeto.tagName == 'I' ? objeto.closest('button') : objeto
	const domModal = app.querySelector(elemento.getAttribute('data-target'))

	const productosOrigen = domModal.querySelector('select[name="productoorigen"]')
	productosOrigen.innerHTML = '<option value="">' + productosOrigen.getAttribute('title') + ' (0)</option>'

	const sucursalOrigen = app.querySelector('[name="sucursalorigen"]')
	const texto = domModal.querySelector('input[name="q"]')
	const categoria = domModal.querySelector('select[name="listaCategoria"]')
	const genero = domModal.querySelector('select[name="listaGenero"]')

	let vParametros = ''
	vParametros = vParametros + '&s='+sucursalOrigen.value
	vParametros = vParametros + '&q='+texto.value
	vParametros = vParametros + '&c='+categoria.value
	vParametros = vParametros + '&g='+genero.value

	fetch('buscarDatos.php?t=productos'+vParametros, { method: 'GET' })
		.then(response => response.json())
		.then(result => {
			productosOrigen.innerHTML = '<option value="">' + productosOrigen.getAttribute('title') + ' (' + result.length + ')</option>'
			let opt = ''
			result.forEach(item => {
				if(Number(item.stock) > 0){
					opt += `<option value="${item.id}">[${item.codigo}] ${item.nombre} | Stock: ${item.stock}</option>`
				}
			})
			productosOrigen.innerHTML += opt
		})
		.catch(error => console.log('Hubo un problema con la petición Fetch: ' + error.message))

}
function getData_ModalBuscarDestino(valorSeleccionado, master, objeto) {
	console.log('getData_ModalBuscarDestino');

	const elemento = objeto.tagName == 'I' ? objeto.closest('button') : objeto
	const domModal = app.querySelector(elemento.getAttribute('data-target'))

	const productosDestino = domModal.querySelector('select[name="productodestino"]')
	productosDestino.innerHTML = '<option value="">' + productosDestino.getAttribute('title') + ' (0)</option>'

	const sucursalDestino = app.querySelector('select[name="sucursaldestino"]')
	const texto = domModal.querySelector('input[name="qDestino"]')
	const categoria = domModal.querySelector('select[name="listaCategoriaDestino"]')
	const genero = domModal.querySelector('select[name="listaGeneroDestino"]')

	let vParametros = ''
	vParametros = vParametros + '&s='+sucursalDestino.value
	vParametros = vParametros + '&q='+texto.value
	vParametros = vParametros + '&c='+categoria.value
	vParametros = vParametros + '&g='+genero.value

	fetch('buscarDatos.php?t=productos'+vParametros, { method: 'GET' })
		.then(response => response.json())
		.then(result => {
			productosDestino.innerHTML = '<option value="">' + productosDestino.getAttribute('title') + ' (' + result.length + ')</option>'
			let opt = ''
			result.forEach(item => {
				opt += `<option value="${item.id}">[${item.codigo}] ${item.nombre} | Stock: ${item.stock}</option>`
			})
			productosDestino.innerHTML += opt
		})
		.catch(error => console.log('Hubo un problema con la petición Fetch: ' + error.message))

}
function getData_ProductoSeleccionado(valorSeleccionado, master, objeto) {
	console.log('getData_ProductoSeleccionado');
	const valores =  objeto.querySelector('option[value="'+valorSeleccionado+'"]').text.split('| Stock: ')

    const container = objeto.closest('form')
	const cantidadOrigen = container.querySelector('input[name="detalleCantidadOrigen"]')
	const cantidadDestino = container.querySelector('input[name="detalleCantidadDestino"]')
	const cantidad = container.querySelector('input[name="detalleCantidad"]')

	switch (objeto.getAttribute('name')) {
		case 'productoorigen':
			if(valorSeleccionado != '' && valores.length > 1){
				cantidadOrigen.value = Number(valores[1])
				cantidad.setAttribute('max',Number(valores[1]))
			} else {
				cantidadOrigen.value = ''
				cantidad.value = ''
				cantidad.setAttribute('max','')
			}
			break;
		case 'productodestino':
			if(valorSeleccionado != '' && valores.length > 1){
				cantidadDestino.value = Number(valores[1])
			} else {
				cantidadDestino.value = ''
				cantidad.value = ''
			}
			break;
	
		default:
			break;
	}
	validForm(container, false)
}

function checkClickRow(valorSeleccionado, master, objeto) {
	const fila = objeto.closest('tr')
	if(objeto.checked){
		fila.classList.add('Checked')
		fila.classList.remove('unChecked')
	} else {
		fila.classList.add('unChecked')
		fila.classList.remove('Checked')
	}
	const formRegistro = objeto.closest('form')
	const botonEliminar = formRegistro.querySelector('button[id="btnEliminarDetalle"]')
	if(formRegistro.querySelectorAll(objeto.tagName+'[name="'+objeto.name+'"]:checked').length > 0){
		botonEliminar.removeAttribute('disabled')
	} else {
		botonEliminar.setAttribute('disabled', 'disabled')
	}
}
function cambiarEstadoRegistro(valorSeleccionado, master, objeto) {
	console.log('cambiarEstadoRegistro',objeto.checked);
	const fila = objeto.closest('tr')
	objeto.checked ? fila.classList.remove('desactived') : fila.classList.add('desactived')
	const checkID = fila.querySelector('input[name="registro[]"]')
	checkID ? checkID.checked = false : false
}

// FUNCIONES ----------

function validForm(form, showNotification = false) {
    let valido = true
    let msj = ''

	form.querySelectorAll(':required').forEach(item => {
		item.classList.remove('valido')
		item.classList.remove('invalido')
		item.validity.valid ? item.classList.add('valido') : item.classList.add('invalido')
		if (!item.validity.valid) valido = false

		// verifico si es un campo numerico
		if (item.getAttribute('min') != null) {
			const valorMinimo = Number(item.getAttribute('min'))
			const valor = Number(item.value.replace('.', '').replace(',', '.'))

			if (valor < valorMinimo) {
				if (valorMinimo > 0) {
					msj += '<li>El dato <strong>' + item.title + '</strong> debe tener un valor numérico positivo mayor o igual a ' + valorMinimo + '</li>'
				} else {
					msj += '<li>El dato <strong>' + item.title + '</strong> debe tener un valor numérico positivo</li>'
				}
				if (item.classList.contains('valido')) {
					item.classList.add('invalido')
					item.classList.remove('valido')
				}
				valido = false
			}

		}

		if (item.getAttribute('max') != null) {
			const valorMaximo = Number(item.getAttribute('max'))
			const valor = Number(item.value.replace('.', '').replace(',', '.'))

			if (valor > valorMaximo) {
				if (valorMaximo > 0) {
					msj += '<li>El dato <strong>' + item.title + '</strong> debe tener un valor numérico positivo menor o igual a ' + valorMaximo + '</li>'
				} else {
					msj += '<li>El dato <strong>' + item.title + '</strong> no puede ser asginado o modificado porque depende de otro dato</li>'
				}
				if (item.classList.contains('valido')) {
					item.classList.add('invalido')
					item.classList.remove('valido')
				}
				valido = false
			}

		}

		// verifico si el campo admite un cantidad minima de caracteres
		if (item.getAttribute('minlength') != null) {
			const cantidadMinima = Number(item.getAttribute('minlength'))
			const cantidad = Number(item.value.length)

			if (cantidad < cantidadMinima) {
				msj += '<li>El dato <strong>' + item.title + '</strong> debe tener ' + cantidadMinima + ' letras y/o números como mínimo</li>'
				if (item.classList.contains('valido')) {
					item.classList.add('invalido')
					item.classList.remove('valido')
				}
				valido = false
			}

		}

		// verifico el campo
		if (!item.checkValidity()) {
			msj += '<li><strong>' + item.title + '</strong>: ' + item.validationMessage + '</li>'
			valido = false
		}

	})

    if (!valido) {
        if (showNotification) {
			modalMessage({type: 'alert', title: 'Atención', message: "Los datos marcados en rojo son obligatorios:\n" + msj, target: ''},(response) => { return false })
        }
    } else {
        return valido
    }
}
function modalMessage(content,callback) {
	
	let containerModal = document.createElement('dialog')
	containerModal.id = 'modalMessage'
	containerModal.innerHTML = `
		<header>
			<h3>${content.title}</h3>
		</header>
		<article>
			<p>${content.message}</p>
		</article>
		<footer>
			<button type='button' class='btn btn-message-cancel'>Cancelar</button>
			<button type='submit' class='btn btn-message-ok btn-primary'>Aceptar</button>
		</footer>
	`
	document.body.appendChild(containerModal);
	containerModal.showModal();

	const btnOk = containerModal.querySelector('.btn-message-ok')
	const btnCancel = containerModal.querySelector('.btn-message-cancel')
	const header = containerModal.querySelector('header')

	switch (content.type.toUpperCase()) {
		case 'ALERT':
			header.classList.add('danger')
			btnOk.innerText = 'Aceptar'
			btnOk.focus()
			btnOk.classList.add('w-100')
			btnCancel.style.display = 'none'
			break;
		case 'CONFIRM':
			header.classList.add('warning')
			btnOk.innerText = 'SI'
			btnOk.focus()
			btnOk.classList.add('w-50')
			btnCancel.innerText = 'NO'
			break;
	
		default:
			header.removeAttribute('class')
			btnOk.innerText = 'Aceptar'
			btnOk.focus()
			btnOk.classList.add('w-50')
			btnCancel.innerText = 'Cancelar'
			break;
	}

	btnOk.addEventListener('click', (e) => {
		e.preventDefault()
		callback(true)
		modalMessageClose()
	})

	btnCancel.addEventListener('click', (e) => {
		e.preventDefault()
		callback(false)
		modalMessageClose()
	})

	function modalMessageClose() {
		var dialog = document.querySelector('dialog#modalMessage');
		dialog.close()
		dialog.remove()
	}

	containerModal.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            e.preventDefault()
        }
    })

}
function setSelectedIndex(target, valsearch) {
	if (target) {
		target.querySelectorAll('option:not([disabled])').forEach(item => {
			if (item.value == valsearch) {
				item.selected = true
				return;
			}
		})
	}
	return;
}

// MAPAS ----------

function initializarMapaUbicacion(latitud, longitud) {
	let polygonArrayPuntos = [];
	let polylineArrayPuntos = [];

	let mymapa = L.map("mapa").setView([latitud.value, longitud.value], 16);
	let geocoder = L.Control.Geocoder.nominatim();
	let control = L.Control.geocoder({ placeholder: "buscar lugar", geocoder: geocoder }).addTo(mymapa);

	L.tileLayer(
		"https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw", {
		maxZoom: 18,
		dragging: true,
		attribution: '&copy; <a href="http://www.xperticia.com" targe="_blank" rel="noopener noreferrer">xperticia.com</a>',
		id: "mapbox.streets"
	}
	).addTo(mymapa);

	mymapa.zoomControl.setPosition('bottomright');

	let actualizarMapa = setTimeout(function () { mymapa.invalidateSize(); }, 400);
	//clearTimeout(actualizarMapa);

	let marker = L.marker([latitud.value, longitud.value])
		.addTo(mymapa);

	function onMapClick(e) {
		marker.setLatLng(e.latlng);
		latitud.value = e.latlng["lat"];
		longitud.value = e.latlng["lng"];
	}
	mymapa.addEventListener('click', onMapClick);

	function crearPolilineaPuntos(lista) {
		borrarPolilineaPuntos();
		var posiciones = [];
		lista.each(function (idx, el) {
			var parametros = $(el).val();
			var valores = parametros.split(",");
			posiciones.push([valores[0], valores[1]]);
		});
		var polyline = L.polyline(posiciones, { color: "red" }).addTo(mymapa);
		polylineArrayPuntos[polylineArrayPuntos.length] = polyline;
	}

	function borrarPolilineaPuntos() {
		for (i = 0; i < polylineArrayPuntos.length; i++) {
			mymapa.removeLayer(polylineArrayPuntos[i]);
		}
		polylineArrayPuntos = [];
	}

	function crearPoligonoPuntos(lista) {
		borrarPoligonosPuntos();
		var posiciones = [];
		lista.each(function (idx, el) {
			var parametros = $(el).val();
			var valores = parametros.split(",");
			posiciones.push([valores[0], valores[1]]);
		});
		var polygon = L.polygon(posiciones, { color: "red" }).addTo(mymapa);
		polygonArrayPuntos[polygonArrayPuntos.length] = polygon;
	}

	function borrarPoligonosPuntos() {
		for (i = 0; i < polygonArrayPuntos.length; i++) {
			mymapa.removeLayer(polygonArrayPuntos[i]);
		}
		polygonArrayPuntos = [];
	}

	function actualizarCoordenadas() {
		mymapa.setView([latitud.value, longitud.value], 16);
		marker.setLatLng(L.latLng(latitud.value, longitud.value));
		mymapa.invalidateSize();
	}
	var recalcularImporte = (e) => {
		if (e.keyCode === 13) {
			e.currentTarget.dispatchEvent(new Event('change'));
			e.target.focus();
			return false;
		}
	};

	latitud.addEventListener('change', actualizarCoordenadas, { once: false });
	latitud.addEventListener('keyup', recalcularImporte, { once: false });
	longitud.addEventListener('change', actualizarCoordenadas, { once: false });
	longitud.addEventListener('keyup', recalcularImporte, { once: false });

	if ($("#vPuntos").length) {
		if ($("#vPuntos option").length) {
			if (getUrlVars()["t"] == "recorrido") {
				crearPolilineaPuntos($("#vPuntos option"));
			} else {
				crearPoligonoPuntos($("#vPuntos option"));
			}
		}
		$("#vPuntos").change(function () {
			if ($("#vPuntos option:selected").val() != "") {
				var valores = $("#vPuntos option:selected")
					.val()
					.split(",");
				marker.setLatLng([valores[0], valores[1]]);
				mymapa.panTo([valores[0], valores[1]]);
				$("#vLatitud").val(valores[0]);
				$("#vLongitud").val(valores[1]);
			}
		});
	}

	if ($("input[type='button']").length) {
		$("input[type='button']").click(function () {
			switch ($(this).attr("name")) {
				case "agregarPosicion":
					$("#vPuntos").append(
						"<option value='" + $("#vLatitud").val() + "," + $("#vLongitud").val() + "'>" + $("#vLatitud").val() + "," + $("#vLongitud").val() + "</option>"
					);
					if (getUrlVars()["t"] == "recorrido") {
						crearPolilineaPuntos($("#vPuntos option"));
					} else {
						crearPoligonoPuntos($("#vPuntos option"));
					}
					break;
				case "eliminarPosicion":
					if (!$("#vPuntos option:selected").val()) {
						window.alert("Usted no ha seleccionado ningun elemento \no no posee elementos en la lista.");
					} else {
						if (confirm('Esta seguro que desea eliminar la posicion seleccionada: \n"' + $("#vPuntos option:selected").val() + '" ')) {
							$("#vPuntos option:selected").remove();
							if (getUrlVars()["t"] == "recorrido") {
								crearPolilineaPuntos($("#vPuntos option"));
							} else {
								crearPoligonoPuntos($("#vPuntos option"));
							}
						}
					}
					break;
				case "subirPosicion":
					if (!$("#vPuntos option:selected").val()) {
						window.alert("Usted no ha seleccionado ningun elemento para mover \no no posee elementos en la lista.");
					} else {
						$("#vPuntos option:selected").each(function () {
							$(this).insertBefore($(this).prev());
						});
						if (getUrlVars()["t"] == "recorrido") {
							crearPolilineaPuntos($("#vPuntos option"));
						} else {
							crearPoligonoPuntos($("#vPuntos option"));
						}
					}
					break;
				case "bajarPosicion":
					if (!$("#vPuntos option:selected").val()) {
						window.alert("Usted no ha seleccionado ningun elemento para mover \no no posee elementos en la lista.");
					} else {
						$("#vPuntos option:selected").each(function () {
							$(this).insertAfter($(this).next());
						});
						if (getUrlVars()["t"] == "recorrido") {
							crearPolilineaPuntos($("#vPuntos option"));
						} else {
							crearPoligonoPuntos($("#vPuntos option"));
						}
					}
					break;
				case "seleccionarPosiciones":
					$("#vPuntos option").each(function (idx) {
						$(this).attr("selected", true);
					});
					break;
				case "dibujarPosiciones":
					if (getUrlVars()["t"] == "recorrido") {
						crearPolilineaPuntos($("#vPuntos option"));
					} else {
						crearPoligonoPuntos($("#vPuntos option"));
					}
					break;
				case "limpiarMapa":
					/*
					borrarMarcadores();
					borrarPoligonos();
					borrarMarcadoresFichas();
					borrarMarcadoresElementos();
					*/
					borrarPoligonosPuntos();
					borrarPolilineaPuntos();

					marker.setLatLng([$("#vLatitud").val(), $("#vLongitud").val()]);
					mymapa.panTo([$("#vLatitud").val(), $("#vLongitud").val()]);
					break;
				case "aceptar":
					if ($("#vPuntos").length) {
						var vPuntos = "";
						$("option", $("#vPuntos")).each(function (i) {
							var valores = $(this).attr("value").split(",");
							vPuntos += valores[0] + "," + valores[1] + "|";
						});
						returnVal = vPuntos;
					} else {
						returnVal = $("#vLatitud").val() + "," + $("#vLongitud").val();
					}
					window.parent.hidePopWin(true);
					break;
				default:
					break;
			}
		});
	}

}

function loadMapsJSAPI() {
	const googleMapsAPIKey = 'AIzaSyBhEkplWNuoX9zNY6M3Tq5FvZ76wJInf9A';
	const googleMapsAPIURI = `https://maps.googleapis.com/maps/api/js?key=${googleMapsAPIKey}&callback=runApp`;

	const script = document.createElement('script');
	script.src = googleMapsAPIURI;
	script.defer = true;
	script.async = true;

	window.runApp = runApp;

	document.head.appendChild(script);
}

function initializarMapaUbicacionGoogle(latitud, longitud) {
	const urlMarker = '../images/marker.png'
	if (!latitud) latitud = document.querySelector('input[type="text"]#vLatitud');
	if (!longitud) longitud = document.querySelector('input[type="text"]#vLongitud');

	var infowindow;
	var directionsDisplay;

	myLatlng = new google.maps.LatLng(latitud.value, longitud.value);

	var myOptions = {
		zoom: 16,
		center: myLatlng,
		zoomControl: true,
		zoomControlOptions: {
			style: google.maps.ZoomControlStyle.SMALL
		},
		mapTypeId: google.maps.MapTypeId.ROADMAP
	};

	map = new google.maps.Map(document.getElementById('mapa'), myOptions);
	directionsDisplay = new google.maps.DirectionsRenderer();
	directionsDisplay.setMap(map);

	marker = new google.maps.Marker({
		position: myLatlng,
		map: map,
		icon: urlMarker,
		draggable: true,
		title: "Aquí estoy"
	});

	infowindow = new google.maps.InfoWindow();

	google.maps.event.addListener(map, 'click', function (event) {
		marker.setMap(null);
		marker = new google.maps.Marker({
			map: map,
			icon: urlMarker,
			draggable: true,
			title: "Aquí estoy"
		});
		marker.setPosition(event.latLng);
		latitud.value = event.latLng.lat()
		longitud.value = event.latLng.lng()
		//obtenerDireccion()

		google.maps.event.addListener(marker, 'click', function (event) {
			if (marker.getAnimation() != null) {
				marker.setAnimation(null);
			} else {
				marker.setAnimation(google.maps.Animation.BOUNCE);
			}
		});

	});

	google.maps.event.addListener(marker, 'dragend', function (event) {
		latitud.value = event.latLng.lat();
		longitud.value = event.latLng.lng();
	});


	// detecta la ubicacion del usuario actual
	document.querySelector('#btn-detectar-ubicacion').addEventListener('click', e => {

		if (navigator.geolocation) {
			navigator.geolocation.getCurrentPosition((position) => {
				latitud.value = position.coords.latitude
				longitud.value = position.coords.longitude

				marker.setMap(null);
				marker = new google.maps.Marker({
					map: map,
					icon: urlMarker,
					draggable: true,
					title: "Aquí estoy"
				});
				myLatlng = new google.maps.LatLng(latitud.value, longitud.value);
				marker.setPosition(myLatlng);
				map.panTo(myLatlng);

			}, (error) => {
				console.log('ERROR(' + error.code + '): ' + error.message)
			}, { enableHighAccuracy: true, timeout: 5000, maximumAge: 0 });
		} else {
			console.log("Geolocation no es soportado por este navegador.")
		}


	})

	// detectar cambios en la ubicacion
	function cambiarUbicacion() {
		myLatlng = new google.maps.LatLng(latitud.value, longitud.value);
		marker.setPosition(myLatlng);
		map.panTo(myLatlng);
	}

	latitud.addEventListener('change', cambiarUbicacion)
	longitud.addEventListener('change', cambiarUbicacion)

	function obtenerDireccion() {
		const geocoder = new google.maps.Geocoder();

		var latlng = new google.maps.LatLng(latitud.value, longitud.value);
		geocoder.geocode({ 'latLng': latlng }, function (results, status) {
			if (status == google.maps.GeocoderStatus.OK) {
				if (results[0]) {
					console.log(results[0]);
					/*
					$.each(results[0].address_components, function(key, componente) {
						switch (componente.types[0]) {
							case "country":
								$("#codigo_pais").val(componente.short_name);
								$("#pais").val(componente.long_name);
								break;
							case "administrative_area_level_1":
								switch (componente.long_name) {
									case "Departamento de Santa Cruz":
										$("#provincia").val("Santa Cruz");
										break;
									default:
										$("#provincia").val(componente.long_name);
										break;
								}
								break;
							case "administrative_area_level_2":
								$("#departamento").val(componente.long_name);
								break;
							case "locality":
								switch (componente.long_name) {
									case "Yacuíba":
										$("#ciudad").val("Yacuiba");
										break;
									default:
										$("#ciudad").val(componente.long_name);
										break;
								}
								break;
							case "route":
								$("#direccion").val(componente.long_name + " " + $("#direccion").val());
								break;
							case "street_number":
								$("#direccion").val($("#direccion").val() + " " + componente.long_name);
								break;
							default:
								break;
						}
					});

					map.setCenter(latlng);
					marker.setPosition(latlng);

					*/

					//$("#address").val(results[0].formatted_address);
					//$("#address").val($("#direccion").val() + ", " + $("#ciudad").val() + ", " + $("#departamento").val() + ", " + $("#provincia").val() + ", " + $("#pais").val());
				} else {
					console.log("Resultado no encontrado");
				}
			} else {
				console.log("Geocoder fallo: " + status);
			}
		});

	}

}


const focusableElements = 'button:not([disabled]), [href], input:not([type="hidden"]):not([disabled]):not([readonly]), select:not([disabled]), textarea:not([style="display: none;"]):not([disabled]), [tabindex]:not([tabindex="-1"])';

document.addEventListener('keydown', (e) => {

	//console.log(e.key,e.target);

	if (e.target.tagName == 'BODY') {
		setFirstFocusableElements(document.querySelectorAll('body > .container-fluid')[0])
	}
	if (e.target.tagName == 'DIV') {
		setFirstFocusableElements(e.target.closest('.modal'))
	}

	if (e.altKey) {
		const container = document.querySelectorAll('body > .container-fluid')[0]
		const botones = [...container.querySelectorAll('button:not([disabled])')].filter(x => x.offsetWidth !== 0 || x.offsetHeight !== 0)

		let keyName = e.key
		keyName = keyName.toUpperCase()
		switch (keyName) {
			case "N": // NEW
				e.preventDefault();
				console.log('Presiono ALT + N');
				const botonNew = botones.filter(item => item.id == 'btNuevoRegistro');
				if (botonNew.length) {
					botonNew[0].dispatchEvent(new Event('click', { bubbles: true, cancelable: true }))
				} else {
					const botonNewDetalle = botones.filter(item => item.id == 'btNuevoDetalle');
					if (botonNewDetalle.length) {
						botonNewDetalle[0].dispatchEvent(new Event('click', { bubbles: true, cancelable: true }))
					}
				}
				break;
			case "V": // VIEW
				console.log('Presiono ALT + V');
				const botonView = botones.filter(item => item.id == 'btVerRegistro');
				if (botonView.length) {
					botonView[0].dispatchEvent(new Event('click', { bubbles: true, cancelable: true }))
				}
				break;
			case "M": // EDIT
				console.log('Presiono ALT + M');
				const botonEdit = botones.filter(item => item.id == 'btEditarRegistro');
				if (botonEdit.length) {
					botonEdit[0].dispatchEvent(new Event('click', { bubbles: true, cancelable: true }))
				}
				break;
			case "D": // DELETE
				e.preventDefault();
				console.log('Presiono ALT + D');
				const botonDelete = botones.filter(item => item.id == 'btEliminarRegistro');
				if (botonDelete.length) {
					botonDelete[0].dispatchEvent(new Event('click', { bubbles: true, cancelable: true }))
				}
				break;
			default:
				//console.log(e.key);
				break;
		}
	}

	const form = e.target.form ? e.target.form : e.target.closest('form')
	if (form) {
		const firstFocusableElement = form.querySelectorAll(focusableElements)[0];
		const focusableContent = [...form.querySelectorAll(focusableElements)].filter(x => x.offsetWidth !== 0 || x.offsetHeight !== 0)
		const lastFocusableElement = focusableContent[focusableContent.length - 1];

		if (e.key === 'Tab' || e.keyCode === 9 || e.key === 'ArrowDown') {
			const index = Array.prototype.indexOf.call(focusableContent, e.target);
			if (document.activeElement === lastFocusableElement) {
				firstFocusableElement.focus();
			} else {
				focusableContent[index + 1].focus();
			}
			e.preventDefault();
		}

		if (e.key === 'ArrowRight') {
			if (document.activeElement.type != 'select-one') {
				if (document.activeElement.type == 'file') {
					let event = document.createEvent("UIEvents");
					event.initUIEvent("change", true, true);
					document.activeElement.dispatchEvent(event);
				} else {
					document.activeElement.dispatchEvent(new Event('click', { bubbles: true, cancelable: true }))
				}
				e.preventDefault();
			}
		}

		if (e.key === 'Enter' || e.keyCode === 13) {

			if ((document.activeElement.type != 'submit') && (document.activeElement.type != 'reset') && (document.activeElement.type != 'button') && (document.activeElement.type != 'file')) {

				if (document.activeElement.closest('.modal') && (document.activeElement.type == 'radio')) {
					document.activeElement.dispatchEvent(new Event('click', { bubbles: true, cancelable: true }))
				} else {
					const index = Array.prototype.indexOf.call(focusableContent, e.target);
					if (document.activeElement === lastFocusableElement) {
						firstFocusableElement.focus();
					} else {
						focusableContent[index + 1].focus();
					}

				}

				e.preventDefault();
			}

		}

		if (e.key === 'ArrowUp') {
			const index = Array.prototype.indexOf.call(focusableContent, e.target);
			if (document.activeElement === firstFocusableElement) {
				lastFocusableElement.focus();
			} else {
				focusableContent[index - 1].focus();
			}
			e.preventDefault();
		}

		if (e.key === 'Insert') {
			e.preventDefault();
			const botones = [...form.querySelectorAll('button:not([disabled])')].filter(x => x.offsetWidth !== 0 || x.offsetHeight !== 0)

			const botonNew = botones.filter(item => item.id == 'btNuevoRegistro');
			if (botonNew.length) {
				botonNew[0].dispatchEvent(new Event('click', { bubbles: true, cancelable: true }))
			} else {
				const botonNewDetalle = botones.filter(item => item.id == 'btNuevoDetalle');
				if (botonNewDetalle.length) {
					botonNewDetalle[0].dispatchEvent(new Event('click', { bubbles: true, cancelable: true }))
				}
			}
		}

		if (e.altKey) {

			let keyName = e.key
			keyName = keyName.toUpperCase()
			switch (keyName) {
				case "G": // SUBMIT
					console.log('Presiono ALT + G');
					const botonSubmit = focusableContent.filter(word => word.type == 'submit');
					if (botonSubmit.length) {
						botonSubmit[0].dispatchEvent(new Event('click', { bubbles: true, cancelable: true }))
					}
					break;
				case "C": // RESET
					console.log('Presiono ALT + C');
					const botonReset = focusableContent.filter(word => word.type == 'reset');
					if (botonReset.length) {
						botonReset[0].dispatchEvent(new Event('click', { bubbles: true, cancelable: true }))
					} else {
						const boton = focusableContent.filter(word => word.type == 'button');
						boton[0].dispatchEvent(new Event('click', { bubbles: true, cancelable: true }))
					}
					break;
				case "B": // SEARCH
					console.log('Presiono ALT + B');
					const botonBuscarCliente = focusableContent.filter(item => item.id == 'btBuscarClienteVentana');
					if (botonBuscarCliente.length) {
						botonBuscarCliente[0].dispatchEvent(new Event('click', { bubbles: true, cancelable: true }))
					}
					break;
				default:
					//console.log(e.key);
					break;
			}

		}

	}

});

function setFirstFocusableElements(target) {
	const focusableElements = 'button:not([disabled]), input:not([type="hidden"]):not([disabled]):not([readonly]), select:not([disabled]), textarea:not([style="display: none;"]):not([disabled]), [tabindex]:not([tabindex="-1"])';

	const form = [...target.querySelectorAll('form')].filter(x => x.offsetWidth !== 0 || x.offsetHeight !== 0)
	//const form = target.querySelectorAll('form')[0]
	if (form.length) {
		const firstFocusableElement = form[0].querySelectorAll(focusableElements)[0];
		const focusableContent = [...form[0].querySelectorAll(focusableElements)].filter(x => x.offsetWidth !== 0 || x.offsetHeight !== 0)
		const lastFocusableElement = focusableContent[focusableContent.length - 1];

		firstFocusableElement.focus();

	}

}
