<div role="tabpanel" class="tab-pane" id="other_clients">
	<table class="table table-bordered table-hover">
									<tr>
										<th>№</th>
										<th></th>
										<th>Клиент(ФИО)</th>
										<th>Телефон</th>
										<th>Номер автомобиля</th>
										<th>Номер пропуска</th>
										<th>Тип пропуска</th>
										<th>Зона (сейчас)</th>
										<th>Статус</th>
										<th>Дата выдачи</th>
										<th>Дата окончания</th>
										<th>СМС</th>
										<th>Комментарий</th>
									</tr>
	<?php
		require_once(dirname(__FILE__)."/../conf/config.php");
		$query="SELECT * FROM `other_clients`";
		$result=mysql_query($query);
		$res=array();
		while ($row=mysql_fetch_assoc($result)) {
				$res[]=$row;
		}
		for ($i=0; $i<count($res); $i++){ ?>

		<tr class="data-row" data-row-id="<?php echo $res[$i]['id'];?>">
			<td><?php echo $i+1;?></td>
			<td class="action-column"><input type="checkbox"></td>
			<td><?php echo $res[$i]['name'];?></td>
			<td><?php echo $res[$i]['phone'];?></td>
			<td><?php echo $res[$i]['number_vehicle'];?></td>
			<td><?php echo $res[$i]['pass_number'];?>	</td>
			<td><?php echo $res[$i]['restriction_zone'];?></td>
			<td><?php echo $res[$i]['pass_type'];?></td>
			<td><?php echo $res[$i]['status'];?></td>
			<td><?php echo $res[$i]['start_date'];?></td>
			<td><?php echo $res[$i]['end_date'];?></td>
			<td><input class="sending" type="checkbox" <?php echo $res[$i]['delivery']=='1'?"checked":"";?> ></td>
			<td class="editable" data-name="comment"><span><?php echo $res[$i]['comment'];?></span></td>
			</tr>			
	<?php } ?>


	</table>
	<div class="other-clients-actions">
		<button type="button" class="move-to-our-clients btn btn-primary">Переместить в "Наши клиенты"</button>
		<button type="button" class="delete btn btn-primary">Удалить</button>
	</div>		
</div>										
								
								