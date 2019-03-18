<!DOCTYPE html>
<!-- 
Template Name: Metronic - Responsive Admin Dashboard Template build with Twitter Bootstrap 3.3.6
Version: 4.6
Author: KeenThemes
Website: http://www.keenthemes.com/
Contact: support@keenthemes.com
Follow: www.twitter.com/keenthemes
Dribbble: www.dribbble.com/keenthemes
Like: www.facebook.com/keenthemes
Purchase: http://themeforest.net/item/metronic-responsive-admin-dashboard-template/4021469?ref=keenthemes
Renew Support: http://themeforest.net/item/metronic-responsive-admin-dashboard-template/4021469?ref=keenthemes
License: You must have a valid license purchased only from themeforest(the above link) in order to legally use the theme for your project.
-->
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<!-- BEGIN HEAD -->

<head>
    <meta charset="utf-8" />
    <title>Power BI</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <meta content="" name="description" />
    <meta content="" name="author" />
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/global/plugins/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/global/plugins/simple-line-icons/simple-line-icons.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/global/plugins/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css')}}" rel="stylesheet" type="text/css" />
    <!-- END GLOBAL MANDATORY STYLES -->
    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <link href="{{asset('assets/global/plugins/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/global/plugins/select2/css/select2-bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
    <!-- END PAGE LEVEL PLUGINS -->
    <!-- BEGIN THEME GLOBAL STYLES -->
    <link href="{{asset('assets/global/css/components.min.css')}}" rel="stylesheet" id="style_components" type="text/css" />
    <link href="{{asset('assets/global/css/plugins.min.css')}}" rel="stylesheet" type="text/css" />
    <!-- END THEME GLOBAL STYLES -->
    <!-- BEGIN PAGE LEVEL STYLES -->
    <link href="{{asset('assets/pages/css/login-4.min.css')}}" rel="stylesheet" type="text/css" />
    <!-- END PAGE LEVEL STYLES -->
    <!-- BEGIN THEME LAYOUT STYLES -->
    <!-- END THEME LAYOUT STYLES -->
    <link rel="shortcut icon" href="favicon.ico" /> 

    <link href="{{asset('assets/pages/css/profile.min.css')}}" rel="stylesheet" type="text/css" />

    <style type="text/css">
        body, html{
            height: 100%;
        }
        .overlay{
            overflow: hidden;
            height: 100%;
            background: rgba(0, 0, 0, 0.8) /* Green background with 30% opacity */
        }

        .login .logo{
            margin-top: 20px;
        }
    </style>

</head>
<!-- END HEAD -->

<body class="login">

    <div class="overlay">
        
        <!-- BEGIN LOGO -->
        <div class="logo">
            <a href="{{ url('/') }}">
                <img width="200px" src="" alt="" /> </a>
            </div>
            <!-- END LOGO -->
            <!-- BEGIN LOGIN -->
            <div class="content">
                <!-- BEGIN LOGIN FORM -->

                <form class="login-form" method="POST" action="{{ url('/login') }}">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <div class="profile-userpic">
                            <h2 style="text-align:center; color:white">Welcome to Power BI</h2><br>
                            <div id="image">
                                <img src="{{asset('img/no-img.png')}}" class="img-responsive" alt=""> 

                            </div>
                            <h4 style="text-align: center;" id="name"></h4>
                        </div>
                    </div>
                    {{-- <h3 class="form-title">Login to your account</h3> --}}
                    <div class="alert alert-danger display-hide">
                        <button class="close" data-close="alert"></button>
                        {{-- <span> Enter any username and password. </span> --}}
                    </div>
                    <div class="form-group">
                        <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
                        <label class="control-label visible-ie8 visible-ie9">Username</label>
                        <div class="input-icon">
                            <i class="fa fa-user"></i>
                            {{--   <input class="form-control placeholder-no-fix" type="text" autocomplete="off" placeholder="Username" name="e" />  --}}
                            <input autocomplete="off" id="email" type="text" class="form-control placeholder-no-fix" name="email" value="{{ old('email') }}" >
                        </div>
                        @if ($errors->has('email'))
                        <span class="help-block">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                        @endif
                    </div>
                    <div class="form-group">
                        <label class="control-label visible-ie8 visible-ie9">Password</label>
                        <div class="input-icon">
                            <i class="fa fa-lock"></i>
                            {{--       <input class="form-control placeholder-no-fix" type="password" autocomplete="off" placeholder="Password" name="password" /> --}}
                            <input autocomplete="off" id="password" type="password" class="form-control placeholder-no-fix" name="password">
                        </div>
                        @if ($errors->has('password'))
                        <span class="help-block">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                        @endif
                    </div>
                    <div class="form-actions" style="padding-bottom: 0px;">
                        <label class="rememberme mt-checkbox mt-checkbox-outline">
                            <input type="checkbox" name="remember" value="1" /> Remember me
                            <span></span>
                        </label>
                        <button type="submit" class="btn green pull-right"> Login </button>
                    </div>

                    <div class="forget-password">
                        <h4>Forgot your password ?</h4>
                        <p>Click
                            <a href="javascript:;" id="forget-password"> here </a> to reset your password. </p>
                        </div>
                                   {{--  <div class="create-account">
                                        <p> Don't have an account yet ?&nbsp;
                                            <a href="javascript:;" id="register-btn"> Create an account </a>
                                        </p>
                                    </div> --}}
                                </form>
                                <!-- END LOGIN FORM -->
                                <!-- BEGIN FORGOT PASSWORD FORM -->
                                {{-- <form class="forget-form" action="index.html" method="post"> --}}
                                <form class="forget-form"  method="POST" action="{{ url('password/reset') }}">
                                    {{ csrf_field() }}
                                    <h3>Forget Password ?</h3>
                                    <p> Enter your e-mail address below to reset your password. </p>
                                    <div class="form-group">
                                        <div class="input-icon">
                                            <i class="fa fa-envelope"></i>
                                            {{-- <input class="form-control placeholder-no-fix" type="text" autocomplete="off" placeholder="Email" name="email" /> --}} 
                                            <input autocomplete="off" type="email" class="form-control placeholder-no-fix" placeholder="Email" name="email" value="{{ old('email') }}">
                                        </div>
                                    </div>
                                    <div class="form-actions">
                                        <button type="button" id="back-btn" class="btn red btn-outline">Back </button>
                                        <button type="submit" class="btn green pull-right"> Submit </button>
                                    </div>
                                </form>
                                <!-- END FORGOT PASSWORD FORM -->

                                <!-- END REGISTRATION FORM -->
                            </div>
                            <!-- END LOGIN -->
                            <!-- BEGIN COPYRIGHT -->
                            <div class="copyright">Copyright &copy; 2009-{{date('Y')}}</div>
                            <!-- END COPYRIGHT -->

    </div>
</body>

</html>