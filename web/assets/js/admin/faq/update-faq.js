$(function(){
  $('#upload').HermesUpload({
      success:function( id, data ) {
          var id = parseInt(id.substr(id.length-1, 1))+1;
          var name = 'attachment'+id;
          $('.img-inputs').append('<input class="hidden" name="'+name+'" type="text" value="'+'files/'+data.uri+'">');
      },
      error:function( err ) {
          console.info( err );
      },
      buttonText : '选择文件',
      chunked:true,
      chunkSize:512 * 1024,
      fileNumLimit:5,
      fileSizeLimit:500000 * 1024,
      fileSingleSizeLimit:50000 * 1024,
  });
});