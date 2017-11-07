$(document).ready(function(){
	$(".data-table").dataTable({
		"oLanguage": {
			"sSearch": "Pesquisar:",
			"sInfo": "Mostrando _START_ a _END_ de _TOTAL_ registros"
		},
		"sScrollY": "400px",
		"sScrollX": "100%",
		"sScrollXInner": "100%",
		"bPaginate": false,
		"aaSorting": [[0, "asc"]]
	});
	//$(".data-table").addClass('row');
	$(".dataTables_filter label").first().focus().addClass('small-4 columns');
});
