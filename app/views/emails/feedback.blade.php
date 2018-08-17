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
                                <h2>Feedback</h2>
                                <?php $feedback = htmlentities($feedback)?>
                                <p><strong>Message:</strong><br/>{{ nl2br($feedback) }}</p>
                                <p><strong>Page title:</strong><br/>{{{ $pageTitle }}}</p>
                                <p><strong>Full name:</strong><br/>{{{ $fullName }}}</p>
                                <p><strong>Practice name:</strong><br/>{{{ $practiceName }}}</p>
                                <p><strong>Username (email):</strong><br/>{{{ $username }}}</p>
                                <p><strong>Lims code:</strong><br/>{{{ $limsCode }}}</p>
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
