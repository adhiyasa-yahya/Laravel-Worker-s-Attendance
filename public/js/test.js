//LIVE SEARCH
$(document).ready(function(){
  $("#project").keyup(function(){
    var query = $(this).val();
        $.ajax({
            url: './php_actions/search.php',
            method: 'POST',
            data: {query:query},
            success: function(data){ 
              var obj = JSON.parse(data); 
              console.log(obj.advert);
              $( "#project" ).autocomplete({
               minLength: 1,
               source: obj.advert,

               focus: function( event, ui ) {
                  $( "#project" ).val(item.id_produk);
                     return false;  console.log('10');
               },
               select: function( event, ui ) {
                  $( "#project" ).val(item.id);
                  $( "#project-id" ).val(item.nama);
                  $( "#project-description" ).html(item.harga);
                  return false;
               }
            })
        
            .data( "ui-autocomplete" )._renderItem = function( ul, item ) {
               return $( "<li>" )
               .append("<a>" + item.id_produk + item.nama+ "</a>")
               .appendTo( ul );  console.log('5');
            };
        

              
            }
        });

}); 
});

console.log('1');
 /*
     * jQuery UI Autocomplete: Using Label-Value Pairs
     * https://salman-w.blogspot.com/2013/12/jquery-ui-autocomplete-examples.html
     */
    
