    function rgbToHex(r, g, b) {
       if (r > 255 || g > 255 || b > 255)
           throw "Invalid color component";
       return ((r << 16) | (g << 8) | b).toString(16);
   }

    function my_isblack(ctx,x,y){
	var p = ctx.getImageData(x, y, 1, 1).data; 
	if(p[0]<50 && p[1]<50 && p[2]<50){
		return 1;
	}
	else{
		return 0;
	}
    }
    
    function getSum(total, num) {
        return total + num;
    }

    function verticalAxes(ctx,start,end,x_pos,markSize,errorAdmited){
       markSize=Math.round(markSize);
       errorAdmited=Math.round(errorAdmited);
       var b = [0,0,0,0,0];                
       var black = []; 
       for (var k = start; k < end; k++){
           b[0]=my_isblack(ctx,x_pos,k);
           b[1]=my_isblack(ctx,x_pos+1,k);
           b[2]=my_isblack(ctx,x_pos+2,k);
           b[3]=my_isblack(ctx,x_pos+3,k);
           b[4]=my_isblack(ctx,x_pos+4,k);
           if(b.reduce(getSum)>3){
               black[k]=1;
               var imgData = ctx.createImageData(4, 1);
               var j;
               for (j = 0; j < imgData.data.length; j += 4) {
                   imgData.data[j+0] = 0;
                   imgData.data[j+1] = 0;
                   imgData.data[j+2] = 255;
                   imgData.data[j+3] = 255;
               }
               ctx.putImageData(imgData, x_pos, k);
           }
           else{                        
               black[k]=0; 
               var imgData = ctx.createImageData(4, 1);
               var j;
               for (j = 0; j < imgData.data.length; j += 4) {
                   imgData.data[j+0] = 255;
                   imgData.data[j+1] = 0;
                   imgData.data[j+2] = 0;
                   imgData.data[j+3] = 255;
               }
               ctx.putImageData(imgData, x_pos, k);
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
    
    function horizontalAxes(ctx,start,end,y_pos,markSize,errorAdmited){     
       markSize=Math.round(markSize);
       errorAdmited=Math.round(errorAdmited);       
       var b = [0,0,0,0,0];                
       var black = [];  
       for (var k = start; k < end; k++){
           b[0]=my_isblack(ctx,k,y_pos);
           b[1]=my_isblack(ctx,k,y_pos+1);
           b[2]=my_isblack(ctx,k,y_pos+2);
           b[3]=my_isblack(ctx,k,y_pos+3);
           b[4]=my_isblack(ctx,k,y_pos+4); 
           if(b.reduce(getSum)>3){
               black[k]=1;
               var imgData = ctx.createImageData(1, 4);
               var j;
               for (j = 0; j < imgData.data.length; j += 4) {
                   imgData.data[j+0] = 0;
                   imgData.data[j+1] = 0;
                   imgData.data[j+2] = 255;
                   imgData.data[j+3] = 255;
               }
               ctx.putImageData(imgData,k,y_pos);
           }
           else{  
               black[k]=0; 
               var imgData = ctx.createImageData(1, 4);
               var j;
               for (j = 0; j < imgData.data.length; j += 4) {
                   imgData.data[j+0] = 255;
                   imgData.data[j+1] = 0;
                   imgData.data[j+2] = 0;
                   imgData.data[j+3] = 255;
               }
               ctx.putImageData(imgData, k, y_pos);
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
        //alert ("puntos x= " + xi + " y= " + yi); 
        return i=[xi,yi];        
    }
    
    function drawRotated(){
        ctx.save();
        ctx.translate(img.width/2,img.width/2);
        ctx.rotate(degrees);
        ctx.drawImage(img,-img.width/2,-img.width/2);
        ctx.restore();
    }   
    
    function drawRotated2(image,ctx,degrees){
        ctx.save();
        ctx.translate(image.width/2,image.width/2);
        ctx.rotate(degrees);
        ctx.drawImage(image,-image.width/2,-image.width/2);
        ctx.restore();
    }  
    
    function handleFileSelect(evt) {
      var files = evt.target.files; // FileList object

      // Loop through the FileList and render image files as thumbnails.
      for (var i = 0, f; f = files[i]; i++) {

        // Only process image files.
        if (!f.type.match('image.*')) {
          continue;
        }

        var reader = new FileReader();

        // Closure to capture the file information.
        reader.onload = (function(theFile) {
          return function(e) { 
            // Render thumbnail.
            var span = document.createElement('span');
            span.innerHTML = ['<img height="100" class="thumb" src="', e.target.result, '" title="', escape(theFile.name), '"/><img class="thumb" name="forms" src="', e.target.result, '" title="', escape(theFile.name), '"/ hidden>'].join('');
            document.getElementById('list').insertBefore(span, null);
            var span = document.createElement('span');
            span.innerHTML = ['<canvas></canvas>'].join('');
            document.getElementById('list2').insertBefore(span, null);            
          };
        })(f);
        // Read in the image file as a data URL.
        reader.readAsDataURL(f);
      }
    }    
    
    function handleFiles(e) {

        var files = input.files;

        for (var i = 0; i < files.length; ++i) {

            var MAX_RATIO_A4 = 0.728;
            var MIN_RATIO_A4 = 0.704;
            var MAX_RATIO_LETTER = 0.776;
            var MIN_RATIO_LETTER = 0.768;            
            
            img.onload = function(){
                var x_y = img.width/img.height;
                if(x_y>MAX_RATIO_A4 || x_y<MIN_RATIO_A4) {
                    if(x_y>MAX_RATIO_LETTER || x_y<MIN_RATIO_LETTER) {
                        alert("Can't process the form, the sheet sizes must be A4 or LETTER");
                        return;
                    }
                }
                ctx = canvas.getContext("2d");
                ctx.clearRect(0, 0, canvas.width, canvas.height);
                canvas.width = img.width;
                canvas.height = img.height;                           
                ctx.drawImage(img, 0, 0, img.width, img.height);
                var x11=(250/1660)*img.width;
                var x12=(270/1660)*img.width; 
                var v1 = verticalAxes(ctx,(40/2340)*img.height,(400/2340)*img.height,x11,(20/2340)*img.height,(10/2340)*img.height);
                var v2 = verticalAxes(ctx,(40/2340)*img.height,(400/2340)*img.height,x12,(20/2340)*img.height,(10/2340)*img.height);               
                var y21=(240/2340)*img.height;
                var y22=(260/2340)*img.height;
                var h1 = horizontalAxes(ctx,(40/1660)*img.width,(400/1660)*img.width,y21,(20/1660)*img.width,(10/1660)*img.width);
                var h2 = horizontalAxes(ctx,(40/1660)*img.width,(400/1660)*img.width,y22,(20/1660)*img.width,(10/1660)*img.width); 
                var i1 = intersection(v1[0],v2[0],x11,x12,y21,y22,h1[0],h2[0]);
                var x11=(250/1660)*img.width;
                var x12=(270/1660)*img.width;   
                var v1 = verticalAxes(ctx,(1940/2340)*img.height,(2300/2340)*img.height,x11,(20/2340)*img.height,(10/2340)*img.height);
                var v2 = verticalAxes(ctx,(1940/2340)*img.height,(2300/2340)*img.height,x12,(20/2340)*img.height,(10/2340)*img.height);               
                var y21=(2060/2340)*img.height;
                var y22=(2080/2340)*img.height;
                var h1 = horizontalAxes(ctx,(40/1660)*img.width,(400/1660)*img.width,y21,(20/1660)*img.width,(10/1660)*img.width);
                var h2 = horizontalAxes(ctx,(40/1660)*img.width,(400/1660)*img.width,y22,(20/1660)*img.width,(10/1660)*img.width); 
                var i2 = intersection(v1[0],v2[0],x11,x12,y21,y22,h1[0],h2[0]);
                
                degrees = -Math.atan(-1*(i2[0]-i1[0])/(i2[1]-i1[1]));                
                drawRotated();
                var x11=(250/1660)*img.width;
                var x12=(270/1660)*img.width; 
                var v1 = verticalAxes(ctx,(40/2340)*img.height,(400/2340)*img.height,x11,(20/2340)*img.height,(10/2340)*img.height);
                var v2 = verticalAxes(ctx,(40/2340)*img.height,(400/2340)*img.height,x12,(20/2340)*img.height,(10/2340)*img.height);               
                var y21=(240/2340)*img.height;
                var y22=(260/2340)*img.height;
                var h1 = horizontalAxes(ctx,(40/1660)*img.width,(400/1660)*img.width,y21,(20/1660)*img.width,(10/1660)*img.width);
                var h2 = horizontalAxes(ctx,(40/1660)*img.width,(400/1660)*img.width,y22,(20/1660)*img.width,(10/1660)*img.width); 
                var i1 = intersection(v1[0],v2[0],x11,x12,y21,y22,h1[0],h2[0]);
                var x11=(1370/1660)*img.width;
                var x12=(1390/1660)*img.width;   
                var v1 = verticalAxes(ctx,(40/2340)*img.height,(400/2340)*img.height,x11,(20/2340)*img.height,(10/2340)*img.height);
                var v2 = verticalAxes(ctx,(40/2340)*img.height,(400/2340)*img.height,x12,(20/2340)*img.height,(10/2340)*img.height);               
                var y21=(240/2340)*img.height;
                var y22=(260/2340)*img.height;
                var h1 = horizontalAxes(ctx,(1260/1660)*img.width,(1620/1660)*img.width,y21,(20/1660)*img.width,(10/1660)*img.width);
                var h2 = horizontalAxes(ctx,(1260/1660)*img.width,(1620/1660)*img.width,y22,(20/1660)*img.width,(10/1660)*img.width); 
                var i2 = intersection(v1[0],v2[0],x11,x12,y21,y22,h1[0],h2[0]);                
                var x11=(250/1660)*img.width;
                var x12=(270/1660)*img.width;   
                var v1 = verticalAxes(ctx,(1940/2340)*img.height,(2300/2340)*img.height,x11,(20/2340)*img.height,(10/2340)*img.height);
                var v2 = verticalAxes(ctx,(1940/2340)*img.height,(2300/2340)*img.height,x12,(20/2340)*img.height,(10/2340)*img.height);               
                var y21=(2060/2340)*img.height;
                var y22=(2080/2340)*img.height;
                var h1 = horizontalAxes(ctx,(40/1660)*img.width,(400/1660)*img.width,y21,(20/1660)*img.width,(10/1660)*img.width);
                var h2 = horizontalAxes(ctx,(40/1660)*img.width,(400/1660)*img.width,y22,(20/1660)*img.width,(10/1660)*img.width); 
                var i3 = intersection(v1[0],v2[0],x11,x12,y21,y22,h1[0],h2[0]);
                var x11=(1370/1660)*img.width;
                var x12=(1390/1660)*img.width;   
                var v1 = verticalAxes(ctx,(1940/2340)*img.height,(2300/2340)*img.height,x11,(20/2340)*img.height,(10/2340)*img.height);
                var v2 = verticalAxes(ctx,(1940/2340)*img.height,(2300/2340)*img.height,x12,(20/2340)*img.height,(10/2340)*img.height);               
                var y21=(2060/2340)*img.height;
                var y22=(2080/2340)*img.height;  
                var h1 = horizontalAxes(ctx,(1260/1660)*img.width,(1620/1660)*img.width,y21,(20/1660)*img.width,(10/1660)*img.width);
                var h2 = horizontalAxes(ctx,(1260/1660)*img.width,(1620/1660)*img.width,y22,(20/1660)*img.width,(10/1660)*img.width); 
                var i4 = intersection(v1[0],v2[0],x11,x12,y21,y22,h1[0],h2[0]);                 
                sheet_corners = new Path(img.width,img.height, "red", 1, ctx);
                sheet_corners.moveTo(i1[0], i1[1]);
                sheet_corners.lineTo(i2[0], i2[1]);
                sheet_corners.lineTo(i4[0], i4[1]);
                sheet_corners.lineTo(i3[0], i3[1]);
                sheet_corners.lineTo(i1[0], i1[1]);
                sheet_corners.stroke();                
                esq=i1;
                dx = i2[0] - i1[0];
                dy = i3[1] - i1[1]; 
                width = (20/1660)*img.width;
                height = (20/2340)*img.height;  
                radius = (10/1660)*img.width;                
                init();                
            };
            img.src = URL.createObjectURL(e.target.files[i]);
        }
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
      this.multiMark = '';
      this.field_name = '';
      this.q_id;
      this.q_option;
    }
    
    Box.prototype.drawshape = function(context, shape, fill, shape_id) {
      context.fillStyle = fill;
      
      if (shape_id == 3 || shape_id == 4 || shape_id == 5 ||  shape_id == 10){
          context.globalAlpha=0.4;
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
      this.fill = '#444444';
      this.multiMark = '';
      this.shape = 2;
      this.field_name = '';
      this.q_id;
      this.q_option;
    }    

    Circle.prototype.drawshape = function(context, shape, fill) {
      context.fillStyle = fill;

      if (shape.x > WIDTH || shape.y > HEIGHT) return; 

      context.beginPath();
      context.arc(shape.x+shape.r, shape.y+shape.r, shape.r, 0, 2 * Math.PI);
      context.lineWidth=0.1;
      context.fill();
      //context.strokeStyle = #ffffff;
      context.stroke();
      context.globalAlpha=1;
    }; 

    function addRect(x, y, w, h, fill, field, question, output, shape, multiMark) {
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
      boxes.push(rect);
      invalidate();
    }
    
    function addCircle(x, y, r, fill, field, question, output, multiMark) {
      var circle = new Circle;
      circle.x = x;
      circle.y = y;
      circle.r = r;
      circle.fill = fill;
      circle.multiMark = multiMark;
      circle.field_name = field;
      circle.q_id = question;
      circle.q_option = output;
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
    
    function addTempRect(x, y, w, h, r, fill, field, question, output, shape, multiMark) {
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

        // draw selection
        // right now this is just a stroke along the edge of the selected box
        /*if (mySel != null) {
          ctx.strokeStyle = mySelColor;
          ctx.lineWidth = mySelWidth;
          ctx.beginPath();
          ctx.arc(mySel.x+mySel.r,mySel.y+mySel.r,mySel.r,0,Math.PI*2,true);
          ctx.closePath();
          ctx.strokeStyle = "#c82124";
          ctx.stroke();
          ctx.strokeRect(mySel.x,mySel.y,mySel.w,mySel.h);
        }*/

        // Add stuff you want drawn on top all the time here


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
    }

    function myUp(){
      isDrag = false;
      canvas.onmousemove = null;
      mySel = [];
    }
    
    function duplicateRect(){
        var rows = document.getElementById("rows").value;
        var colums = document.getElementById("colums").value;                
        var x_dist = -(boxes[2].x - boxes[3].x)/(colums-1);
        var y_dist = -(boxes[2].y - boxes[3].y)/(rows-1);
        var inxy = [boxes[2].x,boxes[2].y];        
        var field_name = document.getElementById("field_name").value;
        var field_orientation = document.getElementById("field_orientation").value; 
        var multiMark = document.getElementById("multiMark").value;
        addRect(boxes[2].x-width, boxes[2].y-height, (boxes[3].x - boxes[2].x) + 3*width, (boxes[3].y - boxes[2].y) + 3*height, '#91e57b', field_name, 0 ,  0, 10, multiMark);
        boxes[2].x=10000;
        boxes[3].y=10000; 
        invalidate();
        //var field_output = document.getElementById("field_field_output").value; 
        for (var k = 0 ; k<rows; k++){
            var tempy = inxy[1] + (k*y_dist);
            for (var h = 0 ; h<colums; h++){
                var tempx = inxy[0] + (h*x_dist);
                //alert ("x,y = (" + tempx + "," + tempy + ")");
                if (field_orientation == 1){                            
                    addRect(tempx, tempy, width, height, '#256b2d', field_name, k+1, h+1, 1, multiMark);
                }else if (field_orientation == 2){
                    addRect(tempx, tempy, width, height, '#256b2d', field_name, h+1, k+1, 1, multiMark);
                }   
            }
        } 
        area_boxes_count = 0;  
        document.getElementById("rows").value = "";
        document.getElementById("colums").value = "";
        document.getElementById("field_name").value = "";
        document.getElementById("field_orientation").value = ""; 
        document.getElementById("multiMark").value = "";
    }
    
    function duplicateCircle(){
        var rows = document.getElementById("rows").value;
        var colums = document.getElementById("colums").value;                
        var x_dist = -(boxes[0].x - boxes[1].x)/(colums-1);
        var y_dist = -(boxes[0].y - boxes[1].y)/(rows-1);
        var inxy = [boxes[0].x,boxes[0].y];        
        var field_name = document.getElementById("field_name").value;
        var field_orientation = document.getElementById("field_orientation").value; 
        var multiMark = document.getElementById("multiMark").value;
        addRect(boxes[0].x-width, boxes[0].y-height, (boxes[1].x - boxes[0].x) + 3*width, (boxes[1].y - boxes[0].y) + 3*height, '#91e57b', field_name, 0, 0, 10, multiMark);
        boxes[0].x=10000;
        boxes[1].y=10000;
        invalidate();
        //var field_output = document.getElementById("field_field_output").value; 
        for (var k = 0 ; k<rows; k++){
            var tempy = inxy[1] + (k*y_dist);
            for (var h = 0 ; h<colums; h++){
                var tempx = inxy[0] + (h*x_dist);
                //alert ("x,y = (" + tempx + "," + tempy + ")");
                if (field_orientation == 1){                            
                    addCircle(tempx, tempy, radius, '#256b2d', field_name, k+1, h+1, multiMark);
                }else if (field_orientation == 2){
                    addCircle(tempx, tempy, radius, '#256b2d', field_name, h+1, k+1, multiMark);
                }   
            }
        } 
        area_boxes_count = 0;  
        document.getElementById("rows").value = "";
        document.getElementById("colums").value = "";
        document.getElementById("field_name").value = "";
        document.getElementById("field_orientation").value = ""; 
        document.getElementById("multiMark").value = "";
    }
    
    function crateArea(shape_id, fill){   
        var field_name = document.getElementById("field_name").value;  
        addRect(boxes[0].x+width/2, boxes[0].y+height/2, (boxes[1].x - boxes[0].x), (boxes[1].y - boxes[0].y), fill , field_name,0, 0, shape_id);
        //addRect(boxes[0].x+width/2, boxes[0].y+height/2, (boxes[1].x - boxes[0].x) + 3/2*width, (boxes[1].y - boxes[0].y) + 3/2*height, '#579ad1', field_name, k+1, h+1, 3);
        boxes[0].x=10000;
        boxes[1].y=10000; 
        invalidate();        
        area_boxes_count = 0;  
        document.getElementById("rows").value = "";
        document.getElementById("colums").value = "";
        document.getElementById("field_name").value = "";
        document.getElementById("field_orientation").value = "";       
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

    function saveform() {  
        if (boxes.length <5){
            alert ("You haven't make any changes");
            return;
        }
        var form_name = document.getElementById("form_name").value;        
        var xhttp1;        
        var xhttp1 = new XMLHttpRequest();
        xhttp1.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                //alert (this.responseText);
            }};
        xhttp1.open("GET", "deleteform.php?id_user=1&form_name=" + form_name, false);       
        xhttp1.send();   
            
        alert (boxes.length);    
        for (var i = 4; i < boxes.length; i++){            
            var xhttp;        
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {                
                var node = document.createElement("LI");
                var textnode = document.createTextNode(this.responseText);
                node.appendChild(textnode);
                document.getElementById("notes").appendChild(node);             
            }};
            var x = (boxes[i].x-esq[0])/dx;
            var y = (boxes[i].y-esq[1])/dy;
            var w = boxes[i].w/dx;
            var h = boxes[i].h/dy;
            var r = boxes[i].r/dx;
            xhttp.open("GET", "saveform.php?id_user=1&form_name=" + form_name + "&field_name=" + boxes[i].field_name + "&x=" + x + "&y=" + y + "&w=" + w + "&h=" + h + "&r=" + r + "&shape=" + boxes[i].shape + "&fill=" + boxes[i].fill.substring(1) + "&multiMark=" + boxes[i].multiMark + "&q_id=" + boxes[i].q_id + "&q_option=" + boxes[i].q_option, false);            
            xhttp.send();            
        } 
        alert ('Form Succesfully Saved');
    }        
    
    