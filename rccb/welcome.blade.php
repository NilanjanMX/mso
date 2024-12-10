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
===============================================================================================
<html>


<head>
    <title>Fullcalendar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">


    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.css" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.min.js"></script>
</head>


<body>
    <h2>
        <center>Javascript Fullcalendar</center>
    </h2>
    <div class="container">
        <div id="calendar"></div>
    </div>
    <br>
    <!-- Button trigger modal -->
    <button type="button" class="d-none" id="event_btn" data-bs-toggle="modal" data-bs-target="#exampleModal">
        Launch demo modal
    </button>


    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="text" name="event_title" id="event_title" class="form-control"
                        placeholder="Event title">
                    <input type="hidden" name="event_id" id="event_id" class="form-control">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" onclick="addEvent()" class="btn btn-primary">Add event</button>
                </div>
            </div>
        </div>
    </div>
</body>


</html>
<script>
    var calendar;
    var G_start;


    var myEvents = [{
            id: 1,
            title: 'Long Event',
            start: '2024-12-11', // yyyy-mm-dd
            end: '2024-12-11', // yyyy-mm-dd
        },
        {
            id: 2,
            title: 'Long Event',
            start: '2024-12-12', // yyyy-mm-dd
            end: '2024-12-12', // yyyy-mm-dd
        },


    ];


    function addEvent() {
        var event_title = document.getElementById('event_title').value;
        var event_id = document.getElementById('event_id').value;
        let date = '';

        console.log(event);
        if (event_id) {
            let event = myEvents.find(event => {
                if (event.id == event_id) {
                    event.title = event_title;
                    date = event.start;
                    return event;
                }
            });

        } else {

            date = G_start.format('YYYY-MM-DD');
            myEvents.push({
                id: Math.floor(Math.random() * 1000), // call api get id
                title: event_title,
                start: date, // yyyy-mm-dd
                end: G_start.format('YYYY-MM-DD'), // yyyy-mm-dd
            });

            G_start = null;

        }


        $('#calendar').fullCalendar('destroy');
        calendarView();

        $('#calendar').fullCalendar('gotoDate', date);



        document.getElementById('event_title').value = '';
        document.getElementById('event_id').value = '';


    }


    $(document).ready(function() {
        calendarView();
    });


    function calendarView() {
        calendar = $('#calendar').fullCalendar({
            header: {
                left: 'prev,next', // Add the built-in prev and next buttons
                center: 'title',
                right: 'year', // You can also add a dropdown to navigate by year (optional)

            },
            defaultView: 'month', // Default view when the calendar loads
            defaultDate: moment().format('YYYY-MM-DD'), // Set today's date as default
            header: {
                left: 'prev,next today',
                center: 'title',
                right: ''
            },
            // header: {
            //     left: 'prevYear,nextYear', // Add custom buttons here
            //     center: 'title',
            //     right: 'month,agendaWeek,agendaDay' // Example view options
            // },
            validRange: {
                start: moment().format('YYYY-MM-DD')
            },
            selectable: true,
            
            eventClick: function(calEvent, jsEvent, view) {
                console.log(document.querySelector('.fc-center h2').innerHTML)
                document.getElementById('event_title').value = calEvent.title;
                document.getElementById('event_id').value = calEvent.id;


                document.getElementById('event_btn').click();


            },
            select: function(start, end, allDay) {
                console.log(document.querySelector('.fc-center h2').innerHTML)

                console.log(start.format('YYYY-MM-DD'));
                G_start = start;


                document.getElementById('event_btn').click();
                // if (title) {
                //     calendar.fullCalendar('renderEvent', {
                //             title: title,
                //             start: start,
                //             end: end,
                //             allDay: allDay
                //         },
                //         true // make the event "stick"
                //     );
                // }
                calendar.fullCalendar('unselect');
            },


            events: myEvents,


        });


        $('.fc-button-prev').click(function() {

            calendar.fullCalendar('renderEvent', {
                title: 'New',
                start: "2013-09-09 10:30"
            });


        });
    }
</script>


