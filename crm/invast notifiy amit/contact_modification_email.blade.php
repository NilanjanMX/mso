<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Newsletter</title>
</head>

<body style="width: 600px; margin: 0 auto;">
    <table cellpadding="0" cellspacing="0"
        style="width:600px; font-family: Arial, Helvetica, sans-serif; font-size: 16px; line-height: 22px; border: 1px solid #C6D7FF;">
        
        <!-- header area start -->
        <tr>
            <td style="padding:25px 25px 0 25px; width: 80%;">
                <table cellpadding="0" cellspacing="0" width="100%">
                    <tr>
                        <td style="font-family: Arial, Helvetica, sans-serif; font-size:15px ;font-weight:bold; color: #264A99; padding-bottom: 10px;"><span style="font-weight: 300;">[MEdge]</span>  Contact Modification Alert Mail </td>
                    </tr>
                    <tr>
                        <td>
                            <table cellpadding="0" cellspacing="0" width="100%">
                                <tr>
                                    <td>
                                        <table cellpadding="0" cellspacing="0" width="100%">
                                            <tr>
                                                <td style="font-family: Arial, Helvetica, sans-serif; font-size: 15px; font-weight: bold; color: #000; padding: 0 0 0 0;">  
                                                
                                                
                                                <table cellpadding="0" cellspacing="0" width="100%">
                                                    
                                                    <tr>
                                                        <td style="width: 9%;"><img src="{{ asset('/assets/images/userImg.png') }}" ></td>
                                                        <td><span style="color: #3D5BFF;"> {{ $oldData['createdBy'] }} has modified a contact which has been assigned to you </span></td>
                                                    </tr>
                                                    <tr>
                                                        <td style="width: 9%;">&nbsp;</td>
                                                        <td style="font-family: Arial, Helvetica, sans-serif; font-size: 10px; font-weight: bold; color: #000; padding: 0 0 12px 0;">On {{date('d F, Y h:i A')}}</td>
                                                    </tr>

                                                </table>
                                              
                                            </td>
                                            </tr>
                                            
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    

                </table>
            </td>
            <td style="width: 20%;">
                <img src="{{ asset('/assets/images/logo.png') }}" alt="">
            </td>
        </tr>
        <!-- header area end -->

        
        <!-- task image start -->
        <tr>
            <td style="text-align:center; padding: 0 20px 0 20px;" colspan="2">
                <div style="border-top:solid 1px #407BFF ; padding-top: 5px;"><img src="{{ asset('/assets/images/frame09.png') }}" alt=""></div>
            </td>
        </tr>
        <!-- task image end -->

        <!-- data table start -->
        <tr>
            <td colspan="2" style="padding:20px;">
                <table cellpadding="0" cellspacing="0" width="100%">
                    <tr>
                        <td style="border:1px solid #C6D7FF; background-color: #F7F7F7; border-radius: 10px; padding: 10px 30px 30px 30px;">
                            <table cellpadding="0" cellspacing="0" width="100%">
                                <tr>
                                    <td style=" width: 33%; font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #407BFF; padding: 5px; border-right: 1px solid #C6D7FF; border-bottom: 1px solid #C6D7FF;">Contact Components</td>
                                    <td style="width: 33%; border-right: 1px solid #C6D7FF;  border-bottom: 1px solid #C6D7FF; font-size: 12px; font-weight: bold;padding:5px 5px 5px 20px; color: #407BFF; ">Old Data</td>
                                    <td style="font-size: 12px; font-weight: bold;padding:5px 5px 5px 20px; border-bottom: 1px solid #C6D7FF; color: #407BFF;">New Data</td>
                                </tr>
                                @if($oldData['contact_name']!= $newData['contact_name'])
                                <tr>
                                    <td style=" width: 33%; font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #000; padding: 5px; border-right: 1px solid #C6D7FF; border-bottom: 1px solid #C6D7FF; vertical-align: top;">Contact Name</td>
                                    <td style="width: 33%; border-right: 1px solid #C6D7FF;  border-bottom: 1px solid #C6D7FF; font-size: 12px; padding:5px 5px 5px 20px; vertical-align: top;">{{$oldData['contact_name']}}</td>
                                    <td style="font-size: 12px; padding:5px 5px 5px 20px; border-bottom: 1px solid #C6D7FF; vertical-align: top;">{{$newData['contact_name']}}</td>
                                </tr>
                                @endif
                                @if($oldData['contactNumber']!= $newData['contactNumber'])
                                <tr>
                                    <td style=" width: 33%; font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #000; padding: 5px; border-right: 1px solid #C6D7FF; border-bottom: 1px solid #C6D7FF; vertical-align: top;">Contact Number</td>
                                    <td style="width: 33%; border-right: 1px solid #C6D7FF;  border-bottom: 1px solid #C6D7FF; font-size: 12px; padding:5px 5px 5px 20px; vertical-align: top;">
                                        
                                        <table cellpadding="0" cellspacing="0" width="100%" >
                                            <tr>
                                                <td style="width: 20%;"><img src="{{ asset('/assets/images/userImg.png') }}" width="24" height="24" ></td>
                                                <td>{{$oldData['contactNumber']}}  </td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td style="font-size: 12px; padding:5px 5px 5px 20px; border-bottom: 1px solid #C6D7FF; vertical-align: top;">
                                        
                                        <table cellpadding="0" cellspacing="0" width="100%" >
                                            <tr>
                                                <td style="width: 22%;"><img src="{{ asset('/assets/images/userImg.png') }}" width="24" height="24" ></td>
                                                <td>{{$newData['contactNumber']}} </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                @endif
                                @if($oldData['contact_email']!= $newData['contact_email'])
                                <tr>
                                    <td style=" width: 33%; font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #000; padding: 5px; border-right: 1px solid #C6D7FF; border-bottom: 1px solid #C6D7FF; vertical-align: top;">Contact Email</td>
                                    <td style="width: 33%; border-right: 1px solid #C6D7FF;  border-bottom: 1px solid #C6D7FF; font-size: 12px; padding:5px 5px 5px 20px; vertical-align: top;">{{$newData['contact_email']}}</td>
                                    <td style="font-size: 12px; padding:5px 5px 5px 20px; border-bottom: 1px solid #C6D7FF; vertical-align: top;">{{$oldData['contact_email']}}</td>
                                </tr>
                                @endif
                                @if($oldData['dob']!= $newData['dob'])
                                <tr>
                                    <td style=" width: 33%; font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #000; padding: 5px; border-right: 1px solid #C6D7FF; border-bottom: 1px solid #C6D7FF; vertical-align: top;">DOB</td>
                                    <td style="width: 33%; border-right: 1px solid #C6D7FF;  border-bottom: 1px solid #C6D7FF; font-size: 12px; padding:5px 5px 5px 20px; vertical-align: top;"><img src="{{ asset('/assets/images/calender_icon.png') }}" width="20" height="20" > {{ $oldData['dob'] }}</td>
                                    <td style="font-size: 12px; padding:5px 5px 5px 20px; border-bottom: 1px solid #C6D7FF; vertical-align: top;"><img src="{{ asset('/assets/images/calender_icon.png') }}" width="20" height="20" > {{ $newData['dob'] }}</td>
                                </tr>
                                @endif
                                @if($oldData['contact_type']!= $newData['contact_type'])
                                <tr>
                                    <td style=" width: 33%; font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #000; padding: 5px; border-right: 1px solid #C6D7FF; vertical-align: top;">Contact Type</td>
                                    <td style="width: 33%; border-right: 1px solid #C6D7FF;  font-size: 12px; padding:5px 5px 5px 20px; vertical-align: top;">{{$oldData['contact_type']}} </td>
                                    <td style="font-size: 12px; padding:5px 5px 5px 20px; vertical-align: top;">{{$newData['contact_type']}}</td>
                                </tr>
                                @endif
                                @if($oldData['refrrer_name']!= $newData['refrrer_name'])
                                <tr>
                                    <td style=" width: 33%; font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #000; padding: 5px; border-right: 1px solid #C6D7FF; vertical-align: top;">Contact Type</td>
                                    <td style="width: 33%; border-right: 1px solid #C6D7FF;  font-size: 12px; padding:5px 5px 5px 20px; vertical-align: top;">{{$oldData['refrrer_name']}} </td>
                                    <td style="font-size: 12px; padding:5px 5px 5px 20px; vertical-align: top;">{{$newData['refrrer_name']}}</td>
                                </tr>
                                @endif
                                @if($oldData['state']!= $newData['state'])
                                <tr>
                                    <td style=" width: 33%; font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #000; padding: 5px; border-right: 1px solid #C6D7FF; vertical-align: top;">State</td>
                                    <td style="width: 33%; border-right: 1px solid #C6D7FF;  font-size: 12px; padding:5px 5px 5px 20px; vertical-align: top;">{{$oldData['state']}} </td>
                                    <td style="font-size: 12px; padding:5px 5px 5px 20px; vertical-align: top;">{{$newData['state']}}</td>
                                </tr>
                                @endif
                                @if($oldData['city']!= $newData['city'])
                                <tr>
                                    <td style=" width: 33%; font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #000; padding: 5px; border-right: 1px solid #C6D7FF; vertical-align: top;">City</td>
                                    <td style="width: 33%; border-right: 1px solid #C6D7FF;  font-size: 12px; padding:5px 5px 5px 20px; vertical-align: top;">{{$oldData['city']}} </td>
                                    <td style="font-size: 12px; padding:5px 5px 5px 20px; vertical-align: top;">{{$newData['city']}}</td>
                                </tr>
                                @endif
                                @if($oldData['address_one']!= $newData['address_one'])
                                <tr>
                                    <td style=" width: 33%; font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #000; padding: 5px; border-right: 1px solid #C6D7FF; vertical-align: top;">Address One</td>
                                    <td style="width: 33%; border-right: 1px solid #C6D7FF;  font-size: 12px; padding:5px 5px 5px 20px; vertical-align: top;">{{$oldData['address_one']}} </td>
                                    <td style="font-size: 12px; padding:5px 5px 5px 20px; vertical-align: top;">{{$newData['address_one']}}</td>
                                </tr>
                                @endif
                                @if($oldData['address_two']!= $newData['address_two'])
                               
                                <tr>
                                    <td style=" width: 33%; font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #000; padding: 5px; border-right: 1px solid #C6D7FF; vertical-align: top;">Address Two</td>
                                    <td style="width: 33%; border-right: 1px solid #C6D7FF;  font-size: 12px; padding:5px 5px 5px 20px; vertical-align: top;">{{$oldData['address_two']}} </td>
                                    <td style="font-size: 12px; padding:5px 5px 5px 20px; vertical-align: top;">{{$newData['address_two']}}</td>
                                </tr>
                                @endif
                                @if($oldData['loyalty_rank']!= $newData['loyalty_rank'])
                               
                                <tr>
                                    <td style=" width: 33%; font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #000; padding: 5px; border-right: 1px solid #C6D7FF; vertical-align: top;">Loyalty Rank</td>
                                    <td style="width: 33%; border-right: 1px solid #C6D7FF;  font-size: 12px; padding:5px 5px 5px 20px; vertical-align: top;">{{$oldData['loyalty_rank']}} </td>
                                    <td style="font-size: 12px; padding:5px 5px 5px 20px; vertical-align: top;">{{$newData['loyalty_rank']}}</td>
                                </tr>
                                @endif
                                {{-- // invast notifiy amit --}}
                                @if (count($invasStatus))
                                    @foreach ($invasStatus as $item)
                                        {!! $item !!}
                                    @endforeach
                                @endif
                                @if (count($invasRemarks))
                                    @foreach ($invasRemarks as $item)
                                        {!! $item !!}
                                    @endforeach
                                @endif
                                {{-- // invast notifiy amit xxx --}}
                                <tr>
                                    <td style=" width: 33%; font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #000; padding: 5px; border-right: 1px solid #C6D7FF; vertical-align: top;">Account Type</td>
                                    <td style="width: 33%; border-right: 1px solid #C6D7FF;  font-size: 12px; padding:5px 5px 5px 20px; vertical-align: top;">{{$oldData['account_type']}} </td>
                                    <td style="font-size: 12px; padding:5px 5px 5px 20px; vertical-align: top;">{{$newData['account_type']}}</td>
                                </tr>

                                <tr>
                                    <td style=" width: 33%; font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #000; padding: 5px; border-right: 1px solid #C6D7FF; vertical-align: top;">Potential</td>
                                    <td style="width: 33%; border-right: 1px solid #C6D7FF;  font-size: 12px; padding:5px 5px 5px 20px; vertical-align: top;">{{$oldData['potential']}} </td>
                                    <td style="font-size: 12px; padding:5px 5px 5px 20px; vertical-align: top;">{{$newData['potential']}}</td>
                                </tr>

                                <tr>
                                    <td style=" width: 33%; font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #000; padding: 5px; border-right: 1px solid #C6D7FF; vertical-align: top;">Client Since</td>
                                    <td style="width: 33%; border-right: 1px solid #C6D7FF;  font-size: 12px; padding:5px 5px 5px 20px; vertical-align: top;">{{$oldData['client_since']}} </td>
                                    <td style="font-size: 12px; padding:5px 5px 5px 20px; vertical-align: top;">{{$newData['client_since']}}</td>
                                </tr>                              
                            </table>
                            <table cellpadding="0" cellspacing="0" width="100%" style="padding-top: 20px;">
                                <tr>
                                    <td style="width: 173px;">
                                        <button style="background-color: #407BFF; width: 153px; height: 33px; color: #fff; font-weight: bold; font-size: 12px; border: none; border-radius: 3px;">Manage details</button>
                                    </td>
                                    <td><a href="#" style="color: #407BFF; text-decoration: none; font-size: 12px;">Manage Notifications</a> </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <!-- data table start -->

        <!-- footer area start -->
        <tr>
            <td colspan="2" style="padding:20px;">
                <table cellpadding="0" cellspacing="0" width="100%">
                    <tr>
                        <td style="font-size: 10px; color: #000; text-align: center;">
                            Powered By <span style="position: relative; top: 10px; padding-left: 10px;"><img src="{{ asset('/assets/images/footerLogo.png') }}" alt=""></span>
                        </td>
                    </tr>
                    <tr>
                        <td style="font-size: 10px;color: #000; text-align: center;">© 2024 All Rights Reserved.</td>
                    </tr>
                </table>
            </td>
        </tr>
        <!-- footer area end -->
        
    </table>
</body>

</html>