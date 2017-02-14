/* Sidebar Menu*/
$(document).ready(function () {
  $('.nav > li > a').click(function(){
    if ($(this).attr('class') != 'active'){
      $('.nav li ul').slideUp();
      $(this).next().slideToggle();
      $('.nav li a').removeClass('active');
      $(this).addClass('active');
    }
  });
});

/* Top Stats Show Hide */
$(document).ready(function(){
    $("#topstats").click(function(){
        $(".topstats").slideToggle(100);
    });
});


/* Sidepanel Show-Hide */
$(document).ready(function(){
    $(".sidepanel-open-button").click(function(){
        $(".sidepanel").toggle(100);
    });
});



/* Sidebar Show-Hide On Mobile */
$(document).ready(function(){
    $(".sidebar-open-button-mobile").click(function(){
        $(".sidebar").toggle(150);
    });
});


/* Sidebar Show-Hide */
$(document).ready(function(){

    $('.sidebar-open-button').on('click', function(){
        if($('.sidebar').hasClass('sidebar-active')){
            $('.sidebar').removeClass('sidebar-active'); 
        }else{
            $('.sidebar').addClass('sidebar-active');   
        }
    });
	
	$('.content').click(function(){
		$('.sidebar').removeClass('sidebar-active'); 
	});

});


/* ===========================================================
PANEL TOOLS
===========================================================*/
/* Minimize */
$(document).ready(function(){
  $(".panel-tools .minimise-tool").click(function(event){
  $(this).parents(".panel").find(".panel-body").slideToggle(100);

  return false;
}); 

 }); 

/* Close */
$(document).ready(function(){
  $(".panel-tools .closed-tool").click(function(event){
  $(this).parents(".panel").fadeToggle(400);

  return false;
}); 

 }); 

 /* Search */
$(document).ready(function(){
  $(".panel-tools .search-tool").click(function(event){
  $(this).parents(".panel").find(".panel-search").toggle(100);

  return false;
}); 

 }); 




/* expand */
$(document).ready(function(){

    $('.panel-tools .expand-tool').on('click', function(){
        if($(this).parents(".panel").hasClass('panel-fullsize'))
        {
            $(this).parents(".panel").removeClass('panel-fullsize');
        }
        else
        {
            $(this).parents(".panel").addClass('panel-fullsize');
 
        }
    });

});


/* ===========================================================
Widget Tools
===========================================================*/


/* Close */
$(document).ready(function(){
  $(".widget-tools .closed-tool").click(function(event){
  $(this).parents(".widget").fadeToggle(400);

  return false;
}); 

 }); 


/* expand */
$(document).ready(function(){

    $('.widget-tools .expand-tool').on('click', function(){
        if($(this).parents(".widget").hasClass('widget-fullsize'))
        {
            $(this).parents(".widget").removeClass('widget-fullsize');
        }
        else
        {
            $(this).parents(".widget").addClass('widget-fullsize');
 
        }
    });

});

/* Kode Alerts */
/* Default */
$(document).ready(function(){
  $(".kode-alert .closed").click(function(event){
  $(this).parents(".kode-alert").fadeToggle(350);

  return false;
}); 

 }); 


/* Click to close */
$(document).ready(function(){
  $(".kode-alert-click").click(function(event){
  $(this).fadeToggle(350);

  return false;
}); 

 }); 



/* Tooltips */
$(function () {
  $('[data-toggle="tooltip"]').tooltip()
})

/* Popover */
$(function () {
  $('[data-toggle="popover"]').popover()
})


/* Page Loading */
$(window).load(function() {
  $(".loading").fadeOut(750);
})


/* Update Fixed */
/* Version 1.2 */
$('.profilebox').on('click',function(){ $(".sidepanel").hide(); })

/* Dashboard boxes height */
$(window).load(function(){
	
	if ($(window).width() >= 992) {
		
		var dashheight1 = $('.dashBox1').outerHeight();
		if($('.dashBox2').outerHeight() > dashheight1){
			dashheight1 = $('.dashBox2').outerHeight();
		}
		if($('.dashBox3').outerHeight() > dashheight1){
			dashheight1 = $('.dashBox3').outerHeight();
		}
		$('.dashBox1, .dashBox2, .dashBox3').css('min-height',dashheight1);
		
		
		var dashheight4 = $('.dashBox4').outerHeight();
		if($('.dashBox5').outerHeight() > dashheight4){
			dashheight4 = $('.dashBox5').outerHeight();
		}
		if($('.dashBox6').outerHeight() > dashheight4){
			dashheight4 = $('.dashBox6').outerHeight();
		}
		$('.dashBox4, .dashBox5, .dashBox6').css('min-height',dashheight4);
		
		var dashheight7 = $('.dashBox7').outerHeight();
		if($('.dashBox8').outerHeight() > dashheight7){
			dashheight7 = $('.dashBox8').outerHeight();
		}
		if($('.dashBox9').outerHeight() > dashheight7){
			dashheight7 = $('.dashBox9').outerHeight();
		}
		$('.dashBox7, .dashBox8, .dashBox9').css('min-height',dashheight7);
		
	}else{
		
		var dashheight1 = $('.dashBox1').outerHeight();
		if($('.dashBox2').outerHeight() > dashheight1){
			dashheight1 = $('.dashBox2').outerHeight();
		}
		$('.dashBox1, .dashBox2').css('min-height',dashheight1);
		
		var dashheight2 = $('.dashBox4').outerHeight();
		if($('.dashBox5').outerHeight() > dashheight2){
			dashheight2 = $('.dashBox5').outerHeight();
		}
		$('.dashBox4, .dashBox5').css('min-height',dashheight2);

        var dashheight7 = $('.dashBox7').outerHeight();
		if($('.dashBox8').outerHeight() > dashheight7){
			dashheight7 = $('.dashBox8').outerHeight();
		}
		$('.dashBox7, .dashBox8').css('min-height',dashheight7);

    }	
	
});



/* Sidebar Height */
/* $(window).load(function(){
	var bodyHeight = $(document).height();
	$('.sidebar').css('min-height',bodyHeight);	
	
	$(document).on('click','.paginate_button',function(){
		var total_heigh = parseInt($('.content').height());
		total_heigh += parseInt($('#top').height());
		$('.sidebar').css('min-height',total_heigh + 20);	
	});
	
}); */

$(document).ready(function(){
	$('li.has_sub').click(function(){
		$(this).children('ul').slideToggle(500);
	});
});