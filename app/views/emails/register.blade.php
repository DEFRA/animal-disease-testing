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
                                    <h2>Your account is now open</h2>
                                    <p>Practice name: {{{$pvsPractice->getName()}}}</p>
                                    <p>Account name: {{{$fullname}}}</p>
                                    <p>Thank you for registering for an APHA web account. I can confirm your account is open and you can complete your registration {{ link_to_route('user-activate', 'here', array($user->getId(), $user->activation_code)) }}.</p>
                                    <p>Your User ID is: {{{$user->email}}}</p>
                                    <p>The link expires after first use so use {{ link_to_route('login', 'Animal disease testing service') }} for all subsequent visits. You may wish to add this to your favourites.</p>
                                    <p>If you have any enquiries about your account, contact APHA Customer Services.</p>
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
