
<!DOCTYPE html>
<html>
  <head>
    <title></title>
    <style type="text/css">
        #Iframe-Master-CC-and-Rs {
      max-width: 100%;
      max-height: 100%; 
      overflow: hidden;
    }

    /* inner wrapper: make responsive */
    .responsive-wrapper {
      position: relative;
      height: 0;    /* gets height from padding-bottom */
      
      /* put following styles (necessary for overflow and scrolling handling on mobile devices) inline in .responsive-wrapper around iframe because not stable in CSS:
        -webkit-overflow-scrolling: touch; overflow: auto; */
      
    }
     
    .responsive-wrapper iframe {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      
      margin: 0;
      padding: 0;
      border: none;
    }

    /* padding-bottom = h/w as % -- sets aspect ratio */
    /* YouTube video aspect ratio */
    .responsive-wrapper-wxh-572x612 {
      padding-bottom: 107%;
    }

    /* general styles */
    /* ============== */
    .set-border {
      border: 5px inset #4f4f4f;
    }
    .set-box-shadow { 
      -webkit-box-shadow: 4px 4px 14px #4f4f4f;
      -moz-box-shadow: 4px 4px 14px #4f4f4f;
      box-shadow: 4px 4px 14px #4f4f4f;
    }
    .set-padding {
      padding: 40px;
    }
    .set-margin {
      margin: 30px;
    }
    .center-block-horiz {
      margin-left: auto !important;
      margin-right: auto !important;
    }
    html, body {
      height: 100%;
    }

    </style>
    <!-- embed responsive iframe --> 
    <!-- ======================= -->
  </head>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script type="text/javascript">
  document.onmousedown = disableRightclick;
  var message = "Right click not allowed !!";
  function disableRightclick(evt){
      if(evt.button == 2){
          alert(message);
          return false;    
      }
  }
  </script>
  <script type="text/jscript">
      function injectJS(){
          var ihtml = `<script>document.onmousedown = disableRightclick;
  var message = "Right click not allowed !!";
  function disableRightclick(evt){
      if(evt.button == 2){
          alert(message);
          return false;    
      }
  }<\/script>`;
          var frame =  $('iframe');
          var contents =  frame.contents();
          var body = contents.find('body').attr("oncontextmenu", "return false");
          var body = contents.find('body').append(ihtml);

      }
  </script>
  <body id="body_view" style="margin: 0px;">

      <!-- <object tabindex="1" data="{{(isset($pdf) && $pdf!='')?asset('uploads/samplereport/'.$pdf):''}}#toolbar=0" type="application/pdf" width="100%" height="100%">
        <p>Your web browser doesn't have a PDF plugin.
        Instead you can <a href="{{(isset($pdf) && $pdf!='')?asset('uploads/samplereport/'.$pdf):''}}">click here to
        download the PDF file.</a></p>
      </object> -->
      <iframe id="fraDisabled" src="{{(isset($pdf) && $pdf!='')?asset('uploads/samplereport/'.$pdf):''}}#toolbar=0&navpanes=0"
   width="100%" height="100%" onload="injectJS()">

  </body>
</html>

