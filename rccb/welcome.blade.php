<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{date('d-m-Y')}} leave report</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">
        <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
        <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
    </head>
    <body>
        @php
            $res = App\Models\Employee::toBase()->get();
        @endphp
        <form action="{{url('imE')}}" method="post" enctype="multipart/form-data">
            @csrf
            <input type="file" name="file" >
            <input type="submit" value="submit">
        </form>
        <table id="example" class="display nowrap" style="width:100%">
            <thead>
                <tr>
                    <td>emp id</td>
                    <td>emp name</td>
                    <td>Casual Leave taken</td>
                    <td>Medical Leave taken</td>
                    <td>Privilege Leave taken</td>
                    {{-- <td>Privilege Leave taken</td> --}}
                    <td>Maternity Leave taken</td>
                    <td>Compensatory Leave taken</td>
                    <td>Extra Oridinary Leave taken</td>
                    <td>Special Leave taken</td>
                    <td>Paternity Leave taken</td>
                    <td>Special Sick Leave taken</td>
                </tr>
            </thead>
            <tbody>
                @foreach ($res as $item)
                @if ($item->emp_id != 1001 && $item->emp_id != 1002)   
                <tr>
                    <td>{{$item->emp_id}}</td>
                    <td>{{$item->name}}</td>
                    <td>
                        {{-- Casual Leave --}}
                        @php
                            $leave = App\Models\EmployeeLeave::where('emp_id', $item->emp_id)->first();
                            
                            if ( $leave && $leave->casual_leave ) {
                                echo $leave->casual_leave;
                            } else {
                                echo $item->casual_leave;
                            }
                        @endphp
                    </td>
                    <td>
                        {{-- Medical Leave --}}
                        @php
                            $leave = App\Models\EmployeeLeave::where('emp_id', $item->emp_id)->first();
                            if ( $leave && $leave->medical_leave ) {
                                echo $leave->medical_leave;
                            } else {
                                echo $item->medical_leave;
                            }
                        @endphp
                    </td>
                    <td>
                        {{-- Privilege Leave --}}
                        @php
                            $leave = App\Models\EmployeeLeave::where('emp_id', $item->emp_id)
                            ->first();
                            
                            if ( $leave && $leave->privilege_leave ) {
                                echo $leave->privilege_leave;
                            } else {
                                if ($item->employee_type == 'Contractual') {
                                    echo 0;
                                } else {
                                    echo $item->privilege_leave;
                                }
                                
                            }
                        @endphp
                    </td>
                    {{-- <td> --}}
                        {{-- Privilege Leave taken --}}
                        @php
                            // $leave = App\Models\EmployeeLeave::where('emp_id', $item->emp_id)
                            // ->where('leave_type', 3)
                            // ->where('privilege_leave_type', 'like', 'privilage%')
                            // ->whereBetween('start_date', ['2023-01-01', '2023-12-31'])
                            // ->where('status','approve')->get();
                            
                            // echo count($leave);
                        @endphp
                    {{-- </td> --}}
                    <td>
                        {{-- Maternity Leave --}}
                        @php
                            $leave = App\Models\EmployeeLeave::where('emp_id', $item->emp_id)
                            ->first();

                            if ( $leave && $leave->maternity_leave) {
                                echo $leave->maternity_leave;
                            } else {
                                if ($item->employee_type == 'Contractual') {
                                    echo 0;
                                } else {
                                    echo $item->maternity_leave ?? 0;
                                }
                                
                            }
                        @endphp
                    </td>
                    <td>
                        {{-- Compensatory Leave --}}
                        @php
                            $leave = App\Models\EmployeeLeave::where('emp_id', $item->emp_id)
                            ->first();
                            
                            if ( $leave && $leave->compensatory_leave) {
                                echo $leave->compensatory_leave;
                            } else {
                                if ($item->employee_type == 'Contractual') {
                                    echo 0;
                                } else {
                                    echo $item->compensatory_leave ?? 0;
                                }
                                
                            }
                        @endphp
                    </td>
                    <td>
                        {{-- Extra Oridinary Leave --}}
                        @php
                            if ($item->employee_type == 'Contractual') {
                                echo 0;
                            } else {
                                $d = App\Models\EmployeeLeave::where('emp_id', $item->emp_id)->toBase()->first();
                                if ($d && $d->extra_oridinary_leave) {
                                    echo $d->extra_oridinary_leave ?? 0;
                                }else {
                                    echo $item->extra_oridinary_leave ?? 0;
                                }
                            }
                        @endphp
                    </td>
                    <td>
                        {{-- Special Leave --}}
                        @php
                            $leave = App\Models\EmployeeLeave::where('emp_id', $item->emp_id)
                            ->first();

                            if ( $leave && $leave->special_leave) {
                                echo $leave->special_leave;
                            } else {
                                echo $item->special_leave ?? 0;        
                            }
                        @endphp
                    </td>
                    <td>
                        {{-- Paternity Leave --}}
                        @php
                            $leave = App\Models\EmployeeLeave::where('emp_id', $item->emp_id)
                            ->first();
                           
                            if ( $leave && $leave->paternity_leave) {
                                echo $leave->paternity_leave;
                            } else {
                                if ($item->employee_type == 'Contractual') {
                                    echo 0;
                                } else {
                                    echo $item->paternity_leave ?? 0;
                                }
                                
                            }
                        @endphp
                    </td>
                    <td>
                        {{-- Special Sick Leave --}}
                        @php
                            $leave = App\Models\EmployeeLeave::where('emp_id', $item->emp_id)
                            ->first();
                            echo $leave->special_sick_leave;
                            if ( $leave && $leave->paternity_leave) {
                                echo $leave->special_sick_leave;
                            } else {
                                if ($item->employee_type == 'Contractual') {
                                    echo 0;
                                } else {
                                    echo $item->special_sick_leave ?? 0;
                                }
                                
                            }
                        @endphp
                    </td>
                </tr>
                @endif
                @endforeach
            </tbody>
        </table>

        <script>
            $(document).ready(function() {
                $('#example').DataTable( {
                    dom: 'Bfrtip',
                    buttons: [
                        'copy', 'csv', 'excel', 'pdf', 'print'
                    ]
                } );
            } );
        </script>
        {{-- <script>
            let dates = @js($newd['dates']);
            console.log(dates);
        </script> --}}
    </body>
</html>
