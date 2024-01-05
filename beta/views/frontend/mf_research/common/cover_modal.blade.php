	<style>
        #errorAfterLoginModal .modal-content {
            border-radius: 15px !important;
        }
        #errorAfterLoginModal .successHead {
            background: #16a1dc;
            text-align: center;
            padding: 20px 0 10px 0;
            border-radius: .7rem .7rem 0 0;
        }
        #errorAfterLoginModal .successHead h3 {
            margin: 0;
            padding: 0;
            color: #fff;
        }
        #errorAfterLoginModal .successHead button.close {
            position: absolute;
            right: 10px;
            top: 10px;
        }
        #errorAfterLoginModal #success_model_body {
            color: #444444;
            font-size: 12px;
            text-align: center;
            margin-top: 0.7rem;
            margin-bottom: 0.5rem;
        }
        #errorAfterLoginModal #successModal .modal-footer {
            justify-content: center;
        }
        #errorAfterLoginModal .btnblue {
            padding: .5rem 2rem;
            border-radius: 1.5rem;
            background: #141f55;
        }
        @media (max-width: 690px) {
          #errorAfterLoginModal .btnblue {
            padding: 0.5rem 0.5rem;
            font-size: 12px;
          }
          #errorAfterLoginModal .successHead h3 {
            font-size: 20px;
          }
        }
    </style>

	<div class="modal fade" id="errorAfterLoginModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	  <div class="modal-dialog modal-dialog-centered" role="document">
	    <div class="modal-content">
	        <div class="successHead text-center">
	            <h3>Choose Download Option</h3>
	            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	              <span aria-hidden="true">&times;</span>
	            </button>
	        </div>
	        <div class="modal-footer text-center" style="justify-content: center;">
	            <a href="javascript:void(0);" class="btn btn-secondary btnblue" id="with_cover_page" onclick="withCoverPage();" >With Cover Page</a>
	            <a href="javascript:void(0);" class="btn btn-secondary btnblue" id="without_cover_page" onclick="withoutCoverPage();" >Without Cover Page</a>
	        </div>
	    </div>
	  </div>
	</div>

	<div class="modal fade" id="downloadModal" tabindex="-1" role="dialog" aria-labelledby="saveList" aria-hidden="true">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title" id="exampleModalLabel">Enter the branding details.</h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body">
	            <div class="form-group">
                    <input type="text" name="pdf_title_line1" class="form-control" id="pdf_title_line1" placeholder="PDF Title Line 1" value="" maxlength="22">
                    
                        <div class="invalid-feedback" id="invalid-feedback" role="alert"></div>
                </div>
                <div class="form-group">
                    <input type="text" name="pdf_title_line2" class="form-control" id="pdf_title_line2" placeholder="PDF Title Line 2" value="" maxlength="22">
                    
                        <div class="invalid-feedback" id="invalid-feedback" role="alert"></div>
                </div>
                
                <div class="form-group">
                    <input type="text" name="client_name" class="form-control" id="pdf_client_name" placeholder="Client Name" value="">
                    
                        <div class="invalid-feedback" id="invalid-feedback" role="alert"></div>
                </div>
                <button  type="button" class="btn btn-primary btn-round" onclick="downloadWithCover();">Download</button>
	      </div>
	    </div>
	  </div>
	</div>

  <script src="https://cdn.jsdelivr.net/npm/html2canvas@1.0.0-rc.5/dist/html2canvas.min.js"></script>

<script type="text/javascript">
	
    function openModal(){
        document.getElementById("pdf_title_line1").value = "";
        document.getElementById("pdf_title_line2").value = "";
        document.getElementById("client_name").value = "";
        $('#errorAfterLoginModal').modal('show');
    }
    
    function withCoverPage(){
        $('#errorAfterLoginModal').modal('hide');
        $('#downloadModal').modal('show');
    }

    function withoutCoverPage(){
        checkDownload();
        document.getElementById("title_line1").value = "";
        document.getElementById("title_line2").value = "";
        document.getElementById("client_name").value = "";
        document.getElementById("save_form_data").submit();
    }

    function downloadWithCover(){
        checkDownload();
        document.getElementById("title_line1").value = document.getElementById("pdf_title_line1").value;
        document.getElementById("title_line2").value = document.getElementById("pdf_title_line2").value;
        document.getElementById("client_name").value = document.getElementById("pdf_client_name").value;
        document.getElementById("save_form_data").submit();
    }


</script>