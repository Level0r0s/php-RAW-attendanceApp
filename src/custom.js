$(document).ready(function() {
    $('#example').DataTable( {
        paging: false,
    	"bSort": false,
    	dom: 'Bfrtip',
         buttons: [{
              extend: 'print',
              text: '<a class="waves-effect waves-light btn">Print</a>',
              title: $('h4').text(),
              footer: false,
              autoPrint: true
            }],
        initComplete: function () {
            this.api().columns('.class').every( function () {
                var column = this;
                var select = $('<select><option value="">All</option></select>')
                    .appendTo( $('.searchCol').empty() )
                    .on( 'change', function () {
                        var val = $.fn.dataTable.util.escapeRegex(
                            $(this).val()
                        );
 
                        column
                            .search( val ? '^'+val+'$' : '', true, false )
                            .draw();
                    });
 
                column.data().unique().sort().each( function ( d, j ) {
                    select.append( '<option value="'+d+'">'+d+'</option>' )
                } );
            } );
        }
    } );

} );

$('.datepicker').pickadate({
    selectMonths: true, // Creates a dropdown to control month
    selectYears: 5 // Creates a dropdown of 15 years to control year
  });


	$(document).ready(function(){
    $('ul.tabs').tabs();
    $('#example').DataTable();
  });
	 function refresh() {
     //alert('dsa');
     location.reload();
 }

 function printDiv(divID) {
    //Get the HTML of div
    var divElements = document.getElementById(divID).innerHTML;
    //Get the HTML of whole page
    var oldPage = document.body.innerHTML;

    //Reset the page's HTML with div's HTML only
    document.body.innerHTML = 
      "<html><head><title></title></head><body>" + 
      divElements + "</body>";

    //Print Page
    window.print();

    //Restore orignal HTML
    document.body.innerHTML = oldPage;


}

  $(document).ready(function() {
    $('select').material_select();
  });

 