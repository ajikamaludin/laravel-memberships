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
                    <td colspan="2" align="center"><b>Invoices</b></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <hr>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" align="center"></td>
                </tr>
            </tbody>
        </table>

        <table border="0" width="240" cellpadding="0" cellspacing="0">
            <tbody>
                <tr>
                    <td style="width: 80px">Member ID</td>
                    <td style="width: 1px">:</td>
                    <td style="text-align: right">{{ $member->code }}</td>
                </tr>
                <tr>
                    <td style="padding-bottom: 10px"><small>{{ formatDate($transaction->transaction_date) }}</small></td>
                    <td style="width: 1px"></td>
                    <td></td>
                </tr>
                <tr>
                    <td>Paket </td>
                    <td style="width: 1px">:</td>
                    <td style="text-align: right">{{ $package }}</td>
                </tr>
                <tr>
                    <td>Harga </td>
                    <td style="width: 1px">:</td>
                    <td style="text-align: right">{{ $package_price }}</td>
                </tr>
                <tr>
                    <td style="padding-top: 10px; padding-bottom: 10px">Join Fee</td>
                    <td style="width: 1px">:</td>
                    <td style="text-align: right"> {{ $join_fee }}</td>
                </tr>
                <tr>
                    <td style="padding-top: 10px; padding-bottom: 10px">Diskon </td>
                    <td style="width: 1px">:</td>
                    <td style="text-align: right">{{ formatIDR($transaction->discount) }}</td>
                </tr>
                <tr>
                    <td style="padding-top: 10px; padding-bottom: 10px;font-weight: bold">Total:</td>
                    <td style="width: 1px">:</td>
                    <td style="text-align: right;font-weight: bold"> {{ formatIDR($transaction->amount) }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
<script type="text/javascript">
    window.print();
</script>

</html>