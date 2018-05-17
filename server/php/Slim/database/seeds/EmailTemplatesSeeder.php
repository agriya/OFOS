<?php

use Phinx\Seed\AbstractSeed;

class EmailTemplatesSeeder extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     */
    public function run()
    {
        $data = [
            [
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'name' => 'welcomemail',
                'description' => 'we will send this mail, when user register in this site and get activate.',
                'from_email' => '##FROM_EMAIL##',
                'reply_to_email' => '##REPLY_TO_EMAIL##',
                'subject' => 'Welcome to ##SITE_NAME##',
                'email_variables' => 'SITE_NAME, SITE_URL,USERNAME, SUPPORT_EMAIL,SITE_URL',
                'html_email_content' => 'Hi ##USERNAME##,

  We wish to say a quick hello and thanks for registering at ##SITE_NAME##.
  
  If you did not request this account and feel this is in error, please contact us at ##SUPPORT_EMAIL##

Thanks,

##SITE_NAME##
##SITE_URL##',
                'text_email_content' => 'Hi ##USERNAME##,

  We wish to say a quick hello and thanks for registering at ##SITE_NAME##.
  
  If you did not request this account and feel this is in error, please contact us at ##SUPPORT_EMAIL##

Thanks,

##SITE_NAME##
##SITE_URL##',
                'display_name' => 'Welcome Mail',
                'to_email' => '##TO_EMAIL##',
                'is_admin_email' => 'f',
                'plugin' => null,
                'is_html' => 'f'
            ],
            [
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'name' => 'changepassword',
                'description' => 'we will send this mail
to user, when the user change password.',
                'from_email' => '##FROM_EMAIL##',
                'reply_to_email' => '##REPLY_TO_EMAIL##',
                'subject' => 'Password changed',
                'email_variables' => 'SITE_NAME,SITE_URL,PASSWORD,USERNAME',
                'html_email_content' => 'Hi ##USERNAME##,

Your password has been changed

Your new password:
##PASSWORD##

Thanks,
##SITE_NAME##
##SITE_URL##',
                'text_email_content' => 'Hi ##USERNAME##,

Your password has been changed

Your new password:
##PASSWORD##

Thanks,
##SITE_NAME##
##SITE_URL##',
                'display_name' => 'Change Password',
                'to_email' => '##TO_EMAIL##',
                'is_admin_email' => 'f',
                'plugin' => null,
                'is_html' => 'f'
            ],
            [
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'name' => 'activationrequest',
                'description' => 'we will send this mail,
when user registering an account he/she will get an activation
request.',
                'from_email' => '##FROM_EMAIL##',
                'reply_to_email' => '##REPLY_TO_EMAIL##',
                'subject' => 'Please activate your ##SITE_NAME## account',
                'email_variables' => 'SITE_NAME,SITE_URL,USERNAME,ACTIVATION_URL',
                'html_email_content' => 'Hi ##USERNAME##,

Your account has been created. Please visit the following URL to activate your account.
##ACTIVATION_URL##

Thanks,

##SITE_NAME##
##SITE_URL##',
                'text_email_content' => 'Hi ##USERNAME##,

Your account has been created. Please visit the following URL to activate your account.
##ACTIVATION_URL##

Thanks,

##SITE_NAME##
##SITE_URL##',
                'display_name' => 'Activation Request',
                'to_email' => '##TO_EMAIL##',
                'is_admin_email' => 'f',
                'plugin' => null,
                'is_html' => 'f'
            ],
            [
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'name' => 'adminuseredit',
                'description' => 'we will send this mail
into user, when admin edit users profile.',
                'from_email' => '##FROM_EMAIL##',
                'reply_to_email' => '##REPLY_TO_EMAIL##',
                'subject' => '[##SITE_NAME##] Profile updated',
                'email_variables' => 'SITE_NAME,EMAIL,USERNAME',
                'html_email_content' => 'Hi ##USERNAME##,

Admin updated your profile in ##SITE_NAME## account.

Thanks,
##SITE_NAME##
##SITE_URL##',
                'text_email_content' => 'Hi ##USERNAME##,

Admin updated your profile in ##SITE_NAME## account.

Thanks,
##SITE_NAME##
##SITE_URL##',
                'display_name' => 'Admin User Edit',
                'to_email' => '##TO_EMAIL##',
                'is_admin_email' => 'f',
                'plugin' => null,
                'is_html' => 'f'
            ],
            [
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'name' => 'adminuserdelete',
                'description' => 'We will send this mail to user, when user delete by administrator.',
                'from_email' => '##FROM_EMAIL##',
                'reply_to_email' => '##REPLY_TO_EMAIL##',
                'subject' => 'Your ##SITE_NAME## account has been removed',
                'email_variables' => 'SITE_NAME,USERNAME, SITE_URL',
                'html_email_content' => 'Dear ##USERNAME##,

Your ##SITE_NAME## account has been removed.

Thanks,
##SITE_NAME##
##SITE_URL##',
                'text_email_content' => 'Dear ##USERNAME##,

Your ##SITE_NAME## account has been removed.

Thanks,
##SITE_NAME##
##SITE_URL##',
                'display_name' => 'Admin User Delete',
                'to_email' => '##TO_EMAIL##',
                'is_admin_email' => 'f',
                'plugin' => null,
                'is_html' => 'f'
            ],
            [
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'name' => 'orderrejected',
                'description' => 'We will send mail to user once restaurant rejected order.',
                'from_email' => '##FROM_EMAIL##',
                'reply_to_email' => '##REPLY_TO_EMAIL##',
                'subject' => 'Order Rejected',
                'email_variables' => 'SITE_NAME,SITE_URL,USERANME,RESTAURANT_NAME,ORDERURL,SITE_URL',
                'html_email_content' => 'Hi ##USERNAME##,

 ##RESTAURANT_NAME## has been rejected in your order request. Please find other restaurants.
  
Order Details:
ORDERURL

Thanks,

##SITE_NAME##
##SITE_URL##',
                'text_email_content' => 'Hi ##USERNAME##,

 ##RESTAURANT_NAME## has been rejected in your order request. Please find other restaurants.
  
Order Details:
ORDERURL

Thanks,

##SITE_NAME##
##SITE_URL##',
                'display_name' => 'Order Rejected',
                'to_email' => '##TO_EMAIL##',
                'is_admin_email' => 'f',
                'plugin' => null,
                'is_html' => 'f'
            ],
            [
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'name' => 'supervisorwelcomemail',
                'description' => 'we will
send this mail to supervisor base user, when a new supervisor added by restaurant in the site.',
                'from_email' => '##FROM_EMAIL##',
                'reply_to_email' => '##REPLY_TO_EMAIL##',
                'subject' => 'Welcome to  ##SITE_NAME##',
                'email_variables' => 'SITE_NAME,RESTAURANT_NAME,SITE_URL,USERNAME,PASSWORD',
                'html_email_content' => 'Hi,

  We will welcome to the ##RESTAURANT_NAME## restaurant as a supervisor.  

Account details:

##SITE_URL##

Username :  ##USERNAME##
Password :  ##PASSWORD##  
 
Thanks,

##SITE_NAME##',
                'text_email_content' => 'Hi,

  We will welcome to the ##RESTAURANT_NAME## restaurant as a supervisor.  

Account details:

##SITE_URL##

Username :  ##USERNAME##
Password :  ##PASSWORD##  
 
Thanks,

##SITE_NAME##',
                'display_name' => 'Supervisor Welcome Mail',
                'to_email' => '##TO_EMAIL##',
                'is_admin_email' => 'f',
                'plugin' => 'Order/Supervisor',
                'is_html' => 'f'
            ],
            [
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'name' => 'deliverypersonwelcomemail',
                'description' => 'we will
send this mail to delivery person base user, when a new delivery person added by restaurant / Supervisor in the site.',
                'from_email' => '##FROM_EMAIL##',
                'reply_to_email' => '##REPLY_TO_EMAIL##',
                'subject' => 'Welcome to  ##SITE_NAME##',
                'email_variables' => 'SITE_NAME,RESTAURANT_NAME,SITE_URL,USERNAME,PASSWORD',
                'html_email_content' => 'Hi,

  We will welcome to the ##RESTAURANT_NAME## restaurant as a delivery person.  

Account details:

##SITE_URL##

Username :  ##USERNAME##
Password :  ##PASSWORD##  
 
Thanks,

##SITE_NAME##',
                'text_email_content' => 'Hi,

  We will welcome to the ##RESTAURANT_NAME## restaurant as a delivery person.  

Account details:

##SITE_URL##

Username :  ##USERNAME##
Password :  ##PASSWORD##  
 
Thanks,

##SITE_NAME##',
                'display_name' => 'Delivery Person Welcome Mail',
                'to_email' => '##TO_EMAIL##',
                'is_admin_email' => 'f',
                'plugin' => 'Order/Delivery',
                'is_html' => 'f'
            ],
            [
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'name' => 'adminpaidyourwithdrawalrequest',
                'description' => 'We will send mail to restaurant once the admin paid.',
                'from_email' => '##FROM_EMAIL##',
                'reply_to_email' => '##REPLY_TO_EMAIL##',
                'subject' => 'Amount paid',
                'email_variables' => 'SITE_NAME,RESTAURANT_NAME,SITE_URL,WITHDRAWAL_URL',
                'html_email_content' => 'Hi ##RESTAURANT_NAME##,

  We have paid your amount as you have requested from withdrawal requested.

Withdrawal:

##WITHDRAWAL_URL##

  
Thanks,

##SITE_NAME##
##SITE_URL##',
                'text_email_content' => 'Hi ##RESTAURANT_NAME##,

  We have paid your amount as you have requested from withdrawal requested.

Withdrawal:

##WITHDRAWAL_URL##

  
Thanks,

##SITE_NAME##
##SITE_URL##',
                'display_name' => 'Paid Withdrawal Request',
                'to_email' => '##TO_EMAIL##',
                'is_admin_email' => 'f',
                'plugin' => 'Common/Withdrawal',
                'is_html' => 'f'
            ],
            [
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'name' => 'newuserjoin',
                'description' => 'we will send this mail to admin, when a new user registered in the site. For this you have to enable "admin mail after register" in the settings page.',
                'from_email' => '##FROM_EMAIL##',
                'reply_to_email' => '##REPLY_TO_EMAIL##',
                'subject' => '[##SITE_NAME##] New user joined',
                'email_variables' => 'SITE_NAME,USERNAME, SITE_URL,USEREMAIL',
                'html_email_content' => 'Hi,

A new user named "##USERNAME##" has joined in ##SITE_NAME##.

Username: ##USERNAME##
Email: ##USEREMAIL##


Thanks,
##SITE_NAME##
##SITE_URL##',
                'text_email_content' => 'Hi,

A new user named "##USERNAME##" has joined in ##SITE_NAME##.

Username: ##USERNAME##
Email: ##USEREMAIL##


Thanks,
##SITE_NAME##
##SITE_URL##',
                'display_name' => 'New User Join',
                'to_email' => '##TO_EMAIL##',
                'is_admin_email' => 'f',
                'plugin' => null,
                'is_html' => 'f'
            ],
            [
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'name' => 'contactusreplymail',
                'description' => 'we will send this mail ti user, when user submit the contact us form.',
                'from_email' => '##FROM_EMAIL##',
                'reply_to_email' => '##REPLY_TO_EMAIL##',
                'subject' => 'RE: ##SUBJECT##',
                'email_variables' => 'MESSAGE, POST_DATE, SITE_NAME, CONTACT_URL, FIRST_NAME, LAST_NAME, SUBJECT, SITE_URL',
                'html_email_content' => 'Dear ##FIRST_NAME####LAST_NAME##,

Thanks for contacting us. We\'ll get back to you shortly.

Please do not reply to this automated response. If you have not contacted us and if you feel this is an error, please contact us through our site ##CONTACT_URL##

Thanks,
##SITE_NAME##
##SITE_URL##

------ On ##POST_DATE## you wrote from ##IP## -----

##MESSAGE##',
                'text_email_content' => 'Dear ##FIRST_NAME####LAST_NAME##,

Thanks for contacting us. We\'ll get back to you shortly.

Please do not reply to this automated response. If you have not contacted us and if you feel this is an error, please contact us through our site ##CONTACT_URL##

Thanks,
##SITE_NAME##
##SITE_URL##

------ On ##POST_DATE## you wrote from ##IP## -----

##MESSAGE##',
                'display_name' => 'Contact Us Auto Reply',
                'to_email' => '##TO_EMAIL##',
                'is_admin_email' => 'f',
                'plugin' => null,
                'is_html' => 'f'
            ],
            [
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'name' => 'adminchangepassword',
                'description' => 'we will send this mail to user, when admin change users password.',
                'from_email' => '##FROM_EMAIL##',
                'reply_to_email' => '##REPLY_TO_EMAIL##',
                'subject' => 'Password changed',
                'email_variables' => 'SITE_NAME,PASSWORD,USERNAME, SITE_URL',
                'html_email_content' => 'Hi ##USERNAME##,

Admin reset your password for your  ##SITE_NAME## account.

Your new password: ##PASSWORD##

Thanks,
##SITE_NAME##
##SITE_URL##',
                'text_email_content' => 'Hi ##USERNAME##,

Admin reset your password for your  ##SITE_NAME## account.

Your new password: ##PASSWORD##

Thanks,
##SITE_NAME##
##SITE_URL##',
                'display_name' => 'Admin Change Password',
                'to_email' => '##TO_EMAIL##',
                'is_admin_email' => 'f',
                'plugin' => null,
                'is_html' => 'f'
            ],
            [
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'name' => 'failedsocialuser',
                'description' => 'we will send this mail, when user submit the forgot password form and the user users social network websites like twitter, facebook to register.',
                'from_email' => '##FROM_EMAIL##',
                'reply_to_email' => '##REPLY_TO_EMAIL##',
                'subject' => 'Forgot password request failed',
                'email_variables' => 'SITE_NAME,PASSWORD,USERNAME, SITE_URL',
                'html_email_content' => 'Hi ##USERNAME##, 

Your forgot password request was failed because you have registered via ##OTHER_SITE## site.

Thanks, 
##SITE_NAME## 
##SITE_URL##',
                'text_email_content' => 'Hi ##USERNAME##, 

Your forgot password request was failed because you have registered via ##OTHER_SITE## site.

Thanks, 
##SITE_NAME## 
##SITE_URL##',
                'display_name' => 'Failed Social User',
                'to_email' => '##TO_EMAIL##',
                'is_admin_email' => 'f',
                'plugin' => null,
                'is_html' => 'f'
            ],
            [
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'name' => 'contactus',
                'description' => 'We will send this mail to admin, when user submit any contact form.',
                'from_email' => '##FROM_EMAIL##',
                'reply_to_email' => '##REPLY_TO_EMAIL##',
                'subject' => '[##SITE_NAME##] ##SUBJECT##',
                'email_variables' => 'FIRST_NAME ,LAST_NAME,FROM_EMAIL,IP,TELEPHONE, MESSAGE, SUBJECT,SITE_NAME,SITE_URL',
                'html_email_content' => '##MESSAGE##

----------------------------------------------------
First Name   : ##FIRST_NAME##  
Last Name    : ##LAST_NAME## 
Email        : ##FROM_EMAIL##
Telephone    : ##TELEPHONE##
IP           : ##IP##
Whois        : http://whois.sc/##IP##

----------------------------------------------------

Thanks,
##SITE_NAME##
##SITE_URL##',
                'text_email_content' => '##MESSAGE##

----------------------------------------------------
First Name   : ##FIRST_NAME##  
Last Name    : ##LAST_NAME## 
Email        : ##FROM_EMAIL##
Telephone    : ##TELEPHONE##
IP           : ##IP##
Whois        : http://whois.sc/##IP##

----------------------------------------------------

Thanks,
##SITE_NAME##
##SITE_URL##',
                'display_name' => 'Contact Us',
                'to_email' => '##TO_EMAIL##',
                'is_admin_email' => 'f',
                'plugin' => null,
                'is_html' => 'f'
            ],
            [
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'name' => 'adminuseradd',
                'description' => 'we will send this mail to user, when a admin add a new user.',
                'from_email' => '##FROM_EMAIL##',
                'reply_to_email' => '##REPLY_TO_EMAIL##',
                'subject' => 'Welcome to ##SITE_NAME##',
                'email_variables' => 'SITE_NAME, USERNAME, PASSWORD, LOGINLABEL, USEDTOLOGIN, SITE_URL',
                'html_email_content' => 'Dear ##USERNAME##,

##SITE_NAME## team added you as a user in ##SITE_NAME##.

Your account details.
##LOGINLABEL##:##USEDTOLOGIN##
Password:##PASSWORD##


Thanks,
##SITE_NAME##
##SITE_URL##',
                'text_email_content' => 'Dear ##USERNAME##,

##SITE_NAME## team added you as a user in ##SITE_NAME##.

Your account details.
##LOGINLABEL##:##USEDTOLOGIN##
Password:##PASSWORD##


Thanks,
##SITE_NAME##
##SITE_URL##',
                'display_name' => 'Admin User Add',
                'to_email' => '##TO_EMAIL##',
                'is_admin_email' => 'f',
                'plugin' => null,
                'is_html' => 'f'
            ],
            [
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'name' => 'failledforgotpassword',
                'description' => 'we will send this mail, when user submit the forgot password form.',
                'from_email' => '##FROM_EMAIL##',
                'reply_to_email' => '##REPLY_TO_EMAIL##',
                'subject' => 'Failed Forgot Password',
                'email_variables' => 'SITE_NAME, SITE_URL,USEREMAIL',
                'html_email_content' => 'Hi there,

You (or someone else) entered this email address when trying to change the password of an ##USEREMAIL## account.

However, this email address is not in our registered users and therefore the attempted password request has failed. If you are our customer and were expecting this email, please try again using the email you gave when opening your account.

If you are not an ##SITE_NAME## customer, please ignore this email. If you did not request this action and feel this is an error, please contact us ##SUPPORT_EMAIL##.

Thanks, 
##SITE_NAME## 
##SITE_URL##',
                'text_email_content' => 'Hi there,

You (or someone else) entered this email address when trying to change the password of an ##USEREMAIL## account.

However, this email address is not in our registered users and therefore the attempted password request has failed. If you are our customer and were expecting this email, please try again using the email you gave when opening your account.

If you are not an ##SITE_NAME## customer, please ignore this email. If you did not request this action and feel this is an error, please contact us ##SUPPORT_EMAIL##.

Thanks, 
##SITE_NAME## 
##SITE_URL##',
                'display_name' => 'Failed Forgot Password',
                'to_email' => '##TO_EMAIL##',
                'is_admin_email' => 'f',
                'plugin' => null,
                'is_html' => 'f'
            ],
            [
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'name' => 'forgotpassword',
                'description' => 'we will send this mail, when
user submit the forgot password form.',
                'from_email' => '##FROM_EMAIL##',
                'reply_to_email' => '##REPLY_TO_EMAIL##',
                'subject' => 'Forgot password',
                'email_variables' => 'USERNAME,PASSWORD,SITE_NAME,SITE_URL',
                'html_email_content' => 'Hi ##USERNAME##, 

We have changed new password as per your requested.

New password: 

##PASSWORD##

Thanks, 
##SITE_NAME## 
##SITE_URL##',
                'text_email_content' => 'Hi ##USERNAME##, 

We have changed new password as per your requested.

New password: 

##PASSWORD##

Thanks, 
##SITE_NAME## 
##SITE_URL##',
                'display_name' => 'Forgot Password',
                'to_email' => '##TO_EMAIL##',
                'is_admin_email' => 'f',
                'plugin' => null,
                'is_html' => 'f'
            ],
            [
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'name' => 'ordercancelled',
                'description' => 'We will send mail to user once user canceled order.',
                'from_email' => '##FROM_EMAIL##',
                'reply_to_email' => '##REPLY_TO_EMAIL##',
                'subject' => 'Order Cancelled',
                'email_variables' => 'SITE_NAME,SITE_URL,USERANME,RESTAURANT_NAME,ORDERURL,SITE_URL',
                'html_email_content' => 'Hi ##USERNAME##,

 Your Order has been cancelled. 
 
Order Details:
Restaurant Name : 	##RESTAURANT_NAME## 
Amount 			:	##AMOUNT##
Date			:	##DATE##

Thanks,

##SITE_NAME##
##SITE_URL##',
                'text_email_content' => 'Hi ##USERNAME##,

 Your Order has been cancelled. 
 
Order Details:
Restaurant Name : 	##RESTAURANT_NAME## 
Amount 			:	##AMOUNT##
Date			:	##DATE##

Thanks,

##SITE_NAME##
##SITE_URL##',
                'display_name' => 'Order Cancelled',
                'to_email' => '##TO_EMAIL##',
                'is_admin_email' => 'f',
                'plugin' => null,
                'is_html' => 'f'
            ],
            [
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'name' => 'restaurantwelcomemail',
                'description' => 'we will
send this mail to restaurant base user, when a new restaurant registered in the site.',
                'from_email' => '##FROM_EMAIL##',
                'reply_to_email' => '##REPLY_TO_EMAIL##',
                'subject' => 'Welcome to ##SITE_NAME##',
                'email_variables' => 'SITE_NAME,RESTAURANT_NAME,SITE_URL,USERNAME,PASSWORD',
                'html_email_content' => 'Hi,

  We wish to say a quick hello and thanks for registered in your ##RESTAURANT_NAME## restaurant in the ##SITE_NAME##.

Account details:

##SITE_URL##

Username :  ##USERNAME##
Password :  ##PASSWORD##  
 
Thanks,

##SITE_NAME##',
                'text_email_content' => 'Hi,

  We wish to say a quick hello and thanks for registered in your ##RESTAURANT_NAME## restaurant in the ##SITE_NAME##.

Account details:

##SITE_URL##

Username :  ##USERNAME##
Password :  ##PASSWORD##  
 
Thanks,

##SITE_NAME##',
                'display_name' => 'Restaurant Welcome Mail',
                'to_email' => '##TO_EMAIL##',
                'is_admin_email' => 'f',
                'plugin' => 'Restaurant/MultiRestaurant',
                'is_html' => 'f'
            ],
            [
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'name' => 'ordermailtorestaurant',
                'description' => 'We will send mail to restaurant once user placed order.',
                'from_email' => '##FROM_EMAIL##',
                'reply_to_email' => '##REPLY_TO_EMAIL##',
                'subject' => '[##SITE_NAME##] New order received [##ORDERID##]',
                'email_variables' => 'SITE_NAME,RESTAURANT_NAME,ORDERID,ORDERURL,SITE_URL',
                'html_email_content' => 'Hi ##RESTAURANT_NAME##,

  New order has been received form the user in ##SITE_NAME##.
  
Order Details:
##ORDERURL##

Thanks,

##SITE_NAME##
##SITE_URL##',
                'text_email_content' => 'Hi ##RESTAURANT_NAME##,

  New order has been received form the user in ##SITE_NAME##.
  
Order Details:
##ORDERURL##

Thanks,

##SITE_NAME##
##SITE_URL##',
                'display_name' => 'Order Mail to Restaurant',
                'to_email' => '##TO_EMAIL##',
                'is_admin_email' => 'f',
                'plugin' => 'Order/Order',
                'is_html' => 'f'
            ],
            [
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'name' => 'orderDelivered',
                'description' => 'We will send mail to user once restaurant delivered order.',
                'from_email' => '##FROM_EMAIL##',
                'reply_to_email' => '##REPLY_TO_EMAIL##',
                'subject' => 'Order Delivered',
                'email_variables' => 'SITE_NAME,USERNAME,ORDER_URL,RESTAURANT_NAME,ORDER_NO',
                'html_email_content' => 'Hi ##USERNAME##,

 ##RESTAURANT_NAME## has been delivered your order ##ORDER_NO##. We hope you enjoyed the ##SITE_NAME## Assured Experience.
  
Order Details:
##ORDER_URL##

Thanks,

##SITE_NAME##
##SITE_URL##',
                'text_email_content' => 'Hi ##USERNAME##,

 ##RESTAURANT_NAME## has been delivered your order ##ORDER_NO##. We hope you enjoyed the ##SITE_NAME## Assured Experience.
  
Order Details:
##ORDER_URL##

Thanks,

##SITE_NAME##
##SITE_URL##',
                'display_name' => 'Order Delivered',
                'to_email' => '##TO_EMAIL##',
                'is_admin_email' => 'f',
                'plugin' => 'Order/Order',
                'is_html' => 'f'
            ],
            [
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'name' => 'deliveryPersonAssigned',
                'description' => 'We will send this mail to user once restaurant assigned deliveryperson.',
                'from_email' => '##FROM_EMAIL##',
                'reply_to_email' => '##REPLY_TO_EMAIL##',
                'subject' => 'Delivery Person Assigned',
                'email_variables' => 'SITE_NAME,USERNAME,ORDER_URL,ORDER_URL,SITE_URL,AMOUNT,CURRENCY_SYMBOL',
                'html_email_content' => 'Hi ##USERNAME##,

 your order ##ORDER_NO## of ##CURRENCY_SYMBOL## ##AMOUNT## will be delivered shortly. Thanks for using ##SITE_NAME##.
  
Order Details:
##ORDER_URL##

Thanks,

##SITE_NAME##
##SITE_URL##',
                'text_email_content' => 'Hi ##USERNAME##,

 your order ##ORDER_NO## of ##CURRENCY_SYMBOL## ##AMOUNT## will be delivered shortly. Thanks for using ##SITE_NAME##.
  
Order Details:
##ORDER_URL##

Thanks,

##SITE_NAME##
##SITE_URL##',
                'display_name' => 'Delivery Person Assigned',
                'to_email' => '##TO_EMAIL##',
                'is_admin_email' => 'f',
                'plugin' => 'Order/Order',
                'is_html' => 'f'
            ],
            [
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'name' => 'orderProcessing',
                'description' => 'We will send this mail to user once restaurant accept order request',
                'from_email' => '##FROM_EMAIL##',
                'reply_to_email' => '##REPLY_TO_EMAIL##',
                'subject' => 'Processing Order',
                'email_variables' => 'SITE_NAME,USERNAME,AMOUNT,ORDER_NO,CURRENCY_SYMBOL,ORDER_URL',
                'html_email_content' => 'Hi ##USERNAME##,

 your order ##ORDER_NO## of ##CURRENCY_SYMBOL## ##AMOUNT## accepted by ##RESTAURANT## and will be delivered shortly. Thanks for using ##SITE_NAME##.
  
Order Details:
##ORDER_URL##

Thanks,

##SITE_NAME##
##SITE_URL##',
                'text_email_content' => 'Hi ##USERNAME##,

 your order ##ORDER_NO## of ##CURRENCY_SYMBOL## ##AMOUNT## accepted by ##RESTAURANT## and will be delivered shortly. Thanks for using ##SITE_NAME##.
  
Order Details:
##ORDER_URL##

Thanks,

##SITE_NAME##
##SITE_URL##',
                'display_name' => 'Processing Order',
                'to_email' => '##TO_EMAIL##',
                'is_admin_email' => 'f',
                'plugin' => 'Order/Order',
                'is_html' => 'f'
            ],
            [
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'name' => 'useraddresturantmail',
                'description' => 'we will send this mail
into admin, when user register as restarunt',
                'from_email' => '##FROM_EMAIL##',
                'reply_to_email' => '##REPLY_TO_EMAIL##',
                'subject' => '[##SITE_NAME##] Restaurant added mail',
                'email_variables' => 'SITE_NAME,EMAIL,USERNAME',
                'html_email_content' => 'Hi Admin,

##USERNAME## added the restaurant.

Thanks,
##SITE_NAME##
##SITE_URL##',
                'text_email_content' => 'Hi Admin,

##USERNAME## added the restaurant.

Thanks,
##SITE_NAME##
##SITE_URL##',
                'display_name' => 'User Registration Resturant Mail',
                'to_email' => '##TO_EMAIL##',
                'is_admin_email' => 'f',
                'plugin' => 'Restaurant/Restaurant',
                'is_html' => 'f'
            ]
        ];

        $posts = $this->table('email_templates');
        $posts->insert($data)
              ->save();
    }
}
