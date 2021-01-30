$(function() {
	const formButton=$('.form-button');
	let queue = {};

	function checkLimitPhoto(){
		if(document.querySelectorAll(".dragImg").length>=1){
			formButton.prop('disabled', false);
		}else{
			formButton.prop('disabled', true);
		}
	}
	checkLimitPhoto();


	$("#button-selectFiles").on('change',function(){
		let statusError=[], errorPhoto='';
		for(let i=0, n=this.files.length; i<n; i++){
			let file=this.files[i];
			let url=URL.createObjectURL(file);

			if(file.type!=="image/jpg" && file.type!=="image/jpeg" && file.type!=="image/png"){
				errorPhoto=file.name+': не корректный формат изображения <br>';
			}
			if(file.size>=10485760){//10мб
				errorPhoto=file.name+': размер файла превышает 10мб <br>';
			}


			if(errorPhoto===''){
				let image = new Image();
				let h,w;
				image.src = url;
				image.onload=function(){
					w=this.width; h=this.height;
					let countPhoto=document.querySelectorAll(".dragImg").length;
					let rand=Math.ceil((Math.random() * 10000));
					let newId='newImg'+rand;
					if(w<=12500 && w>=200 && h<=12500 && h>=200 && countPhoto<19){
						if(h>=w){
							w=w*(200/h);
							h=200;
						}else{
							h=h*(200/w);
							w=200;
						}
						$('#box-img').append('<div class="newBox boxImg" id="newBox'+rand+'" draggable="true">' +
							'<img src="'+url+'" draggable="true" width="'+w+'" height="'+h+'" class="dragImg img" id="newImg'+rand+'" alt="Новое изображение"/>' +
							'<span id="deleteNew'+rand+'" class="deletePhoto" >x</span></div>');

						queue[newId] = file;

						let changeImg = document.querySelectorAll(".dragImg");
						countPhoto=changeImg.length;
						for(let i=0; i<countPhoto; i++){
							changeImg[i].ondragstart=f_drag;
							changeImg[i].ondrag=f_ondrag;
							changeImg[i].ondragend=f_ondragend;
						}
						checkLimitPhoto();
					}else{
						statusError.push(file.name+': недопустимое разрешение изображения');
					}
				}
			}
			statusError.push(errorPhoto);
			errorPhoto='';
		}
		setTimeout(function(){
			$('.info-text').html(statusError.join("<br>"));
		}, 1700);

	});

	$("#box-img").on( "click", ".deletePhoto", function() {
		$(this.parentNode).remove();
		delete queue[$(this).siblings().attr('id')];
		checkLimitPhoto();
	});



	formButton.on('click',function(){
		let formData = new FormData();
		let countPhoto=0;
		let id='';
		$('.dragImg').each(function() {
			id=$(this).prop('id');
			formData.append('images[]', queue[id]);
			countPhoto++;
			if(countPhoto>=15){return false;}
		});
		$(".info-text").html("Началась загрука фотографий. Пожалуйста дождитесь результата загрузки...");
		$.ajax({
			 url: 'app/add2.php',
			type: 'POST',
			data: formData,
			async: true,
			success: function (res) {
				$('#getApp').html(res);
			},

			cache: false,
			contentType: false,
			processData: false
		});
	});
});