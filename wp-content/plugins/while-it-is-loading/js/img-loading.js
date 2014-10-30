function isElementInViewport(e){
	var top = e.offsetTop;
	var left = e.offsetLeft;
	var width = e.offsetWidth;
	var height = e.offsetHeight;
	while(e.offsetParent){
		e = e.offsetParent;
		top += e.offsetTop;
		left += e.offsetLeft;
	}
	return (
		top < (window.pageYOffset + window.innerHeight) &&
		left < (window.pageXOffset + window.innerWidth) &&
		(top + height) > window.pageYOffset &&
		(left + width) > window.pageXOffset
	);
}
function cargarLento(){
	var elements = document.querySelectorAll("[data-src]");
	for(img in elements){
		if(elements.hasOwnProperty(img)){
			if(isElementInViewport(elements[img])){
				console.log("Debemos pedir ya a " + elements[img].getAttribute("data-src"));
				elements[img].src = elements[img].getAttribute("data-src");
				elements[img].removeAttribute("data-src");
			}
		}
	}
}
window.addEventListener("DOMContentLoaded",cargarLento,false);
window.addEventListener("resize",cargarLento,false);
window.addEventListener("touchmove",cargarLento,false);
window.addEventListener("scroll",cargarLento,false);