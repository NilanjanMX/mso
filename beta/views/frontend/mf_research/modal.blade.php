<style type="text/css">
	
    [class^="icon-"]:before, [class*=" icon-"]:before {
        font-family: "fontello";
        font-style: normal;
        font-weight: normal;
        speak: none;
        display: inline-block;
        text-decoration: inherit;
        text-align: center;
        font-variant: normal;
        text-transform: none;
        line-height: 1rem;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
    }
    .icon-cancel:before {
        content: '\e800';
        color:#16a1db;
        font-size: 12px;
    }
</style>
<script type="text/javascript">
	function openViewPermissionModal(){
		$("#view_permission_modal").modal("show");
	}
	function openDownloadPermissionModal(){
		$("#download_permission_modal").modal("show");
	}
	function openSavePermissionModal(){
		$("#save_permission_modal").modal("show");
	}

    function openDownloadLoginModal(){
        $("#download_login_modal").modal("show");
    }

    function openSaveLoginModal(){
        $("#save_login_modal").modal("show");
    }
</script>


    
<div class="modal fade" id="view_permission_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Alert View</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
        </div>
        <div class="modal-body">
            <p id="success_model_body" style="text-align: center;font-size: 16px;">This feature is not available in the current plan.</p>
        </div>
        <div class="modal-footer text-center" style="justify-content: center;">
            <button type="button" class="btn btn-secondary btnblue" data-dismiss="modal">Cancel</button>
            <a href="{{route('account.upgradePackage')}}" class="btn btn-secondary btnblue">Upgrade plan</a>
        </div>
    </div>
  </div>
</div>
    
<div class="modal fade" id="download_permission_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Alert Download</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
        </div>
        <div class="modal-body">
            <p id="success_model_body" style="text-align: center;font-size: 16px;">This feature is not available in the current plan.</p>
        </div>
        <div class="modal-footer text-center" style="justify-content: center;">
            <button type="button" class="btn btn-secondary btnblue" data-dismiss="modal">Cancel</button>
            <a href="{{route('account.upgradePackage')}}" class="btn btn-secondary btnblue">Upgrade plan</a>
        </div>
    </div>
  </div>
</div>
    
<div class="modal fade" id="save_permission_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Alert Save</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
        </div>
        <div class="modal-body">
            <p id="success_model_body" style="text-align: center;font-size: 16px;">This feature is not available in the current plan.</p>
        </div>
        <div class="modal-footer text-center" style="justify-content: center;">
            <button type="button" class="btn btn-secondary btnblue" data-dismiss="modal">Cancel</button>
            <a href="{{route('account.upgradePackage')}}" class="btn btn-secondary btnblue">Upgrade plan</a>
        </div>
    </div>
  </div>
</div>

<div class="modal fade" id="download_login_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Alert</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
        </div>
        <div class="modal-body">
            <p id="success_model_body" style="text-align: center;font-size: 16px;">This feature is available only to members.</p>
        </div>
        <div class="modal-footer text-center" style="justify-content: center;">
            <button type="button" class="btn btn-secondary btnblue" data-dismiss="modal">Cancel</button>
            <a href="{{url('login')}}" class="btn btn-secondary btnblue">Login</a>
            <a href="{{url('membership')}}" class="btn btn-primary btnblue">Become a member</a>
        </div>
    </div>
  </div>
</div>

<div class="modal fade" id="save_login_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Alert</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
        </div>
        <div class="modal-body">
            <p id="success_model_body" style="text-align: center;font-size: 16px;">This feature is available only to members.</p>
        </div>
        <div class="modal-footer text-center" style="justify-content: center;">
            <button type="button" class="btn btn-secondary btnblue" data-dismiss="modal">Cancel</button>
            <a href="{{url('login')}}" class="btn btn-secondary btnblue">Login</a>
            <a href="{{url('membership')}}" class="btn btn-primary btnblue">Become a member</a>
        </div>
    </div>
  </div>
</div>