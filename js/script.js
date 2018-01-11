/*
Author name: Tejas;
E-mail ID:   tej.sourceus@gmail.com
Date:        12-12-2017
*/

// Read window width on load
    	var wdth = $(window).width();
    	$(document).ready(function(){
		    if(wdth<"880")
				{
				   	$(".right-bars").css("display","none");
				   	$(".left-bar").css("display","none");
					$("#openNavID").css("display","inline-block");
					$("#mainContainer").removeClass( "mainContainer", 1000, "easeInBack" );
				}
				else if(wdth>="880")
				{
				   	$(".right-bars").css("display","block");
				   	$(".left-bar").css("display","block");
					$("#openNavID").css("display","none");
					$("#mainContainer").addClass("mainContainer");
					   
				}
		});
		// Read window width change
		$(window).on('resize', function(){
		   if($(this).width() != wdth){
		      wdth = $(this).width();
		       if(wdth<"880")
				{
				   	$(".right-bars").css("display","none");
				   	$(".left-bar").css("display","none");
					$("#openNavID").css("display","inline-block");
					$("#mainContainer").removeClass( "mainContainer", 1000, "easeInBack" );
				}
				else if(wdth>="880")
				{
				   	$(".right-bars").css("display","block");
				   	$(".left-bar").css("display","block");
					$("#openNavID").css("display","none");
					$("#mainContainer").addClass("mainContainer");
				}
		   }
		});

		
function openNav() {
    document.getElementById("mySidenav").style.width = "100%";
}

function closeNav() {
    document.getElementById("mySidenav").style.width = "0";
}


