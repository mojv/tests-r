<script src="http://cdnjs.cloudflare.com/ajax/libs/processing.js/1.4.1/processing-api.min.js"></script><html>
<!--
  Created using jsbin.com
  Source can be edited via http://jsbin.com/pdfjs-helloworld-v2/8598/edit
-->
<body>
  <canvas id="the-canvas" style="border:1px solid black"></canvas>
  <input id='pdf' type='file'/>

  <!-- Use latest PDF.js build from Github -->
  <script type="text/javascript" src="https://rawgithub.com/mozilla/pdf.js/gh-pages/build/pdf.js"></script>
  
  <script type="text/javascript">
    //
    // Disable workers to avoid yet another cross-origin issue (workers need the URL of
    // the script to be loaded, and dynamically loading a cross-origin script does
    // not work)
    //
    PDFJS.disableWorker = true;
    //
    // Asynchronous download PDF as an ArrayBuffer
    //
    
    var pdf = document.getElementById('pdf');
    pdf.addEventListener('change', pdftocanvas);

  </script>
  

<style id="jsbin-css">
</style>
<script>
</script>
</body>
</html>