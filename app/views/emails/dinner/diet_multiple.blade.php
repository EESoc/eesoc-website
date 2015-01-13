<!DOCTYPE html>
<html lang="en-US">
  <head>
    <meta charset="utf-8">
  </head>
  <body>
    <p>
      Dear {{{ $sale->user->name }}},
    </p>
    <p>
      Thank you for buying a ticket to the EESoc New Year's Dinner, Sponsored by BAE Systems Applied Intelligence. We are very glad you have decided to spend your evening on Thursday January 22<sup>nd</sup> with us and look forward to welcoming you at Gibson Hall! 
    </p>
      The dress code for this event is smart.
    </p>
    <p>
      The event will start at 7:30pm with a drinks reception.
    </p>
    <p>
      Our database says that you have bought {{ $sale->quantity }} tickets. As a first step, we need to know if you or any of your guests have any dietary requirements. Please <a href="https://docs.google.com/forms/d/1ystXxtiOQw6Zs2kpbOM5WkvXvFVQBAd7MKHD5svNcBE/viewform">fill in the form</a> letting us know of your own meal preferences and ensure that it is also filled in for your guests. We are happy to email them if you provide us with their college usernames by replying to this email. Please note that this form will close on Thursday 29th November and anyone who has not entered anything specific will get the standard choices.
    </p>
    <p>
      We will soon be sending you another email to sort out seating arrangements, so please watch out for that one!
    </p>
    <p>
      Thank you once again.
    </p>
    <p>
      With Best Wishes,<br>
      The EESoc tEEEm
    </p>
  </body>
</html>
