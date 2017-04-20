(function ($) {
    "use strict";

/*---------------------
 jQuery MeanMenu
--------------------- */
	jQuery('nav#dropdown').meanmenu();	
	
/*---------------------
 parallax-area
--------------------- */	
$('.parallax-area').parallax("50%", 0.4);
	
/*----------------------------------
 home-4 .home-2-testimonial-area
------------------------------------ */	
$('.home-4 .home-2-testimonial-area').parallax("50%", 0.4);	

/*----- main slider -----*/
$('#mainSlider').nivoSlider({
	directionNav: true,
	animSpeed: 500,
	slices: 18,
	pauseTime: 50000000,
	pauseOnHover: false,
	controlNav: true,
	prevText: '<i class="fa fa-angle-left nivo-prev-icon"></i>',
	nextText: '<i class="fa fa-angle-right nivo-next-icon"></i>'
});

/*---------------------
 office-banner
--------------------- */
	$('.office-banner').owlCarousel({
		loop:true,
		margin:10,
		nav:true,
		autoplay:false,
		smartSpeed:3000,
		navText: ["<i class='fa fa-angle-left'></i>","<i class='fa fa-angle-right'></i>"],
		responsive:{
			0:{
				items:1
			},
			600:{
				items:1
			},
			1000:{
				items:1
			}
		}
	});
	
/*---------------------
 team-2-curosel
--------------------- */
	$('.team-2-curosel').owlCarousel({
		loop:true,
		margin:10,
		nav:true,
		autoplay:false,
		stagePadding: 50,
		smartSpeed:1000,
		navText: ["<i class='fa fa-angle-left'></i>","<i class='fa fa-angle-right'></i>"],
		responsive:{
			0:{
				items:1
			},
			770:{
				items:3
			},
			1000:{
				items:5
			}
		}
	});
	
/*---------------------
 news-curosel
--------------------- */
	$('.news-curosel').owlCarousel({
		loop:true,
		margin:10,
		nav:false,
		autoplay:false,
		smartSpeed:1000,
		navText: ["<i class='fa fa-angle-left'></i>","<i class='fa fa-angle-right'></i>"],
		responsive:{
			0:{
				items:1
			},
			770:{
				items:3
			},
			1000:{
				items:4
			}
		}
	});
	
/*---------------------
 testimonial-list
--------------------- */
	$('.testimonial-list').owlCarousel({
		loop:true,
		margin:10,
		nav:true,
		animateOut: 'slideOutDown',
		animateIn: 'flipInX',		
		autoplay:true,
		smartSpeed:1000,
		navText: ["<i class='fa fa-angle-left'></i>","<i class='fa fa-angle-right'></i>"],
		responsive:{
			0:{
				items:1
			},
			600:{
				items:1
			},
			1000:{
				items:1
			}
		}
	});
	
/*---------------------
 home-2-testimonial-list
--------------------- */
	$('.home-2-testimonial-list').owlCarousel({
		loop:true,
		margin:10,
		nav:false,
		animateOut: 'slideOutDown',
		animateIn: 'flipInX',		
		autoplay:true,
		smartSpeed:1000,
		navText: ["<i class='fa fa-angle-left'></i>","<i class='fa fa-angle-right'></i>"],
		responsive:{
			0:{
				items:1
			},
			600:{
				items:1
			},
			1000:{
				items:1
			}
		}
	});
	
/*---------------------
 upcoming-product-list
--------------------- */
	$('.upcoming-product-list').owlCarousel({
		loop:true,
		margin:10,
		nav:true,	
		autoplay:true,
		smartSpeed:1000,
		navText: ["<i class='fa fa-angle-left'></i>","<i class='fa fa-angle-right'></i>"],
		responsive:{
			0:{
				items:1
			},
			600:{
				items:4
			},
			1000:{
				items:6
			}
		}
	});
	
/*---------------------
 customer-say-curosel
--------------------- */
	$('.customer-say-curosel').owlCarousel({
		loop:true,
		margin:10,
		nav:true,	
		autoplay:true,
		smartSpeed:1000,
		navText: ["<i class='fa fa-angle-left'></i>","<i class='fa fa-angle-right'></i>"],
		responsive:{
			0:{
				items:1
			},
			600:{
				items:1
			},
			1000:{
				items:1
			}
		}
	});	
	
/*---------------------
 statistics-counter
--------------------- */	
    $('.statistics-counter').counterUp({
        delay: 50,
        time: 3000
    });
	
/*---------------------
 about-counter
--------------------- */	
    $('.about-counter').counterUp({
        delay: 50,
        time: 3000
    });	
	
/*---------------------
 team-counter
--------------------- */	
    $('.team-counter').counterUp({
        delay: 50,
        time: 3000
    });	
	
/*---------------------
 team-3-couter
--------------------- */	
    $('.team-3-couter').counterUp({
        delay: 50,
        time: 3000
    });
	
	
/* --------------------------------------------------------
   contact-accordion
* -------------------------------------------------------*/ 
	$(".contact-accordion").collapse({
		accordion:true,
	  open: function() {
		this.slideDown(550);
	  },
	  close: function() {
		this.slideUp(550);
	  }		
	});	
	
/* --------------------------------------------------------
   service-accordion
* -------------------------------------------------------*/ 
	$(".service-accordion").collapse({
		accordion:true,
	  open: function() {
		this.slideDown(550);
	  },
	  close: function() {
		this.slideUp(550);
	  }		
	});
	
/* --------------------------------------------------------
   faq-accordion
* -------------------------------------------------------*/ 
	$(".faq-accordion").collapse({
		accordion:true,
	  open: function() {
		this.slideDown(550);
	  },
	  close: function() {
		this.slideUp(550);
	  }		
	});	
/* --------------------------------------------------------
   qa-accordion
* -------------------------------------------------------*/ 
	$(".qa-accordion").collapse({
		accordion:true,
	  open: function() {
		this.slideDown(550);
	  },
	  close: function() {
		this.slideUp(550);
	  }		
	});		
	
/*---------------------
   Circular Bars - Knob
--------------------- */	
	  if(typeof($.fn.knob) != 'undefined') {
		$('.knob').each(function () {
		  var $this = $(this),
			  knobVal = $this.attr('data-rel');
	
		  $this.knob({
			'draw' : function () { 
			  $(this.i).val(this.cv + '%')
			}
		  });
		  
		  $this.appear(function() {
			$({
			  value: 0
			}).animate({
			  value: knobVal
			}, {
			  duration : 2000,
			  easing   : 'swing',
			  step     : function () {
				$this.val(Math.ceil(this.value)).trigger('change');
			  }
			});
		  }, {accX: 0, accY: -150});
		});
	  };
		
/*---------------------
   scrollUp
--------------------- */	
	$.scrollUp({
        scrollText: '<i class="fa fa-angle-up"></i>',
        easingType: 'linear',
        scrollSpeed: 900,
        animation: 'fade'
    });
		
/*---------------------
 fancybox
--------------------- */	
	$('.fancybox').fancybox();			
	



})(jQuery);
	