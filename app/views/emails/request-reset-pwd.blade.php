<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="utf-8">
</head>
<body>

<div style="font-family:Helvetica,Arial,sans-serif;font-size:16px;margin:0;color:#0b0c0c">
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tbody>
            @include('emails.header')
            <tr>
                <td border="0" cellpadding="0" cellspacing="0" width="100%">
                <table align="center" border="0" cellpadding="0" cellspacing="0" width="580">
                    <tbody>
                        <tr>
                            <td>
                                <img alt="" border="0" height="27" src="{{{asset('/assets/images/apha-logo.png')}}}" width="290" alt="Animal and Plant Health Agency">
                            </td>
                        </tr>
                        <tr>
                            <td border="0" cellpadding="0" cellspacing="0" style="font-family:Helvetica,Arial,sans-serif;color:#0b0c0c" valign="bottom" width="100%">
                                <div>
                                <h2>Reset your password</h2>
                                <span></span>
                                <p>Click the following link to {{ link_to_route('reset-password-form', 'reset your password', array($user->getId(), $user->reset_password_code)) }}.</p>
                                <p>The link expires after first use so please use {{ link_to_route('login', 'forgotten password') }} if you need to try again.</p>
                                <p>Regards,<br/>APHA Customer Support</p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                </td>
            </tr>
            @include('emails.footer')
        </tbody>
    </table>
</div>

</body>
</html>
