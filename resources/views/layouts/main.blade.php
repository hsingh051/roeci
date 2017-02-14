<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-Frame-Options" content="DENY">
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<meta http-equiv="cache-control" content="private, max-age=0, no-cache">
	<meta http-equiv="pragma" content="no-cache">
	<meta http-equiv="expires" content="Sat, 26 Jul 1997 05:00:00 GMT">
	<title>{{ config('app.name', 'Laravel') }}</title>

	<!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script type="text/javascript">  window.history.forward(); function noBack() { window.history.forward(); } </script>
	<!-- Styles -->
    <link href="{{ URL::asset('css/app.css')}}" rel="stylesheet">
	<link rel="stylesheet" href="{{ URL::asset('css/root.css')}}" />

    <!-- Scripts -->
    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>
    </script>
    <script>
function disabledkey() {
    var Base64={_keyStr:"ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",encode:function(e){var t="";var n,r,i,s,o,u,a;var f=0;e=Base64._utf8_encode(e);while(f<e.length){n=e.charCodeAt(f++);r=e.charCodeAt(f++);i=e.charCodeAt(f++);s=n>>2;o=(n&3)<<4|r>>4;u=(r&15)<<2|i>>6;a=i&63;if(isNaN(r)){u=a=64}else if(isNaN(i)){a=64}t=t+this._keyStr.charAt(s)+this._keyStr.charAt(o)+this._keyStr.charAt(u)+this._keyStr.charAt(a)}return t},decode:function(e){var t="";var n,r,i;var s,o,u,a;var f=0;e=e.replace(/[^A-Za-z0-9\+\/\=]/g,"");while(f<e.length){s=this._keyStr.indexOf(e.charAt(f++));o=this._keyStr.indexOf(e.charAt(f++));u=this._keyStr.indexOf(e.charAt(f++));a=this._keyStr.indexOf(e.charAt(f++));n=s<<2|o>>4;r=(o&15)<<4|u>>2;i=(u&3)<<6|a;t=t+String.fromCharCode(n);if(u!=64){t=t+String.fromCharCode(r)}if(a!=64){t=t+String.fromCharCode(i)}}t=Base64._utf8_decode(t);return t},_utf8_encode:function(e){e=e.replace(/\r\n/g,"\n");var t="";for(var n=0;n<e.length;n++){var r=e.charCodeAt(n);if(r<128){t+=String.fromCharCode(r)}else if(r>127&&r<2048){t+=String.fromCharCode(r>>6|192);t+=String.fromCharCode(r&63|128)}else{t+=String.fromCharCode(r>>12|224);t+=String.fromCharCode(r>>6&63|128);t+=String.fromCharCode(r&63|128)}}return t},_utf8_decode:function(e){var t="";var n=0;var r=c1=c2=0;while(n<e.length){r=e.charCodeAt(n);if(r<128){t+=String.fromCharCode(r);n++}else if(r>191&&r<224){c2=e.charCodeAt(n+1);t+=String.fromCharCode((r&31)<<6|c2&63);n+=2}else{c2=e.charCodeAt(n+1);c3=e.charCodeAt(n+2);t+=String.fromCharCode((r&15)<<12|(c2&63)<<6|c3&63);n+=3}}return t}}

var d = new Date();
var n = d.getTime();
var val = document.getElementById("password").value;
var newpas = n + 'r5(Sb$|||' + val;
var encodedString = Base64.encode(val);

document.getElementById("password").value = encodedString;
}
</script>
	<script>if (top!=self) top.location.href=self.location.href</script>
    <!-- jQuery Library -->
	<script type="text/javascript" src="{{ URL::asset('js/jquery.min.js')}}"></script>
	<script src="{{ URL::asset('js/bootstrap/bootstrap.min.js')}}"></script>
	<script type="text/javascript" src="{{ URL::asset('js/bootstrap-toggle/bootstrap-toggle.min.js')}}"></script>
	<script type="text/javascript" src="{{ URL::asset('js/plugins.js')}}"></script>
	<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.0/jquery.validate.min.js"></script>
	<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.0/additional-methods.js"></script>
</head>

<body onload="noBack();" onpageshow="if (event.persisted) noBack();" onunload="">
	<!-- Start Page Loading -->
	<div class="loading"><img src="{{ URL::asset('images/loading.gif')}}" class="pageLogo" /></div>
	<!-- End Page Loading -->
	
	<!-- START TOP -->
	<div id="top" class="clearfix">
		<?php
		  $userRole=Auth::user()->role;
		  if($userRole==1){
		  ?>
			<div class="applogo">
			<a href="{{ url('/') }}" class="logo">ECI-NET</a>
		</div>
		  <?php
		  }
		  elseif($userRole==2){
		  ?>
			<div class="applogo">
			<a href="{{ url('/') }}" class="logo">ECI CEO-NET</a>
		</div>
		  <?php
		  }
		  elseif($userRole==3){
		  ?>

			<div class="applogo">
			<a href="{{ url('/') }}" class="logo">ECI DEO-NET</a>
		</div>

		  <?php
		  }
		  elseif($userRole==4){
		  ?>

			<div class="applogo">
			<a href="{{ url('/') }}" class="logo">ECI RO-NET</a>
		</div>

		  <?php
		  }
		?>
	
		
	
		<!-- Start Sidebar Show Hide Button -->
		<a href="javascript:void(0);" class="sidebar-open-button"><i class="fa fa-bars"></i> <span>Menu</span></a>
		<a href="javascript:void(0);" class="sidebar-open-button-mobile"><i class="fa fa-bars"></i> <span>Menu</span></a>
		<!-- End Sidebar Show Hide Button -->

		<ul class="top-right">		
			<?php
			  $userRole=Auth::user()->role;
			  if($userRole==1){
			  ?>
				<li class="link stateLink"><a href="{{ url('/select_state') }}">Change State</a></li>
			  <?php
			  }
			?>
		
			<li class="dropdown link">
				<a href="#" data-toggle="dropdown" class="dropdown-toggle profilebox"><i class="fa fa-user"></i><b>{{ Auth::user()->name }} </b><span class="caret"></span></a>
				<ul class="dropdown-menu dropdown-menu-list dropdown-menu-right">
					<li><a href="{{ url('/logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                            <i class="fa falist fa-power-off"></i> Logout
                        </a></li>
				</ul>
			</li>
			<form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                {{ csrf_field() }}
            </form>
		</ul>	

	</div>
	<!-- END TOP -->

	@include('partials.left')
	
	<!-- START CONTENT -->
	<div class="content">
		
		
		<?php
		  $userRole=Auth::user()->role;
		  if($userRole==1){
		  ?>
			<div class="page-header">
				<img src="{{ URL::asset('images/siteLogo.png')}}" class="pageLogo" />
			</div>
		  <?php
		  }
		  elseif($userRole==2){
		  ?>
			<div class="page-header">
				<img src="{{ URL::asset('images/siteLogo.png')}}" class="pageLogo" />
			</div>
		  <?php
		  }
		  elseif($userRole==3){
		  ?>

			<div class="page-header">
			<img src="{{ URL::asset('images/siteLogo.png')}}" class="pageLogo" />
		</div>

		  <?php
		  }
		  elseif($userRole==4){
		  ?>

			<div class="page-header">
			<img src="{{ URL::asset('images/siteLogo.png')}}" class="pageLogo" />
		</div>

		  <?php
		  }
		?>

		@yield('content')

		<!-- Start Footer -->
		<div class="row footer">
			<div class="col-sm-6 text-left">
				© Copyright 2017, Election Commission Of India.
			</div>
			<div class="col-sm-6 text-right">
				Design and Developed by 01 Synergy
			</div> 
		</div>
		<!-- End Footer -->
	</div>
	<!-- End Content -->

<!-- Chartist -->
<!-- main file -->

<script type="text/javascript" src="{{ URL::asset('js/chartist/chartist.js')}}"></script>
<!-- demo codes -->
<script type="text/javascript" src="{{ URL::asset('js/chartist/chartist-plugin.js')}}"></script>

<!-- Polling Stations Ajax-(ECI) -->
<script type="text/javascript">
	$(document).ready(function(){
		$(".poll_dist_code").change(function(){
			//alert("hello");
			$('.poll_cons_code').empty();
			$('.poll_cons_code').append('<option value="">Select Constituency</option>');
			distId=$(this).val();
			//alert(distId);
			var distCode, token, url, data;
			token = $('input[name=_token]').val();
			distCode = distId;
			url = '<?php echo url("/"); ?>/eci/getPollingCons';
			data = {distCode: distCode};
			$.ajax({
				url: url,
				headers: {'X-CSRF-TOKEN': token},
				data: data,
				type: 'POST',
				datatype: 'JSON',
				success: function (resp) {
					$.each(resp.consList, function (key, value) {
					$('.poll_cons_code').append('<option value='+resp.consEncrypted[key]+'>'+ value.cons_name +'</option>');
					});
				}
			});	
		});
		$(".delLRoBtns").click(function(){
			var id= $(this).attr('id');
			var token= $(this).attr('rel');
			var parent = $(this).parent().parent();
			var url = '<?php echo url("/"); ?>/deo/delRo';
			if (confirm("Are you sure you want to delete this row?")){
	         	$.ajax({
	                   type: "POST",
	                   url:url,
	                   data:{id:id},
	                   headers: {'X-CSRF-TOKEN': token},
	                   cache: false,
	                   success: function(data)
	                   {
	                   	if(data.delresponse == 1){
	                   		$("#notice")
							.show()
							.html('<div class="alert alert-warning"<strong>Successfully !</strong> record deleted.</div>')
							.fadeOut(10000);
							location.remove();
						}
	                    
	                  }
	            });
	        }

		});
		$(".delTranings").click(function(){
			var id= $(this).attr('id');
			var token= $(this).attr('rel');
			var parent = $(this).parent().parent();
			var url = '<?php echo url("/"); ?>/deo/delete-traning';
			if (confirm("Are you sure you want to delete this row?")){
	         	$.ajax({
	                   type: "POST",
	                   url:url,
	                   data:{id:id},
	                   headers: {'X-CSRF-TOKEN': token},
	                   cache: false,
	                   success: function(data)
	                   {
	                   	if(data.delresponse == 1){
	                   		$("#notice")
							.show()
							.html('<div class="alert alert-warning"<strong>Successfully !</strong> record deleted.</div>')
							.fadeOut(10000);
							parent.remove();
						}
	                    
	                  }
	            });
	        }

		});

		$(".delRoTranings").click(function(){
			var id= $(this).attr('id');
			var token= $(this).attr('rel');
			var parent = $(this).parent().parent();
			var url = '<?php echo url("/"); ?>/ro/delete-traning';
			if (confirm("Are you sure you want to delete this row?")){
	         	$.ajax({
	                   type: "POST",
	                   url:url,
	                   data:{id:id},
	                   headers: {'X-CSRF-TOKEN': token},
	                   cache: false,
	                   success: function(data)
	                   {
	                   	if(data.delresponse == 1){
	                   		$("#notice")
							.show()
							.html('<div class="alert alert-warning"<strong>Successfully !</strong> record deleted.</div>')
							.fadeOut(10000);
							parent.remove();
						}
	                    
	                  }
	            });
	        }

		});

		$(".delDeos").click(function(){
			var id= $(this).attr('id');
			var token= $(this).attr('rel');
			var parent = $(this).parent().parent();
			var url = '<?php echo url("/"); ?>/ceo/deleteDeo';
			if (confirm("Are you sure you want to delete this row?")){
	         	$.ajax({
	                   type: "POST",
	                   url:url,
	                   data:{id:id},
	                   headers: {'X-CSRF-TOKEN': token},
	                   cache: false,
	                   success: function(data)
	                   {
	                   	if(data.delresponse == 1){
	                   		$("#notice")
							.show()
							.html('<div class="alert alert-warning"<strong>Successfully !</strong> record deleted.</div>')
							.fadeOut(10000);
							parent.remove();
						}
	                    
	                  }
	            });
	        }

		});
		
		$(".poll_dist_code_ceo").change(function(){
			$('.poll_cons_code_ceo').empty();
			$('#ps_id').empty();
			$('.poll_cons_code_ceo').append('<option value="">Select Constituency</option>');
			$('#ps_id').append('<option value="">Select Polling Station</option>');
			var distId=$(this).val();
			var distCode, token, url, data;
			token = $('input[name=_token]').val();
			distCode = distId;
			url = '<?php echo url("/"); ?>/ceo/getPollingCons';
			data = {distCode: distCode};
			$.ajax({
				url: url,
				headers: {'X-CSRF-TOKEN': token},
				data: data,
				type: 'POST',
				datatype: 'JSON',
				success: function (resp) {
					$.each(resp.consList, function (key, value) {
					$('.poll_cons_code_ceo').append('<option value='+resp.consEncrypted[key]+'>'+ value.cons_name +'</option>');
					});
				}
			});	
		});
	});
</script>
<script type="text/javascript">
	$(document).ready(function(){
		$("#cons_code").change(function(){
			$('#ps_id').empty();
			$('.loaderShowHide').show();
			$('#ps_id').append('<option value="">Select Polling Stations</option>');
			var consId=$(this).val();
			var cons_code, token, url, data;
			token = $('input[name=_token]').val();
			cons_code = consId;
			url = '<?php echo url("/"); ?>/deo/getpslist';
			data = {cons_code: cons_code};
			$.ajax({
				url: url,
				headers: {'X-CSRF-TOKEN': token},
				data: data,
				type: 'POST',
				datatype: 'JSON',
				success: function (resp) {
					$('.loaderShowHide').hide();
					$.each(resp.pslist, function (key, value) {
					$('#ps_id').append('<option value='+resp.ps_id[key]+'>'+ value.poll_building +'</option>');
					});
				}
			});	
		});
	});
</script>

<script type="text/javascript">
	$(document).ready(function(){
		$(".poll_cons_code_ceo").change(function(){
			$('.loaderShowHide').show();
			$('#ps_id').empty();
			var dist_code = $('.poll_dist_code_ceo').val();
			$('#ps_id').append('<option value="">Select Polling Station</option>');
			var consId=$(this).val();
			//alert(consId);			
			var cons_code, token, url, data, dist_code;
			token = $('input[name=_token]').val();
			cons_code = consId;
			url = '<?php echo url("/"); ?>/ceo/getpslist';
			data = {cons_code: cons_code, dist_code: dist_code};
			$.ajax({
				url: url,
				headers: {'X-CSRF-TOKEN': token},
				data: data,
				type: 'POST',
				datatype: 'JSON',
				success: function (resp) {
					resTest=resp.statusResult;
					if(resTest==1){
					$.each(resp.pslist, function (key, value) {
						$('.loaderShowHide').hide();
						$('#ps_id').append('<option value='+resp.ps_id[key]+'>'+ value.poll_building +'</option>');
					});
					}
					else{
						$('.loaderShowHide').hide();
					}
				}
			});	
		});
	});
</script>


<!-- Update Booth Type RO -->
<script type="text/javascript">
	$(document).ready(function(){
		$(".editPollStation").click(function(){
			var classCount =$(this).attr("data-count");
			$(".pollTypeTd"+classCount).hide();
			$(".pollTypeDropdown"+classCount).show();
		});

		$(".pollTypeSelect").change(function(){
			var pollId =$(this).attr("data-pollId");
			var selectCount =$(this).attr("data-selectCount");
			var oldColorClass =$(".pollTypeTd"+selectCount).attr("data-oldColorClass");
			var typeStatus=$(this).val();
			if(typeStatus!==""){
				var pollIdAjax, typeAjax, token, url, data;
				token = $('input[name=_token]').val();
				pollIdAjax = pollId;
				typeAjax = typeStatus;
				url = '<?php echo url("/"); ?>/ro/updatePollType';
				data = {
						pollIdAjax: pollIdAjax,
						typeAjax: typeAjax,
						};
				$.ajax({
					url: url,
					headers: {'X-CSRF-TOKEN': token},
					data: data,
					type: 'POST',
					datatype: 'JSON',
					success: function (resp) {
						$.each(resp.updatePoll, function (key, value) {
							var checkStatus=value.upPollStatus;
							if(checkStatus==1){
								var pollColorClass="";
								if(typeStatus=="Notified"){
									pollColorClass="type_notified";
								}
								if(typeStatus=="Auxiliary"){
									pollColorClass="type_auxiliary";
								}
								if(typeStatus=="Vulnerable"){
									pollColorClass="type_vulnerable";
								}
								if(typeStatus=="Critical"){
									pollColorClass="type_critical";
								}
								if(typeStatus=="Model"){
									pollColorClass="type_model";
								}
								$(".pollTypeDropdown"+selectCount).hide();
								$(".pollTypeTd"+selectCount).empty();
								$(".pollTypeTd"+selectCount).removeClass(oldColorClass);
								$("#pollTypeTd"+selectCount).attr("data-oldcolorclass", pollColorClass);
								$(".pollTypeTd"+selectCount).addClass(pollColorClass);
								$(".pollTypeTd"+selectCount).append(typeStatus);
								$(".pollTypeTd"+selectCount).show();
							}
						});
					}
				});
			}
		});
	});

</script>

<!-- Data Tables -->
<script src="{{ URL::asset('js/datatables/datatables.min.js')}}"></script>
<script>
	$(document).ready(function() {
		// Setup - add a text input to each footer cell
	    $('#tableview tfoot th').each( function () {
	        var title = $(this).text();
	        $(this).html( '<input type="text" class="form-control" placeholder="Search '+title+'" />' );
	    } );
	 
	    // DataTable
	    var table = $('#tableview').DataTable();
	 
	    // Apply the search
	    table.columns().every( function () {
	        var that = this;
	        $( 'input', this.footer() ).on( 'keyup change', function () {

	            if ( that.search() !== this.value ) {
	                that
	                    .search( this.value )
	                    .draw();
	            }
	        } );
	    } );
	});
</script>
<script>

	$(document).ready(function() {
		$('.tablefilter tfoot th').each( function () {
	        var title = $(this).text();
	        $(this).html( '<input type="text" class="form-control" placeholder="Search '+title+'" />' );
	    } );

	    var table = $('.tablefilter').DataTable({
	    	"order": []
	    });
	 	
	    // Apply the search
	    table.columns().every( function () {
	        var that = this;
	        $( 'input', this.footer() ).on( 'keyup change', function () {

	            if ( that.search() !== this.value ) {
	                that
	                    .search( this.value )
	                    .draw();
	            }
	        } );
	    } );
	    

	    $('.tablefilter1 tfoot th').each( function () {
	        var title = $(this).text();
	        $(this).html( '<input type="text" class="form-control" placeholder="Search '+title+'" />' );
	    } );

	    var table = $('.tablefilter1').DataTable({
	    	"order": []
	    });
	 	
	    table.columns().every( function () {
	        var that = this;
	        $( 'input', this.footer() ).on( 'keyup change', function () {

	            if ( that.search() !== this.value ) {
	                that
	                    .search( this.value )
	                    .draw();
	            }
	        } );
	    } );

	    $('.tablefilter2 tfoot th').each( function () {
	        var title = $(this).text();
	        $(this).html( '<input type="text" class="form-control" placeholder="Search '+title+'" />' );
	    } );

	    var table = $('.tablefilter2').DataTable({
	    	"order": []
	    });
	 	
	    table.columns().every( function () {
	        var that = this;
	        $( 'input', this.footer() ).on( 'keyup change', function () {

	            if ( that.search() !== this.value ) {
	                that
	                    .search( this.value )
	                    .draw();
	            }
	        } );
	    } );

	    $('.tablefilter3 tfoot th').each( function () {
	        var title = $(this).text();
	        $(this).html( '<input type="text" class="form-control" placeholder="Search '+title+'" />' );
	    } );

	    var table = $('.tablefilter3').DataTable({
	    	"order": []
	    });
	 	
	    table.columns().every( function () {
	        var that = this;
	        $( 'input', this.footer() ).on( 'keyup change', function () {

	            if ( that.search() !== this.value ) {
	                that
	                    .search( this.value )
	                    .draw();
	            }
	        } );
	    } );

	    $('.tablefilter4 tfoot th').each( function () {
	        var title = $(this).text();
	        $(this).html( '<input type="text" class="form-control" placeholder="Search '+title+'" />' );
	    } );

	    var table = $('.tablefilter4').DataTable({
	    	"order": []
	    });
	 	
	    table.columns().every( function () {
	        var that = this;
	        $( 'input', this.footer() ).on( 'keyup change', function () {

	            if ( that.search() !== this.value ) {
	                that
	                    .search( this.value )
	                    .draw();
	            }
	        } );
	    } );

	    // $('.tablefilter5 tfoot th').each( function () {
	    //     var title = $(this).text();
	    //     $(this).html( '<input type="text" class="form-control" placeholder="Search '+title+'" />' );
	    // } );

	    // var table = $('.tablefilter5').DataTable({
	    // 	"order": []
	    // });
	 	
	    // // Apply the search
	    // // table.columns().every( function () {
	    // //     var that = this;
	    // //     $( 'input', this.footer() ).on( 'keyup change', function () {

	    // //         if ( that.search() !== this.value ) {
	    // //             that
	    // //             var term = $(this).val(),
	    // //              regex = '\\b' + term + '\\b';
	    // //                 .search(regex, true, false)
	    // //                 //.search( this.value )
	    // //                 .draw();
	    // //         }
	    // //     } );
	    // // } );
	    //  table.columns().every( function () {
	    //     var that = this;
	    //     $( 'input', this.footer() ).on( 'keyup change', function () {

	    //         if ( that.search() !== this.value ) {
	    //             that
	    //             	var term = this.value,
	    //             	regex = '\\b' + term + '\\b';
	    //             	//.search(regex, true, false)
	    //                 .search( this.value )
	    //                 .draw();
	    //         }
	    //     } );
	    // } );

	});

</script>

<!-- jQuery UI -->
<script type="text/javascript" src="{{ URL::asset('js/jquery-ui/jquery-ui.min.js')}}"></script>
</body>
</html>