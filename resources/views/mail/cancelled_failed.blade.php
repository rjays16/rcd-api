<!DOCTYPE html>
<html>

<head>
    <title>Cancelled/Failed Registration Email</title>
<style>
.btn {
    margin-top: 1.5rem;
    color: #fff !important;
    background-color: #007bff;
    display: inline-block;
    text-decoration: none;
    text-align: center;
    vertical-align: middle;
    border: 1px solid #007bff;
    font-family: 'Open Sans';
    padding: 10px;
    padding-left: 40px;
    padding-right: 40px;
    box-shadow: 1px 2px 15px #ABABAB;
    border-radius: 20px;
}
.btn:hover {
    background-color: #2E93FF;
    transition: 0.5s;
}
.btn:active {
    background-color: #0065D1;
    transition: 0.5s;
}
.clickable {
    cursor: pointer;
}
.container {
    padding: 1.5rem;
}
.main-wrapper {
    max-width: 500px;
    margin: auto;
    background-color: #fff;
    height: auto;
    padding: 2.5rem;
    box-shadow: 1px 1px 15px #A6A9AA;
}
.header-content {
    font-family: 'Open Sans';
    text-align: center;
    color: #474747;
    margin-top: 0;
    font-size: 2rem;
}
.text-center {
    text-align: center;
}
.greeting {
    text-align: left;
    margin: 15px 0px;
}
.first-name {
    text-transform: capitalize;
}
table {
    border: 1px solid black;
    width: 100%;
}
td {
    border: 1px solid black;
    padding: 0px 5px;
}
.w-50 {
    width: 50%;
}
.footer {
    margin: 0 auto;
    padding: 0;
    padding-bottom: .25rem;
    text-align: center;
    width: 570px;
    -premailer-cellpadding: 0;
    -premailer-cellspacing: 0;
    -premailer-width: 570px;
}
.footer p {
    color: #AEAEAE;
    font-size: 12px;
    text-align: center;
    font-family: 'Open Sans';
}
.payment-details {
    font-family: 'Open Sans';
    color: #575757;
    line-height: 1.75;
}
.mb-0 {
    margin-bottom: 0px;
}
</style>

</head>

<body style="background-color: #e4e7e9;">
    <div class="container">
        <div class="main-wrapper">
            <div class="greeting">
                Dear <span class="first-name">{{join(' ', array_filter(array($user['first_name'], $user['last_name'])))}}</span>,
                <p>Sorry, but your payment may have been declined or canceled  for the <b>25<sup>th</sup> Regional Conference of Dermatology</b>.</p>
                <p>There is no need for you to go through the registration again, you can login your account now using the email & password that you have submitted and try to process your payment again through the profile dashboard page.
                <p>Please check the information in your registration details below. Your user email is <b>{{$user['email']}}</b> and contains the following information:</p>
            </div>
            <div class="payment-details text-left mb-3">
                <table>
                    <tr>
                        <td class="w-50">Registration Type:</td>
                        <td>{{ $user->member->registration_type["name"] }}</td>
                    </tr>                    
                    <tr>
                        <td class="w-50">Last Name:</td>
                        <td>{{ $user['last_name'] }}</td>
                    </tr>
                    <tr>
                        <td class="w-50">First Name:</td>
                        <td>{{ $user['first_name'] }}</td>
                    </tr>
                    <tr>
                        <td class="w-50">Email Address:</td>
                        <td>{{ $user['email'] }}</td>
                    </tr>
                </table>
                <p class="mb-0">You may log in to your delegate dashboard through this link:</p>
                <a class="btn clickable" target="_blank" href="{{ config('settings.CONVENTION_URL').'login' }}">
                    Login
                </a><br><br>

                <p class="mb-0">If you forgot your password, you can reset it in the login page by clicking the <i><a target="_blank" href="{{ config('settings.CONVENTION_URL').'password/reset-request' }}">forgot password</i>.</p>
                <br/>

                <p class="mb-0">If your issue is not mentioned above, please contact our technical support at registration@rcd2022manila.com.ph , expect a 24-48 hour turn around.</p>

                <b>RCD Registration Committee, 25th RCD</b><br/>
                
                <div class="main-header mb-3">
                    <div style="width: 100px; height: 100px;">
                        <img style="width:100%" src="{{url('/storage/images/logos/logo-rcd2022.png')}}">
                    </div>
                </div>                
            </div>
        </div>
    </div>
    <div class="footer">
       <p> © {{ date('Y') }} {{ config('app.name') }}. @lang('All rights reserved.')</p>
    </div>
</body>

</html>