<!DOCTYPE html>
<html lang="en-US">
  <head>
	<title>{{{ $subject }}}</title>
    <meta charset="utf-8">
	<style>
	body{
    font-family: HelveticaNeue-Light, 'Helvetica Neue Light', 'Helvetica Neue', 'Trade Gothic W01 Light', Helvetica, Arial, 'Lucida Grande', sans-serif;
	color:#606060;
	}
	</style>
  </head>
  <body>
    <h2>{{{ $subject }}}</h2>
    <p>
      Dear {{{ $user->name }}},
    </p>
    <p>
      You recently purchased a locker from EESoc. However, you have yet to claim your locker. Please head to this page to do so: <a href="https://eesoc.com/dashboard/lockers">https://eesoc.com/dashboard/lockers</a>.
    </p>
      Please be aware that the lockers are not 100% secure and so should only be used for items of low value. The safety of the contents of lockers is your responsibility. Neither EESoc nor the Department accept any liability for any items lost or stolen.
    </p>
    <p>
      Please be aware that combination padlocks are very insecure; a high percentage of thefts have been in lockers with these types of padlocks. All padlocks will only deter an opportunist thief; a committed thief will always break in.
    </p>
	<p>Thank you for your cooperation. For any issues, please do not hesistate to contact Sautrik at <a href="mailto:sautrik.banerjee13@imperial.ac.uk">sautrik.banerjee13@imperial.ac.uk</a>.</p>
    <p>
      Kind Regards, <br/ >
      The EESoc Locker Team
    </p>
  </body>
</html>
