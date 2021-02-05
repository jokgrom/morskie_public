var boxImg=document.querySelector('#box-img');

boxImg.ondragover=f_allowDrop; //срабатывает, когда элемент перемещают над допустимой зоной для переноса
boxImg.ondrop=f_drop; //срабатывает после того, как перетаскиваемый элемент опустился на объект перетаскивания

function f_allowDrop(event) {
	event.preventDefault(); //отмена стандартных событий
}
function f_ondrag(){
	this.parentNode.style.opacity='0.4';
	this.style.boxShadow='0 0 8px 2px #000';
}
function f_ondragend(){
	this.parentNode.style.opacity='1';
	this.style.boxShadow='none';
}
function f_drag(event){
	event.dataTransfer.setData('id_transfer_id', this.parentNode.id); //в дататрансфер добавляем значение ид перетаскиваемого элемента
}
function f_drop(event){
	event.preventDefault(); //отмена стандартных событий
	// this.style.opacity='1';
	var id_place =event.target.id;
	var tagName=event.target.tagName;
	var itemId=event.dataTransfer.getData('id_transfer_id'); //получаем значения(это у нас ид элемента) у дататрансфер по заданному ключу id_transfer
	var newBox=document.getElementById(itemId);
	if(newBox!=null){
		if(tagName==='IMG'){
			var place=document.querySelector('#'+event.target.parentNode.id);
			if(event.offsetX>(place.clientWidth/2)){
				place.after(newBox);//после картинкой
			}else{
				place.before(newBox);//после картинки
			}
		}else if(tagName==='SPAN'){
			var place=document.querySelector('#'+event.target.parentNode.id);
			place.after(newBox);
		}else{
			var place=document.querySelector('#'+id_place);
			place.append(newBox);
		}
	}
}
