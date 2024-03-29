@extends('frontend.layouts.app')
@section('style')
<style>
/* The Modal (background) */
.modal {
  display: none; /* Hidden by default */
  position: fixed; /* Stay in place */
  z-index: 9999; /* Sit on top */
  padding-top: 100px; /* Location of the box */
  left: 0;
  top: 0;
  width: 100%; /* Full width */
  height: 100%; /* Full height */
  overflow: auto; /* Enable scroll if needed */
  background-color: rgb(0,0,0); /* Fallback color */
  background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
}

/* Modal Content */
.modal-content {
  background-color: #fefefe;
  margin: auto;
  padding: 20px;
  border: 1px solid #888;
  width: 50%;
  border-radius: 27px;
}

/* The Close Button */
.close {
  color: #aaaaaa;
  float: right;
  font-size: 28px;
  font-weight: bold;
}

.close:hover,
.close:focus {
  color: #000;
  text-decoration: none;
  cursor: pointer;
}
.ok-btn {
  background-color: #04AA6D;
  color: white;
  padding: 14px 20px;
  margin: 8px 0;
  border: none;
  cursor: pointer;
  width: 100%;
  opacity: 0.9;
}

.ok-btn:hover {
  opacity:1;
}
/* Clear floats */
.clearfix::after {
  content: "";
  clear: both;
  display: table;
}

</style>
@endsection
@section('content')
<div class="rightPanel">
    <div class="headingArea">
        <h1>Hello {{auth()->user()->name}}!</h1> 
        <div class="subHeading">Goodmorning.</div>
        <div class="categorychoose">
            <input type="radio" class="mso-member-type" name="member_type" value="own" checked>Own
            <input type="radio" class="mso-member-type" name="member_type" value="team">Team
            <input type="radio" class="mso-member-type" name="member_type" value="all">All
            {{-- <div class="switch-crm">

                <div class="switch-crm-name">
                    <div class="subscription-panel-title">Own</div>
                    <div class="devider"></div>
                </div>

                <label class="switch">
                    <input type="checkbox" class="mso-member-type" value="own">
                    <span class="slider round"></span>
                </label>

                <div class="switch-crm-name">
                    <div class="subscription-panel-title">All</div>
                    <div class="devider"></div>
                </div>
            </div> --}}
        </div>
        
    </div>
    <!-- dashboard home start  -->
    <div class="dashboard-home">
        <!-- dashboard left start  -->
        <div class="dashboard-left">
            <div class="dashboard-left-row">
                <div class="col">
                    <div class="dashboard-left-row-sub align-v">
                        <div class="col-sub">
                            <div class="item-title">Task</div>
                        </div>
                        <div class="col-sub">
                            <a href="{{ route('user.task') }}" class="btn-all">See All</a>
                        </div>
                    </div>
                    <div class="dashboard-left-row-sub task-count-desktop" style="cursor:pointer;">
                        <div class="col-sub pb-14">
                            <div class="blue-box" id="upcomming-task-desktop">
                                <div class="top-icon">
                                    <span class="icon"><img src="{{asset('securepanel/images/icon-doc.svg')}}" alt=""></span>
                                </div>
                                <h2></h2>
                                <h3>Upcoming<br>Tasks</h3>
                            </div>
                        </div>
                        <div class="col-sub pb-14">
                            <div class="blue-box" id="overdue-task-desktop">
                                <div class="top-icon">
                                    <span class="icon"><img src="{{asset('securepanel/images/icon-time.svg')}}" alt=""></span>
                                </div>
                                <h2></h2>
                                <h3>Overdue<br>Tasks</h3>
                            </div>
                        </div>
                    </div>
                   
                </div>
                <div class="col">
                    <div class="dashboard-left-row-sub align-v">
                        <div class="col-sub">
                            <div class="item-title">Meetings</div>
                        </div>
                        <div class="col-sub">
                            <a href="{{ route('meetings.index') }}" class="btn-all">See All</a>
                        </div>
                    </div>
                    <div class="dashboard-left-row-sub">
                        <div class="col-sub pb-14" style="cursor:pointer;" id="upcomming-meeting-desktop">
                            <div class="blue-box">
                                <div class="top-icon">
                                    <span class="icon"><img src="{{asset('securepanel/images/icon-time.svg')}}" alt=""></span>
                                </div>
                                <h2></h2>
                                <h3>Upcoming<br>Meetings</h3>
                            </div>
                        </div>
                        <div class="col-sub pb-14" style="cursor:pointer;" id="overdue-meeting-desktop">
                            <div class="blue-box">
                                <div class="top-icon">
                                    <span class="icon"><img src="{{asset('securepanel/images/icon-time.svg')}}" alt=""></span>
                                </div>
                                <h2></h2>
                                <h3>Overdue<br>Meetings</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="dashboard-left-row">
                <div class="col">
                    <div class="dashboard-left-row-sub align-v">
                        <div class="col-sub">
                            <div class="item-title">Leads</div>
                        </div>
                        <div class="col-sub">
                            <a href="{{ route('user.lead') }}" class="btn-all">See All</a>
                        </div>
                    </div>
                    <div class="dashboard-left-row-sub lead-count" style="cursor:pointer;">
                        <div class="col-sub pb-14">
                            <div class="blue-box" id="upcoming-lead">
                                <div class="top-icon">
                                    <span class="icon"><img src="{{asset('securepanel/images/icon-time.svg')}}" alt=""></span>
                                </div>
                                <h2></h2>
                                <h3>Upcoming<br>Leads</h3>
                            </div>
                        </div>
                        <div class="col-sub pb-14">
                            <div class="blue-box" id="converted-lead">
                                <div class="top-icon">
                                    <span class="icon"><img src="{{asset('securepanel/images/icon-time.svg')}}" alt=""></span>
                                </div>
                                <h2></h2>
                                <h3>Converted<br>Leads</h3>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="dashboard-left-row-sub align-v">
                        <div class="col-sub">
                            <div class="item-title">Opportunity</div>
                        </div>
                        <div class="col-sub">
                            <a href="{{ route('user.opportunity') }}" class="btn-all">See All</a>
                        </div>
                    </div>
                    <div class="dashboard-left-row-sub opportunity-count" style="cursor:pointer;">
                        <div class="col-sub pb-14">
                            <div class="blue-box" id="followup-opportunity">
                                <div class="top-icon">
                                    <span class="icon"><img src="{{asset('securepanel/images/icon-time.svg')}}" alt=""></span>
                                </div>
                                <h2></h2>
                                <h3>Converted<br>Opportunity</h3>
                            </div>
                        </div>
                        <div class="col-sub pb-14">
                            <div class="blue-box" id="overdue-opportunity">
                                <div class="top-icon">
                                    <span class="icon"><img src="{{asset('securepanel/images/icon-time.svg')}}" alt=""></span>
                                </div>
                                <h2></h2>
                                <h3>Overdue<br>Opportunities</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- <div class="dashboard-left-row nomobileview">
                <div class="col-full">
                    <div class="dashboard-left-row-sub align-v">
                        <div class="col-sub-full">
                            <div class="item-title">Heading</div>
                        </div>
                    </div>
                    <div class="blue-box full">
                        <div class="top-icon">
                            <span class="icon"><img src="{{asset('securepanel/images/icon-time.svg')}}" alt=""></span>
                        </div>
                        <h2>5</h2>
                        <h3>Completed<br>Tasks</h3>
                    </div>
                </div>
            </div> -->
        </div>
        <!-- dashboard left endf  -->
        <!-- dashboard right start  -->
        <div class="dashboard-right nomobileview">
            <div class="item-title">Upcoming Birthdays</div>
            <div class="birthday-box">
              
            </div>
            <!-- <div class="item-title">Heading</div>
            <div class="blue-box">
                <div class="top-icon">
                    <span class="icon"><img src="{{asset('securepanel/images/icon-time.svg')}}" alt=""></span>
                </div>
                <h2>5</h2>
                <div class="content-space"></div>
                <h3>Completed<br>Tasks</h3>
            </div> -->
        </div>
        <!-- dashboard right endf  -->
    </div>
    <!-- dashboard home end  -->
    <!-- recent activity log start -->
    {{-- @if($is_main_user == 1) --}}
        <div class="recentactivityArea">
            <div class="recentactivityHeader">
                <h2>Recent Activity Log</h2>
                <div>
                    <a href="{{ route('user.activity') }}" class="addbutton">View More</a>
                </div>
            </div>
            <div class="recentactivityBody">
                <div class="tablearea">
                    <table cellpadding="0" cellspacing="0" >
                        <thead>
                            <tr>
                                <th>Sl No.</th>
                                <th>Activity ID</th>
                                <th>Activity Details</th>
                                <th>Logged Date & Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($activity_logs->count() > 0)
                                @foreach($activity_logs as $key => $activity_log)
                                    @php
                                        if( $key+1 < 10){
                                            $slNo = 'AC00' . $key+1;
                                        }else{
                                            $slNo = 'AC0' . $key+1;
                                        }
                                        if ($activity_log->id < 10) {
                                            $prefixedId = 'AC00' . $activity_log->id;
                                        } else {
                                            $prefixedId = 'AC0' . $activity_log->id;
                                        }
                                    @endphp
                                    <tr>
                                        <td>{{ $slNo }}</td>
                                        <td>{{ $prefixedId }}</td>
                                        <td>{!! $activity_log->acitivity_description !!}</td>
                                        <td>
                                            <div class="deadline-content">
                                                <span class="date"><img src="./assets/images/icon-calendar.svg" alt="">{{ date('d/m/Y', strtotime($activity_log->created_at)) }}</span>
                                                <span class="time"><img src="./assets/images/icon-time2.svg" alt="">{{ date('h:i A', strtotime($activity_log->created_at)) }}</span>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="4" align="center"><p>No record found!!</p></td>
                                </tr>
                            @endif
                        
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    {{-- @endif --}}
    <!-- recent activity log end -->
    <!-- The Modal -->
    <!-- <div id="myModal" class="modal">
      
        <div class="modal-content">
            <span class="close">&times;</span>
            <p>Some text in the Modal..</p>
            <div class="clearfix">
                <button type="button" class="ok-btn">OK</button>
            </div>
        </div>
    </div> -->
    <!-- The profileReminderModal -->
    <div id="profileReminderModal" class="modal">
        <!-- Modal content -->
        <div class="modal-content">
            <span class="close">&times;</span>
            <p class="popupHeading">Complete Profile Reminder</p>
            <div class="popupcontentArea">
                <p class="popupContnt">Please complete your profile information settings to continue to the Dashboard Page. Do you want to proceed?</p>
            </div>
            <div class="popupbtnArea">
            <a href="http://127.0.0.1:8000/agenda-add" class="addbutton">CONTINUE</a>
         </div>
        </div>
    </div>

    <!-- The User Survey Modal -->
    <div id="usersurveyModal" class="modal">
        @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        <!-- Modal content -->
        <div class="modal-content">
            <form action="{{ \URL::route('user.question.submit'); }}" method="POST" id="questionForm">
                @csrf
                <span class="close usersurveyClose">&times;</span>
                <p class="popupHeading">User Survey Form</p>
                
                <div class="popupFromContent">
                    <p class="pb-10"> In order to help us plan and improve the product better for you, please help us with the details below</p>
                    @if($questions->count() > 0)
                        @foreach($questions as $key => $question)
                            <div class="questionPlot">
                                <div class="questiontext">{{ ++$key }}.{{ $question->title }}</div>
                                @if($question->answers->count() > 0)
                                    <div class="checkboxArea">
                                        <!-- <label class="chkboxcontainer">
                                            <input type="checkbox" checked="checked">
                                            <span class="checkmark1"></span> Task Management
                                        </label> -->
                                        @foreach($question->answers as $ans_key => $answer)
                                            <label class="chkboxcontainer">
                                                <input type="checkbox" class="answers" name="answers[]" value="{{ $answer->id }}">
                                                <!-- <span class="checkmark1"></span> -->
                                                {{ $answer->answer_title }}
                                            </label>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    @endif
                </div>
                <div class="popupbtnArea02">
                    <span class="question-answer-error" style="color:red;"></span>
                    <a href="javascript:void(0)" class="skipbtn">SKIP</a>
                    <button class="addbutton usersurveyAddBtn" type="submit">SUBMIT</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
@section('script')

<script>
    toastr.options = {
        "closeButton": true,
        "progressBar": true
    }
   
  var base_url = window.location.origin;
    
    function startFCM() {
      messaging
          .requestPermission()
          .then(function () {
              return messaging.getToken()
          })
          .then(function (response) {
              $.ajaxSetup({
                  headers: {
                      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                  }
              });
              $.ajax({
                  url: `${base_url}/store-token`,
                  type: 'POST',
                  data: {
                      token: response
                  },
                  dataType: 'JSON',
                  success: function (response) {
                     // alert('Token stored.');
                     console.log('Token stored.');
                  },
                  error: function (error) {
                     // alert(error);
                      console.log(error);
                  },
              });
          }).catch(function (error) {
            console.log(error);
             // alert(error);
          });
    }
    if(messaging){
        startFCM();
        messaging.onMessage(function (payload) {
            console.log(payload)
            const title = payload.notification.title;
            const options = {
                body: payload.notification.body,
                icon: payload.notification.icon,
            };
            new Notification(title, options);
        });
    }
    
   
    
    let usersurveyModal = document.getElementById("usersurveyModal");
    let usersurveyCloseBtn = document.getElementsByClassName("usersurveyClose")[0];
    let skipBtn = document.querySelector(".skipbtn");
    const btn_qa_submit = document.querySelector('.usersurveyAddBtn');
    if(sessionStorage.is_new_user){
        usersurveyModal.style.display = "block";
        //usersurveyModal.style.display = "none";
    }
    skipBtn.addEventListener("click",()=>{
        usersurveyModal.style.display = "none";
        sessionStorage.removeItem('is_new_user');
    });

    usersurveyCloseBtn.addEventListener("click",()=>{
        usersurveyModal.style.display = "none";
        sessionStorage.removeItem('is_new_user');
    });
    
    // btn_qa_submit.addEventListener("click",()=>{
    //     usersurveyModal.style.display = "none";
    //     sessionStorage.removeItem('is_new_user');
    // });
    if(sessionStorage.add_on_member){
        sessionStorage.removeItem('add_on_member');
    }
    if(sessionStorage.is_auto_debit){
        sessionStorage.removeItem('is_auto_debit');
    }
    if(sessionStorage.subscription){
        sessionStorage.removeItem('subscription');
    }
    if(sessionStorage.u_id){
        sessionStorage.removeItem('u_id');
    }
    if(sessionStorage.u_pass){
        sessionStorage.removeItem('u_pass');
    }
    if(sessionStorage.addon_user_count){
        sessionStorage.removeItem('addon_user_count');
    }
    if(sessionStorage.order_id){
        sessionStorage.removeItem('order_id');
    }
    if(sessionStorage.subscription_token){
        sessionStorage.removeItem('subscription_token');
    }
    @if(Session::has('message'))
        toastr.success("{{ session('message') }}");
    @endif
    @if(Session::has('error'))
        toastr.error("{{ session('error') }}");
    @endif
    $('#questionForm').submit(function(e) {
        e.preventDefault();
        const myAnswers = new Array();
        $("input[name='answers[]']:checked").each(function() {
            myAnswers.push($(this).val());

        });
        console.log(myAnswers);
        if(myAnswers.length <=0){
            toastr.error("Please check the the answer!");
            //$(".question-answer-error").text("Please check the the answer");
            return;
        }
        let formData = {
            'answers':myAnswers
         };
        $.ajaxSetup({
            headers: {
               'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
         });
         $.ajax({
            type: 'POST',
            data : formData,
            beforeSend:function(){
               $('.usersurveyAddBtn').addClass("disabled").attr('disabled',true);
               $(document).find('span.question-answer-error').text('');
            },
            complete: function(){
               $('.usersurveyAddBtn').removeClass("disabled").attr('disabled',false);
            },
            url: "{{route('user.question.submit')}}",
            success: function(res){
                if(res.success == true){
                    usersurveyModal.style.display = "none";
                    sessionStorage.removeItem('is_new_user');
                    toastr.success(res.message);
                }else{
                    toastr.error(res.message);
                }
                if(res.error == true){
                    toastr.error(res.message);
                }
            },
            error(err){
               $.each(err.responseJSON,function(prefix,val) {
               $('.'+prefix+'_error').text(val[0]);
               });
               console.log(err);
            }
         });
      });
    const upcoming_meeting_desktop = document.querySelector('#upcomming-meeting-desktop');

    const overdue_meeting_desktop = document.querySelector('#overdue-meeting-desktop');
  
    const task_count_desktop = document.querySelector('.task-count-desktop');

    const lead_count = document.querySelector('.lead-count');

    const opportunity_count = document.querySelector('.opportunity-count');

    upcoming_meeting_desktop.addEventListener('click',(e)=>{
        e.preventDefault();
        localStorage.setItem("page", 1);
        let upcoming_meeting ="upcoming";
        localStorage.setItem("upcoming_meeting", upcoming_meeting);
        window.location.href = "{{ route('meetings.index') }}";
    })

    overdue_meeting_desktop.addEventListener('click',(e)=>{
        e.preventDefault();
        localStorage.setItem("page", 1);
        let upcoming_meeting ="overdue";
        localStorage.setItem("upcoming_meeting", upcoming_meeting);
        window.location.href = "{{ route('meetings.index') }}";
    })

    task_count_desktop.addEventListener("click",(e)=>{
        e.preventDefault();
        if(e.target.closest("#upcomming-task-desktop")){
            let task_status ="upcoming";
            localStorage.setItem("task_status", task_status);
            window.location.href = "{{ route('user.task') }}";
        }
        if(e.target.closest("#overdue-task-desktop")){
            let task_status ="overdue";
            localStorage.setItem("task_status", task_status);
            window.location.href = "{{ route('user.task') }}";
        }
    });

    lead_count.addEventListener("click",(e)=>{
        e.preventDefault();
        if(e.target.closest("#upcoming-lead")){
            let lead_status ="upcoming";
            localStorage.setItem("lead_status", lead_status);
            localStorage.removeItem("current_lead");
            window.location.href = "{{ route('user.lead') }}";
        }
        if(e.target.closest("#converted-lead")){
            let current_lead ="converted";
            localStorage.setItem("current_lead", current_lead);
            localStorage.removeItem("lead_status");
            window.location.href = "{{ route('user.lead') }}";
        }
    });
    opportunity_count.addEventListener("click",(e)=>{
        e.preventDefault();
        if(e.target.closest("#followup-opportunity")){
            let activeTab ="converted";
            localStorage.setItem("activeTab", activeTab);
            window.location.href = "{{ route('user.opportunity') }}";
        }
        if(e.target.closest("#overdue-opportunity")){
            let activeTab ="overdue";
            localStorage.setItem("activeTab", activeTab);
            window.location.href = "{{ route('user.opportunity') }}";
        }
    });

    jQuery(document).click(function(event) {
        if ($(event.target).is('#profileReminderModal')) {
            $("#profileReminderModal").hide();
        }
    });

    jQuery(document).click(function(event) {
        if ($(event.target).is('#usersurveyModal')) {
            $("#usersurveyModal").hide();
        }
    });
    
    let is_create_by = true;

    get_kpi_data("own");



    $('body').on('click','.mso-member-type',function (e) {
        var member_type = $(this).val();
        get_kpi_data(member_type)
        return;
        // is_create_by = !is_create_by
        // if(is_create_by){
        //     $(".mso-member-type").val("own")
        //     get_kpi_data('own')
        // }else{
        //     get_kpi_data('all')
        // }
       
    })

    function get_kpi_data(member_type){
        $.ajax({
            type: "get",
            dataType: "json",
            url: "{{ route('user.dashboard')}}",
            data: {
                member_type: member_type
            },
            success: function(data) {
                // console.log(data)
                let upcoming_birthdays = data.upcoming_birthdays; 
                let upcoming_task_count = data.upcoming_task_count; 
                let overdue_task_count = data.overdue_task_count; 
                let upcoming_meeting_count = data.upcoming_meeting_count; 
                let overdue_meeting_count = data.overdue_meeting_count;

                let follow_up_count = data.follow_up_count; 
                let converted_lead_count = data.converted_lead_count; 
                let converted_opp_count = data.converted_opp_count; 
                let overdue_opp_count = data.overdue_opp_count;

                let birthday_elem = '';
                // console.log(data.upcoming_birthdays);
                if(upcoming_birthdays.length > 0){
                    for(let i = 0; i < upcoming_birthdays.length; i++){
                        birthday_elem+= `
                        <ul>
                            <a href="${upcoming_birthdays[i].contactDetailUrl}">
                                <li>
                                    <div class="pic">
                                        <img src="${upcoming_birthdays[i].profile_pic_url}" alt="${upcoming_birthdays[i].first_name}">
                                    </div>
                                
                                    <div class="txt">
                                        <div class="name">
                                            ${(upcoming_birthdays[i].first_name)+' '+(upcoming_birthdays[i].last_name || '')}
                                        </div>
                                        <div class="date">
                                            ${upcoming_birthdays[i].formatedDob}
                                        </div>
                                    </div>
                                </li>
                            </a>
                        </ul>
                        `;
                    }
                }else{
                    birthday_elem = `<p style="color: #fefefe">No upcoming birthdays!</p>`;
                }
                $('.birthday-box').html("");
                $('.birthday-box').append(birthday_elem);
                $("#upcomming-task-desktop > h2").html(upcoming_task_count)
                $("#overdue-task-desktop > h2").html(overdue_task_count)
                $("#upcomming-meeting-desktop > div > h2").html(upcoming_meeting_count)
                $("#overdue-meeting-desktop > div > h2").html(overdue_meeting_count)
                $("#upcoming-lead > h2").html(follow_up_count)
                $("#converted-lead > h2").html(converted_lead_count)
                $("#followup-opportunity > h2").html(converted_opp_count)
                $("#overdue-opportunity > h2").html(overdue_opp_count)
                
                
                return false;
                // toastr.options = {
                //     "closeButton": true,
                //     "progressBar": true
                // }
                // toastr.success(data.message);
            }
        })
    }
</script>

@endsection