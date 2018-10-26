
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
	<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>EESoc - Beta Homepage</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="Electrical Engineering Society" />
	<meta name="keywords" content="free html5, free template, free bootstrap, html5, css3, mobile first, responsive" />
	<meta name="author" content="Haaris" />

  <!-- 
	//////////////////////////////////////////////////////

	FREE HTML5 TEMPLATE 
	DESIGNED & DEVELOPED by FREEHTML5.CO
		
	Website: 		http://freehtml5.co/
	Email: 			info@freehtml5.co
	Twitter: 		http://twitter.com/fh5co
	Facebook: 		https://www.facebook.com/fh5co

	//////////////////////////////////////////////////////
	 -->

  	<!-- Facebook and Twitter integration -->
	<meta property="og:title" content=""/>
	<meta property="og:image" content=""/>
	<meta property="og:url" content=""/>
	<meta property="og:site_name" content=""/>
	<meta property="og:description" content=""/>
	<meta name="twitter:title" content="" />
	<meta name="twitter:image" content="" />
	<meta name="twitter:url" content="" />
	<meta name="twitter:card" content="" />

	<!-- Place favicon.ico and apple-touch-icon.png') }} in the root directory -->
	<link rel="shortcut icon" href="favicon.ico">

	<link href='https://fonts.googleapis.com/css?family=PT+Sans:400,700,400italic,700italic' rel='stylesheet' type='text/css'>
	
	<!-- Animate.css -->
	<link rel="stylesheet" href="{{ asset('assets/beta/css/animate.css') }}">
	<!-- Icomoon Icon Fonts-->
	<link rel="stylesheet" href="{{ asset('assets/beta/css/icomoon.css') }}">
	<!-- Simple Line Icons -->
	<link rel="stylesheet" href="{{ asset('assets/beta/css/simple-line-icons.css') }}">
	<!-- Bootstrap  -->
	<link rel="stylesheet" href="{{ asset('assets/beta/css/bootstrap.css?dd') }}">
	<!-- Owl Carousel  -->
	<link rel="stylesheet" href="{{ asset('assets/beta/css/owl.carousel.min.css') }}">
	<link rel="stylesheet" href="{{ asset('assets/beta/css/owl.theme.default.min.css') }}">
	<!-- Style -->
	<link rel="stylesheet" href="{{ asset('assets/beta/css/style.css?') }}<?php echo time()?>">


	<!-- Modernizr JS -->
	<script src="{{ asset('assets/beta/js/modernizr-2.6.2.min.js') }}"></script>
	<!-- FOR IE9 below -->
	<!--[if lt IE 9]>
	<script src="js/respond.min.js"></script>
	<![endif]-->

	</head>
	<body>
	<header role="banner" id="fh5co-header">
		<div class="fluid-container">
			<nav class="navbar navbar-default">
				<div class="navbar-header">
					<!-- Mobile Toggle Menu Button -->
					<a href="#" class="js-fh5co-nav-toggle fh5co-nav-toggle" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar"><i></i></a>
					<a class="navbar-brand" href="/beta"><img src="{{ asset('assets/images/eesoc-logo.png') }}" alt="EESoc"></a> 
				</div>
				<div id="navbar" class="navbar-collapse collapse">
					<ul class="nav navbar-nav navbar-right">
						<li class="active"><a href="#" data-nav-section="home"><span>Home</span></a></li>
						<li><a href="#" data-nav-section="events"><span>Events</span></a></li>
						<!--li><a href="#" data-nav-section="pricing"><span>Pricing</span></a></li-->
						<li><a href="#" data-nav-section="services"><span>Services</span></a></li>
						<li><a href="#" data-nav-section="team"><span>Team</span></a></li>
						<li><a href="#" data-nav-section="sponsor"><span>Sponsors</span></a></li>
						<li class="call-to-action"><a class="external" href="{{ url('sign-in') }}"><span>Sign In</span></a></li>
					</ul>
				</div>
			</nav>
	  </div>
	</header>
	
	<section id="fh5co-home" data-section="home" style="background-image: url({{ asset('assets/beta/images/soc2018.jpg') }}); background-position-x: 50%;" data-stellar-background-ratio="0.5">
		<div class="gradient"></div>
		<div class="container">
			<div class="text-wrap">
				<div class="text-inner">
					<div class="row">
						<div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1 col-xs-12">
							<h1 class="to-animate">Welcome to EESoc</h1>
							<h2 class="to-animate">We run frequent, varied and engaging events, all with the goal of creating a close knit community within the Electrical Engineering Department.</h2>
							<!--h2 class="to-animate">100% Free HTML5 Template. Licensed under <a href="http://creativecommons.org/licenses/by/3.0/" target="_blank">Creative Commons Attribution 3.0.</a> <br> Crafted with love by <a href="http://freehtml5.co/" target="_blank" title="Free HTML5 Bootstrap Templates" class="fh5co-link">FREEHTML5.co</a></h2-->
							<div class="call-to-action">
								<a href="#" class="demo to-animate read-on-btn" data-nav-section="events">Read On</a>
								<a href="https://facebook.com/ImperialEESoc" class="download to-animate" target="_blank">Visit Facebook</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<section id="fh5co-explore" data-section="events">
		<div class="container">
			<div class="row">
				<div class="col-md-12 section-heading text-center">
					<h2 class="to-animate">Upcoming Events</h2>
					<div class="row">
						<div class="col-md-8 col-md-offset-2 subtext to-animate">
							<h3>We boast a broad range of events — with socials, events with industry, sports fixtures, and more, we have it all covered.</h3>
						</div>
					</div>
				</div>
			</div>
		</div>
		
		<div class="fh5co-explore">
			<div class="container">
				<!--div class="row">
					<div class="col-md-8 col-md-push-5 to-animate-2">
						<img class="img-responsive" src="{{ asset('assets/beta/images/work_1.png') }}" alt="work">
					</div>
					<div class="col-md-4 col-md-pull-8 to-animate-2">
						<div class="mt">
							<h3>Real Project For Real Solutions</h3>
							<p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts. Separated they live in Bookmarksgrove right at the coast of the Semantics, a large language ocean. </p>
							<ul class="list-nav">
								<li><i class="icon-check2"></i>Far far away, behind the word</li>
								<li><i class="icon-check2"></i>There live the blind texts</li>
								<li><i class="icon-check2"></i>Separated they live in bookmarksgrove</li>
								<li><i class="icon-check2"></i>Semantics a large language ocean</li>
								<li><i class="icon-check2"></i>A small river named Duden</li>
							</ul>
						</div>
					</div>
					
				</div-->
				<!--unlike all other items, events is array of named array not object!-->
				@foreach ($events as $indexKey => $event)
					
					<div class="card-media">
						<!-- media container -->
						<div class="card-media-object-container">
						<div class="card-media-object" style="background-image: url({{ asset('assets/beta/images/test.png') }}); background-size: 50%; background-repeat: no-repeat;"></div>
						</div>
						<!-- body container -->
						<div class="card-media-body">
						<div class="card-media-body-top">
							<span class="subtle">{{ date("D, d M, g:i A", strtotime($event['start_time'])) }}</span>
						</div>
						<span class="card-media-body-heading">{{ $event['name'] }}</span>
						<div class="card-media-body-supporting-bottom">
							<span class="card-media-body-supporting-bottom-text subtle">{{ $event['place']['name'] }}</span>
							<span class="card-media-body-supporting-bottom-text subtle u-float-right">Free</span>
						</div>
						<div class="card-media-body-supporting-bottom card-media-body-supporting-bottom-reveal">
							<!--span class="card-media-body-supporting-bottom-text subtle">#Music #Party</span-->
							<a href="https://facebook.com/events/{{ $event['id'] }}" class="card-media-body-supporting-bottom-text card-media-link u-float-right" target="_blank">VIEW DETAILS</a>
						</div>
						</div>
					</div>
				@endforeach
  
			</div>
		</div>

		<!--div class="fh5co-explore fh5co-explore-bg-color">
			<div class="container">
				<div class="row">
					<div class="col-md-8 col-md-pull-1 to-animate-3">
						<img class="img-responsive" src="{{ asset('assets/beta/images/work_1.png') }}" alt="work">
					</div>
					<div class="col-md-4 to-animate-3">
						<div class="mt">
							<div>
								<h4><i class="icon-people"></i>Real Project For Real Solutions</h4>
								<p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts. </p>
							</div>
							<div>
								<h4><i class="icon-video2"></i>Real Project For Real Solutions</h4>
								<p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts. </p>
							</div>
							<div>
								<h4><i class="icon-shield"></i>Real Project For Real Solutions</h4>
								<p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts. </p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div-->
	</section>
	

	<hr>

	<section id="fh5co-services" data-section="services">
		<div class="fh5co-services">
			<div class="container">
				<div class="row">
					<div class="col-md-12 section-heading text-center">
						<h2 class="to-animate">What We Do</h2>
						<div class="row">
							<div class="col-md-8 col-md-offset-2 subtext">
								<h3 class="to-animate">We hold our own Careers Fair, and we start the year in style with our New Years Dinner. With an unparalleled range of activities, there is something for everyone. </h3>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-4">
						<div class="box-services">
							<i class="icon-people to-animate-2"></i>
							<div class="fh5co-post to-animate">
								<h3>Mums and Dads</h3>
								<p>We run a scheme each year which pairs up higher year students with freshers. Helping you (freshers) to settle down at university guide you through your first year in the department.<br><br></p>
							</div>
						</div>

						<div class="box-services">
							<i class="icon-disc to-animate-2"></i>
							<div class="fh5co-post to-animate">
								<h3>Bar Nights</h3>
								<p>No college experience would be complete without a night spent tuning your bowling skills. Last year EESoc organized an event at the Kingpin Suite, a venue that offers drinks, arcade games, karaoke and yes you guessed it, five bowling lanes.</p>
							</div>
						</div>
					</div>
					<div class="col-md-4">
						<div class="box-services">
							<i class="icon-book-open to-animate-2"></i>
							<div class="fh5co-post to-animate">
								<h3>EESoc Dinner</h3>
								<p>Every year, EESoc organizes a glamorous black-tie dinner party — a great opportunity for you to mingle with both professors and fellow students while enjoying a formal 3-course meal at a lavish venue.</p>
							</div>
						</div>

						<div class="box-services">
							<i class="icon-briefcase to-animate-2"></i>
							<div class="fh5co-post to-animate">
								<h3>Careers Fair</h3>
								<p>Our Careers Fair was attended by over 25 firms from the banking, consulting and engineering industries last year. Its the perfect platform for the top engineering and technology companies to meet the best technical engineers in the UK in an intimate environment. </p>
							</div>
						</div>
					</div>
					<div class="col-md-4">
						<div class="box-services">
							<i class="icon-info to-animate-2"></i>
							<div class="fh5co-post to-animate">
								<h3>Industry Talks</h3>
								<p>EESoc runs careers talks throughout the year. Such talks are typically held in the department during lunchtime. We invite guest companies, many of whom are sponsors, to give tailored presentation to you, our members. </p>
							</div>
						</div>

						<div class="box-services">
							<i class="icon-screen-desktop to-animate-2"></i>
							<div class="fh5co-post to-animate">
								<h3>Hackathons</h3>
								<p>Last year, we worked with Microsoft to run a ‘hackathon’. This was an event where students pitted their wits against each other to create the most effective algorithm to solve an array of classical problems.</p>
							</div>
						</div>
					</div>
				</div>
				<div class="call-to-action text-center to-animate"><a href="{{ asset('files/other/pdf/EESocFresherGuidebook2018.pdf') }}" class="btn btn-learn" target="_blank" title="EESoc Freshers Guidebook 2018 Edition (PDF)">Learn More</a></div>
			</div>
		</div>
	</section>

	<div class="getting-started getting-started-1">
		<div class="container">
			<div class="row">
				<div class="col-md-6 to-animate">
					<h3 style="margin: 0 0 7px 0;">Join Us</h3>
					<p>Want exclusive access to our amazing events?<br>Buy our membership now, it's only £10 for the entire year!</p>
				</div>
				<div class="col-md-6 to-animate-2">
					<div class="call-to-action text-right">
						<a href="{{ action('BetaController@getBuyMembership') }}" class="sign-up">Buy Membership</a>
					</div>
				</div>
			</div>
		</div>
	</div>

	<section id="fh5co-team" data-section="team">
		<div class="fh5co-team">
			<div class="container">
				<div class="row">
					<div class="col-md-12 section-heading text-center">
						<h2 class="to-animate">Meet The Team</h2>
						<div class="row">
							<div class="col-md-8 col-md-offset-2 subtext">
								<h3 class="to-animate">The backbone of EESoc is its diverse committee, working tirelessly in each of their roles to make sure you have an unforgettable university experience.</h3>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					@foreach ($committee as $indexKey => $member)

						<div class="col-md-4">
							<div class="team-box text-center to-animate-2" data-committee-show="{{ $member->id }}" data-committee-name="{{ $member->name }}">
								<div class="user"><img class="img-reponsive" src="{{ $member->logo_path }}" alt="{{ $member->name }}">
								</div>
								<h3 class="committee-title">{{ $member->name }}</h3>
								<span class="position committee-role">{{ $member->role }}</span>
								<p>
									{{ $member->short_description }}
									@if ($member->description != "")
										<br><a class="more-desc" data-committee-show="{{ $member->id }}" style="text-decoration: none; cursor: pointer;">Read more...</a>
									@endif
								</p>
								<ul class="social-media">
									@if ($member->facebookURL != NULL)
										<li><a href="{{ $member->facebookURL }}" target="_blank" class="facebook"><i class="icon-facebook"></i></a></li>
									@endif
									@if ($member->githubURL != NULL)
										<li><a href="{{ $member->githubURL }}" target="_blank" class="github"><i class="icon-github-alt"></i></a></li>
									@endif
									@if ($member->email != NULL)
										<li><a href="mailto:{{ $member->email }}" class="dribbble"><i class="icon-envelope"></i></a></li>
									@endif
								</ul>
								<div class="hidden committee-excerpt-long">
										{{ $member->description }}
								</div>
							</div>
						</div>
						@if (($indexKey+1) % 3 == 0)
							<!--hacks to prevent overcrowding -->
							</div><div class="row">
						@endif
					 @endforeach


					<!--div class="col-md-4">
						<div class="team-box text-center to-animate-2">
							<div class="user"><img class="img-reponsive" src="{{ asset('files/committees/committee1819/square/Eren.jpg') }}" alt="Roger Garfield"></div>
							<h3>Eren Kopuz</h3>
							<span class="position">Vice President (Events)</span>
							<p>"For me EESoc has always been an integral part of the student experience in our department, working to enhance both the social and academic aspect of our time at university."</p>
							<ul class="social-media">
								<li><a href="#" class="facebook"><i class="icon-facebook"></i></a></li>
								<li><a href="#" class="twitter"><i class="icon-twitter"></i></a></li>
								<li><a href="#" class="dribbble"><i class="icon-dribbble"></i></a></li>
								<li><a href="#" class="codepen"><i class="icon-codepen"></i></a></li>
								<li><a href="#" class="github"><i class="icon-github-alt"></i></a></li>
							</ul>
						</div>
					</div-->
					
				</div>

			</div>
		</div>
	</section>

	<!--section id="fh5co-faq" data-section="faq">
		<div class="fh5co-faq">
			<div class="container">
				<div class="row">
					<div class="col-md-12 section-heading text-center">
						<h2 class="to-animate">Common Questions</h2>
						<div class="row">
							<div class="col-md-8 col-md-offset-2 subtext">
								<h3 class="to-animate">Everything you need to know before you get started</h3>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="box-faq to-animate-2">
							<h3>What is Union?</h3>
							<p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts. Separated they live in Bookmarksgrove right at the coast of the Semantics, a large language ocean.</p>
						</div>
						<div class="box-faq to-animate-2">
							<h3>I have technical problem, who do I email? </h3>
							<p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts. Separated they live in Bookmarksgrove right at the coast of the Semantics, a large language ocean.</p>
						</div>
						<div class="box-faq to-animate-2">
							<h3>How do I use Bato features?</h3>
							<p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts. Separated they live in Bookmarksgrove right at the coast of the Semantics, a large language ocean.</p>
						</div>
					</div>

					<div class="col-md-6">
						<div class="box-faq to-animate-2">
							<h3>What language are available?</h3>
							<p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts. Separated they live in Bookmarksgrove right at the coast of the Semantics, a large language ocean.</p>
						</div>
						<div class="box-faq to-animate-2">
							<h3>Can I have a username that is already taken?</h3>
							<p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts. Separated they live in Bookmarksgrove right at the coast of the Semantics, a large language ocean.</p>
						</div>
						<div class="box-faq to-animate-2">
							<h3>Is Union free?</h3>
							<p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts. Separated they live in Bookmarksgrove right at the coast of the Semantics, a large language ocean.</p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section-->

	<!--hr-->

	<section id="fh5co-trusted" data-section="sponsor">
		<div class="fh5co-trusted">
			<div class="container">
				<div class="row">
					<div class="col-md-12 section-heading text-center">
						<h2 class="to-animate">Our Sponsors</h2>
						<div class="row">
							<div class="col-md-8 col-md-offset-2 subtext">
								<h3 class="to-animate">We’re generously sponsored by all these companies.<br>Click on a logo to learn more about each company.</h3>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					 @foreach ($sponsors as $indexKey => $sponsor)
						<div class="col-md-2 col-sm-3 col-xs-6 col-lg-offset-1">
							<div class="partner-logo sponsor to-animate-2" data-sponsor-show="{{ $sponsor->id }}" data-sponsor-name="{{ $sponsor->name }}" style="cursor: pointer;">
								<img src="{{ $sponsor->logo_path }}" alt="Partners" class="img-responsive">
								<p class="hidden sponsor-title">"{{ $sponsor->name }}"</p>
								<div class="hidden sponsor-excerpt-long">
									{{ $sponsor->description }}
								</div>
							</div>
						</div>
						@if (($indexKey+1) % 4 == 0)
							<!--hacks to prevent overcrowding -->
							</div><div class="row">
						@endif
					 @endforeach
					 <!--div class="col-md-2 col-sm-3 col-xs-6 col-sm-offset-0 col-md-offset-1">
					 	<div class="partner-logo sponsor-logo to-animate-2" data-sponsor-show="pt1">
							<img src="{{ asset('assets/beta/images/logo1.png') }}" alt="Partners" class="img-responsive">
							<p class="hidden sponsor-title">Canon</p>
							 <div class="hidden sponsor-excerpt-long">
								<p>Hello! I'm Sigrid, a final year EEE student who will be your Women in EEngineering officer for this year.</p>
								<p>Last year we started the women's society within the EEE department, designed to increase the cohesion among the women in the department,</p>
								<p>arrange skills workshops and presentations by women in industry, and to do outreach to schools in order to encourage more girls to apply.</p>
								<p>My role on the EESoc committee is to interface between these two societies. I'm excited about the role, and would be happy to hear from</p>
								<p>anyone with questions or inputs.</p>
							  </div>
					 	</div>
					 </div-->
				</div>
			</div>
		</div>
	</section>

	<div id="fh5co-footer" role="contentinfo">
		<div class="container">
			<div class="row">
				<div class="col-md-6 to-animate">
					<h3 class="section-title">About Us</h3>
					<p style="margin-bottom: 60px;"> We boast a broad range of events— with socials, events with industry, sports fixtures, and more, we have it all covered. We hold our own Careers Fair, and we start the term in style with our New Years Dinner. With an unparalled range of activities, there is something for everyone. This year we'll be working hard to create a standout society.</p>
					<p class="copy-right">&copy; 2018 EESoc<br>
						Designed by <a href="http://freehtml5.co/" target="_blank">FREEHTML5.co</a><br>
						Code by <a href='https://github.com/hsed'>Haaris Mehmood</a>
          and <a href="https://github.com/EESoc/eesoc-website/graphs/contributors">others</a>, available on
          <a href="http://bit.ly/196sso5">GitHub</a>.
					</p>
				</div>

				<div class="col-md-6 to-animate">
					<h3 class="section-title">Our Address</h3>
					<ul class="contact-info">
						<li><i class="icon-map-marker"></i>Imperial College Union, Beit Quadrangle, Prince Consort Rd, London<br>SW7 2BB</li>
						<li><i class="icon-envelope"></i><a href="mailto:eesoc@imperial.ac.uk">eesoc@imperial.ac.uk</a></li>
						<li><i class="icon-globe2"></i><a href="https://www.eesoc.com">www.eesoc.com</a></li>
					</ul>
					<h3 class="section-title">Connect with Us</h3>
					<ul class="social-media">
						<li><a href="https://facebook.com/ImperialEESoc" target="_blank" class="facebook"><i class="icon-facebook"></i></a></li>
						<li><a href="https://twitter.com/EESoc" class="twitter"><i class="icon-twitter"></i></a></li>
						<li><a href="https://github.com/EESoc" class="github"><i class="icon-github-alt"></i></a></li>
					</ul>
				</div>
				<!--div class="col-md-4 to-animate">
					<h3 class="section-title">Drop us a line</h3>
					<form class="contact-form">
						<div class="form-group">
							<label for="name" class="sr-only">Name</label>
							<input type="name" class="form-control" id="name" placeholder="Name">
						</div>
						<div class="form-group">
							<label for="email" class="sr-only">Email</label>
							<input type="email" class="form-control" id="email" placeholder="Email">
						</div>
						<div class="form-group">
							<label for="message" class="sr-only">Message</label>
							<textarea class="form-control" id="message" rows="7" placeholder="Message"></textarea>
						</div>
						<div class="form-group">
							<input type="submit" id="btn-submit" class="btn btn-send-message btn-md" value="Send Message">
						</div>
					</form>
				</div-->
			</div>
		</div>
	</div>
	<!--div id="map" class="fh5co-map"></div-->

	<!--IMP DO NOT REMOVE EVER -->
	<div class="modal fade" id="modal-info-template" tabindex="-1" role="dialog">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"></h4>
              </div>
              <div class="modal-body">
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              </div>
            </div>
          </div>
    </div>


	
	<!-- jQuery -->
	<script src="{{ asset('assets/beta/js/jquery.min.js') }}"></script>
	<!-- jQuery Easing -->
	<script src="{{ asset('assets/beta/js/jquery.easing.1.3.js') }}"></script>
	<!-- Bootstrap -->
	<script src="{{ asset('assets/beta/js/bootstrap.min.js') }}"></script>
	<!-- Waypoints -->
	<script src="{{ asset('assets/beta/js/jquery.waypoints.min.js') }}"></script>
	<!-- Stellar Parallax -->
	<script src="{{ asset('assets/beta/js/jquery.stellar.min.js') }}"></script>
	<!-- Owl Carousel -->
	<script src="{{ asset('assets/beta/js/owl.carousel.min.js') }}"></script>
	<!-- Google Map -->
	<!--script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCefOgb1ZWqYtj7raVSmN4PL2WkTrc-KyA&sensor=false"></script>
	<script src="{{ asset('assets/beta/js/google_map.js') }}"></script-->
	<!-- Main JS (Do not remove) -->
	<script src="{{ asset('assets/beta/js/main.js') }}?<?php echo time()?>"></script>

	</body>
</html>

