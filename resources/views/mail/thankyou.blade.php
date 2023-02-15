<!DOCTYPE html>
<html>

<head>
    <title>Thank You Email</title>
<style>
body {
    font-family: Open Sans;
}
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
.container {
    padding: 1.5rem;
}
.verify-wrapper {
    text-align: center;
    max-width: 350px;
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
.greeting {
    text-align: left;
    margin: 15px 0px;
}
.first-name {
    text-transform: capitalize;
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
</style>

</head>

<body style="background-color: #e4e7e9;">
    <div class="container">
        <div class="verify-wrapper text-center">
            <div class="verify-header text-center mb-3">
                <div style="width: 100px; height: 100px; margin: auto;">
                    <img style="width:100%" src="{{url('/storage/images/logos/pds-logo.png')}}">
                </div>
            </div>
            <div class="greeting">
                Hi <span class="first-name">{{$registration['first_name']}}</span>,
            </div>
            <div class="verify-content text-center mb-3" style="color: #575757; line-height: 1.75;">
                Thank you for registering to the RCD Convention 2022.
                This email confirms that your registration has been successful.<br/><br/>
            </div>
        </div>
    </div>
    <div class="footer">
       <p> © {{ date('Y') }} {{ config('app.name') }}. @lang('All rights reserved.')</p>
    </div>
</body>

</html>