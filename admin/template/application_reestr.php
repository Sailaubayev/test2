	<div role="tabpanel" class="tab-pane" id="application_reestr">
								<table class="table table-bordered table-hover">
								<thead>
									<tr>
										<th></th>
										<th>№</th>
										<th></th>
										<th>Дата получения документов</th>
										<th>Клиент</th>
										<th>Номер телефона</th>
										<th>Номер автомобиля</th>
										<th>Пропуск действует до:</th>
										<th>Куда нужен пропуск ?</th>
										<th>На какой срок ?</th>
										<th>Нужна ли ДК ?</th>
										<th>Цена</th>
										<th>Статус</th>
										<th>Кто взял в работу ?</th>
										<th>Информация</th>
									</tr>
									</thead>
									<tbody>
	<?php
		require_once(dirname(__FILE__)."/../conf/config.php");
		$query="SELECT * FROM `application_reestr`";
		$result=mysql_query($query);
		$res=array();
		while ($row=mysql_fetch_assoc($result)) {
				$res[]=$row;
		}
		for ($i=0; $i<count($res); $i++){ 
			$color=$res[$i]['color'];
			if(mb_strtoupper($color)=="#FFFFFF"){
				$date1 = !strcmp($res[$i]['active_till'],"Нет активного пропуска")?new DateTime():DateTime::createFromFormat("Y-m-d",$res[$i]['active_till']);
				$date2 = new DateTime();
				$interval = date_diff($date2, $date1);
				if($interval->invert==1 || !strcmp($res[$i]['active_till'],"Нет активного пропуска")){
					$color="#fab6b6";//Красный
				}
				elseif($interval->days>2){
					$color="#c0fcb8";//Зеленый
				}
				elseif($interval->days<=2){
					$color="#faf796";//Желтый
				}
				else{
					$color="#fab6b6";//Красный
				}
			}
			?>

		<tr style="background-color:<?php echo $color;?>;" class="data-row" data-row-id="<?php echo $res[$i]['id'];?>">
			<td>
				<div style="background-color:<?php echo $color;?>;" data-color="<?php echo $res[$i]['color'];?>" class="colorpicker_label"></div>
			</td>
			<td><?php echo $i+1;?></td>
			<td class="action-column"><input type="checkbox"></td>
			<td class="editable"  data-name="app_date"><span><?php echo $res[$i]['app_date'];?></span></td>
			<td  class="editable" data-name="client_name"><span><?php echo $res[$i]['client_name'];?></span></td>
			<td class="editable" data-name="client_phone"><span><?php echo $res[$i]['client_phone'];?></span></td>
			<td class="editable" data-name="number_vehicle"><span><?php echo $res[$i]['number_vehicle'];?></span></td>
			<td><?php echo $res[$i]['active_till'];?></td>
			<td class="editable" data-name="where_need"><span><?php echo $res[$i]['where_need'];?></span></td>
			<td class="editable" data-name="what_term"><span><?php echo $res[$i]['what_term'];?></span></td>
			<td class="editable" data-name="neeed_dk"><span><?php echo $res[$i]['neeed_dk'];?></span></td>
			<td class="editable" data-name="price"><span><?php echo $res[$i]['price'];?></span></td>
			<td class="editable" data-name="status"><span><?php echo $res[$i]['status'];?></span></td>
			<td class="editable" data-name="who_works"><span><?php echo $res[$i]['who_works'];?></span></td>
			<td class="editable" data-name="info"><span><?php echo $res[$i]['info'];?></span></td>

			</tr>
			
	<?php } ?>
	</tbody>
	</table>
	<div class="reestr-actions">
		<button type="button"  data-toggle="modal" data-target="#new_reestr" class="btn btn-primary">Добавить запись</button>
		<button type="button" class="delete btn btn-primary">Удалить</button>
		<button type="button" class="move-to-our-clients btn btn-primary">Переместить в "Наши клиенты"</button>
	</div>
	<div class="modal fade" tabindex="-1" role="dialog"  id="new_reestr" aria-labelledby="">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title">Новая запись в реестре</h4>
	      </div>
	      <div class="modal-body">
	        <form id="new_reestr_form">
			  <div class="form-group">
			    <label for="name_input">Клиент (ФИО)</label>
			    <input type="text" name="name" required class="form-control" id="name_input" placeholder="Имя">
			  </div>
			  <div class="form-group">
			    <label for="phone_input">Телефон</label>
			    <input type="phone" name="phone" class="form-control" id="phone_input" placeholder="Телефон">
			  </div>
			  <div class="form-group">
			    <label for="number_vehicle_input">Номер ТС</label>
			    <input type="text" name="number_vehicle" class="form-control" id="number_vehicle_input" placeholder="Номер ТС">
			  </div>
			  <button type="submit" class="add-client  btn btn-default">Отправить</button>
			</form>	  
		</div>
	    </div><!-- /.modal-content -->
	  </div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
</div>




									
										
									
										
								
						