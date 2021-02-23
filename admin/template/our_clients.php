	<div role="tabpanel" class="tab-pane" id="our_clients">
								<table class="table table-bordered table-hover data-table">
								<thead>
									<tr>
										<th>№</th>
										<th></th>
										<th>Клиент(ФИО)</th>
										<th>Телефон</th>
										<th>Номер автомобиля</th>
										<th>Номер пропуска</th>
										<th>Тип пропуска</th>
										<th>Зона</th>
										<th>Статус</th>
										<th>Дата выдачи</th>
										<th>Дата окончания</th>
										<th>Email</th>
										<th>Комментарий</th>
										<th>СМС</th>
									</tr>
									</thead>
									<tbody>
	<?php
		require_once(dirname(__FILE__)."/../conf/config.php");
		$query="SELECT * FROM `our_clients`";
		$result=mysql_query($query);
		$res=array();
		while ($row=mysql_fetch_assoc($result)) {
				$res[]=$row;
		}
		for ($i=0; $i<count($res); $i++){ 
			$color=$res[$i]['color'];
			if(mb_strtoupper($color)=="#FFFFFF"){
				$date1 =  DateTime::createFromFormat("Y-m-d",$res[$i]['end_date']);
				$date2 = new DateTime();
				$interval = date_diff($date2, $date1);
				if($res[$i]['pass_number']==""){

				}
				elseif( $res[$i]['status']=="АННУЛИРОВАН"){
					$color="#fab6b6";//Красный
				}
				elseif($res[$i]['pass_type']=="Годовой"){
					$color="#c0fcb8";//Зеленый
				}
				else{
					$color="#faf796";//Желтый
				}
			}
			?>

		<tr style="background-color:<?php echo $color;?>;" class="data-row" data-row-id="<?php echo $res[$i]['id'];?>">
			<td><?php echo $i+1;?></td>
			<td class="action-column"><input type="checkbox"></td>
			<td class="editable"  data-name="name"><span><?php echo $res[$i]['name'];?></span></td>
			<td  class="editable" data-name="phone"><span><?php echo $res[$i]['phone'];?></span></td>
			<td><?php echo $res[$i]['number_vehicle'];?></td>
			<td><?php echo $res[$i]['pass_number'];?></td>
			<td><?php echo $res[$i]['pass_type'];?></td>
			<td><?php echo $res[$i]['restriction_zone'];?></td>
			<td><?php echo $res[$i]['status'];?></td>
			<td><?php echo $res[$i]['start_date'];?></td>
			<td><?php echo $res[$i]['end_date'];?></td>
			<td class="editable" data-name="email"><span><?php echo $res[$i]['email'];?></span></td>
			<td class="editable" data-name="comment"><span><?php echo $res[$i]['comment'];?></span></td>
			<td><input class="sending" type="checkbox" <?php echo $res[$i]['delivery']=='1'?"checked":"";?>></td>
			</tr>
			
	<?php } ?>
	</tbody>
	</table>
	<div class="our-clients-actions">
		<button type="button"  data-toggle="modal" data-target="#new_client" class="btn btn-primary">Добавить клиента</button>
		<button type="button" class="delete btn btn-primary">Удалить</button>
		<!-- <button type="button" class="move-to-other-clients btn btn-primary">Переместить в "Временный контроль"</button> -->
		<!-- <button type="button" class="move-to-reestr btn btn-primary">Переместить в "Реестр заявок"</button> -->
		<button type="button" class="update btn btn-primary">Обновить данные</button>
		<!-- <button type="button"  class="get-sql-dump btn btn-primary">Скачать Excel дамп</button> -->

	</div>
	<div class="modal fade" tabindex="-1" role="dialog"  id="new_client" aria-labelledby="Ne client">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title">Новый клиент</h4>
	      </div>
	      <div class="modal-body">
	        <form id="new_user_form">
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




									
										
									
										
								
								