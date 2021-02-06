const regular_productTitle=/[^a-zA-Zа-яА-ЯёЁ0-9_ \-«»№]/g;
const regular_productNumber=/[^0-9]/g;
const regular_productText=/[^a-zA-Zа-яА-ЯёЁ0-9_ \n\-(){}\[\]\/.,;:@!?#№=+«»]/g;
const regular_productAddress=/[^a-zA-Zа-яА-ЯёЁ0-9_ \-\/.,]/g;

const regular_productAddress_coordinates=/[^0-9,.]/g;

function check_productTitle(productTitle){
    productTitle=productTitle.replace(regular_productTitle,'');
    productTitle=productTitle.substr(0, 60);
    if(productTitle.toString().length<3){
        return {title: productTitle, color : colorError, boolError: true, textError : 'не заполнено поле "Название объявления"'};
    }else{
        return {title: productTitle, color : colorNoError, boolError: false, textError : ''};
    }
}


function check_productCity(productCity){
    productCity=productCity.replace(regular_productNumber,'');
    productCity=Number.parseInt(productCity.substr(0, 3));
    if(isNaN(productCity)){productCity=0;}
    if(productCity<1){
        return {city: productCity, color : colorError, boolError: true, textError : 'не выбрано поле "Город"'};
    }else{
        return {city: productCity, color : colorNoError, boolError: false, textError : ''};
    }
}

function check_productSuburb(productSuburb){
    productSuburb=productSuburb.replace(regular_productNumber,'');
    productSuburb=Number.parseInt(productSuburb.substr(0, 3));
    if(isNaN(productSuburb)){productSuburb=0;}
    if(productSuburb<1){
        return {suburb: productSuburb, color : colorError, boolError: true, textError : 'не выбрано поле "Пригород"'};
    }else{
        return {suburb: productSuburb, color : colorNoError, boolError: false, textError : ''};
    }
}

function check_productGuest(productGuest){
    productGuest=productGuest.replace(regular_productNumber,'');
    productGuest=Number.parseInt(productGuest.substr(0, 3));
    if(isNaN(productGuest)){productGuest=0;}
    if(productGuest<1){
        return {guest: productGuest, color : colorError, boolError: true, textError : 'не выбрано поле "Вместительность номера"'};
    }else{
        return {guest: productGuest, color : colorNoError, boolError: false, textError : ''};
    }
}

function check_productTypeHousing(productTypeHousing){
    productTypeHousing=productTypeHousing.replace(regular_productNumber,'');
    productTypeHousing=Number.parseInt(productTypeHousing.substr(0, 3));
    if(isNaN(productTypeHousing)){productTypeHousing=0;}
    if(productTypeHousing<1){
        return {typeHousing: productTypeHousing, color : colorError, boolError: true, textError : 'не выбрано поле "Тип жилья"'};
    }else{
        return {typeHousing: productTypeHousing, color : colorNoError, boolError: false, textError : ''};
    }
}

function check_productDistance(productDistance){
    productDistance=productDistance.replace(regular_productNumber,'');
    productDistance=Number.parseInt(productDistance.substr(0, 3));
    if(isNaN(productDistance)){productDistance=0;}
    if(productDistance<1){
        return {distance: productDistance, color : colorError, boolError: true, textError : 'не выбрано поле "До моря"'};
    }else{
        return {distance: productDistance, color : colorNoError, boolError: false, textError : ''};
    }
}

function check_productAdOwner(productAdOwner){
    productAdOwner=productAdOwner.replace(regular_productNumber,'');
    productAdOwner=Number.parseInt(productAdOwner.substr(0, 3));
    if(isNaN(productAdOwner)){productAdOwner=0;}
    if(productAdOwner<1){
        return {adOwner: productAdOwner, color : colorError, boolError: true, textError : 'не выбрано поле "Владелец объявления"'};
    }else{
        return {adOwner: productAdOwner, color : colorNoError, boolError: false, textError : ''};
    }
}

function check_productRules(productRules){
    productRules=productRules.replace(regular_productText,'');
    productRules=productRules.substr(0, 2000);
    if(productRules.toString().length<3){
        return {rules: productRules, color : colorError, boolError: true, textError : 'не заполнено поле "Правила и ограничения"'};
    }else{
        return {rules: productRules, color : colorNoError, boolError: false, textError : ''};
    }
}

function check_productDescription(productDescription){
    productDescription=productDescription.replace(regular_productText,'');
    productDescription=productDescription.substr(0, 2000);
    if(productDescription.toString().length<3){
        return {description: productDescription, color : colorError, boolError: true, textError : 'не заполнено поле "Описание"'};
    }else{
        return {description: productDescription, color : colorNoError, boolError: false, textError : ''};
    }
}

function check_productAddress(productAddress){
    productAddress=productAddress.replace(regular_productAddress,'');
    productAddress=productAddress.substr(0, 200);
    if(productAddress.toString().length<3){
        return {address: productAddress, color : colorError, boolError: true, textError : 'не заполнено поле "Адрес"'};
    }else{
        return {address: productAddress, color : colorNoError, boolError: false, textError : ''};
    }
}

function check_productAddressCoordinates(productAddressCoordinates){
    productAddressCoordinates=productAddressCoordinates.replace(regular_productAddress_coordinates,'');
    productAddressCoordinates=Number.parseFloat(productAddressCoordinates.substr(0, 10));
    if(isNaN(productAddressCoordinates)){productAddressCoordinates=0;}
    if(productAddressCoordinates<35){
        return {addressCoordinates: productAddressCoordinates, color : colorError, boolError: true, textError : 'не верная координата адреса'};
    }else{
        return {addressCoordinates: productAddressCoordinates, color : colorNoError, boolError: false, textError : ''};
    }
}

function check_productContacts(productContacts){
    productContacts=productContacts.replace(regular_productText,'');
    productContacts=productContacts.substr(0, 2000);
    if(productContacts.toString().length<3){
        return {contacts: productContacts, color : colorError, boolError: true, textError : 'не заполнено поле "Контакты"'};
    }else{
        return {contacts: productContacts, color : colorNoError, boolError: false, textError : ''};
    }
}

function check_productPrice(productPrice){
    productPrice=productPrice.replace(regular_productNumber,'');
    productPrice=Number.parseInt(productPrice.substr(0, 6));
    if(isNaN(productPrice)){productPrice=0;}
    if(productPrice<50 && productPrice!=='' && productPrice!==0){
        return {price: productPrice, color : colorError, boolError: true, textError : 'не корректная цена'};
    }else{
        return {price: productPrice, color : colorNoError, boolError: false, textError : ''};
    }
}

function check_productPriceMonth(productPriceMonth){
    productPriceMonth=productPriceMonth.replace(regular_productNumber,'');
    productPriceMonth=Number.parseInt(productPriceMonth.substr(0, 3));
    if(isNaN(productPriceMonth)){productPriceMonth=0;}
    if(productPriceMonth<1){
        return {priceMonth: productPriceMonth, color : colorError, boolError: true, textError : 'не выбрано поле "Цена в"'};
    }else{
        return {priceMonth: productPriceMonth, color : colorNoError, boolError: false, textError : ''};
    }
}