$(document).ajaxStart(function(){
    // Show image container
    $("#loader").show();
});
$(document).ajaxComplete(function(){
    // Hide image container
    $("#loader").hide();
});
var csrft = $('#csrf').attr('name');
function generate_image()
{
    var msg = $('#msg').val();
    var html ='<div class="media media-chat media-chat-reverse"><div class="media-body"><p>'+msg+'</p></div></div>';
    $('#chat-content').append(html);
    $('#msg').val('');
    $.ajax({
        type:'POST',
        url:"/generate_image",
        data:{_token: csrft,msg:msg},
        success:function(data){
    //   alert(data);
    $('#image').empty();
    $.each( data.success, function( key, value ) {
        var reply = '<figure><img src="'+value.url+'" alt="Snow"></figure>';
        $('#image').append(reply);
    });
}
});

}


function sendmsg()
{
      var msg = $('#msg').val();
      var html ='<div class="media media-chat media-chat-reverse"><div class="media-body"><p>'+msg+'</p></div></div>';
      $('#chat-content').append(html);
      $('#msg').val('');
  $.ajax({
    type:'POST',
    url:"/sendmsg",
    data:{_token: csrft ,msg:msg},
    success:function(data){
      $.each( data.success, function( key, value ) {
          var reply = '<div class="media media-chat"><img class="avatar" src="https://img.icons8.com/color/36/000000/administrator-male.png" alt="..."><div class="media-body"><p>'+value.text+'</p></div></div>';
          $('#chat-content').append(reply);
      });
  }
});

}