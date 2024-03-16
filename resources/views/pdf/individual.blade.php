<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">


    <title>KYC AGREEMENT</title>

    <style>
        .smpltxt {

            line-height: 30px;
        }

        .pflot {
            float: center;
        }

        thead tr {
            width: 100%;
            font-size: 0.8rem;

        }

        tbody td {

            font-size: 20px;
            font-weight: bold;

        }

        @font-face {
            font-family: 'Garet';
            font-weight: normal;
            font-style: normal;

            src: url("/Garet/Garet.ttf") format('truetype');
        }

        p {
            font-size: 0.8rem;
            line-height: 17px;
            font-weight: bold;
        }

        body {
            font-family: 'Garet', sans-serif;
        }

        .header {
            background-color: #c5c0c0;
            width: 100%;
            margin-bottom: 16px;
        }

        .header strong {
            font-size: 0.8rem;
            padding: 0px 10px 10px;
        }

        .box3 {
            float: left;
            width: 33.33%;
            margin-top: 0px;
            margin-bottom: 10px;
        }

        .box2 {
            float: left;
            width: 60%;
            margin-top: 0px;
            margin-bottom: 10px;
        }

        .box4 {
            float: left;
            width: 25%;
            margin-top: 0px;
            margin-bottom: 10px;
        }

        .clearfix::after {

            content: "";
            clear: both;
            display: table;
        }

        .footer {
            position: absolute;
            width: 100%;
            bottom: 10px;

        }
    </style>
</head>

@php
    $aadhar = json_decode($user['get_aadhar_data']['core']);
    $bank = $user['get_bank_data'];
@endphp

<body>
    <center><u>KNOW YOUR CUSTOMER (KYC) ACKNOWLEDGEMENT FORM </u>
    </center>
    <h5>
        I {{ $user['name'] }}, A citizen of India, residing in {{ $aadhar->data->split_address->dist }}, {{ $aadhar->data->split_address->state }}, hereby provide the following details for
        Know Your Customer (KYC) purposes:
    </h5>

    <div class="header">
        <strong>
            A. IDENTITY DETAILS:
        </strong>
    </div>
    <p>1. Full Name : {{ $user['name'] }}</p>
    <p>2. Aadhar Number : {{ $user['get_aadhar_data']['aadhar_number'] }}</p>
   
    <p style="line-height:25px;">3. Address : {{  str_replace('-,',"",$aadhar->data->address) }}</p>

    <div class="clearfix">
        <p class="box3">4. City : {{ $aadhar->data->split_address->dist }}</p>
        <p class="box3">5. State : {{ $aadhar->data->split_address->state }}</p>
        <p class="box3">6. Pin Code : {{ $aadhar->data->split_address->pincode }}</p>
    </div>
    <div class="clearfix" style="margin-top: 10px">
        <p class="box3">7. Date of Birth: {{  $aadhar->data->dob }}</p>
        <p class="box3">8. Gender : {{ $aadhar->data->gender=="M"?"Male":$aadhar->data->gender }}</p>
        <p class="box3">9. Father’s/ Spouse Name:  {{ $aadhar->data->care_of }}</p>
    </div>
    <p class="smpltxt" style="margin-top:-5px;">10. Specify the proof of Identity submitted:  Aadhar Card</p>
    <div class="header">
        <strong>
            B. CONTACT INFORMATION:
        </strong>
    </div>
    <div class="clearfix">
        <p class="box2">1. Email Address :{{ $user['email']!=null?$user['email']:"NA" }}</p>
        <p class="box2">2. Phone Number: {{ $user['mobile_number'] }}</p>

    </div>
    <div class="header">
        <strong>
            C. BANK DETAILES:
        </strong>
    </div>
    <div class="clearfix">
        <p class="box2">1. Account Holder Name: {{ $bank['account_holder_name'] }}</p>
        <p class="box2">2. Type of Account: [Savings/Current]</p>
    </div>
    <div class="clearfix">
        <p class="box2">3. Account Number:  {{ $bank['account_number'] }}</p>
        <p class="box2">4. IFSC Code: {{ $bank['ifsc_code'] }}</p>

    </div>

    <div class="header">
        <strong>
            D. AGREEMENT TO NEXGINO's POLICIES:
        </strong>
    </div>
    <p> I hereby acknowledge and agree to Nexgino's:</p>
    <div class="clearfix">
        <p class="box4">1. <a href="https://nexgino.com">Privacy Policy</a></p>
        <p class="box4">2. <a href="https://nexgino.com">Refund Policy</a></p>
        <p class="box4">3. <a href="https://nexgino.com">Terms and Conditions</a></p>
        <p class="box4">4. <a href="https://nexgino.com">Cancellation Policy</a></p>
    </div>

    <div class="header">
        <strong>
            E. RESPONSIBILITY FOR PROJECT:
        </strong>
    </div>
    <p class="smpltxt">
        I understand and accept full responsibility for my project. Nexgino is not liable for any issues, losses, or
        damages currently or in the future.
    </p>
    <div class="header">
        <strong>
            F. REFUND PROCESS:
        </strong>
    </div>
    <p class="smpltxt">
        In the event of any refund, the amount will be credited to the provided bank account. I acknowledge that Nexgino
        is not responsible for any errors in the provided bank details.
    </p>

    <div class="header">
        <strong>
            G. LEGAL COMPLIANCE:
        </strong>
    </div>
    <p class="smpltxt">
        I confirm that all the information provided is accurate, and I am legally authorized to enter into this
        KYC.
    </p>
    <p class="smpltxt">
        By providing the above information and agreeing to Nexgino's policies, I confirm the accuracy of the details
        provided, accept the terms outlined in this KYC Agreement, and agree to abide by the confidentiality agreement.
    </p>
    <div class="footer">
        <div class="clearfix">
            <p class="box2" style="width: 75%; font-weight:normal"> Date: {{ date('d-m-Y') }}</p>
            <p class="box2" style="width: 75%; font-weight:normal">IP LOCKED: {{ request()->ip() }}</p>
        </div>
    </div>
</body>

</html>
