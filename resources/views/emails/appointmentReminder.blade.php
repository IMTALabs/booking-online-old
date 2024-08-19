<!doctype html>
<html lang="vi">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <style>
        th,
        td {
            border: 0;
        }
    </style>
</head>

<body style="font-family: sans-serif; margin: 0; padding: 0; background-color: #f4f4f4">
    <div style="
                width: 80%;
                max-width: 600px;
                margin: 40px auto;
                padding: 20px;
                background-color: #fff;
                border-radius: 10px;
                box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            ">
        <h1 style="text-align: center; font-size: 24px; color: #248df7">
            Thông tin Booking của bạn sau 15 phút nữa
        </h1>
        <table style="width: 100%; border-collapse: separate; margin-top: 20px" class="info-table">

            <tr>
                <th style="padding: 10px; text-align: left">Giờ hẹn:</th>
                <td style="padding: 10px; text-align: left">{{ $booking->time}}</td>
            </tr>
            <tr>
                <th style="padding: 10px; text-align: left">Ngày:</th>
                <td style="padding: 10px; text-align: left">{{$booking->day}}</td>
            </tr>
            @foreach ($booking->bases as $base)
            <tr>
                <th style="padding: 10px; text-align: left">Tên cửa hàng:</th>
                <td style="padding: 10px; text-align: left">{{ $base->store_name}}</td>
            </tr>
            <tr>
                <th style="padding: 10px; text-align: left">Tên khách hàng:</th>
                <td style="padding: 10px; text-align: left">{{ $base->name}}</td>
            </tr>

            <tr>
                <th style="padding: 10px; text-align: left">Số điện thoại:</th>
                <td style="padding: 10px; text-align: left">{{ $base->phone}}</td>
            </tr>
            <tr>
                <th style="padding: 10px; text-align: left">Ghi Chú:</th>
                <td style="padding: 10px; text-align: left">{{ $base->note }}</td>
            </tr>
            @endforeach
        </table>
        <hr />
        <div style="text-align: center; margin-top: 20px" class="contact">
            <p style="text-align: center;">Trân Trọng!</p>
        </div>
    </div>
</body>

</html>
