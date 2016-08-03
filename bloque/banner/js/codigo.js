$(document).ready(function() {
		//ADD CLASS "ON" TO THE DIVS
		$('.birds-path').addClass("on");
		$('.birds').addClass("on");
		//MOVING BLOCK THAT CONTAINTS THE ANIMATION
		var pause = 0;
		function birds() 
		{
			var element = ".birds.on";
			var interval = 27000;
			var left = 10;
			var away = 100;
			if(pause == 0)
			{
				pause = 1;
				$(element).css("margin-left", left + "px").animate({marginLeft: away + "%"}, interval, 'linear', function()
				{
					$(element).css("margin-left", left + "px");
					pause = 0;
					birds();
				});
			}
		}


		birds();
		//BIRDS SPRITE ANIMATION
		var recursion = function(toggle) 
		{
			setTimeout(function() 
			{
				switch(toggle) 
				{
					case 1:toggle=0;$(".birds.on").css({backgroundPosition: '-167px 0px'});break;
					case 2:toggle=1;$(".birds.on").css({backgroundPosition: '-334px 0px'});break;
					case 3:toggle=2;$(".birds.on").css({backgroundPosition: '-501px 0px'});break;
					case 4:toggle=3;$(".birds.on").css({backgroundPosition: '-668px 0px'});break;
					case 5:toggle=4;$(".birds.on").css({backgroundPosition: '-835px 0px'});break;
					case 6:toggle=5;$(".birds.on").css({backgroundPosition: '-1002px 0px'});break;
					case 7:toggle=6;$(".birds.on").css({backgroundPosition: '-1169px 0px'});break;
					default:toggle=7;$(".birds.on").css({backgroundPosition: '0px 0px'});break;	
				}
				recursion(toggle);
			},80);
		}
		recursion(0);	
	});
