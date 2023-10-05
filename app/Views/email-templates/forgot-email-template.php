<p>Dear <?=$mail_data['user']->username?>,</p>
<p>We are received a request to reset password for EasyStay Reservation account associated with
  <i><?=$mail_data['user']->email?></i>.
  You can reset your password by clicking the button below:
  <br><br>
  <a href="<?=$mail_data['action_link']?>"
    style="background-color: #f3c300; color: #fff; border-color: #f3c300; display: inline-block; text-decoration: none; border-width: 5px 10px; border-radius: 3px; box-shadow: 0 2px 3px rgba(0,0,0,0.16);-webkit-text-size-adjust: none; box-sizing: border-box;"
    target="_blank">Reset Password</a>
  <br><br>
  <b>NB:</b> This link will still valid withing 15 minutes.
  <br><br>
  If you did not request for password reset, please ignore this email.
</p>