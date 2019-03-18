<script type="text/javascript">
var count=1;
var previousCount=0;
function uploadExcel(){
  // $('#myModal').modal('toggle');
  event.preventDefault();
    $('#myModal').modal('toggle');
  var file=document.getElementById("fileUpload");
  // console.log(document.getElementById("fileForm").serialize());
  for(var i=0;i<file.files.length;i++){
        var formData = new FormData();
        formData.append("file", document.getElementById('fileUpload').files[i]);
        $.ajax({
            url: 'file/upload/excel',
            type: 'POST',
            data: formData,
            xhr: function() {
                var myXhr = $.ajaxSettings.xhr();
                return myXhr;
            },
            success: function (data) {
             data = $.parseJSON(data);
              $('#myTable tr:last').after('<tr class="row"><td>'+(count)+'</td><td>'+data[0]+'</td><td>'+data[1]+'</td><td>'+data[2]+'</td></tr>');
              if(count==file.files.length+previousCount){
                  $('#myModal').modal('toggle');
                $('#fileForm').trigger("reset");
                previousCount=count;
              }
              count++;
              // console.log(data);
            },
            error:function(){alert("Error");},
            cache: false,
            contentType: false,
            processData: false
        });
  }
  // $('#myModal').modal('toggle');
  }
  function uploadWord(){
  event.preventDefault();
  $('#myModal').modal('toggle');
  var file=document.getElementById("fileUpload");
  // console.log(document.getElementById("fileForm").serialize());
  for(var i=0;i<file.files.length;i++){
        var formData = new FormData();
        formData.append("file", document.getElementById('fileUpload').files[i]);
        $.ajax({
            url: 'file/upload/word',
            type: 'POST',
            data: formData,
            headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
            xhr: function() {
                var myXhr = $.ajaxSettings.xhr();
                return myXhr;
            },
            success: function (data) {
             data = $.parseJSON(data);
              $('#myTable tr:last').after('<tr class="row"><td>'+(count)+'</td><td>'+data[0]+'</td><td>'+data[1]+'</td><td>'+data[2]+'</td></tr>');
              if(count==file.files.length+previousCount){
                $('#myModal').modal('toggle');
                $('#fileForm').trigger("reset");
                previousCount=count;
              }
              count++;
              console.log(data);
            },
            error:function(){alert("Error");},
            cache: false,
            contentType: false,
            processData: false
        });
  }
  }
</script>