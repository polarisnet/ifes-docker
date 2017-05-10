(function($){
	var cSpeed=6;
	var cWidth=20;
	var cHeight=20;
	var cTotalFrames=75;
	var cFrameWidth=20;
	var cImageSrc='sprites.gif';
	
	var cImageTimeout=false;
	var cIndex=0;
	var cXpos=0;
	var cPreloaderTimeout=false;
	var SECONDS_BETWEEN_FRAMES=0;
	var obj;
	
	function startAnimation(){
		obj.css({'backgroundImage': 'url('+cImageSrc+')'},{'width': cWidth+'px'},{'height': cHeight+'px'});
	
		//FPS = Math.round(100/(maxSpeed+2-speed));
		FPS = Math.round(100/cSpeed);
		SECONDS_BETWEEN_FRAMES = 1 / FPS;
		cPreloaderTimeout = setTimeout(continueAnimation(), SECONDS_BETWEEN_FRAMES/1000);
	}
	
	function continueAnimation(){
		cXpos += cFrameWidth;
		//increase the index so we know which frame of our animation we are currently on
		cIndex += 1;
		 
		//if our cIndex is higher than our total number of frames, we're at the end and should restart
		if (cIndex >= cTotalFrames) {
			cXpos =0;
			cIndex=0;
		}
		obj.css({'backgroundPosition': (-cXpos)+'px 0'});
		cPreloaderTimeout=setTimeout(continueAnimation(), SECONDS_BETWEEN_FRAMES*1000);
	}
	
	function stopAnimation(){
		clearTimeout(cPreloaderTimeout);
		cPreloaderTimeout=false;
	}
	
	function imageLoader(s, fun){
		clearTimeout(cImageTimeout);
		cImageTimeout=0;
		genImage = new Image();
		genImage.onload = function (){cImageTimeout = setTimeout(fun, 0)};
		//genImage.onerror = new Function('alert(\'Could not load the image\')');
		genImage.src = s;
	}
	
	$.fn.ozpreloader = function(opt){
		obj = this;
		new imageLoader(cImageSrc, startAnimation());
	}
})(jQuery);