<script>

	var reports = new Vue({
		el: '#monthly_report',
		data: {
			month_year: ""
		},
		created: function(){
			this.set_month();
		},
		methods: {
			date_formats: function(value){
				var date = new Date(value);
				var options = {  
				    weekday: "long", 
				    year: "numeric",
				    month: "long",
				    day: "numeric",
				    hour: "2-digit", 
				    minute: "2-digit"  
				};  
				return date.toLocaleTimeString("en-us", options);
			},
			set_month: function(){
				var $this = this;
				swal({
					content: {
					    element: "input",
					    title: 'Set month and year',
					    attributes: {
					      type: "month"
					    },
				  	},				  	
				    closeOnClickOutside: false
				})
				.then((value) => {
					if(value){
						var date = new Date(value);
						var my = $this.date_formats(date);
						var s = my.split(" ");
						var month = s[1];
						var year  = s[3].replace(',', '');
						$this.month_year = month + " " + year;
						$("#month").text($this.month_year)
						$this.distribute_rows(value);
					}
					else{
						$this.set_month();
					}
				});
			},
			distribute_rows: function(value){
				$.getJSON('<?= base_url("reports/monthly_collection_data") ?>', {month_year: value}, function(json, textStatus) {
					console.log(json)
				});
			}
		}
	});
	
	$("#report_summary").tableExport({
	    headings: true,                     // (Boolean), display table headings (th/td elements) in the <thead>
	    footers: true,                      // (Boolean), display table footers (th/td elements) in the <tfoot>
	    formats: ["xlsx"],     // (String[]), filetype(s) for the export
	    fileName: "id",                     // (id, String), filename for the downloaded file
	    bootstrap: true,                    // (Boolean), style buttons using bootstrap
	    position: "bottom",                 // (top, bottom), position of the caption element relative to table
	    ignoreRows: null,                   // (Number, Number[]), row indices to exclude from the exported file
	    ignoreCols: null,                   // (Number, Number[]), column indices to exclude from the exported file
	    ignoreCSS: ".tableexport-ignore",   // (selector, selector[]), selector(s) to exclude cells from the exported file
	    emptyCSS: ".tableexport-empty",     // (selector, selector[]), selector(s) to replace cells with an empty string in the exported file
	    trimWhitespace: false               // (Boolean), remove all leading/trailing newlines, spaces (including non-breaking spaces), and tabs from cell text
	});	
	

</script>

<style>
	.header_small {
		font-size: 8px !important;
	}
</style>