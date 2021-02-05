const colorError='#fcc', colorNoError='#fff';
$(function(){
	const formProductTitle = $('#product-title');
	const formFilterCity = $('.filter-city');
	const formFilterSuburb = $('.filter-suburb');
	const formFilterGuest = $('.filter-guest');
	const formFilterTypeHousing = $('.filter-typeHousing');
	const formFilterDistance = $('.filter-distance');
	const formFilterAdOwner = $('.filter-adOwner');
	const formProductRules = $('#product-rules');
	const formProductDescription = $('#product-description');
	const formProductAddress = $('#product-address');
	const formProductContacts = $('#product-contacts');
	const formProductPrice =$(".product-price");




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

	formFilterGuest.on("change",function(){
		var product=check_productGuest(formFilterGuest.val());
		formFilterGuest.val(product.guest);
		if(product.boolError===false){formFilterGuest.css('backgroundColor',  product.color);}
	});

	formFilterTypeHousing.on("change",function(){
		var product=check_productTypeHousing(formFilterTypeHousing.val());
		formFilterTypeHousing.val(product.typeHousing);
		if(product.boolError===false){formFilterTypeHousing.css('backgroundColor',  product.color);}
	});

	formFilterDistance.on("change",function(){
		var product=check_productDistance(formFilterDistance.val());
		formFilterDistance.val(product.distance);
		if(product.boolError===false){formFilterDistance.css('backgroundColor',  product.color);}
	});

	formFilterAdOwner.on("change",function(){
		var product=check_productAdOwner(formFilterAdOwner.val());
		formFilterAdOwner.val(product.adOwner);
		if(product.boolError===false){formFilterAdOwner.css('backgroundColor',  product.color);}
	});

	formProductRules.on("keyup",function(){
		var product=check_productRules(formProductRules.val());
		formProductRules.val(product.rules);
		if(product.boolError===false){formProductRules.css('backgroundColor',  product.color);}
	});

	formProductDescription.on("keyup",function(){
		var product=check_productDescription(formProductDescription.val());
		formProductDescription.val(product.description);
		if(product.boolError===false){formProductDescription.css('backgroundColor',  product.color);}
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

	formProductPrice.on("keyup",function(){
		var product=check_productPrice($(this).val()); //переделать, не подъодит
		$(this).val(product.price);
		if(product.boolError===false){$(this).css('backgroundColor',  product.color);}
	});



	$('#GO').on("click",function(){
		var statusError=[], productCheck;
		var product={
			"id": $('#GO').attr('productId'),
			"title" : '',
			"city" : '',
			"suburb" : '',
			"guest" : '',
			"typeHousing" : '',
			"distance" : '',
			"adOwner" : '',
			"conveniences": '',
			"rules" : '',
			"description" : '',
			"addressLatitude" : '',
			"addressLongitude" : '',
			"address" : '',
			"contacts" : '',
			"price" : ''
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

		productCheck=check_productGuest(formFilterGuest.val());
		statusCheck(formFilterGuest,productCheck);
		product['guest']=productCheck.guest;

		productCheck=check_productTypeHousing(formFilterTypeHousing.val());
		statusCheck(formFilterTypeHousing,productCheck);
		product['typeHousing']=productCheck.typeHousing;

		productCheck=check_productDistance(formFilterDistance.val());
		statusCheck(formFilterDistance,productCheck);
		product['distance']=productCheck.distance;

		productCheck=check_productAdOwner(formFilterAdOwner.val());
		statusCheck(formFilterAdOwner,productCheck);
		product['adOwner']=productCheck.adOwner;

		productCheck=check_productRules(formProductRules.val());
		statusCheck(formProductRules,productCheck);
		product['rules']=productCheck.rules;

		productCheck=check_productDescription(formProductDescription.val());
		statusCheck(formProductDescription,productCheck);
		product['description']=productCheck.description;

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
		var product_conveniences=[];
		$('.filter-check').each(function() {
			if(this.checked){//если чек выбран
				if(!product_conveniences.includes(this.value)){//если его ещё нету в массиве
					product_conveniences.push(this.value);
				}
			}else{
				if(product_conveniences.includes(this.value)){
					product_conveniences.splice(product_conveniences.indexOf(this.value), 1);
				}
			}
		});
		if(product_conveniences.length<3){statusError.push('выбранно мало удобств(минимум 3) <br>');}
		product['conveniences']=product_conveniences;

		//собираем данные о цене
		var product_price=[];
		var nanMonth=true;
		formProductPrice.each(function() {
			productCheck=check_productPrice(this.value);
			$(this).css('backgroundColor',  productCheck.color);
			if(productCheck.boolError===true){
				statusError.push(productCheck.textError+'<br>');
			}else{
				if(productCheck.price!==0){nanMonth=false;}
			}
			product_price.push(productCheck.price);
		});
		if(nanMonth){statusError.push('не заполнено поле "Цена"(минимум 1) <br>');}
		product['price']=product_price;


		// если ошибок нет то отправляем запрос
		if(statusError.length>0){
			$(".info-text").html(statusError.join(""));
		}else{
			$(".info-text").html('');
			$.post('app/edit1.php',  {product}, function(data) {
				$('#getApp').html(data);
			});
		}
	});
});