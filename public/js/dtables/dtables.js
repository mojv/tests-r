var tpdf=0;
var tocr=0;
var tomr=0;
var tbcr=0;
var hasId=0;
var cuts=[];
var cuts_id=[];
var omr_titles=[];
var img_titles=[];
var img_grades_temp=[];
var tr_img = document.createElement('tr');
var threshold  = 147;
var learn = 0; //0.005;
var learn2 = 0; //0.25;
var dark_comp = 0.20;


    function rgbToHex(r, g, b) {
       if (r > 255 || g > 255 || b > 255){
           throw "Invalid color component";
        }
       return ((r << 16) | (g << 8) | b).toString(16);
    }

    function toblackWhite(img_temp,ctx_temp) {
      var pixels  = img_temp.data;
        for (var i = 0, n = pixels.length; i < n; i += 4) {
            if (((pixels[i] + pixels[i+1] + pixels[i+2])/3) <= threshold){
               pixels[i  ] = 0;        // red
               pixels[i+1] = 0;        // green
               pixels[i+2] = 0;        // blue
            }else{
               pixels[i  ] = 255;        // red
               pixels[i+1] = 255;        // green
               pixels[i+2] = 255;        // blue
            }
        }
        ctx_temp.putImageData(img_temp, 0, 0);
      }

    function my_isblack(data,pos){
      	if(((data[pos]+data[pos+1]+data[pos+2])/3)<threshold){
      		return 1;
      	}
      	else{
      		return 0;
      	}
    }

    function getSum(total, num) {
        return total + num;
    }

    function verticalAxes(start,end,x_pos,markSize,errorAdmited){
	   start=Math.round(start);
	   end=Math.round(end);
           markSize=Math.round(markSize);
           errorAdmited=Math.round(errorAdmited);
           var b = [0,0,0,0,0];
           var black = [];
	   var data1 = ctx.getImageData(x_pos, 0, 1, end).data;
	   var data2 = ctx.getImageData(x_pos+1, 0, 1, end).data;
	   var data3 = ctx.getImageData(x_pos+2, 0, 1, end).data;
	   var data4 = ctx.getImageData(x_pos+3, 0, 1, end).data;
	   var data5 = ctx.getImageData(x_pos+4, 0, 1, end).data;
           for (var k = start; k < end; k++){
               b[0]=my_isblack(data1,k*4);
               b[1]=my_isblack(data2,k*4);
               b[2]=my_isblack(data3,k*4);
               b[3]=my_isblack(data4,k*4);
               b[4]=my_isblack(data5,k*4);
               if(b.reduce(getSum)>3){
                   black[k]=1;
                   /*var imgData = ctx.createImageData(4, 1);
                   var j;
                   for (j = 0; j < imgData.data.length; j += 4) {
                       imgData.data[j+0] = 0;
                       imgData.data[j+1] = 0;
                       imgData.data[j+2] = 255;
                       imgData.data[j+3] = 255;
                   }
                   ctx.putImageData(imgData, x_pos, k);*/
               }
               else{
                   black[k]=0;
                   /*var imgData = ctx.createImageData(4, 1);
                   var j;
                   for (j = 0; j < imgData.data.length; j += 4) {
                       imgData.data[j+0] = 255;
                       imgData.data[j+1] = 0;
                       imgData.data[j+2] = 0;
                       imgData.data[j+3] = 255;
                   }
                   ctx.putImageData(imgData, x_pos, k);*/
               }
           }
           var peaks=[];
           var first=-1;
           var first_found='no';
           var second=-1;
           var second_found='no';
           var m = 0;
           var sub=0;

           for(var k=start+(markSize/2);k<end-(markSize/2);k++){
                sub=0;
                for (var z = k-(markSize/2); z < k+(markSize/2); z++){
                    sub = sub + black[z];
                }
                if (sub >= errorAdmited && first_found==='no' && second_found==='no'){
                    first=k;
                    first_found='yes';
                }
                if (sub < errorAdmited && first_found==='yes' && second_found==='no'){
                    second=k;
                    second_found='yes';
                }
                if (first_found==='yes'&& second_found==='yes'){
                    peaks[m]=(first+second)/2;
                    first_found='no';
                    second_found='no';
                    m++;
                }
            }
            return peaks;

    }

    function horizontalAxes(start,end,y_pos,markSize,errorAdmited){
	    start=Math.round(start);
	    end=Math.round(end);
      markSize=Math.round(markSize);
      errorAdmited=Math.round(errorAdmited);
      var b = [0,0,0,0,0];
      var black = [];
	    var data1 = ctx.getImageData(0,y_pos,end,1).data;
	    var data2 = ctx.getImageData(0,y_pos+1,end,1).data;
	    var data3 = ctx.getImageData(0,y_pos+2,end,1).data;
	    var data4 = ctx.getImageData(0,y_pos+3,end,1).data;
	    var data5 = ctx.getImageData(0,y_pos+4,end,1).data;
            for (var k = start; k < end; k++){
               b[0]=my_isblack(data1,k*4);
               b[1]=my_isblack(data2,k*4);
               b[2]=my_isblack(data3,k*4);
               b[3]=my_isblack(data4,k*4);
               b[4]=my_isblack(data5,k*4);
                if(b.reduce(getSum)>3){
                    black[k]=1;
                    /*var imgData = ctx.createImageData(1, 4);
                    var j;
                    for (j = 0; j < imgData.data.length; j += 4) {
                        imgData.data[j+0] = 0;
                        imgData.data[j+1] = 0;
                        imgData.data[j+2] = 255;
                        imgData.data[j+3] = 255;
                    }
                    ctx.putImageData(imgData,k,y_pos);*/
                }
                else{
                    black[k]=0;
                    /*var imgData = ctx.createImageData(1, 4);
                    var j;
                    for (j = 0; j < imgData.data.length; j += 4) {
                        imgData.data[j+0] = 255;
                        imgData.data[j+1] = 0;
                        imgData.data[j+2] = 0;
                        imgData.data[j+3] = 255;
                    }
                    ctx.putImageData(imgData, k, y_pos);*/
                }
            }
            var peaks=[];
            var first=-1;
            var first_found='no';
            var second=-1;
            var second_found='no';
            var m = 0;
            var sub=0;

            for(var k=start+(markSize/2);k<end-(markSize/2);k++){
                 sub=0;
                 for (var z = k-(markSize/2); z < k+(markSize/2); z++){
                     sub = sub + black[z];
                 }
                 if (sub >= errorAdmited && first_found==='no' && second_found==='no'){
                     first=k;
                     first_found='yes';
                 }
                 if (sub < errorAdmited && first_found==='yes' && second_found==='no'){
                     second=k;
                     second_found='yes';
                 }
                 if (first_found==='yes'&& second_found==='yes'){
                     peaks[m]=(first+second)/2;
                     first_found='no';
                     second_found='no';
                     m++;
                 }
             }
             return peaks;
    }

    function intersection(y11,y12,x11,x12,y21,y22,x21,x22){
        var m1 = (y11-y12)/(x11-x12);
        var m2 = (y21-y22)/(x21-x22);
        if ((x21-x22)==0){
            var xi=x21;
        }else{
            var xi=(m1*x11-m2*x21-y11+y21)/(m1-m2);
        }
        var yi=(m1*(xi-x11)+y11);
        return i=[xi,yi];
    }

    function delStreaks(hm,wm,h,w){
      l=0;
      r=0;
      u=0;
      d=0;
      var b = [0,0,0,0,0];
      var black = [];
      var data1 = ctx.getImageData(0,hm,w,1).data;
      var data2 = ctx.getImageData(0,hm+1,w,1).data;
      var data3 = ctx.getImageData(0,hm+2,w,1).data;
      var data4 = ctx.getImageData(0,hm+3,w,1).data;
      var data5 = ctx.getImageData(0,hm+4,w,1).data;
      for (var k = 0; k < w; k++){
         b[0]=my_isblack(data1,k*4);
         b[1]=my_isblack(data2,k*4);
         b[2]=my_isblack(data3,k*4);
         b[3]=my_isblack(data4,k*4);
         b[4]=my_isblack(data5,k*4);
          if(b.reduce(getSum)>3){
              black[k]=1;
          }
          else{
              black[k]=0;
          }
      }
      for (var k=20;k<w;k++){
        white=0;
        for (var p=k-20; p<k; p++){
          white=white+black[p];
        }
        if (white<=2){
           l=k-20;
           break;
        }
      }
      for (var k=black.length-20;k>1;k--){
        white=0;
        for (var p=k+20; p>k; p--){
          white=white+black[p];
        }
        if (white<=2){
          r=k+20;
          break;
        }
      }
      var b = [0,0,0,0,0];
      var black = [];
      var data1 = ctx.getImageData(wm,0,1,h).data;
      var data2 = ctx.getImageData(wm+1,0,1,h).data;
      var data3 = ctx.getImageData(wm+2,0,1,h).data;
      var data4 = ctx.getImageData(wm+3,0,1,h).data;
      var data5 = ctx.getImageData(wm+4,0,1,h).data;

      for (var k = 0; k < h; k++){
        b[0]=my_isblack(data1,k*4);
        b[1]=my_isblack(data2,k*4);
        b[2]=my_isblack(data3,k*4);
        b[3]=my_isblack(data4,k*4);
        b[4]=my_isblack(data5,k*4);
         if(b.reduce(getSum)>3){
             black[k]=1;
         }
         else{
             black[k]=0;
         }
      }
      for (var k=20;k<h;k++){
        white=0;
        for (var p=k-20; p<k; p++){
           white=white+black[p];
        }
        if (white<=2){
          u=k-20;
          break;
        }
      }

      for (var k=black.length-20;k>1;k--){
        white=0;
        for (var p=k+20; p>k; p--){
           white=white+black[p];
        }
        if (white<=2){
          d=k+20;
          break;
        }
      }
      return [l, (w-r), u , (h-d)];
    }


    function drawRotated(){
        ctx.save();
        ctx.translate(img.width/2,img.width/2);
        ctx.rotate(degrees);
        ctx.drawImage(img,-img.width/2,-img.width/2);
        ctx.restore();
    }

    function drawRotated2(image,degrees){
        ctx.save();
        ctx.translate(image.width/2,image.width/2);
        ctx.rotate(degrees);
        ctx.drawImage(image,-image.width/2,-image.width/2);
        ctx.restore();
    }

    function handleFileSelect(evt) {
      $('#loading').modal('show');
      $('#files_input').hide();
      $('#redForm_run').show();
      var files = evt.target.files; // FileList object
      // Loop through the FileList and render image files as thumbnails.
      var i = 0;
      (function loop() {
        f = files[i]
        if (f == null){
            $('#loading').modal('hide');
        }else{
            // Only process image files.
            if (!f.type.match('image.*')) {
                i++;
                setTimeout(loop, 0);
            }else{
                var reader = new FileReader();

                // Closure to capture the file information.
                reader.onload = (function(theFile) {
                  return function(e) {
                    // Render thumbnail.
                    var span = document.createElement('span');
                    span.innerHTML = ['<img height="100" class="thumb" src="', e.target.result, '" title="', escape(theFile.name), '"/><img class="thumb" name="forms" src="', e.target.result, '" title="', escape(theFile.name), '"/ hidden>'].join('');
                    document.getElementById('list').insertBefore(span, null);
                    var span = document.createElement('span');
                    span.innerHTML = ['<canvas id="borrar"></canvas>'].join('');
                    document.getElementById('list2').insertBefore(span, null);
                  };
                })(f);
                // Read in the image file as a data URL.
                reader.readAsDataURL(f);
                i++
                setTimeout(loop, 0);
            }
        }
      })();

    }

    function setImages(ca,ct,page, viewport, pdfs){
        var task = page.render({canvasContext: ct, viewport: viewport})
        task.promise.then(function(){
            srcs = ca.toDataURL('image/png');
            var span = document.createElement('span');
            span.innerHTML = ['<img height="100" class="thumb" src="', srcs, '"/><img class="thumb" name="forms" src="', srcs, '"/ hidden>'].join('');
            document.getElementById('list').insertBefore(span, null);
            var span = document.createElement('span');
            span.innerHTML = ['<canvas id="borrar"></canvas>'].join('');
            document.getElementById('list2').insertBefore(span, null);
            tpdf++;
            if (pdfs==tpdf){
              $('#loading').modal('hide');
            }
        });
    }

    function handleFileSelectPdf(ev) {
      $('#loading').modal('show');
      $('#files_input').hide();
      $('#redForm_run').show();
      if (file = document.getElementById('pdf').files[0]) {
        fileReader = new FileReader();
        fileReader.onload = function(ev) {
          PDFJS.getDocument(fileReader.result).then(function getPdfHelloWorld(pdf) {
            for (i = 1; i <= pdf.numPages; i++){
                pdf.getPage(i).then(function getPageHelloWorld(page) {
                    var scale = 3;
                    var viewport = page.getViewport(scale);
                    var tcanvas = document.createElement('canvas');
                    var tctx = tcanvas.getContext("2d");
                    tctx.clearRect(0, 0, tcanvas.width, tcanvas.height);
                    tcanvas.width = viewport.width;
                    tcanvas.height = viewport.height;
                    setImages(tcanvas,tctx, page, viewport, pdf.numPages);
                });
            }
          }, function(error){
            alert(error);
          });
        };
        fileReader.readAsArrayBuffer(file);
      }
    }

    function handleFiles(e) {

        var files = input.files;

        for (var i = 0; i < files.length; ++i) {
            img.onload = function(){
                set_sheet_corners()
            };
            img.src = URL.createObjectURL(e.target.files[i]);
        }
    }

    function pdftocanvas(ev) {
      if (file = document.getElementById('pdf').files[0]) {
        fileReader = new FileReader();
        fileReader.onload = function(ev) {
          PDFJS.getDocument(fileReader.result).then(function getPdfHelloWorld(pdf) {
            pdf.getPage(1).then(function getPageHelloWorld(page) {
              var scale = 3;
              var viewport = page.getViewport(scale);
              ctx = canvas.getContext("2d");
              ctx.clearRect(0, 0, canvas.width, canvas.height);
              canvas.width = viewport.width;
              canvas.height = viewport.height;
              var task = page.render({canvasContext: ctx, viewport: viewport})
              task.promise.then(function(){
                  srcs = canvas.toDataURL('image/png');
                  img.src = srcs;
                  setTimeout(function(){ set_sheet_corners(); },500);
              });
            });
          }, function(error){
            alert(error);
          });
        };
        fileReader.readAsArrayBuffer(file);
      }
    }

    function set_sheet_corners(){
      $('#file_upload').hide();
      $('#commands').show();
      ctx = canvas.getContext("2d");
      ctx.clearRect(0, 0, canvas.width, canvas.height);
      canvas.width = img.width;
      canvas.height = img.height;
      ctx.drawImage(img, 0, 0, img.width, img.height);
      corners=delStreaks(Math.round(img.height/2),Math.round(img.width/2),img.height,img.width);
      var x11=(260/1660)*img.width+corners[0];
      var v1 = verticalAxes(corners[2]+(2/2340)*img.height,corners[2]+(400/2340)*img.height,x11,(2/2340)*img.height,(1/2340)*img.height);
      var y21=(250/2340)*img.height+corners[2];
      var h1 = horizontalAxes(corners[0]+(2/1660)*img.width,corners[0]+(400/1660)*img.width,y21,(2/1660)*img.width,(1/1660)*img.width);
      var x12=(1380/1660)*img.width-corners[1];
      var v2 = verticalAxes(corners[2]+(2/2340)*img.height,corners[2]+(400/2340)*img.height,x12,(2/2340)*img.height,(1/2340)*img.height);
      var x13=(260/1660)*img.width+corners[0];
      var v3 = verticalAxes((1940/2340)*img.height-corners[3],(2320/2340)*img.height-corners[3],x13,(2/2340)*img.height,(1/2340)*img.height);
      var y23=(2070/2340)*img.height-corners[3];
      var h3 = horizontalAxes(corners[0]+(2/1660)*img.width,corners[0]+(400/1660)*img.width,y23,(2/1660)*img.width,(1/1660)*img.width);
      var x14=(1380/1660)*img.width-corners[2];
      var v4 = verticalAxes((1940/2340)*img.height-corners[3],(2320/2340)*img.height-corners[3],x14,(2/2340)*img.height,(1/2340)*img.height);
      var i1 = intersection(v1[0],v2[0],x11,x12,y21,y23,h1[0],h3[0]);
      var i3 = intersection(v3[(v3.length)-1],v4[(v4.length-1)],x13,x14,y23,y21,h3[0],h1[0]);

      degrees = -Math.atan(-1*(i3[0]-i1[0])/(i3[1]-i1[1]));
      drawRotated();

      var x11=(260/1660)*img.width+corners[0];
      var v1 = verticalAxes(corners[2]+(2/2340)*img.height,corners[2]+(400/2340)*img.height,x11,(2/2340)*img.height,(1/2340)*img.height);
      var y21=(250/2340)*img.height+corners[2];
      var h1 = horizontalAxes(corners[0]+(2/1660)*img.width,corners[0]+(400/1660)*img.width,y21,(2/1660)*img.width,(1/1660)*img.width);
      var x12=(1380/1660)*img.width-corners[1];
      var v2 = verticalAxes(corners[2]+(2/2340)*img.height,corners[2]+(400/2340)*img.height,x12,(2/2340)*img.height,(1/2340)*img.height);
      var y22=(250/2340)*img.height+corners[2];
      var h2 = horizontalAxes((1260/1660)*img.width-corners[1],(1640/1660)*img.width-corners[1],y22,(2/1660)*img.width,(1/1660)*img.width);
      var x13=(260/1660)*img.width+corners[0];
      var v3 = verticalAxes((1940/2340)*img.height-corners[3],(2320/2340)*img.height-corners[3],x13,(2/2340)*img.height,(1/2340)*img.height);
      var y23=(2070/2340)*img.height-corners[3];
      var h3 = horizontalAxes(corners[0]+(2/1660)*img.width,corners[0]+(400/1660)*img.width,y23,(2/1660)*img.width,(1/1660)*img.width);
      var x14=(1380/1660)*img.width-corners[2];
      var v4 = verticalAxes((1940/2340)*img.height-corners[3],(2320/2340)*img.height-corners[3],x14,(2/2340)*img.height,(1/2340)*img.height);
      var y24=(2070/2340)*img.height-corners[3];
      var h4 = horizontalAxes((1260/1660)*img.width-corners[1],(1640/1660)*img.width-corners[1],y24,(2/1660)*img.width,(1/1660)*img.width);
      var i1 = intersection(v1[0],v2[0],x11,x12,y21,y23,h1[0],h3[0]);
      var i2 = intersection(v1[0],v2[0],x11,x12,y22,y24,h2[(h2.length)-1],h4[(h4.length)-1]);
      var i3 = intersection(v3[(v3.length)-1],v4[(v4.length)-1],x13,x14,y23,y21,h3[0],h1[0]);
      var i4 = intersection(v3[(v3.length)-1],v4[(v4.length)-1],x13,x14,y24,y22,h4[(h4.length)-1],h2[(h2.length)-1]);
      sheet_corners = new Path(img.width,img.height, "red", 1, ctx);
      sheet_corners.moveTo(i1[0], i1[1]);
      sheet_corners.lineTo(i2[0], i2[1]);
      sheet_corners.lineTo(i4[0], i4[1]);
      sheet_corners.lineTo(i3[0], i3[1]);
      sheet_corners.lineTo(i1[0], i1[1]);
      sheet_corners.stroke();
      esq=[i1, i2, i3, i4];
      for (i = 0; i < esq.length; i++){
          if (isNaN(esq[i][0]) || isNaN(esq[i][1])) {
              alert("Sorry, the software could not detect the 'L' corners. Please check their position, thickness or darkness. If you don't know what are the 'L' corners, watch the video tutorial in order to understand how FormRead Works.");
          }
      }
      dx = i2[0] - i1[0];
      dy = i3[1] - i1[1];
      width = Math.round((2/1660)*img.width);
      height = Math.round((2/2340)*img.height);
      radius = Math.round((10/1660)*img.width);
      $("#width").val(width);
      $("#height").val(height);
      $("#radius").val(radius);
      init();
    }

    function Path(maxWidth, maxHeight, color, linewidth, drawingContext) {
        this.width = maxWidth;
        this.height = maxHeight;
        this.drawingCtx = drawingContext;
        this.points = [];
        this.canvas = document.createElement("canvas");
        this.canvas.width = maxWidth;
        this.canvas.height = maxHeight;
        this.ctx = this.canvas.getContext("2d");
        this.ctx.strokeStyle = color;
        this.ctx.lineWidth = linewidth;
        this.lastX;
        this.lastY;
    }
    Path.prototype.moveTo = function (x, y) {
        this.lastX = x;
        this.lastY = y;

    };
    Path.prototype.lineTo = function (x, y) {
        this.ctx.moveTo(this.lastX, this.lastY);
        this.ctx.lineTo(x, y);
        this.ctx.stroke();
        this.lastX = x;
        this.lastY = y;
    };
    Path.prototype.stroke = function () {
        this.drawingCtx.drawImage(this.canvas, 0, 0);
    };

    function Box() {
      this.x = 0;
      this.y = 0;
      this.w = 1;
      this.h = 1;
      this.r = 0;
      this.shape;
      this.fill = '#444444';
      this.multiMark = '2';
      this.field_name = '';
      this.q_id;
      this.q_option;
      this.idField=0;
      this.concatenate=0;
      this.corner=0;
    }

    Box.prototype.drawshape = function(context, shape, fill, shape_id) {
      context.fillStyle = fill;

      if (shape_id == 3 || shape_id == 4 || shape_id == 5 ||  shape_id == 10){
          context.globalAlpha=0.4;
      }else{
          context.globalAlpha=0.7;
      }

      if (shape.x > WIDTH || shape.y > HEIGHT) return;
      if (shape.x + shape.w < 0 || shape.y + shape.h < 0) return;

      context.fillRect(shape.x,shape.y,shape.w,shape.h);
      context.globalAlpha=1;
    };

    function Circle() {
      this.x = 0;
      this.y = 0;
      this.w = 0;
      this.h = 0;
      this.r = 1;
      this.shape;
      this.fill = '#444444';
      this.multiMark = '2';
      this.field_name = '';
      this.q_id;
      this.q_option;
      this.idField=0;
      this.concatenate=0;
    }

    Circle.prototype.drawshape = function(context, shape, fill) {
      context.fillStyle = fill;
      context.globalAlpha=0.7;
      if (shape.x > WIDTH || shape.y > HEIGHT) return;
      if (shape.x + shape.r < 0 || shape.y + shape.r < 0) return;

      context.beginPath();
      x=parseFloat(shape.x)+parseFloat(shape.r);
      y=parseFloat(shape.y)+parseFloat(shape.r);
      context.arc(x, y, shape.r, 0, 2 * Math.PI);

      context.lineWidth=0.1;
      context.fill();
      //context.strokeStyle = #ffffff;
      context.stroke();
      context.globalAlpha=1;
    };

    function addRect(x, y, w, h, fill, field, question, output, shape, multiMark, idField, concatenate) {
      var rect = new Box;
      rect.x = x;
      rect.y = y;
      rect.w = w;
      rect.h = h;
      rect.fill = fill;
      rect.multiMark = multiMark;
      rect.shape = shape;
      rect.field_name = field;
      rect.q_id = question;
      rect.q_option = output;
      rect.idField= idField;
      rect.concatenate = concatenate;
      boxes.push(rect);
      invalidate();
    }

    function addCircle(x, y, r, fill, field, question, output, shape, multiMark, idField, concatenate) {
      var circle = new Circle;
      circle.x = x;
      circle.y = y;
      circle.r = r;
      circle.fill = fill;
      circle.multiMark = multiMark;
      circle.shape = shape;
      circle.field_name = field;
      circle.q_id = question;
      circle.q_option = output;
      circle.idField= idField;
      circle.concatenate = concatenate;
      boxes.push(circle);
      invalidate();
    }

    function addArea(x, y, w, h, fill) {
      var rect = new Box;
      rect.x = x;
      rect.y = y;
      rect.w = w;
      rect.h = h;
      rect.fill = fill;
      boxes.push(rect);
      invalidate();
    }

    function addTempRect(x, y, w, h, r, fill, field, question, output, shape, multiMark, idField, concatenate, corner) {
      var rect = new Box;
      rect.x = x;
      rect.y = y;
      rect.w = w;
      rect.h = h;
      rect.r = r;
      rect.fill = fill;
      rect.multiMark = multiMark;
      rect.shape = shape;
      rect.field_name = field;
      rect.q_id = question;
      rect.q_option = output;
      rect.idField = idField;
      rect.concatenate = concatenate;
      rect.corner = corner;
      temp_boxes.push(rect);
    }

    function clear(c) {
      c.clearRect(0, 0, WIDTH, HEIGHT);
    }

    function draw() {
      if (canvasValid == false) {
        clear(ctx);
        // Add stuff you want drawn in the background all the time here
        ctx.save();
        ctx.translate(img.width/2,img.width/2);
        ctx.rotate(degrees);
        ctx.drawImage(img,-img.width/2,-img.width/2);
        ctx.restore();
        sheet_corners.stroke();
        // draw all boxes
        var l = boxes.length;

        for (var i = 0; i < l; i++) {
            boxes[i].drawshape(ctx, boxes[i], boxes[i].fill, boxes[i].shape);
        }

        canvasValid = true;
      }
    }

    function erase(){
        clear(gctx);
        var l = boxes.length;
        for (var i = l-1; i >= 0; i--) {
          // draw shape onto ghost context
          boxes[i].drawshape(gctx, boxes[i], 'black');

          // get image data at the mouse x,y pixel
          var imageData = gctx.getImageData(mx, my, 1, 1);
          var index = (mx + my * imageData.width) * 4;

          // if the mouse pixel exists, select and break
          if (imageData.data[3] > 0) {
            var fieldName = boxes[i].field_name;
            for (var js = 4 ; js < l ; js++) {
                if (boxes[js].field_name == fieldName){
                    boxes.splice(js,1);
                    invalidate();
                    js = 0;
                }
            }
            invalidate();
            return;
          }
        }
        clear(gctx);
        invalidate();
    }

    function myMove(e){
      if (isDrag){
        getMouse(e);

        for (var k = 0 ; k < mySel.length; k++){

            mySel[k].x = mx - offsetsx[k];
            mySel[k].y = my - offsetsy[k];
        }

        // something is changing position so we better invalidate the canvas!
        invalidate();
      }
    }

    function myDown(e){
      if (e.button==0){
        getMouse(e);
        clear(gctx);
        var l = boxes.length;
        for (var i = l-1; i >= 0; i--) {
          // draw shape onto ghost context
          boxes[i].drawshape(gctx, boxes[i], 'black');

          // get image data at the mouse x,y pixel
          var imageData = gctx.getImageData(mx, my, 1, 1);
          var index = (mx + my * imageData.width) * 4;

          // if the mouse pixel exists, select and break
          if (imageData.data[3] > 0) {
            for (var js = 0 ; js < l ; js++) {
                if (boxes[js].field_name == boxes[i].field_name){
                    mySel.push(boxes[js]);
                }
            }
            //alert (mySel.length);
            for (var k = 0 ; k < mySel.length; k++){
              offsetsx[k] = mx - mySel[k].x;
              offsetsy[k] = my - mySel[k].y;
              mySel[k].x = mx - offsetsx[k];
              mySel[k].y = my - offsetsy[k];
            }
            isDrag = true;
            canvas.onmousemove = myMove;
            invalidate();
            clear(gctx);
            return;
          }

        }
        // havent returned means we have selected nothing
        mySel = [];
        // clear the ghost canvas for next time
        clear(gctx);
        // invalidate because we might need the selection border to disappear
        invalidate();
      }else if(e.button==1) {
        getMouse(e);
        clear(gctx);
        var l = boxes.length;
        for (var i = l-1; i >= 0; i--) {
          // draw shape onto ghost context
          boxes[i].drawshape(gctx, boxes[i], 'black');

          // get image data at the mouse x,y pixel
          var imageData = gctx.getImageData(mx, my, 1, 1);
          var index = (mx + my * imageData.width) * 4;

          // if the mouse pixel exists, select and break
          if (imageData.data[3] > 0) {
            mySel.push(boxes[i])

            //alert (mySel.length);
            for (var k = 0 ; k < mySel.length; k++){
              offsetsx[k] = mx - mySel[k].x;
              offsetsy[k] = my - mySel[k].y;
              mySel[k].x = mx - offsetsx[k];
              mySel[k].y = my - offsetsy[k];
            }
            isDrag = true;
            canvas.onmousemove = myMove;
            invalidate();
            clear(gctx);
            return;
          }

        }
        // havent returned means we have selected nothing
        mySel = [];
        // clear the ghost canvas for next time
        clear(gctx);
        // invalidate because we might need the selection border to disappear
        invalidate();
      }else if(e.button==2) {
        getMouse(e);
        clear(gctx);
        var l = boxes.length;
        for (var i = l-1; i >= 0; i--) {
          // draw shape onto ghost context
          boxes[i].drawshape(gctx, boxes[i], 'black');

          // get image data at the mouse x,y pixel
          var imageData = gctx.getImageData(mx, my, 1, 1);
          var index = (mx + my * imageData.width) * 4;

          // if the mouse pixel exists, select and break
          if (imageData.data[3] > 0) {
            fsSel=boxes[i];
            for (var js = 0 ; js < l ; js++) {
                if (boxes[js].field_name == boxes[i].field_name){
                  if (boxes[js].shape!=10) {
                    if (boxes[js].q_option=="A"){
                        var output_up = 1;
                        js = l;
                    }else if (boxes[js].q_option=="0") {
                        var output_up = 2;
                        js = l;
                    }else {
                        var output_up = 3;
                    }
                  }
                    mySel.push(boxes[js]);
                }
            }
            //alert (mySel.length);
            $('#field_name_up').val(fsSel.field_name);
            $('#old_name').val(fsSel.field_name);
            $('#id_field_div_up').hide();
            if (fsSel.shape == 3 || fsSel.shape == 4 || fsSel.shape == 5){
              $('#multiMark_up').prop('disabled', true);
              $('#output_up').prop('disabled', true);
              $('#concatenate_up').prop('disabled', true);
              $('#multiMark_up').val('');
              $('#output_up').val('');
              $('#concatenate_up').val('');
              if (fsSel.shape == 5 || fsSel.shape == 4 ){
                $('#idField_up').val(fsSel.idField);
                $('#id_field_div_up').show();
              }
            }else{
              $('#multiMark_up').prop('disabled', false);
              $('#output_up').prop('disabled', false);
              $('#concatenate_up').prop('disabled', false);
              $('#multiMark_up').val(fsSel.multiMark);
              $('#output_up').val(output_up);
              $('#old_output').val(output_up);
              $('#concatenate_up').val(fsSel.concatenate);
              $('#idField_up').val(fsSel.idField);
              $('#id_field_div_up').show();
            }
            $('#myModal3').modal('show');

            isDrag = false;
            canvas.onmousemove = null;
            invalidate();
            clear(gctx);
            mySel = [];
            return;
          }

        }
        // havent returned means we have selected nothing
        mySel = [];
        // clear the ghost canvas for next time
        clear(gctx);
        // invalidate because we might need the selection border to disappear
        invalidate();
      }
    }

    function myUp(){
      isDrag = false;
      canvas.onmousemove = null;
      mySel = [];
    }

    function updateField(){
      $('#myModal3').modal('hide');
      var l = boxes.length;
      for (var i = 0 ; i < l ; i++) {
        if (boxes[i].field_name == $('#old_name').val()){
          boxes[i].field_name= $('#field_name_up').val();
          boxes[i].multiMark=$('#multiMark_up').val();
          boxes[i].idField=$('#idField_up').val();
          boxes[i].concatenate=$('#concatenate_up').val();
          if (boxes[i].shape!=10) {
            if ($('#old_output').val() != $('#output_up').val()){
              if ($('#old_output').val() == "2" && $('#output_up').val() == "3"){
                boxes[i].q_option=(parseInt(boxes[i].q_option)+1).toString();
              }else if ($('#old_output').val() == "3" && $('#output_up').val() == "2"){
                boxes[i].q_option=(parseInt(boxes[i].q_option)-1).toString();
              }else if ($('#old_output').val() == "2" && $('#output_up').val() == "1"){
                boxes[i].q_option=String.fromCharCode(65 + parseInt(boxes[i].q_option));
              }else if ($('#old_output').val() == "3" && $('#output_up').val() == "1"){
                boxes[i].q_option=String.fromCharCode(64 + parseInt(boxes[i].q_option));
              }else if ($('#old_output').val() == "1" && $('#output_up').val() == "2"){
                boxes[i].q_option=(boxes[i].q_option.charCodeAt(0) - 65).toString();
              }else if ($('#old_output').val() == "1" && $('#output_up').val() == "3"){
                boxes[i].q_option=(boxes[i].q_option.charCodeAt(0) - 64).toString();
              }
            }
          }
        }
      }
      $('#idField_up').val("0");
    }

    function duplicateRect(){
        var output = $("#output").val();
        var rows = $("#rows").val();
        var columns = $("#columns").val();
        if (rows == 1 && columns > 1){
            var x_dist = -(boxes[2].x - boxes[3].x)/(columns-1);
            var y_dist = 0;
        }else if(columns ==1 && rows > 1){
            var x_dist = 0;
            var y_dist = -(boxes[2].y - boxes[3].y)/(rows-1);
        }else if(columns ==1 && rows == 1){
            var x_dist = 0;
            var y_dist = 0;
        }else{
            var x_dist = -(boxes[2].x - boxes[3].x)/(columns-1);
            var y_dist = -(boxes[2].y - boxes[3].y)/(rows-1);
        }
        var inxy = [boxes[2].x,boxes[2].y];
        var field_name = $("#field_name").val();
        var field_orientation = $("#field_orientation").val();
        var multiMark = $("#multiMark").val();
        var idField = $("#idField").val();
        var concatenate = $("#concatenate").val();
        addRect(boxes[2].x-width, boxes[2].y-height, (boxes[3].x - boxes[2].x) + 3*width, (boxes[3].y - boxes[2].y) + 3*height, '#91e57b', field_name, 0 ,  0, 10, multiMark, idField, concatenate);
        boxes[2].x=10000;
        boxes[3].y=10000;
        invalidate();
        //var field_output = document.getElementById("field_field_output").value;
        for (var k = 0 ; k<rows; k++){
            var tempy = inxy[1] + (k*y_dist);
            for (var h = 0 ; h<columns; h++){
                var tempx = inxy[0] + (h*x_dist);
                //alert ("x,y = (" + tempx + "," + tempy + ")");
                if (field_orientation == 1){
                    if (output==1){
                       var que = String.fromCharCode(65 + h);
                    }else if(output==2){
                       var que = h;
                    }else if(output==3){
                       var que = h+1;
                    }
                    addRect(tempx, tempy, width, height, '#256b2d', field_name, k+1, que, 1, multiMark, idField, concatenate);
                }else if (field_orientation == 2){
                    if (output==1){
                       var que = String.fromCharCode(65 + k);
                    }else if(output==2){
                       var que = k;
                    }else if(output==3){
                       var que = k+1;
                    }
                    addRect(tempx, tempy, width, height, '#256b2d', field_name, h+1, que, 1, multiMark, idField, concatenate);
                }
            }
        }
        area_boxes_count = 0;
        $('#rows').val("");
        $('#columns').val("");
        $('#field_name').val("");
        $('#field_orientation').val("");
        $('#multiMark').val("");
        $('#commands').show();
        $('#cancel').hide();
        $("#markwidth").hide();
        $("#markheight").hide();
        $("#markradius").hide();
        $("#idField").val("");
        $("#concatenate").val('0');
    }

    function duplicateCircle(){
        var output = $("#output").val();
        var rows = $("#rows").val();
        var columns = $("#columns").val();
        if (rows == 1 && columns > 1){
            var x_dist = -(boxes[0].x - boxes[1].x)/(columns-1);
            var y_dist = 0;
        }else if(columns ==1 && rows > 1){
            var x_dist = 0;
            var y_dist = -(boxes[0].y - boxes[1].y)/(rows-1);
        }else if(columns ==1 && rows == 1){
            var x_dist = 0;
            var y_dist = 0;
        }else{
            var x_dist = -(boxes[0].x - boxes[1].x)/(columns-1);
            var y_dist = -(boxes[0].y - boxes[1].y)/(rows-1);
        }
        var inxy = [boxes[0].x,boxes[0].y];
        var field_name = $("#field_name").val();
        var field_orientation = $("#field_orientation").val();
        var multiMark = $("#multiMark").val();
        var idField = $("#idField").val();
        var concatenate = $("#concatenate").val();
        addRect(boxes[0].x-width, boxes[0].y-height, (boxes[1].x - boxes[0].x) + 3*width, (boxes[1].y - boxes[0].y) + 3*height, '#91e57b', field_name, 0, 0, 10, multiMark, idField, concatenate);
        boxes[0].x=10000;
        boxes[1].y=10000;
        invalidate();
        //var field_output = document.getElementById("field_field_output").value;
        for (var k = 0 ; k<rows; k++){
            var tempy = inxy[1] + (k*y_dist);
            for (var h = 0 ; h<columns; h++){
                var tempx = inxy[0] + (h*x_dist);
                //alert ("x,y = (" + tempx + "," + tempy + ")");
                if (field_orientation == 1){
                    if (output==1){
                       var que = String.fromCharCode(65 + h);
                    }else if(output==2){
                       var que = h;
                    }else if(output==3){
                       var que = h+1;
                    }
                    addCircle(tempx, tempy, radius, '#256b2d', field_name, k+1, que, 2, multiMark, idField, concatenate);
                }else if (field_orientation == 2){
                    if (output==1){
                       var que = String.fromCharCode(65 + k);
                    }else if(output==2){
                       var que = k;
                    }else if(output==3){
                       var que = k+1;
                    }
                    addCircle(tempx, tempy, radius, '#256b2d', field_name, h+1, que, 2, multiMark, idField, concatenate);
                }
            }
        }
        area_boxes_count = 0;

        $('#field_name').val("");
        $('#commands').show();
        $('#cancel').hide();
        $("#markwidth").hide();
        $("#markheight").hide();
        $("#markradius").hide();
        $("#idField").val("");
        $("#concatenate").val('0');
    }

    function crateArea(shape_id, fill){
        var field_name = $("#field_name").val();
        var idField = $("#idField").val();
        addRect(boxes[0].x+width/2, boxes[0].y+height/2, (boxes[1].x - boxes[0].x), (boxes[1].y - boxes[0].y), fill , field_name,0, 0, shape_id, "2", idField, 0);
        //addRect(boxes[0].x+width/2, boxes[0].y+height/2, (boxes[1].x - boxes[0].x) + 3/2*width, (boxes[1].y - boxes[0].y) + 3/2*height, '#579ad1', field_name, k+1, h+1, 3);
        boxes[0].x=10000;
        boxes[1].y=10000;
        invalidate();
        area_boxes_count = 0;
        $("#rows").val("");
        $("#columns").val("");
        $("#field_name").val("");
        $("#field_orientation").val("");
        $('#commands').show();
        $('#cancel').hide();
        $("#markwidth").hide();
        $("#markheight").hide();
        $("#markradius").hide();
        $("#idField").val("");
    }

    function myDblClick(e) {
        shape = document.getElementById("shape").value;
        getMouse(e);
        // for this method width and height determine the starting X and Y, too.
        // so I left them as vars in case someone wanted to make them args for something and copy this code
        if (shape == 1){
            area_boxes_count++;
            if (area_boxes_count==1){
                boxes[2].x=mx - radius;
                boxes[2].y=my - radius;
                invalidate();
            }else if(area_boxes_count==2){
                boxes[3].x=mx - radius;
                boxes[3].y=my - radius;
                invalidate();
            }if (area_boxes_count==3){
                duplicateRect();
                document.getElementById("shape").value = "";
            }
        }else if(shape == 2){
            area_boxes_count++;
            if (area_boxes_count==1){
                boxes[0].x=mx - radius;
                boxes[0].y=my - radius;
                invalidate();
            }else if(area_boxes_count==2){
                boxes[1].x=mx - radius;
                boxes[1].y=my - radius;
                invalidate();
            }if (area_boxes_count==3){
                duplicateCircle();
                document.getElementById("shape").value = "";
            }
        }else if(shape == 3){
            area_boxes_count++;
            if (area_boxes_count==1){
                boxes[0].x=mx - radius;
                boxes[0].y=my - radius;
                invalidate();
            }else if(area_boxes_count==2){
                boxes[1].x=mx - radius;
                boxes[1].y=my - radius;
                invalidate();
            }if (area_boxes_count==3){
                crateArea(shape, '#579ad1');
                document.getElementById("shape").value = "";
            }
        }else if(shape == 4){
            area_boxes_count++;
            if (area_boxes_count==1){
                boxes[0].x=mx - radius;
                boxes[0].y=my - radius;
                invalidate();
            }else if(area_boxes_count==2){
                boxes[1].x=mx - radius;
                boxes[1].y=my - radius;
                invalidate();
            }if (area_boxes_count==3){
                crateArea(shape, '#a815a8');
                document.getElementById("shape").value = "";
            }
        }else if(shape == 5){
            area_boxes_count++;
            if (area_boxes_count==1){
                boxes[0].x=mx - radius;
                boxes[0].y=my - radius;
                invalidate();
            }else if(area_boxes_count==2){
                boxes[1].x=mx - radius;
                boxes[1].y=my - radius;
                invalidate();
            }if (area_boxes_count==3){
                crateArea(shape, '#d8772d');
                document.getElementById("shape").value = "";
            }
        }else if(shape == 6){
                erase();
        }
    }

    function invalidate() {
      canvasValid = false;
    }

    function getMouse(e) {
          var element = canvas, offsetX = 0, offsetY = 0;

          if (element.offsetParent) {
            do {
              offsetX += element.offsetLeft;
              offsetY += element.offsetTop;
            } while ((element = element.offsetParent));
          }

          // Add padding and border style widths to offset
          offsetX += stylePaddingLeft;
          offsetY += stylePaddingTop;

          offsetX += styleBorderLeft;
          offsetY += styleBorderTop;

          mx = e.pageX - offsetX;
          my = e.pageY - offsetY
    }

    function saveform(deleteHTML, updateHTML) {
        $('#loading').modal('show');
        if (boxes.length <5){
            alert ("No ha realizado cambios");
            $('#loading').modal('hide');
            return;
        }
        var token = $( "input[name='_token']" ).val();

        $.ajax({
            async: false,
            url: deleteHTML,
            headers: {"X-CSRF-TOKEN": token},
            type: 'PUT',
        });

        var postData = [];
        for (i = 4 ; i<boxes.length; i++){
            var corner = getCorner(boxes[i].x,boxes[i].y,esq);
            var x = (boxes[i].x-esq[corner][0])/dx;
            var y = (boxes[i].y-esq[corner][1])/dy;
            var w = boxes[i].w/dx;
            var h = boxes[i].h/dy;
            var r = boxes[i].r/dx;
            postData.push({field_name:boxes[i].field_name, x: x, y: y, w: w, h: h, r: r, shape: boxes[i].shape, fill: boxes[i].fill.substring(1), multiMark: boxes[i].multiMark, q_id: boxes[i].q_id, q_option: boxes[i].q_option, idField: boxes[i].idField, concatenate: boxes[i].concatenate, corner: corner, _token: token});
        }

        $.ajax({
            async: true,
            url: updateHTML,
            headers: {"X-CSRF-TOKEN": token},
            type: 'POST',
            contentType: 'application/json',
            dataType: 'json',
            data: JSON.stringify(postData)
        }).always(function() {
            $('#loading').modal('hide');
        });
    }

    function getCorner(x,y,esq){
      d1 = Math.sqrt((Math.abs(y-esq[0][1])^2)+(Math.abs(x-esq[0][0])^2));
      d2 = Math.sqrt((Math.abs(y-esq[1][1])^2)+(Math.abs(x-esq[1][0])^2));
      d3 = Math.sqrt((Math.abs(y-esq[2][1])^2)+(Math.abs(x-esq[2][0])^2));
      d4 = Math.sqrt((Math.abs(y-esq[3][1])^2)+(Math.abs(x-esq[3][0])^2));
      d = new Array(d1,d2,d3,d4);
      corner =d.indexOf(Math.min.apply(null,d));
      return corner;
    }

    function set_tables(){
        $('#img_table').hide();
        var temp_q_id=0;
        var temp1 = "";
        for (var j=0; j<relativeCoord.length; j++){
            if (relativeCoord[j][8]>2){
                break;
            }
            if (relativeCoord[j][10] == 1){
                hasId=2 ;
                continue;
            }
            if (relativeCoord[j][11] == 1){
                var temp2 = relativeCoord[j][5];
            }else{
                var temp2 = relativeCoord[j][5] + "-" + relativeCoord[j][6];
            }
            if (temp2!=temp1){
                temp_q_id=0;
            }
            if (temp_q_id==0){
                var th = document.createElement('th');
                if (relativeCoord[j][11] == 1){
                    th.appendChild(document.createTextNode(relativeCoord[j][5]));
                    temp1 = relativeCoord[j][5];
                }else{
                    th.appendChild(document.createTextNode(relativeCoord[j][5] + "  " + relativeCoord[j][6]));
                    omr_titles.push(relativeCoord[j][5] + "-" + relativeCoord[j][6]);
                    temp1 = relativeCoord[j][5] + "-" + relativeCoord[j][6];
                }
                document.getElementById('resultsFormOmrHead').appendChild(th);
                temp_q_id=1;
            }
        }
        var temp_q_id=0;
        var temp1 = "";
        for (var j=0; j<relativeCoord.length; j++){
            if (relativeCoord[j][8]<=2){
                continue;
            }
            if (relativeCoord[j][8]>3){
                break;
            }
            var temp2 = relativeCoord[j][5] + "-" + relativeCoord[j][6];
            if (temp2!=temp1){
                temp_q_id=0;
            }
            if (temp_q_id==0){
                $('.cutting').show();
                var th = document.createElement('th');
                th.appendChild(document.createTextNode(relativeCoord[j][5]));
                document.getElementById('resultsFormImgHead').appendChild(th);
                img_titles.push(relativeCoord[j][5] + "-" + relativeCoord[j][6]);
                temp1 = relativeCoord[j][5] + "-" + relativeCoord[j][6];
                temp_q_id=1;
            }
        }
        var temp_q_id=0;
        var temp1 = "";
        for (var j=0; j<relativeCoord.length; j++){
            if (relativeCoord[j][8]<=3){
                continue;
            }
            if (relativeCoord[j][8]>4){
                break;
            }
            if (relativeCoord[j][10] == 1){
                hasId=3;
                continue;
            }
            var temp2 = relativeCoord[j][5] + "-" + relativeCoord[j][6];
            if (temp2!=temp1){
                temp_q_id=0;
            }
            if (temp_q_id==0){
                var th = document.createElement('th');
                th.appendChild(document.createTextNode(relativeCoord[j][5]));
                document.getElementById('resultsFormOcrHead').appendChild(th);
                temp1 = relativeCoord[j][5] + "-" + relativeCoord[j][6];
                temp_q_id=1;
            }
        }
        var temp_q_id=0;
        var temp1 = "";
        for (var j=0; j<relativeCoord.length; j++){
            if (relativeCoord[j][8]<=4){
                continue;
            }
            if (relativeCoord[j][8]>5){
                break;
            }
            if (relativeCoord[j][10] == 1){
                hasId=1;
                continue;
            }
            var temp2 = relativeCoord[j][5] + "-" + relativeCoord[j][6];
            if (temp2!=temp1){
                temp_q_id=0;
            }
            if (temp_q_id==0){
                var th = document.createElement('th');;
                th.appendChild(document.createTextNode(relativeCoord[j][5]));
                document.getElementById('resultsFormBcrHead').appendChild(th);
                temp1 = relativeCoord[j][5] + "-" + relativeCoord[j][6];
                temp_q_id=1;
            }
        }
    }

    function read() {
        $('#threshold_canvas').hide();
        $('#redForm_run').hide();
        $('#results').show();
        imgs = document.getElementsByName('forms');
        var canvas = document.createElement("canvas");
        //var canvas = document.getElementById("borrar");
        var iterations=imgs.length;
        (sId = []).length = imgs.length;
        var i = 0;
        (function loop() {
            prepare_sheet(i, canvas, imgs[i], relativeCoord);
            i++;
            if (i < iterations) {
                setTimeout(loop, 0);
            }
        })();
    }

    function prepare_sheet(i, canvas, img, relativeCoord2){
      ctx = canvas.getContext("2d");
      ctx.clearRect(0, 0, canvas.width, canvas.height);
      preview.style.width = img.width + "px";
      preview.style.height = img.height + "px";
      canvas.width = img.width;
      canvas.height = img.height;
      ctx.drawImage(img, 0, 0, img.width, img.height);
      corners=delStreaks(Math.round(img.height/2),Math.round(img.width/2),img.height,img.width);
      var x11=(260/1660)*img.width+corners[0];
      var v1 = verticalAxes(corners[2]+(2/2340)*img.height,corners[2]+(400/2340)*img.height,x11,(2/2340)*img.height,(1/2340)*img.height);
      var y21=(250/2340)*img.height+corners[2];
      var h1 = horizontalAxes(corners[0]+(2/1660)*img.width,corners[0]+(400/1660)*img.width,y21,(2/1660)*img.width,(1/1660)*img.width);
      var x12=(1380/1660)*img.width-corners[1];
      var v2 = verticalAxes(corners[2]+(2/2340)*img.height,corners[2]+(400/2340)*img.height,x12,(2/2340)*img.height,(1/2340)*img.height);
      var x13=(260/1660)*img.width+corners[0];
      var v3 = verticalAxes((1940/2340)*img.height-corners[3],(2320/2340)*img.height-corners[3],x13,(2/2340)*img.height,(1/2340)*img.height);
      var y23=(2070/2340)*img.height-corners[3];
      var h3 = horizontalAxes(corners[0]+(2/1660)*img.width,corners[0]+(400/1660)*img.width,y23,(2/1660)*img.width,(1/1660)*img.width);
      var x14=(1380/1660)*img.width-corners[2];
      var v4 = verticalAxes((1940/2340)*img.height-corners[3],(2320/2340)*img.height-corners[3],x14,(2/2340)*img.height,(1/2340)*img.height);
      var i1 = intersection(v1[0],v2[0],x11,x12,y21,y23,h1[0],h3[0]);
      var i3 = intersection(v3[(v3.length)-1],v4[(v4.length-1)],x13,x14,y23,y21,h3[0],h1[0]);

      degrees = -Math.atan(-1*(i3[0]-i1[0])/(i3[1]-i1[1]));
      drawRotated2(img,degrees);

      var x11=(260/1660)*img.width+corners[0];
      var v1 = verticalAxes(corners[2]+(2/2340)*img.height,corners[2]+(400/2340)*img.height,x11,(2/2340)*img.height,(1/2340)*img.height);
      var y21=(250/2340)*img.height+corners[2];
      var h1 = horizontalAxes(corners[0]+(2/1660)*img.width,corners[0]+(400/1660)*img.width,y21,(2/1660)*img.width,(1/1660)*img.width);
      var x12=(1380/1660)*img.width-corners[1];
      var v2 = verticalAxes(corners[2]+(2/2340)*img.height,corners[2]+(400/2340)*img.height,x12,(2/2340)*img.height,(1/2340)*img.height);
      var y22=(250/2340)*img.height+corners[2];
      var h2 = horizontalAxes((1260/1660)*img.width-corners[1],(1640/1660)*img.width-corners[1],y22,(2/1660)*img.width,(1/1660)*img.width);
      var x13=(260/1660)*img.width+corners[0];
      var v3 = verticalAxes((1940/2340)*img.height-corners[3],(2320/2340)*img.height-corners[3],x13,(2/2340)*img.height,(1/2340)*img.height);
      var y23=(2070/2340)*img.height-corners[3];
      var h3 = horizontalAxes(corners[0]+(2/1660)*img.width,corners[0]+(400/1660)*img.width,y23,(2/1660)*img.width,(1/1660)*img.width);
      var x14=(1380/1660)*img.width-corners[2];
      var v4 = verticalAxes((1940/2340)*img.height-corners[3],(2320/2340)*img.height-corners[3],x14,(2/2340)*img.height,(1/2340)*img.height);
      var y24=(2070/2340)*img.height-corners[3];
      var h4 = horizontalAxes((1260/1660)*img.width-corners[1],(1640/1660)*img.width-corners[1],y24,(2/1660)*img.width,(1/1660)*img.width);
      var i1 = intersection(v1[0],v2[0],x11,x12,y21,y23,h1[0],h3[0]);
      var i2 = intersection(v1[0],v2[0],x11,x12,y22,y24,h2[(h2.length)-1],h4[(h4.length)-1]);
      var i3 = intersection(v3[(v3.length)-1],v4[(v4.length)-1],x13,x14,y23,y21,h3[0],h1[0]);
      var i4 = intersection(v3[(v3.length)-1],v4[(v4.length)-1],x13,x14,y24,y22,h4[(h4.length)-1],h2[(h2.length)-1]);
      /*sheet_corners = new Path(img.width,img.height, "red", 1, ctx);
      sheet_corners.moveTo(i1[0], i1[1]);
      sheet_corners.lineTo(i2[0], i2[1]);
      sheet_corners.lineTo(i4[0], i4[1]);
      sheet_corners.lineTo(i3[0], i3[1]);
      sheet_corners.lineTo(i1[0], i1[1]);
      sheet_corners.stroke();*/
      var esq=[i1, i2, i3, i4];
      var stop_corner=0
      for (jj = 0; jj < esq.length; jj++){
          if (isNaN(esq[jj][0]) || isNaN(esq[jj][1])) {
              stop_corner=1
              //alert("Sorry, the software could not detect the 'L' corners of one of the sheets. Please check their position, thickness or darkness. If you don't know what are the 'L' corners, watch the video tutorial in order to understand how FormRead Works.");
          }
      }
      var dx = i2[0] - i1[0];
      var dy = i3[1] - i1[1];
      if (stop_corner==0){
        if (hasId==1){
            idRead(esq, dx, dy, ctx, relativeCoord2, function (id) {
              omrRead(id,  esq, dx, dy, ctx, relativeCoord2);
              bcrRead(id,  esq, dx, dy, ctx, relativeCoord2);
              ocrRead(id,  esq, dx, dy, ctx, relativeCoord2);
            });
        } else if (hasId==2) {
            idReadOmr(esq, dx, dy, ctx, relativeCoord2, function (id) {
              asyncRead(id,  esq, dx, dy, ctx, relativeCoord2);
            });
        } else if (hasId==3) {
            idReadOcr(esq, dx, dy, ctx, relativeCoord2, function (id) {
              omrRead(id,  esq, dx, dy, ctx, relativeCoord2);
              bcrRead(id,  esq, dx, dy, ctx, relativeCoord2);
              ocrRead(id,  esq, dx, dy, ctx, relativeCoord2);
            });
        } else {
          console.log(i);
            asyncRead(i,  esq, dx, dy, ctx, relativeCoord2);
        }
      }else{

      }
    }

    function asyncRead(id,  esq, dx, dy, ctxl, relativeCoord2) {
        setTimeout(function(){
          omrRead(id,  esq, dx, dy, ctxl, relativeCoord2);
          bcrRead(id,  esq, dx, dy, ctxl, relativeCoord2);
          ocrRead(id,  esq, dx, dy, ctxl, relativeCoord2);
        }, 1);
    }

    function is_box_black_corner(x,y,x_box,y_box, ctxl){
        x = Math.round(x);
        y = Math.round(y);
        x_box = Math.round(x_box);
        y_box = Math.round(y_box);
        var data = ctxl.getImageData(x, y, x_box, y_box).data;
        var counter=0;
        for (var xx=0; xx<data.length; xx++){
            var yy = xx*4;
	          counter+=((data[yy] + data[yy+1] + data[yy+2])/3)<threshold;
        }
        /*for (var xx=x; xx<(x+x_box); xx++){
            for (var yy=y; yy<(y+y_box); yy++){
                if(my_isblack(ctx,xx,yy)==1){
                    ctxl.fillStyle = "#FF0000";
                    ctxl.fillRect(xx,yy, 1, 1);
                }
            }
        }
        ctxl.beginPath();
        ctxl.lineWidth = "3";
        ctxl.strokeStyle = "red";
        ctxl.rect(x,y, x_box, y_box);
        ctxl.stroke();*/
        var mark = 0;
        if (counter >= (x_box*y_box*(darkn/100)) && Math.random()<=learn*10){
        	 mark = 1;
        	 var newCanvas = document.createElement("canvas");
        	 newCanvas.width = x_box;
        	 newCanvas.height = y_box;
        	 var imageData = ctxl.getImageData(x, y, x_box, y_box);
        	 newCanvas.getContext("2d").putImageData(imageData, 0, 0);
        	 var img = newCanvas.toDataURL();
        	 var percent = Math.round((counter/(x_box*y_box))*10000);
        	 storeOmrImg(img,mark,percent);
           if (Math.random()<=learn2){
             mark=3;
             var newCanvas = document.createElement("canvas");
             newCanvas.width = x_box;
             newCanvas.height = y_box;
             var imageData1 = ctxl.getImageData(x+(x_box/2), y, x_box, y_box);
             var imageData2 = ctxl.getImageData(x-(x_box/2), y, x_box, y_box);
             var imageData3 = ctxl.getImageData(x, y+(y_box/2), x_box, y_box);
             var imageData4 = ctxl.getImageData(x, y-(y_box/2), x_box, y_box);
             var imageData5 = ctxl.getImageData(x+(x_box/2), y+(y_box/2), x_box, y_box);
             var imageData6 = ctxl.getImageData(x+(x_box/2), y-(y_box/2), x_box, y_box);
             var imageData7 = ctxl.getImageData(x-(x_box/2), y+(y_box/2), x_box, y_box);
             var imageData8 = ctxl.getImageData(x-(x_box/2), y-(y_box/2), x_box, y_box);
             newCanvas.getContext("2d").putImageData(imageData1, 0, 0);
             var img1 = newCanvas.toDataURL();
             newCanvas.getContext("2d").putImageData(imageData2, 0, 0);
             var img2 = newCanvas.toDataURL();
             newCanvas.getContext("2d").putImageData(imageData3, 0, 0);
             var img3 = newCanvas.toDataURL();
             newCanvas.getContext("2d").putImageData(imageData4, 0, 0);
             var img4 = newCanvas.toDataURL();
             newCanvas.getContext("2d").putImageData(imageData5, 0, 0);
             var img5 = newCanvas.toDataURL();
             newCanvas.getContext("2d").putImageData(imageData6, 0, 0);
             var img6 = newCanvas.toDataURL();
             newCanvas.getContext("2d").putImageData(imageData7, 0, 0);
             var img7 = newCanvas.toDataURL();
             newCanvas.getContext("2d").putImageData(imageData8, 0, 0);
             var img8 = newCanvas.toDataURL();
             storeOmrImg(img1,mark,0);
             storeOmrImg(img2,mark,0);
             storeOmrImg(img3,mark,0);
             storeOmrImg(img4,mark,0);
             storeOmrImg(img5,mark,0);
             storeOmrImg(img6,mark,0);
             storeOmrImg(img7,mark,0);
             storeOmrImg(img8,mark,0);
           }
        }else if(counter <= (x_box*y_box*(darkn/100)) && Math.random()<=learn){
        	 var newCanvas = document.createElement("canvas");
        	 newCanvas.width = x_box;
        	 newCanvas.height = y_box;
        	 var imageData = ctxl.getImageData(x, y, x_box, y_box);
        	 newCanvas.getContext("2d").putImageData(imageData, 0, 0);
        	 var img = newCanvas.toDataURL();
        	 var percent = Math.round((counter/(x_box*y_box))*10000);
        	 storeOmrImg(img,mark,percent);
           if (Math.random()<=learn2){
             mark=3;
             var newCanvas = document.createElement("canvas");
             newCanvas.width = x_box;
             newCanvas.height = y_box;
             var imageData1 = ctxl.getImageData(x+(x_box/2), y, x_box, y_box);
             var imageData2 = ctxl.getImageData(x-(x_box/2), y, x_box, y_box);
             var imageData3 = ctxl.getImageData(x, y+(y_box/2), x_box, y_box);
             var imageData4 = ctxl.getImageData(x, y-(y_box/2), x_box, y_box);
             var imageData5 = ctxl.getImageData(x+(x_box/2), y+(y_box/2), x_box, y_box);
             var imageData6 = ctxl.getImageData(x+(x_box/2), y-(y_box/2), x_box, y_box);
             var imageData7 = ctxl.getImageData(x-(x_box/2), y+(y_box/2), x_box, y_box);
             var imageData8 = ctxl.getImageData(x-(x_box/2), y-(y_box/2), x_box, y_box);
             newCanvas.getContext("2d").putImageData(imageData1, 0, 0);
             var img1 = newCanvas.toDataURL();
             newCanvas.getContext("2d").putImageData(imageData2, 0, 0);
             var img2 = newCanvas.toDataURL();
             newCanvas.getContext("2d").putImageData(imageData3, 0, 0);
             var img3 = newCanvas.toDataURL();
             newCanvas.getContext("2d").putImageData(imageData4, 0, 0);
             var img4 = newCanvas.toDataURL();
             newCanvas.getContext("2d").putImageData(imageData5, 0, 0);
             var img5 = newCanvas.toDataURL();
             newCanvas.getContext("2d").putImageData(imageData6, 0, 0);
             var img6 = newCanvas.toDataURL();
             newCanvas.getContext("2d").putImageData(imageData7, 0, 0);
             var img7 = newCanvas.toDataURL();
             newCanvas.getContext("2d").putImageData(imageData8, 0, 0);
             var img8 = newCanvas.toDataURL();
             storeOmrImg(img1,mark,0);
             storeOmrImg(img2,mark,0);
             storeOmrImg(img3,mark,0);
             storeOmrImg(img4,mark,0);
             storeOmrImg(img5,mark,0);
             storeOmrImg(img6,mark,0);
             storeOmrImg(img7,mark,0);
             storeOmrImg(img8,mark,0);
           }
        }
        return counter;
    }

    function progressUpdate(packet){
           return packet.data.text;
    }

    function ocrRead (i, esq, dx, dy, ctxl, relativeCoord2){
        var tr = document.createElement('tr');
        for (var j=0; j<relativeCoord2.length; j++){
            if (relativeCoord2[j][8]<=3){
                continue;
            }
            if (relativeCoord2[j][8]>4){
                break;
            }
            if (relativeCoord2[j][10]==1){
                continue;
            }
            var width = (relativeCoord2[j][2]*dx);
            var height = (relativeCoord2[j][3]*dy);
            var newCanvas = document.createElement("canvas");
            newCanvas.width = width;
            newCanvas.height = height;
            var imageData = ctxl.getImageData((relativeCoord2[j][0]*dx)+esq[relativeCoord2[j][12]][0], (relativeCoord2[j][1]*dy)+esq[relativeCoord2[j][12]][1], width, height);
            newCanvas.getContext("2d").putImageData(imageData, 0, 0);
            ocrRead2(newCanvas,tr);
        }
    }

    function ocrRead2 (newCanvas, tr){
        var langsel = document.getElementById("langsel").value;
        Tesseract.recognize(newCanvas, {
        lang: langsel})
        .then(function(data){
            temp = progressUpdate({ status: 'done', data: data });
            var td = document.createElement('td');
            temp = temp;
            td.appendChild(document.createTextNode(temp));
            tr.appendChild(td);
            document.getElementById('resultsFormOcrBody').appendChild(tr);
        });
    }

    function idReadOcr (esq, dx, dy, ctxl, relativeCoord2, callback){
        for (var j=0; j<relativeCoord2.length; j++){
            if (relativeCoord2[j][8]<=3){
                continue;
            }
            if (relativeCoord2[j][8]>4){
                break;
            }
            if (relativeCoord2[j][10]==1){
              var width = (relativeCoord2[j][2]*dx);
              var height = (relativeCoord2[j][3]*dy);
              var newCanvas = document.createElement("canvas");
              newCanvas.width = width;
              newCanvas.height = height;
              var imageData = ctxl.getImageData((relativeCoord2[j][0]*dx)+esq[relativeCoord2[j][12]][0], (relativeCoord2[j][1]*dy)+esq[relativeCoord2[j][12]][1], width, height);
              newCanvas.getContext("2d").putImageData(imageData, 0, 0);
              idReadOcr2(newCanvas, function(id){
                  callback(id);
              });
            }
        }
    }

    function idReadOcr2 (newCanvas,callback){
    	var temp = OCRAD(newCanvas);
    	callback(temp);
        /*var langsel = document.getElementById("langsel").value;
        Tesseract.recognize(newCanvas, {
        lang: langsel})
        .then(function(data){
            temp = progressUpdate({ status: 'done', data: data });
            callback(temp);
        });*/
    }

    function idRead (esq, dx, dy, ctxl, relativeCoord2, callback){
        var tr = document.createElement('tr');
        for (var j=0; j<relativeCoord2.length; j++){
            if (relativeCoord2[j][8]<=4){
                continue;
            }
            if (relativeCoord2[j][8]>5){
                break;
            }
            if (relativeCoord2[j][10]==1){
              var width = (relativeCoord2[j][2]*dx);
              var height = (relativeCoord2[j][3]*dy);
              var newCanvas = document.createElement("canvas");
              newCanvas.width = width;
              newCanvas.height = height;
              var imageData = ctxl.getImageData((relativeCoord2[j][0]*dx)+esq[relativeCoord2[j][12]][0], (relativeCoord2[j][1]*dy)+esq[relativeCoord2[j][12]][1], width, height);
              newCanvas.getContext("2d").putImageData(imageData, 0, 0);
              var qrImg = newCanvas.toDataURL();
              idRead2(imageData,qrImg,tr, function(id){
                  callback(id);
              });
            }
        }
    }

    function idRead2 (imageData,qrImg,tr,callback){
        var qr = jsQR(imageData.data, imageData.width, imageData.height);
        if (qr){
            callback(qr.data);
        }else{
        var qr = new QCodeDecoder();
        qr.decodeFromImage(qrImg, function (err, result) {
            //if (err) throw err;
            temp = result;
            if (err){
               callback("Error");
               throw err;
            }else{
               callback(temp);
            }
        });
        }
    }

    function bcrRead (i,  esq, dx, dy, ctxl, relativeCoord2){
        var tr = document.createElement('tr');
        for (var j=0; j<relativeCoord2.length; j++){
            if (relativeCoord2[j][8]<=4){
                continue;
            }
            if (relativeCoord2[j][8]>5){
                break;
            }
            if (relativeCoord2[j][10]==1){
                continue;
            }
            var width = (relativeCoord2[j][2]*dx);
            var height = (relativeCoord2[j][3]*dy);
            var newCanvas = document.createElement("canvas");
            newCanvas.width = width;
            newCanvas.height = height;
            var imageData = ctxl.getImageData((relativeCoord2[j][0]*dx)+esq[relativeCoord2[j][12]][0], (relativeCoord2[j][1]*dy)+esq[relativeCoord2[j][12]][1], width, height);
            newCanvas.getContext("2d").putImageData(imageData, 0, 0);
            var qrImg = newCanvas.toDataURL("image/jpg");
            bcrRead2(imageData,qrImg,tr);
        }
    }

    function bcrRead2 (imageData,qrImg,tr){
        var td = document.createElement('td');
        var qr = jsQR(imageData.data, imageData.width, imageData.height);
        if (qr){
            td.appendChild(document.createTextNode(qr.data));
        }else{
        var qr = new QCodeDecoder();
        qr.decodeFromImage(qrImg, function (err, result) {
            temp = result;
            if (err){
              /*Quagga.decodeSingle({
                  decoder: {
                      readers: [
                        "code_128_reader",
                        "ean_reader",
                        "ean_8_reader",
                        "code_39_reader",
                        "code_39_vin_reader",
                        "codabar_reader",
                        "upc_reader",
                        "upc_e_reader",
                        "i2of5_reader"] // List of active readers
                  },
                  locate: true, // try to locate the barcode in the image
                  // You can set the path to the image in your server
                  // or using its base64 data URI representation data:image/jpg;base64, + data
                  src: qrImg
              }, function(result){
                  if(result.codeResult) {
                      td.appendChild(document.createTextNode(result.codeResult.code));
                  } else {*/
                      td.appendChild(document.createTextNode("not detected"));
                  /*}
              });*/
            }else{
               td.appendChild(document.createTextNode(temp));
            }
        });
        }
        tr.appendChild(td);
        document.getElementById('resultsFormBcrBody').appendChild(tr);
    }

    function omrRead (i,  esq, dx, dy, ctxl, relativeCoord2){
        darkn = document.getElementById("darkness").value;
        var darkness = document.getElementById("darkness").value;
        var tr = document.createElement('tr');
        tr.setAttribute("id", i, 0);
        var td_id = document.createElement('td');
        td_id.appendChild(document.createTextNode(i));
        tr.appendChild(td_id);

        var temp_q_id=0;
        var temp1 = "";
        var qtemp = "";
        var ocrTemp = [];
        var omr_responses = [];
        var concatenate = "";
        var temp3 = "";
        for (var j=0; j<relativeCoord2.length; j++){
            if (relativeCoord2[j][10]==1){
                continue;
            }
            if (relativeCoord2[j][8]>2){
                if (temp_q_id==0){
                    break;
                }
                if (temp2!='finish'){
                    var temp2 = relativeCoord2[j][5] + "-" + relativeCoord2[j][6];
                }
                if (temp2!=temp1){
                    temp_q_id=0;
                    var td = document.createElement('td');
                    var max_darkness=darkness/100;
                    var markTempCount = 0;
                    var markTemp =[];
                    var current_darkness = 0;
                    for (var qt=0; qt<ocrTemp.length; qt++){
                        if (ocrTemp[qt][0] >= (ocrTemp[qt][4]*ocrTemp[qt][5]*(darkness/100))){
                            current_darkness = (ocrTemp[qt][0]/(ocrTemp[qt][4]*ocrTemp[qt][5]));
                            if (current_darkness > max_darkness + 0.3){
                                max_darkness=current_darkness;
                                markTempCount=1;
                                markTemp = [ocrTemp[qt][1]];  
                            }else if (current_darkness >= dark_comp){
                                markTempCount++;
                                markTemp.push(ocrTemp[qt][1]);  
                            }
                        }
                    }
                    if (markTempCount==0){
                       qtemp = '';
                    }else if (markTempCount==1){
                       qtemp = markTemp[0];
                    }else if (markTempCount>1 && ocrTemp[0][3]==0){
                       qtemp = 'M';
                    }else if (markTempCount>1 && ocrTemp[0][3]==1){
                       for (var mt = 0; mt<markTemp.length ; mt++){
                           if (mt == 0){
                               qtemp = markTemp[mt];
                           }else{
                               if (ocrTemp[0][6]==1 || ocrTemp[0][7]==1){
                                  qtemp =qtemp + markTemp[mt];
                               }else{
                                  qtemp =qtemp + "," + markTemp[mt];
                               }
                           }
                       }
                    }
                    if (ocrTemp[0][6]==1 || ocrTemp[0][7]==1){
                        concatenate = concatenate + qtemp;
                        td.appendChild(document.createTextNode(concatenate));
                    }else{
                        td.appendChild(document.createTextNode(qtemp));
                    }
                    omr_responses.push(qtemp);
                    tr.appendChild(td);
                    ocrTemp = [];
                    qtemp = "";
                    temp1='finish';
                    temp2='finish';
                }
                break;
            }
            var width = (relativeCoord2[j][2]*dx);
            var height = (relativeCoord2[j][3]*dy);
            var radius = (relativeCoord2[j][4]*dx);
            var temp2 = relativeCoord2[j][5] + "-" + relativeCoord2[j][6];
            if (temp_q_id==0){
                if (relativeCoord2[j][8]==1){
                    ocrTemp.push([is_box_black_corner((relativeCoord2[j][0]*dx)+esq[relativeCoord2[j][12]][0], (relativeCoord2[j][1]*dy)+esq[relativeCoord2[j][12]][1], width, height, ctxl),relativeCoord2[j][7],relativeCoord2[j][8],relativeCoord2[j][9],width,height,relativeCoord2[j][10], relativeCoord2[j][11]]);
                } else if (relativeCoord2[j][8]==2){
                    ocrTemp.push([is_box_black_corner((relativeCoord2[j][0]*dx)+esq[relativeCoord2[j][12]][0], (relativeCoord2[j][1]*dy)+esq[relativeCoord2[j][12]][1], radius*2, radius*2, ctxl),relativeCoord2[j][7],relativeCoord2[j][8],relativeCoord2[j][9],radius*2, radius*2, relativeCoord2[j][10], relativeCoord2[j][11]]);
                }
                temp1 = relativeCoord2[j][5] + "-" + relativeCoord2[j][6];
                temp_q_id=1;
            }
            else  if (temp2!=temp1){
                temp_q_id=0;
                var td = document.createElement('td');
                var max_darkness=darkness/100;
                var markTempCount = 0;
                var markTemp =[];
                var current_darkness = 0;
                for (var qt=0; qt<ocrTemp.length; qt++){
                    if (ocrTemp[qt][0] >= (ocrTemp[qt][4]*ocrTemp[qt][5]*(darkness/100))){
                        current_darkness = (ocrTemp[qt][0]/(ocrTemp[qt][4]*ocrTemp[qt][5]));
                        if (current_darkness > max_darkness + dark_comp){
                            max_darkness=current_darkness;
                            markTempCount=1;
                            markTemp = [ocrTemp[qt][1]];  
                        }else if (current_darkness >= max_darkness){
                            markTempCount++;
                            markTemp.push(ocrTemp[qt][1]);  
                        }
                    }
                }
                if (markTempCount==0){
                   qtemp = '';
                }else if (markTempCount==1){
                   qtemp = markTemp[0];
                }else if (markTempCount>1 && ocrTemp[0][3]==0){
                   qtemp = 'M';
                }else if (markTempCount>1 && ocrTemp[0][3]==1){
                   for (var mt = 0; mt<markTemp.length ; mt++){
                       if (mt == 0){
                           qtemp = markTemp[mt];
                       }else{
                           if (ocrTemp[0][6]==1 || ocrTemp[0][7]==1){
                              qtemp =qtemp + markTemp[mt];
                           }else{
                              qtemp =qtemp + "," + markTemp[mt];
                           }
                       }
                   }
                }
                if (ocrTemp[0][6]==1 || ocrTemp[0][7]==1){
                    concatenate = concatenate + qtemp;
                    if (relativeCoord2[j-1][5] != relativeCoord2[j][5]) {
                      td.appendChild(document.createTextNode(concatenate));
                      tr.appendChild(td);
                      concatenate = "";
                    }
                }else{
                    td.appendChild(document.createTextNode(qtemp));
                    tr.appendChild(td);
                }
                omr_responses.push(qtemp);
                ocrTemp = [];
                qtemp = "";
		            j=j-1;
            }else if (temp2==temp1 && temp_q_id!=0){
                if (relativeCoord2[j][8]==1){
                    ocrTemp.push([is_box_black_corner((relativeCoord2[j][0]*dx)+esq[relativeCoord2[j][12]][0], (relativeCoord2[j][1]*dy)+esq[relativeCoord2[j][12]][1], width, height, ctxl),relativeCoord2[j][7],relativeCoord2[j][8],relativeCoord2[j][9],width, height, relativeCoord2[j][10],relativeCoord2[j][11]]);
                } else if (relativeCoord2[j][8]==2){
                    ocrTemp.push([is_box_black_corner((relativeCoord2[j][0]*dx)+esq[relativeCoord2[j][12]][0], (relativeCoord2[j][1]*dy)+esq[relativeCoord2[j][12]][1], radius*2, radius*2, ctxl),relativeCoord2[j][7],relativeCoord2[j][8],relativeCoord2[j][9],radius*2, radius*2, relativeCoord2[j][10],relativeCoord2[j][11]]);
                }
            }
        }
        document.getElementById('resultsFormOmrBody').appendChild(tr);
        if (typeof answers !== 'undefined'){
            imgRead(i,  esq, dx, dy, relativeCoord2, omr_responses);
        }
    }

    function idReadOmr (esq, dx, dy, ctxl, relativeCoord2, callback){
        darkn = document.getElementById("darkness").value;
        var darkness = document.getElementById("darkness").value;
        var temp_q_id=0;
        var temp1 = "";
        var qtemp = "";
        var ocrTemp = [];
        var concatenate = "";
        for (var j=0; j<relativeCoord2.length; j++){
          if (relativeCoord2[j][10]==1){
            if (relativeCoord2[j][8]>2){
                if (temp2!='finish'){
                    var temp2 = relativeCoord2[j][5] + "-" + relativeCoord2[j][6];
                }
                if (temp2!=temp1){
                    temp_q_id=0;
                    var markTempCount = 0;
                    var markTemp =[];
                    for (var qt=0; qt<ocrTemp.length; qt++){
                        if (ocrTemp[qt][0] >= (ocrTemp[qt][4]*ocrTemp[qt][5]*(darkness/100))){
                            markTempCount++;
                            markTemp.push(ocrTemp[qt][1]);
                        }
                    }
                    if (markTempCount==0){
                       qtemp = '';
                    }else if (markTempCount==1){
                       qtemp = markTemp[0];
                    }else if (markTempCount>1 && ocrTemp[0][3]==0){
                       qtemp = 'M';
                    }else if (markTempCount>1 && ocrTemp[0][3]==1){
                       for (var mt = 0; mt<markTemp.length ; mt++){
                           if (mt == 0){
                               qtemp = markTemp[mt];
                           }else{
                               qtemp =qtemp + markTemp[mt];
                           }
                       }
                    }
                    concatenate = concatenate + qtemp;
                    callback(concatenate);
                    ocrTemp = [];
                    qtemp = "";
                    temp1='finish';
                    temp2='finish';
                }
                break;
            }
            var width = (relativeCoord2[j][2]*dx);
            var height = (relativeCoord2[j][3]*dy);
            var radius = (relativeCoord2[j][4]*dx);
            var temp2 = relativeCoord2[j][5] + "-" + relativeCoord2[j][6];
            if (temp_q_id==0){
                if (relativeCoord2[j][8]==1){
                    ocrTemp.push([is_box_black_corner((relativeCoord2[j][0]*dx)+esq[relativeCoord2[j][12]][0], (relativeCoord2[j][1]*dy)+esq[relativeCoord2[j][12]][1], width, height, ctxl),relativeCoord2[j][7],relativeCoord2[j][8],relativeCoord2[j][9],width,height, relativeCoord2[j][10], relativeCoord2[j][11]]);
                } else if (relativeCoord2[j][8]==2){
                    ocrTemp.push([is_box_black_corner((relativeCoord2[j][0]*dx)+esq[relativeCoord2[j][12]][0], (relativeCoord2[j][1]*dy)+esq[relativeCoord2[j][12]][1], radius*2, radius*2, ctxl),relativeCoord2[j][7],relativeCoord2[j][8],relativeCoord2[j][9],radius*2, radius*2,relativeCoord2[j][10], relativeCoord2[j][11]]);
                }
                temp1 = relativeCoord2[j][5] + "-" + relativeCoord2[j][6];
                temp_q_id=1;
            }else  if (temp2!=temp1){
                temp_q_id=0;
                var td = document.createElement('td');
                var markTempCount = 0;
                var markTemp =[];
                for (var qt=0; qt<ocrTemp.length; qt++){
                    if (ocrTemp[qt][0] >= (ocrTemp[qt][4]*ocrTemp[qt][5]*(darkness/100))){
                        markTempCount++;
                        markTemp.push(ocrTemp[qt][1]);
                    }
                }
                if (markTempCount==0){
                   qtemp = '';
                }else if (markTempCount==1){
                   qtemp = markTemp[0];
                }else if (markTempCount>1 && ocrTemp[0][3]==0){
                   qtemp = 'M';
                }else if (markTempCount>1 && ocrTemp[0][3]==1){
                   for (var mt = 0; mt<markTemp.length ; mt++){
                       if (mt == 0){
                           qtemp = markTemp[mt];
                       }else{
                           qtemp =qtemp + markTemp[mt];
                       }
                   }
                }
                concatenate = concatenate + qtemp;
                ocrTemp = [];
                qtemp = "";
                if (relativeCoord2[j][8]==1){
                    ocrTemp.push([is_box_black_corner((relativeCoord2[j][0]*dx)+esq[relativeCoord2[j][12]][0], (relativeCoord2[j][1]*dy)+esq[relativeCoord2[j][12]][1], width, height, ctxl),relativeCoord2[j][7],relativeCoord2[j][8],relativeCoord2[j][9],width,height,relativeCoord2[j][10], relativeCoord2[j][11]]);
                } else if (relativeCoord2[j][8]==2){
                    ocrTemp.push([is_box_black_corner((relativeCoord2[j][0]*dx)+esq[relativeCoord2[j][12]][0], (relativeCoord2[j][1]*dy)+esq[relativeCoord2[j][12]][1], radius*2, radius*2, ctxl),relativeCoord2[j][7],relativeCoord2[j][8],relativeCoord2[j][9],radius*2, radius*2,relativeCoord2[j][10], relativeCoord2[j][11]]);
                }
            }else if (temp2==temp1 && temp_q_id!=0){
                if (relativeCoord2[j][8]==1){
                    ocrTemp.push([is_box_black_corner((relativeCoord2[j][0]*dx)+esq[relativeCoord2[j][12]][0], (relativeCoord2[j][1]*dy)+esq[relativeCoord2[j][12]][1], width, height, ctxl),relativeCoord2[j][7],relativeCoord2[j][8],relativeCoord2[j][9],width,height, relativeCoord2[j][10],relativeCoord2[j][11]]);
                } else if (relativeCoord2[j][8]==2){
                    ocrTemp.push([is_box_black_corner((relativeCoord2[j][0]*dx)+esq[relativeCoord2[j][12]][0], (relativeCoord2[j][1]*dy)+esq[relativeCoord2[j][12]][1], radius*2, radius*2, ctxl),relativeCoord2[j][7],relativeCoord2[j][8],relativeCoord2[j][9],radius*2, radius*2, relativeCoord2[j][10],relativeCoord2[j][11]]);
                }
            }
        }
      }
    }


   function imgRead (i,  esq, dx, dy, relativeCoord2, omr_responses){
       var temp =[];
       for (var j=0; j<relativeCoord2.length; j++){
           if (relativeCoord2[j][8]<=2){
               continue;
           }
           if (relativeCoord2[j][8]>3){
               break;
           }
           var td = document.createElement('td');
           var width = (relativeCoord2[j][2]*dx);
           var height = (relativeCoord2[j][3]*dy);
           var newCanvas = document.createElement("canvas");
           newCanvas.width = width;
           newCanvas.height = height;
           var imageData = ctx.getImageData((relativeCoord2[j][0]*dx)+esq[0], (relativeCoord2[j][1]*dy)+esq[1], width, height);
           newCanvas.getContext("2d").putImageData(imageData, 0, 0);
           temp.push(newCanvas.toDataURL());
       }
       cuts.push(temp);
       cuts_id.push(i);
       if (typeof answers !== 'undefined'){
           storeResponses(i, omr_responses, temp);
       }
   }

   function gradeImage(){
     if (cuts.length!=0) {
       if (cuts[0].length!=0){
         $('#temp_img').attr("src",cuts[0][0]);
         $('#modal_cuttings').modal('show');
       }else{
         cuts.shift();
         document.getElementById('resultsFormImgBody').appendChild(tr_img);
         idImg=cuts_id[0];
         cuts_id.shift();
         img_grades=img_grades_temp;
         img_grades_temp=[];
         if (typeof answers !== 'undefined'){
             storeResponsesImg(idImg, img_grades);
         }
         if (cuts.length==0) {
           $('#modal_cuttings').modal('hide');
         }else{
           tr_img = document.createElement('tr');
           setTimeout(gradeImage(),0);
         }
       }
     }
   }

   function gradeImage2(){
     cuts[0].shift();
     var td = document.createElement('td');
     td.appendChild(document.createTextNode($('#gradeImage').val()));
     tr_img.appendChild(td);
     img_grades_temp.push($('#gradeImage').val());
     $('#temp_img').attr("src","");
     $('#gradeImage').val("");
     gradeImage();
   }

   function storeResponses(student_id, omr_responses, imgs) {
       var average=0;
       var points=0;
       var omisiones=0;
       omr_titles.forEach(function(title, i){
           j = titles.indexOf(title);
           if (omr_responses[i]==''){
             omisiones=omisiones+1;
           }
           if (answers[j] != "*"){
               if (omr_responses[i]==answers[j]){
                 average = average + parseInt(weights[j]);
               }else{
                 average = average + 0;
               }
           }
       });
       titles.forEach(function(title, i){
           if(answers[i] != "*"){
             points = points + parseInt(weights[j]);
           }
       });
       grade=(average/points)*100;
       omr_responses=omr_responses.join(";");
       var token = $("input[name='_token']").val();
       console.log(omisiones);
       $.ajax({
           async: true,
           url: store_omr,
           headers: {"X-CSRF-TOKEN": token},
           type: 'POST',
           contentType: 'application/json',
           dataType: 'json',
           data: JSON.stringify({student_id: student_id, omr_responses: omr_responses, omr_grade: grade, responsible: cedula_oper, cuts: imgs, omisiones: omisiones}),
           success: function (data) {
             $("#"+student_id).css('background-color','rgba(0, 135, 0, 0.3)');
           }.bind(this, student_id),
           error:function() {
             $("#"+student_id).css('background-color','rgba(199, 0, 0, 0.5)');
           }.bind(this, student_id)
       })
   }

   function storeResponsesImg(student_id, img_responses) {
       var average=0;
       var points=0;
       img_titles.forEach(function(title, i){
           j = titles.indexOf(title);
           if (answers[j] != "*"){
              average = average + ((parseInt(img_responses[i])/10)*parseInt(weights[j]));
           }
       });
       titles.forEach(function(title, i){
           if(answers[i] != "*"){
             points = points + parseInt(weights[j]);
           }
       });
       grade=(average/points)*100;
       img_responses=img_responses.join(";");
       var token = $("input[name='_token']").val();
       $.ajax({
           async: true,
           url: store_img,
           headers: {"X-CSRF-TOKEN": token},
           type: 'POST',
           contentType: 'application/json',
           dataType: 'json',
           data: JSON.stringify({student_id: student_id, img_responses: img_responses, img_grade: grade}),
           success: function (data) {

           },
           error:function(){

           }
       })
   }

  function storeOmrImg(img, mark, percent) {  
  
       var my_string = '' + percent;
       while (my_string.length < 4) {
         my_string = '0' + my_string;
       }
       var token = $("input[name='_token']").val();
       $.ajax({
           async: true,
           url: storeOmrImgRoute,
           headers: {"X-CSRF-TOKEN": token},
           type: 'POST',
           contentType: 'application/json',
           dataType: 'json',
           data: JSON.stringify({cut: img, mark: mark, per: my_string}),
       })
   }