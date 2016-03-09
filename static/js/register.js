var root="http://bonappetit.encoding-ideas.com"
//root="www.encoding-ideas.com/restaurants"
function bgBody(){
	var images = ['#00B6A9 url("'+root+'/static/img/fg-1.png")','#E6E6E6 url("'+root+'/static/img/fg-2.png")','#7F794A url("'+root+'/static/img/fg-3.png")'];
document.body.style.background=images[Math.floor(Math.random() * images.length)] + ' no-repeat left bottom';
document.body.style.backgroundSize='30% auto';
}