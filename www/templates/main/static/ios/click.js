$(document).ready(function() {
    $("#ios").click(function() {

      if(document.getElementById('radio-1').checked) {
      window.location.href="hfios.html";
    }
    else if(document.getElementById('radio-2').checked){
     window.location.href="dpios.html"
    }
    });

   $("#android").click(function() {

      if(document.getElementById('radio-1').checked) {
      window.location.href="hfandroid.html";
    }
    else if(document.getElementById('radio-2').checked){
     window.location.href="dpandroid.html"
    }
    });
   });