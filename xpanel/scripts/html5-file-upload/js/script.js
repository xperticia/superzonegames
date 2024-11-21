$(function(){
	var dropbox = $('#dropbox'),
		message = $('.message', dropbox);
	
	dropbox.filedrop({
		// The name of the $_FILES entry:
		paramname:'pic',
		
		maxfiles: 100,
    	maxfilesize: 2,
		url: 'subirArchivos.php',
		
		uploadFinished:function(i,file,response){
			$.data(file).addClass('done');
			// response is the JSON object that subirArchivos.php returns
			$("input[id='id_foto"+i+"']").val(response.status);
		},
		
    	error: function(err, file) {
			switch(err) {
				case 'BrowserNotSupported':
					showMessage('Su navegador no soporta carga de archivos con HTML5!');
					break;
				case 'TooManyFiles':
					alert('Demasiados archivos! Por favor seleccione no mas de 10! (configurable)');
					break;
				case 'FileTooLarge':
					alert(file.name+' es muy largo! Por favor cargue archivos inferiores a 2MB (configurable).');
					break;
				default:
					break;
			}
		},
		
		// Called before each upload is started
		beforeEach: function(file){
			if(!file.type.match(/^image\//)){
				alert('Only images are allowed!');
				
				// Returning false will cause the
				// file to be rejected
				return false;
			}
		},
		
		uploadStarted:function(i, file, len){
			createImage(file,i);
		},
		
		progressUpdated: function(i, file, progress) {
			$.data(file).find('.progress').width(progress);
		}
    	 
	});
	
	var template = '<div class="preview">'+
						'<span class="imageHolder">'+
							'<img />'+
							'<span class="uploaded"></span>'+
						'</span>'+
						'<div class="progressHolder">'+
							'<div class="progress"></div>'+
							'<input type="hidden" name="id_foto[]" value="" />'+
							'<textarea name="imageText[]" rows="5" cols="20" placeholder="descripci&oacute;n de la imagen"></textarea>'+
						'</div>'+
					'</div>'; 

	function createImage(file,i){

		var preview = $(template), 
			image = $('img', preview),
			campo = $("input[name='id_foto[]']", preview),
			texto = $("textarea[name='imageText[]']", preview);

		var reader = new FileReader();
		
		//	modifico el identificador del campo
		campo.attr("id","id_foto"+i);
		if($("#obtenerDescripcion").length){
			if ($("#obtenerDescripcion").attr("checked")){
				texto.val(file.name);
			}
		}

		image.width = 100;
		image.height = 100;

		reader.onload = function(e){
			
			// e.target.result holds the DataURL which
			// can be used as a source of the image:
			
			image.attr('src',e.target.result);
		};
		
		// Reading the file as a DataURL. When finished,
		// this will trigger the onload function above:
		reader.readAsDataURL(file);

		message.hide();
		preview.appendTo(dropbox);
		
		// Associating a preview container
		// with the file, using jQuery's $.data():
		
		$.data(file,preview);
	}

	function showMessage(msg){
		message.html(msg);
	}

});