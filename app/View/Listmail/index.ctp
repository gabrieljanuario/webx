<div class="container">
	<p class="bg-primary" style="padding:10px"><?php echo $this->Html->link('>> Get Pages/Emails', array('controller' => 'crawler', 'action' => 'index'), array('style' => 'color: #FFF')); ?></p>

	<div class="panel panel-default">
	  <div class="panel-body">
	
		<table class="table table-striped">
			<thead>
				<tr>
					<td>Id</td>
					<td>Email</td>
				</tr>	
			</thead>
		
			<tbody id="loaderContent">
			</tbody>
		</table>
	
	  </div>
	</div>
</div>


<?php
echo $this->Html->scriptBlock('

	setInterval(function() {
		$.getJSON("'.Router::url(array('controller' => 'listmail', 'action' => 'getList')).'", function(data){

            var html = [];

           	$.each(data, function (i, item){
           	
           		if (!$("#loaderContent").filter("#" + data[i]["Emailz"]["id"]).length){
					html.push("<tr id="+data[i]["Emailz"]["id"]+"><td>"+data[i]["Emailz"]["id"]+"</td><td>"+data[i]["Emailz"]["email"]+"</td></tr>");
				}
            	
			});
			
           	$("#loaderContent").html(html.join(""));
            
		});

	}, 1000);

'); 
?>
