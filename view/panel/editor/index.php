  <link rel="stylesheet" href="/upload/jqueryFileTree/jqueryFileTree.css">
  <script src="http://code.jquery.com/jquery.js"></script>
  <script type="text/javascript" src="http://code.jquery.com/ui/1.10.0/jquery-ui.js"></script>
  <script src="/upload/jqueryFileTree/jqueryFileTree.js"></script>


<div class="box box-solid flat">
    <div class="box-header with-border">
       <h3 class="box-title">Архив</h3>
       <a href="/logs/download/<?=$type['id'];?>" class="btn btn-success-outline btn-xs pull-right">Скачать</a>

    </div>
<div class="box-body" style="padding: 10px;">
<div class="col-md-4 col-sm-12 col-xs-12">
<div class="files" style="width: 100%;"></div>
</div>
<div class="col-md-8 col-sm-12 col-xs-12">
<div class="input-group" style="display: block;">
<input type="text" class="form-control" id="textSearch" style="width: 70%;">
<button class="btn btn-success-outline btn-flat" id="searchBtn" style="width: 30%;">Найти</button>
</div>
<textarea id="texter" class="form-control" disabled="" style="width: 100%;height: 580px;"></textarea>
</div>
</div>
</div>
<script>
	$(document).ready( function() {
	    $('.files').fileTree({
	        script: '/editor/system/<?=$type['id'];?>',
	    }, function(file) {
             $('#texter').val('Загрузка...');
	        $.post("/editor/text/<?=$type['id'];?>",{ id: file }, function (data){
	          $('#texter').attr('data-id', file);
			  $('#texter').val(data);
	        });

	    });

	    $.get("/editor/text/home/<?=$type['id'];?>", function (data){
	    	var str = JSON.parse(data);
	       $('#texter').attr('data-id', str.id);
		   $('#texter').val(str.text);
	    });


$('#searchBtn').click(function (){
var tex = $('#textSearch').val();
var id = $('#texter').attr('data-id');


	    $.post("/editor/search/<?=$type['id'];?>", {id: id, text: tex},function (data){
	    	var str = JSON.parse(data);
          $('ul li[data-active]').removeClass('active');
         $.each(str, function(index, value){
		        if(value.check == 1){
                  $('ul li[data-active="' +index+ '"]').addClass('active');
                  console.log(value);
		        }
	     });
         
		   $('#texter').val(str[id].edit);
	    });

});

	});
</script>