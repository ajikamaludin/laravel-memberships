<!DOCTYPE html>
<html>
<head>
  <title>Slip Gaji</title>
  <style>
    body {
      font-family: Arial, sans-serif;
    }
  </style>
</head>
<body>
  <h1 style="text-align: center;">Slip Gaji</h1>
  <div style="">
    <table style="border-collapse: collapse; width: 100%;">
      <tbody>
        <tr>
            <td style="padding: 10px; border: 1px solid #ddd;width: 100px;">Karyawan</td>
            <td style="width: 1px; padding: 10px; border: 1px solid #ddd;">:</td>
            <td style="text-align: left; padding: 10px; border: 1px solid #ddd;">{{ $payment->employee->name }}</td>
        </tr>
        <tr>
            <td style="padding: 10px; border: 1px solid #ddd;width: 100px;">Tanggal</td>
            <td style="width: 1px; padding: 10px; border: 1px solid #ddd;">:</td>
            <td style="text-align: left; padding: 10px; border: 1px solid #ddd;">{{ formatDate($payment->payment_date) }}</td>
        </tr>
        <tr>
          <td style="padding: 10px; border: 1px solid #ddd;width: 100px;">Gaji Pokok</td>
          <td style="width: 1px; padding: 10px; border: 1px solid #ddd;">:</td>
          <td style="text-align: right; padding: 10px; border: 1px solid #ddd;">{{ formatIDR($payment->basic_salary_per_session) }}</td>
        </tr>
        <tr>
            <td style="padding: 10px; border: 1px solid #ddd;width: 100px;">Jumlah Sesi</td>
            <td style="width: 1px; padding: 10px; border: 1px solid #ddd;">:</td>
            <td style="text-align: right; padding: 10px; border: 1px solid #ddd;">{{ formatIDR($payment->items()->count()) }}</td>
          </tr>
        <tr>
          <td style="padding: 10px; border: 1px solid #ddd;width: 100px;">Total</td>
          <td style="width: 1px; padding: 10px; border: 1px solid #ddd;">:</td>
          <td style="text-align: right; padding: 10px; border: 1px solid #ddd;">{{ formatIDR($payment->amount) }}</td>
        </tr>
      </tbody>
    </table>

    <table style="border-collapse: collapse; width: 100%; margin-top: 20px;">
      <tbody>
        <tr>
          <th style="padding: 10px; border: 1px solid #ddd;">Tanggal</th>
          <th style="padding: 10px; border: 1px solid #ddd;">Waktu</th>
          <th style="padding: 10px; border: 1px solid #ddd;">Kelas</th>
          <th style="padding: 10px; border: 1px solid #ddd;">Jumlah Member</th>
          <th style="padding: 10px; border: 1px solid #ddd;">Fee Kelas</th>
          <th style="padding: 10px; border: 1px solid #ddd;">Subtotal</th>
        </tr>
        @foreach($payment->items as $item)
          <tr>
            <td style="padding: 10px; border: 1px solid #ddd;">{{ formatDate($item->subjectSession->session_date) }}</td>
            <td style="padding: 10px; border: 1px solid #ddd;">{{ $item->subjectSession->trainingTime->name }}</td>
            <td style="padding: 10px; border: 1px solid #ddd;">{{ $item->subject->name }}</td>
            <td style="padding: 10px; border: 1px solid #ddd;">{{ $item->person_amount }}</td>
            <td style="padding: 10px; border: 1px solid #ddd;">{{ formatIDR($item->employee_fee_per_person) }}</td>
            <td style="padding: 10px; border: 1px solid #ddd;">{{ formatIDR($item->person_amount * $item->employee_fee_per_person) }}</td>
          </tr>
        @endforeach
        <tr>
            <td style="text-align: right; padding: 10px; border: 1px solid #ddd;" colspan="5">Subtotal</td>
            <td style="padding: 10px; border: 1px solid #ddd;">{{ formatIDR($payment->items()->selectRaw('sum(person_amount * employee_fee_per_person) as total')->first()->total) }}</td>
          </tr>
          <tr>
            <td style="text-align: right; padding: 10px; border: 1px solid #ddd;" colspan="5">Sesi x Gaji Pokok</td>
            <td style="padding: 10px; border: 1px solid #ddd;">{{ formatIDR($payment->items()->count() * $payment->basic_salary_per_session) }}</td>
          </tr>
        <tr>
            <td style="text-align: right; padding: 10px; border: 1px solid #ddd;" colspan="5">Total</td>
            <td style="padding: 10px; border: 1px solid #ddd;">{{ formatIDR($payment->amount) }}</td>
          </tr>
      </tbody>
    </table>
  </div>
</body>
<script type="text/javascript">
    window.print();
    </script>
</html>