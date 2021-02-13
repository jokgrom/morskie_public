var colorError='#fcc', colorNoError='#fff';
$(function(){
	const formProductTitle = $('#product-title');
	const formFilterCity = $('.filter-city');
	const formFilterSuburb = $('.filter-suburb');
	const formProductDescription = $('#product-description');
	const formProductEntertainmentPrice =$(".productEntertainment-price");
	const formProductAddress = $('#product-address');
	const formProductContacts = $('#product-contacts');



	formProductTitle.on("keyup",function(){
		var product=check_productTitle(formProductTitle.val());
		formProductTitle.val(product.title);
		if(product.boolError===false){formProductTitle.css('backgroundColor',  product.color);}
	});

	formFilterCity.on("change",function(){
		var product=check_productCity(formFilterCity.val());
		formFilterCity.val(product.city);
		if(product.boolError===false){formFilterCity.css('backgroundColor',  product.color);}
		$.get('/root/files/update-location.php',  {city: product.city}, function(data) {
			$('.filter-suburb').html(data);
		});
	});

	formFilterSuburb.on("change",function(){
		var product=check_productSuburb(formFilterSuburb.val());
		formFilterSuburb.val(product.suburb);
		if(product.boolError===false){formFilterSuburb.css('backgroundColor',  product.color);}
	});

	formProductDescription.on("keyup",function(){
		var product=check_productDescription(formProductDescription.val());
		formProductDescription.val(product.description);
		if(product.boolError===false){formProductDescription.css('backgroundColor',  product.color);}
	});

	formProductEntertainmentPrice.on("keyup",function(){
		var product=check_productEntertainmentPrice($(this).val());
		$(this).val(product.entertainmentPrice);
		if(product.boolError===false){$(this).css('backgroundColor',  product.color);}
	});

	formProductAddress.on("keyup",function(){
		var product=check_productAddress(formProductAddress.val());
		formProductAddress.val(product.address);
		if(product.boolError===false){formProductAddress.css('backgroundColor',  product.color);}
	});
	formProductAddress.on("change",function(){
		var product=check_productAddress(formProductAddress.val());
		if(product.boolError===false){
			$.get('/root/files/verify-address.php',  {product_address: product.address}, function(data) {
				$('#mapbox').html(data);
			});
		}
	});

	formProductContacts.on("keyup",function(){
		var product=check_productContacts(formProductContacts.val());
		formProductContacts.val(product.contacts);
		if(product.boolError===false){formProductContacts.css('backgroundColor',  product.color);}
	});



	$('#GO').on("click",function(){
		var statusError=[], productCheck;
		var product={
			"title" : '',
			"city" : '',
			"suburb" : '',
			"description" : '',
			"price" : '',
			"addressLatitude" : '',
			"addressLongitude" : '',
			"address" : '',
			"contacts" : ''
		};
		function statusCheck(form,productCheck){
			form.css('backgroundColor',  productCheck.color);
			if(productCheck.boolError===true){statusError.push(productCheck.textError+'<br>');}
		}
		//делаем проверку и собираем данные
		productCheck=check_productTitle(formProductTitle.val());
		statusCheck(formProductTitle,productCheck);
		product['title']=productCheck.title;

		productCheck=check_productCity(formFilterCity.val());
		statusCheck(formFilterCity,productCheck);
		product['city']=productCheck.city;

		productCheck=check_productSuburb(formFilterSuburb.val());
		statusCheck(formFilterSuburb,productCheck);
		product['suburb']=productCheck.suburb;

		productCheck=check_productDescription(formProductDescription.val());
		statusCheck(formProductDescription,productCheck);
		product['description']=productCheck.description;

		productCheck=check_productEntertainmentPrice(formProductEntertainmentPrice.val());
		statusCheck(formProductEntertainmentPrice,productCheck);
		product['EntertainmentPrice']=productCheck.entertainmentPrice;

		productCheck=check_productAddress(formProductAddress.attr('address'));
		statusCheck(formProductAddress,productCheck);
		product['address']=productCheck.address;

		productCheck=check_productAddressCoordinates(formProductAddress.attr('addressLatitude'));
		statusCheck(formProductAddress,productCheck);
		product['addressLatitude']=productCheck.addressCoordinates;

		productCheck=check_productAddressCoordinates(formProductAddress.attr('addressLongitude'));
		statusCheck(formProductAddress,productCheck);
		product['addressLongitude']=productCheck.addressCoordinates;


		productCheck=check_productContacts(formProductContacts.val());
		statusCheck(formProductContacts,productCheck);
		product['contacts']=productCheck.contacts;


		// собираем данные по удобствам
		$('.filter-check').each(function() {
			if(this.checked){//если чек выбран
				product['listEntertainment']=$(this).val();
			}
        });

		// если ошибок нет то отправляем запрос
		if(statusError.length>0){
			$(".info-text").html(statusError.join(""));
		}else{
			$(".info-text").html('');
			$.post('app/add1.php',  {product}, function(data) {
				$('#getApp').html(data);
			});
		}
	});
});