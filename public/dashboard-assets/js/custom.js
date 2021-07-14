/*************************************
@@File: Job Stock  Template Custom Js

All custom js files contents are below
**************************************
* 01. Company Brand Carousel
* 02. Client Story testimonial
* 03. Bootstrap wysihtml5 editor
* 04. Tab Js
* 05. Add field Script
**************************************/

(function($){
"use strict";
	 
	
	/*---Bootstrap wysihtml5 editor --*/	
	$('.textarea').wysihtml5();
	
	/*------------Tooltip-----------*/
	$('[data-toggle="tooltip"]').tooltip({
        placement : 'top'
    });
	
	/*------------Scroll Bar-----------*/
	$('#friend-scroll').slimscroll({
        disableFadeOut: true,
		color: '#07b107',
		height:505
		});	  

      $('#chat-scroll').slimscroll({
        disableFadeOut: true,
		color: '#07b107',
		height:400
      });
	
	/*-------------Menu------------*/
	/*====================================
	  METIS MENU 
	  ======================================*/
	$('#main-menu').metisMenu();

	/*====================================
	  LOAD APPROPRIATE MENU BAR
   ======================================*/
	$(window).bind("load resize", function () {
		if ($(this).width() < 768) {
			$('div.sidebar-collapse').addClass('collapse')
		} else {
			$('div.sidebar-collapse').removeClass('collapse')
		}
	});
			
	})(jQuery);