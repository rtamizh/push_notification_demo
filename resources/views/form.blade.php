<!DOCTYPE html>
<html>
    <head>
        <title>Laravel</title>
        <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="css/custom.css">
        <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    </head>
    <body>
		<div class="container">
			<div class="stepwizard">
			    <div class="stepwizard-row setup-panel">
			        <div class="stepwizard-step">
			            <a href="#step-1" type="button" class="btn btn-primary btn-circle">1</a>
			            <p>Create App</p>
			        </div>
			        <div class="stepwizard-step">
			            <a href="#step-2" type="button" class="btn btn-default btn-circle" disabled="disabled">2</a>
			            <p>Create User</p>
			        </div>
			        <div class="stepwizard-step">
			            <a href="#step-3" type="button" class="btn btn-default btn-circle" disabled="disabled">3</a>
			            <p>Create Notification</p>
			        </div>
			    </div>
			</div>
			<form role="form">
			    <div class="row setup-content" id="step-1">
			        <div class="col-xs-12">
			            <div class="col-md-12">
			                <h3> Create App </h3>
			                <div class="alert-primary" id="create-app-response" style="display:none"></div>
			                <div class="form-group">
			                    <label class="control-label">App Name</label>
			                    <input  maxlength="100" type="text" name="app_name" id="app_name" required="required" class="form-control" placeholder="Enter First Name"/>
			                </div>
			                @if(Config::get('realtime-pusher.app_secret_id') != null)
			                	<span class="stepwizard-step">
			                		You already created app. click on second step.
			                	</span>
			                @endif
			                <button class="btn btn-primary nextBtn btn-lg pull-right create-app" type="button" >Next</button>
			            </div>
			        </div>
			    </div>
			    <div class="row setup-content" id="step-2">
			        <div class="col-xs-12">
			            <div class="col-md-12">
			                <h3> Create User</h3>
			                <div class="form-group">
			                    <label class="control-label">User Name</label>
			                    <input maxlength="200" type="text" name="user_name" id="user_name" required="required" class="form-control" placeholder="Enter Company Name" />
			                </div>
			                <div class="form-group">
			                    <label class="control-label">Password</label>
			                    <input maxlength="200" type="text" name="user_password" id="user_password" required="required" class="form-control" placeholder="Enter Company Address"  />
			                </div>
			                @if(Session::has('user_secret'))
			                	You already created user. You can skip this step, open as many <a href="/notification/demo?user_secret={{Session::get('user_secret')}}" target="_blank"> tabs </a> to recieve notification for created user. click the next step to send notification.
			                @endif
			                <button class="btn btn-primary nextBtn btn-lg pull-right create-user" type="button" >Next</button>
			            </div>
			        </div>
			    </div>
			    <div class="row setup-content" id="step-3">
			        <div class="col-xs-12">
			            <div class="col-md-12">
			                <h3> Create Notification</h3>
			                <div class="form-group">
			                    <label class="control-label">Text</label>
			                    <input maxlength="200" type="text" name="text" id="text" required="required" class="form-control" placeholder="Enter Company Name" />
			                </div>
			                <div class="form-group">
			                    <label class="control-label">Image Url</label>
			                    <input maxlength="200" type="text" name="image_url" id="image_url" required="required" class="form-control" placeholder="Enter Company Address"  />
			                </div>
			                <div class="alert-primary" id="create-user-response" style="display:none"></div>
			                @if(Session::has('user_secret'))
			                	open as many <a href="/notification/demo?user_secret={{Session::get('user_secret')}}" target="_blank"> tabs </a> to recieve notification for created user.
			                @endif
			                <button class="btn btn-success btn-lg pull-right create-notification">Send</button>
			            </div>
			        </div>
			    </div>
			</form>
		</div>
	</body>
	<script>
		$(document).ready(function () {

		    var navListItems = $('div.setup-panel div a'),
		            allWells = $('.setup-content'),
		            allNextBtn = $('.nextBtn');

		    allWells.hide();

		    navListItems.click(function (e) {
		        e.preventDefault();
		        var $target = $($(this).attr('href')),
		                $item = $(this);

		        if (!$item.hasClass('disabled')) {
		            navListItems.removeClass('btn-primary').addClass('btn-default');
		            $item.addClass('btn-primary');
		            allWells.hide();
		            $target.show();
		            $target.find('input:eq(0)').focus();
		        }
		    });

		    allNextBtn.click(function(){
		    	if ($(this).hasClass('create-app')) {
		    		app_name = $('#app_name').val();
		    		$.ajax({
		    			url: '/create-app',
		    			data: {'app_name': app_name},
		    			type: 'GET',
		    			success: function (data) {
		    				if (data['message'] == 'error') {
		    					alert(data['error_msg']);
		    				}else{
		    					alert = 'change app_secret in .env, restart the server and reload this page- '+data['app_secret'];
		    					$('#create-app-response').html(alert);
		    					$('#create-app-response').show();
		    				}
		    			}
		    		});
		    	}else{
		    		if ($(this).hasClass('create-user')) {
			    		user_name = $('#user_name').val();
			    		password = $('#user_password').val();
			    		$.ajax({
			    			url: '/create-user',
			    			data: {'user_name': user_name, 'password': password},
			    			type: 'GET',
			    			success: function (data) {
			    				if (data['message'] == 'error') {
			    					alert(data['error_msg']);
			    				}
			    				$('#create-user-response').html('open as many <a href="/notification/demo?user_secret='+data['user_secret']+'" target="_blank"> tabs </a> to recieve notification for created user.');
			    				$('#create-user-response').show();
			    			}
			    		})
			    	}
			        var curStep = $(this).closest(".setup-content"),
			            curStepBtn = curStep.attr("id"),
			            nextStepWizard = $('div.setup-panel div a[href="#' + curStepBtn + '"]').parent().next().children("a"),
			            curInputs = curStep.find("input[type='text'],input[type='url']"),
			            isValid = true;

			        $(".form-group").removeClass("has-error");
			        for(var i=0; i<curInputs.length; i++){
			            if (!curInputs[i].validity.valid){
			                isValid = false;
			                $(curInputs[i]).closest(".form-group").addClass("has-error");
			            }
			        }

			        if (isValid)
			            nextStepWizard.removeAttr('disabled').trigger('click');
		    	}
		    });

		    $('div.setup-panel div a.btn-primary').trigger('click');

		    $('.create-notification').click(function () {
		    	text = $('#text').val();
		    	image = $('#image_url').val();
		    	$.ajax({
		    		url: '/notify',
		    		data: {'text': text, 'image': image},
		    		type: 'GET',
		    		success: function (data) {
		    			console.log('success');
		    		}
		    	})
		    })
		});
	</script>
</html>
