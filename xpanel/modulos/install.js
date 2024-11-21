/* Script Install XPANEL 3   */
/* -------------------------- */

$(function() {

	if($("#signup").length) {
		$.getScript("../scripts/h5f/h5f.js",function(){
			H5F.setup(document.getElementById("signup"));
		});
	};

	if ($("a[rel=popover]").length) {
		$("a[rel=popover]")
			.popover({
				offset: 10
			})
			.click(function(e) {
				e.preventDefault()
			})
	}

	if ($("input[rel=popover]").length) {
		$("input[rel=popover]")
			.popover({
				offset: 10
			})
			.click(function(e) {
				e.preventDefault()
			})
	}

	if ($("select[rel=popover]").length) {
		$("select[rel=popover]")
			.popover({
				offset: 10
			})
			.click(function(e) {
				e.preventDefault()
			})
	}

	if ($("textarea[rel=popover]").length) {
		$("textarea[rel=popover]")
			.popover({
				offset: 10
			})
			.click(function(e) {
				e.preventDefault()
			})
	}

	if($("#sitio_plantilla").length) {
		$('#sitio_plantilla').change(function() {
			var valor = $(this).val();
			$('#sitio_estilo').empty();
			$('#sitio_estilo').append("<option value=''>&laquo;-- seleccionar plantilla</option>");

			$.getJSON('install_obtenerDatos.php?t=estilos&valor=' + valor, function(data) {
				$.each(data, function(key, val) {
					if ($("#old_sitio_estilo").val() == val.value) {
						$('#sitio_estilo').append("<option value='"+val.value+"' selected>" + val.label + "</option>");
					} else {
						$('#sitio_estilo').append("<option value='"+val.value+"'>" + val.label + "</option>");
					}
                });     
			});
		});
	}

	$('#sitio_plantilla').change();

});

function getUrlVars() {
    var vars = [], hash;
    var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
    for(var i = 0; i < hashes.length; i++) {
        hash = hashes[i].split('=');
        vars.push(hash[0]);
        vars[hash[0]] = hash[1];
    }
    return vars;
};
