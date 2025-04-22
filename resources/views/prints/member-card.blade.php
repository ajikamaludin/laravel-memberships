<!DOCTYPE html>
<html lang="en" data-theme="winter">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        /*  */
    </style>
    <style>
        .page-break {
            page-break-after: always;
        }
    </style>
</head>

<body>
    <div class="col-md-10 col-md-offset-1">
        <table width="240" border="0">
            <tbody>
                <tr>
                    <td align="center" colspan="4">{{ $setting->getValueByKey('name') }}, {{ $setting->getValueByKey('city') }}<br></td>
                </tr>
                <tr>
                    <td colspan="2" align="center"><b>MEMBER CARD</b></td>
                </tr>
                <tr>
                    <td colspan="2"><hr></td>
                </tr>
                <tr>
                    <td colspan="2" align="center"></td>
                </tr>
            </tbody>
        </table>

        <table border="0" width="240" cellpadding="0" cellspacing="0">
            <tbody>
                <tr>
                    <td rowspan="2">
                        {!! $qr_code !!}
                    </td>
                    <td>Member ID: <br>{{ $member->code }} </td>
                </tr>
                <tr>
                    <td>Nama: <br>{{ $member->name }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
<script type="text/javascript">
window.print();
</script>
</html>