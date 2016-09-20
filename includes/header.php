<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Global Fellows in Washington, DC - University of Maryland</title>
<link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon /" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta content="Army ROTC - University of Maryland" />
	<meta name="" content=""/>
	<meta name="Description" content=" " />

<!-- Bootstrap -->
<link rel="stylesheet" href="css/bootstrap.css">
<!-- Font Awesome -->
<link rel="stylesheet" href="css/font-awesome.css" />

<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    
    
<style type="text/css">  
	p {
		font-size: 1em;
    	font-family: "verdana";
		font-weight: normal;
		line-height: 1.5em;
		color: #333;
		margin: 0 0 1.5em 0;
		padding: 0; 
		text-align:left !important;
	}

	body {
		margin: 0;
		padding: 0;
		background-color: #2a5135;
		text-align: left;
		color: #333;
		margin-left: 1px;
		margin-right: 1px;
	}
	
    .dropdown-menu {
		min-width: 200px; 
		background-color:#333;
	}
	
	.dropdown-menu.columns-5 {
		min-width: 400px;
	}
	
	.dropdown-menu.columns-2 {
		min-width: 600px;
	}
	
	.dropdown-menu.columns-3 {
		min-width: 600px;
	}
	
	.dropdown-menu li a {
		padding: 5px 15px;
		font-weight: 300;
	}
	
	.multi-column-dropdown {
		list-style: none;
	}
	
	.multi-column-dropdown li a {
		display: block;
		clear: both;
		line-height: 1.4;
		color: #fff;
		white-space: normal;
	}
	
	.multi-column-dropdown li a:hover {
		text-decoration: none;
		color: #262626; text-decoration:underline;
		background-color: #f5f5f5;
	}
	
	@media (max-width: 767px) {
		.dropdown-menu.multi-column {
			min-width: 240px !important;
			overflow-x: hidden;
		}	
	}
	
	@media (max-width: 480px) {
		.content {
			width: 90%;
			margin: 20px auto;
			padding: 10px;
		}
	}
</style>
<!--Carousel files -->
<link rel="stylesheet" href="css/flickity.css" type="text/css" media="screen">
<script src="js/flickity.pkgd.js" language="javascript"></script>   

<script>
var elem = document.querySelector('.main-gallery');
var flkty = new Flickity( elem, {
  // options
  /*cellAlign: 'left',*/
  contain: true,
  accessibility:true,
  resize: true,
  wrapAround:false,
  imagesLoaded:true,
  lazyLoad: 4,
  autoplay:6000
});

$(document).ready( function() {
  var $gallery = $('.gallery').flickity({
    cellSelector: 'img',
    imagesLoaded: true,
    percentPosition: false
  });
  var $caption = $('.caption');
  // Flickity instance
  var flkty = $gallery.data('flickity');

  $gallery.on( 'cellSelect', function() {
    // set image caption using img's alt
    $caption.text( flkty.selectedElement.alt )
  });
});

</script>


<style type="text/css">

.gallery {
  background: #FAFAFA;
}

.gallery-cell {
	width: 66%;
	height: 200px;
	margin-right: 10px;
	background: #c2c2c2;
	counter-increment: gallery-cell;
}
.gallery img {
  display: block;
  height: 250px;
}

/* cell number 
.gallery-cell:before {
  display: block;
  text-align: center;
  content: counter(gallery-cell);
  line-height: 200px;
  font-size: 80px;
  color: white;
}*/

/* gallery focus */
.flickity-enabled:focus .flickity-viewport {
  /*outline: thin solid;
  outline: 5px auto -webkit-focus-ring-color;*/
}


.caption {
	background: #FAFAFA;
	color: #000000;
	margin: 0;
	padding: 10px;
	text-align: left;
}

.flickity-page-dots {
  bottom: -10px;
}


.container {
	max-width: 1000px;
	border: 1px;
	border-color: #FFF;
	background-color:#FFF;
	border-style:solid;
	
}
p{
	font-size:1.0em;
}
a{
	text-decoration:underline;
}
.navbar .navbar-inner {
	padding: 0px 5px 0px 10px;
}
.nav > li > a {
    position: relative;
    display: block;
    padding: 10px 30px 10px 30px;
}
.navbar .nav li a {
	font-weight: normal;
	
	border-left: 1px solid rgba(255,255,255,.75);
	border-right: 1px solid rgba(0,0,0,.1);
}
.navbar .nav li:first-child a {
	border-left: 0;
	border-radius: 0px 0 0 0px;
}
.navbar .nav li:last-child a {
	border-right: 0;
	border-radius: 0 0px 0px 0;
}
.navbar-default .navbar-nav > li > a {
 color: #fff;
font-size: 17px;
   text-decoration:none; 
    font-weight: 200;
}
.navbar-default .navbar-nav > li > a:hover {
    color: #fff;
	text-decoration:underline; 
}
.navbar-default .navbar-nav > li > a:focus {
    color: #fff;
	text-decoration:underline
}
.navbar-inverse .brand {
    color: #999999;
}
.navbar-inverse .brand, .navbar-inverse .nav > li > a {
    color: #999999;
    text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25);
}
.navbar .brand {
    display: block;
    float: left;
    padding: 10px 20px 10px;
    
    font-size: 20px;
    font-weight: 200;
    color: #777777;
    
}


    .dropdown-menu {
		min-width: 200px; 
	}
	.dropdown-menu.columns-5 {
		min-width: 400px;
	}
	.dropdown-menu.columns-2 {
		min-width: 600px;
	}
	.dropdown-menu.columns-3 {
		min-width: 600px;
	}
	.dropdown-menu li a {
		padding: 5px 15px;
		font-weight: 300;
	}
	.multi-column-dropdown {
		list-style: none;
	}
	.multi-column-dropdown li a {
		display: block;
		clear: both;
		line-height: 1.4;
		color: #333;
		white-space: normal;
	}
	.multi-column-dropdown li a:hover {
		text-decoration: none;
		color: #262626; text-decoration:underline;
		background-color: #f5f5f5;
	}
	
	@media (max-width: 767px) {
		.dropdown-menu.multi-column {
			min-width: 240px !important;
			overflow-x: hidden;
		}
	}
	
	@media (max-width: 480px) {
		.content {
			width: 90%;
			margin: 20px auto;
			padding: 10px;
		}
	}
</style>

<!--/Carousel files -->

</head>
<body leftmargin="1" marginwidth="1" > 

<!--/UMD Header-->


<div class="container">
  	<!-- Banners and Nav -->
	<script src="//s3.amazonaws.com/umdheader.umd.edu/app/js/main.min.js"></script>
	<script> umdHeader.init({ news: false }); </script>
  
     <!--UGST Header-->
     <nav class="navbar navbar-default" role="navigation" style="margin-bottom:0px;background-color:#000;">
     	<div class="navbar-header">
        	<button type="button"  class="navbar-toggle" data-toggle="collapse"
                 data-target="#bs-example-navbar-collapse-1" style="background-color:#000;color:#fff;" >
                <p align="center" style="font-size: x-small;color:#fff;"> MENU
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </p>
        	</button>
        	<a class="brand" href="http://www.globalsemesterdc.umd.edu" style="color:#fff;">Global Fellows in Washington, D.C.</a> <br>
        </div>
            
        <!--/.navbar-header-->   	
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1" style="background-color:#000;color:#fff;">
            <ul class="nav navbar-nav">
            <!--Top Menu contents -->
            <!--UGST Top Menu for Bootstrap build updated July, 2015 -->
                <li><a href="about-gs.html" style="">About</a></li>                
                <li><a href="seminars-gs.html" style="">Seminars</a></li> 
                <li><a href="internships-gs.html" style="">Internships</a></li> 
                <li><a href="courses-gs.html" style="">Supplementary Courses</a></li>    
                <li><a href="alumni-gs.html" style="">Alumni</a></li>             
                <li><a href="../Jointapplication.docx" style="">Application</a></li>
            </ul>
        </div>
        <!--/.navbar-collapse-->
    </nav>
    
 	<div class="row" align="center" style="margin:0px;background-color:#000;">
    	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="align-content:center;background-color:#000; ">
  			<a href="http://www.globalsemesterdc.umd.edu"><img src="images/globalsemester1000w.jpg"  class="img-responsive" alt="Global 		Fellows in Washington, DC icon and banner" height="333" hspace="0" vspace="0" border="0"></a>
    	</div>
    </div>
