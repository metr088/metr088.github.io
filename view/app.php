$(function () {
toastr.options = {
"closeButton": false,
"progressBar": true,
"debug": true,
"positionClass": 'toast-bottom-left',
"showDuration": 330,
"hideDuration": 330,
"timeOut": 2000,
"extendedTimeOut": 1000,
"showEasing": 'swing',
"hideEasing": 'swing', 
"showMethod": 'slideDown',
"hideMethod": 'slideUp',
"onclick": null
};
  $('[data-toggle="tooltip"]').tooltip();
var datatable = $('#datatablet').DataTable({
      'paging'      : false,
      'lengthChange': false,
      'searching'   : true,
      'ordering'    : true,
      'stateSave'   : true,
      'info'        : false,
      'autoWidth'   : false

    });

<? if($this->session['user_id'] == 1){ ?>

 $('#datatablet tbody').on('click', 'input[type="checkbox"]', function () {
        var fi = $(this).attr('data-ul');
        $('#define_' + fi).toggleClass('selected');
    });


$('#UserNewsAll').select2();


$("#selectCatAll").on('change', function (ev){
    var id = $(this).attr('data-id');
    var new_id = this.value;
    var ids = $.map(datatable.rows('.selected').data(), function (item) {
          return item[1]
    });

if(new_id >= 0){
    $.post('/logs/ajax/AllTransfer', {id_cat: id, new_cat: new_id, check_id: ids}, function (data){
    var str = JSON.parse(data);
    if('error' in str){
$('#selectCatAll option:selected').each(function(){
  this.selected=false;
});
     toastr.error(str.error);
    }
    if('success' in str){
        if(new_id > 0){
          location.href = "/logs/" + new_id;
        }else{
          location.href = "/logs";
        }

    }
    });
  }
});



$("#UserNewsAll").on('change', function (ev){
    var new_user = this.value;
    var ids = $.map(datatable.rows('.selected').data(), function (item) {
          return item[1]
    });

    $.post('/logs/ajax/selectUserAll', {user: new_user, ids: ids}, function (data){
    var str = JSON.parse(data);
    if('error' in str){
$('#UserNewsAll option:selected').each(function(){
  this.selected=false;
})
     toastr.error(str.error);
    }
    if('success' in str){
     toastr.success(str.success);
    }
    });
});


<? } ?>



$('#myInputTextField').keyup(function(){
      datatable.search($(this).val()).draw();
      console.log(datatable.row('td:first-child'));
});
}); 


<? if($this->session['user_id'] == 1){ ?>
function deleteFile(thiss){
    if(confirm('Вы уверены что хотите удалить?')){
    var id = $(thiss).attr('data-id');

   $("#loading").show();
setTimeout(function (){
    $.post('/logs/ajax/delete', {id: id}, function (data){
    var str = JSON.parse(data);
    
    if('error' in str){
     toastr.error(str.error);
    }
    if('success' in str){
     location.href = location.href;
    }

    });
    $("#loading").hide();
}, 200);
}
}

$('#deleteCat').click(function (){
   if(confirm('Вы уверены что хотите удалить?')){
    var id = $(this).attr('data-id');

    $.post('/logs/ajax/deleteTransfer', {id: id}, function (data){
    location.href = '/logs';
    });
  }
});
<? } ?>

function slupid(id){

    $.post('/logs/ajax/sulp', {id: id}, function (data){

     location.href = location.href;

    });


}


$('#changePassword').click(function (){
    var chp1 = $('#changePass1Hasher').val();
    var chp2 = $('#changePass2Hasher').val();

    $.post('/logs/ajax/change', {p1: chp1, p2: chp2}, function (data){
    var str = JSON.parse(data);
    if('error' in str){
     toastr.error(str.error);
    }
    if('success' in str){
     toastr.success(str.success);
    }
    });

});

$('#changeLogin').click(function (){
    var cpLogin = $('#changeNameHasher').val();
    var chp = $('#changePassHasher').val();

    $.post('/logs/ajax/change', {login: cpLogin, p1: chp}, function (data){
    var str = JSON.parse(data);
    if('error' in str){
     toastr.error(str.error);
    }
    if('success' in str){
     toastr.success(str.success);
    }
    });

});



<? if($this->session['user_id'] == 1){ ?>

$('#deleteAllb').click(function (){
    if(confirm('Вы уверены что хотите удалить?')){
    var password = $('#PasswordHasher').val();
    $("#loading").show();
setTimeout(function (){
    $.post('/logs/ajax/deleteAll', {password: password}, function (data){
    var str = JSON.parse(data);
    
    if('error' in str){
     toastr.error(str.error);
    }
    if('success' in str){
     location.href = location.href;
    }

    });
    $("#loading").hide();
}, 200);
}
});

$('#addCat').click(function (){
    var name = $('#catNameHasher').val();
    $.post('/logs/ajax/addTransfer', {name: name}, function (data){
     location.href = location.href;
    });
});
$('#deleteAllLog').click(function (){
    if(confirm('Вы уверены что хотите удалить?')){
    var password = $('#PasswordHasher').val();

    $("#loading").show();
setTimeout(function (){
    $.post('/logs/ajax/deleteLog', {password: password}, function (data){
    var str = JSON.parse(data);
    
    if('error' in str){
     toastr.error(str.error);
    }
    if('success' in str){
    location.href = location.href;
    }

    });
    $("#loading").hide();
  }, 200);
 }
});

<? } ?>

$('#datatablet #infoGet').click(function (){
    var id = $(this).attr('data-id');
    $("#loading").show();

    setTimeout(function (){

    $.post('/logs/ajax/information', {id: id}, function (data){
    //var jsic = JSON.parse(data);
    $('#InfoDataTable .modal-body').html(data);
    $('#InfoDataTable').modal();
    });
    $("#loading").hide();

    }, 200);
});