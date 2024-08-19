<!doctype html>
<html lang="vi">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Thông tin lịch booking</title>
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
            Kính gửi quý khách thông tin lịch Booking
        </h1>
        <table style="width: 100%; border-collapse: separate; margin-top: 20px" class="info-table">
            <tr>
                <th style="padding: 10px; text-align: left">Tên khách hàng:</th>
                <td style="padding: 10px; text-align: left">{{ $output['customer_name'] }}</td>
            </tr>
            <tr>
                <th style="padding: 10px; text-align: left">Ngày Hẹn:</th>
                <td style="padding: 10px; text-align: left">{{ $output['date_order'] }}</td>
            </tr>
            <tr>
                <th style="padding: 10px; text-align: left">Giờ hẹn:</th>
                <td style="padding: 10px; text-align: left">{{ $output['time_order'] }}</td>
            </tr>
            <tr>
                <th style="padding: 10px; text-align: left">Tên cửa hàng:</th>
                <td style="padding: 10px; text-align: left">{{ $output['store_name'] }}</td>
            </tr>
            <tr>
                <th style="padding: 10px; text-align: left">Địa chỉ cửa hàng:</th>
                <td style="padding: 10px; text-align: left">{{ $output['store_address'] }}</td>
            </tr>
            <tr>
                <th style="padding: 10px; text-align: left">Nhân Viên:</th>
                <td style="padding: 10px; text-align: left">{{ $output['staff_name'] }}</td>
            </tr>
            <tr>
                <th style="padding: 10px; text-align: left">Ghi Chú:</th>
                <td style="padding: 10px; text-align: left">{{ $output['customer_note'] }}</td>
            </tr>
        </table>
        <h4 style="margin-top: 5px;font-weight: bold; ">Dịch vụ đã chọn:</h4>
        <table border="0" style="width: 100%; border-spacing: 0; border-collapse: collapse" class="services-table">
            <thead style="border: 1px solid #ddd; border-bottom: 0px solid transparent">
                <tr>
                    <th style="padding: 10px;
                                text-align: center;
                                background-color: #f0f0f0;
                                border-bottom: 2px solid #ddd;
                                font-weight: bold">
                        STT
                    </th>
                    <th style="
                                padding: 10px;
                                text-align: center;
                                background-color: #f0f0f0;
                                border-bottom: 2px solid #ddd;
                                font-weight: bold">
                        Dịch vụ
                    </th>
                    <th style="
                                padding: 10px;
                                text-align: center;
                                background-color: #f0f0f0;
                                border-bottom: 2px solid #ddd;
                                font-weight: bold">
                        Giá tiền
                    </th>
                    <th style="
                                padding: 10px;
                                text-align: center;
                                background-color: #f0f0f0;
                                border-bottom: 2px solid #ddd;">
                        Thời gian
                    </th>
                </tr>
            </thead>
            <tbody style="border: 1px solid #ddd">
                @foreach ($output['services'] as $index => $service)
                <tr>
                    <td style="padding: 10px; text-align: center; border-bottom: 1px solid #ddd">{{ $index + 1 }}</td>
                    <td style="padding: 10px; text-align: center; border-bottom: 1px solid #ddd">
                        {{ $service['name'] }}
                    </td>
                    <td style="padding: 10px; text-align: center; border-bottom: 1px solid #ddd">
                        {{ $service['price'] }}VND
                    </td>
                    <td style="padding: 10px; text-align: center; border-bottom: 1px solid #ddd">
                        {{ $service['time'] }}p
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
            <tr class="a">

               <td colspan="2" style="
                           padding: 10px;
                           text-align: right;
                           font-weight: bold;
                           border-left: none !important;
                           border-right: none !important;
                           border-bottom: none !important;">
                   Giảm giá:
               </td>
               <td style="
                           padding: 10px;
                           text-align: center;
                           border-left: none !important;
                           border-right: none !important;
                           border-bottom: none !important;">
                   {{ $output['discount_amount'] }}.00VND
               </td>
            </tr>
                <tr class="a">

                    <td colspan="2" style="
                                padding: 10px;
                                text-align: right;
                                font-weight: bold;
                                border-left: none !important;
                                border-right: none !important;
                                border-bottom: none !important;">
                        Tổng Tiền:
                    </td>
                    <td style="
                                padding: 10px;
                                text-align: center;
                                border-left: none !important;
                                border-right: none !important;
                                border-bottom: none !important;">
                        {{ $output['total_price'] }}.00VND
                    </td>
                    <td style="
                                padding: 10px;
                                text-align: center;
                                border-left: none !important;
                                border-right: none !important;
                                border-bottom: none !important;">
                        {{ $output['total_time'] }}p
                    </td>
                </tr>
            </tfoot>
        </table>
        <hr />
        <div style="text-align: center; margin-top: 20px" class="contact">
            <p>Xin chân thành cảm ơn quý khách đã sử dụng dịch vụ của IMTATECH!</p>
            <h3 style="margin-top: 50px">Mọi thắc mắc xin liên hệ:</h3>
            <p><a href="tel:0926755061" style="text-decoration: none;">0926755061</a></p>
            <p><a href="mailto:manhpkph30134@gmail.com" style="text-decoration: none;">manhpkph30134@gmail.com</a></p>
        </div>
    </div>
</body>

</html>
