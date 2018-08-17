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
                            
                                <h2>Registration</h2>

                                <p><strong>Existing APHA laboratory testing customer: </strong><br />{{{ $existing_customer }}}</p>
                                <p><strong>Business: </strong><br />{{{ $business_name or '' }}}</p>
                                <p><strong>Contact: </strong><br />{{{ $contact_name or '' }}}</p>
                                <p><strong>Address: </strong><br />{{{ $address_1 or '' }}}</p>
                                <p><strong></strong><br />{{{ $address_2 or '' }}}</p>
                                <p><strong></strong><br />{{{ $address_3 or '' }}}</p>
                                <p><strong>County: </strong><br />{{{ $county or '' }}}</p>
                                <p><strong>Postcode: </strong><br />{{{ $postcode or '' }}}</p>
                                <p><strong>Email: </strong><br />{{{ $email or '' }}}</p>
                                <p><strong>Telephone: </strong><br />{{{ $telephone or '' }}}</p>

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
