$(document).ready(function(){	
	var dropbox;  

var oprand = {
	dragClass : "active",
    on: {
        load: function(e, file) {
			// check file type
			var imageType = /image.*/;
			if (!file.type.match(imageType)) {  
			  alert("File \""+file.name+"\" is not a valid image file");
			  return false;	
			} 
			
			// check file size
			if (parseInt(file.size / 1024) > 2050) {  
			  alert("File \""+file.name+"\" is too big.Max allowed size is 2 MB.");
			  return false;	
			} 

			create_box(e,file);
    	},
    }
};

	FileReaderJS.setupInput(document.getElementById('imagens'), oprand);
	
});

create_box = function(e,file){
	var rand = Math.floor((Math.random()*100000)+3);
	var imgName = file.name; // not used, Irand just in case if user wanrand to print it.
	var src		= e.target.result;

	var template = '<div class="col-xs-6 col-sm-3 box-img col-md-4 col-lg-2" style="text-align: center;" id="'+rand+'">';
	template += '<span class="preview" id="previ-'+rand+'"><a href="#" class="thumbnail"><img src="'+src+'"></a><span class="overlay"><img src="/images/big_blue_loading_circle.gif" alt=""></span>';
	template += '</span>';
	template += '<div class="progress progress-sm" id="pro-'+rand+'">';
	template += '<div class="progress-bar progress-bar-primary" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"></div>';
	template += '</div>';

	if($("#hall-imagens").html() == null)
		$("#hall-imagens").html(template);
	else
		$("#hall-imagens").append(template);
	
	// upload image
	upload(file,rand);
}

upload = function(file,rand){
	// now upload the file
	var xhr = new Array();
	xhr[rand] = new XMLHttpRequest();
	xhr[rand].open("post", $('#input-image').data('url'), true);
	//xhr[rand].responseType = 'json';

	var fd = new FormData();
	fd.append('foto', file);

	xhr[rand].upload.addEventListener("progress", function (event) {
		if (event.lengthComputable) {

			value = (event.loaded / event.total) * 100;
			$(".progress[id='pro-"+rand+"'] .progress-bar").css("width",(value) + "%");
			$(".progress[id='pro-"+rand+"'] .progress-bar").attr('aria-valuenow', value.toFixed(2) + "%");
			$(".progress[id='pro-"+rand+"'] .progress-bar").html(value + "%");
			//$("#previ-"+rand+" .overlay .updone").html(value + "%");

		} else {
            sweetAlert("Falha!", "Fala ao computar o andamento do upload!", "error");
		}
	}, false);

	xhr[rand].onreadystatechange = function (oEvent) {
	  if (xhr[rand].readyState === 4) {
		if (xhr[rand].status === 200) {

		  $(".progress[id='"+rand+"'] span").css("width","100%");
		  $(".preview[id='"+rand+"']").find(".updone").html("100%");
		  $("#previ-"+rand+" .overlay").css("display","none");

            $("#hall-imagens").append(xhr[rand].response);

          	$("#"+rand).remove();

			//Exibe o botão de apagar todas
			if($('#deleteAll').hasClass('hidden')){
				$('#deleteAll').removeClass('hidden');
			}

		} else if(xhr[rand].status === 500) {

            $("#"+rand).remove();
            var message = JSON.parse(xhr[rand].response);
            sweetAlert("Erro!", message['message'], "error");

		}
	  }  
	};

	// Send the file (doh)
	xhr[rand].send(fd);
}