<div role='tabpanel' class='tab-pane active in' id='our_applications'>

<?php
	require_once(dirname(__FILE__)."/../conf/config.php");
	$query="SELECT * FROM `our_applications`";
	$result=mysql_query($query);
	$res=array();
	while ($row=mysql_fetch_assoc($result)) {
		$res[]=$row;
	}
	if(count($res)==0){
		echo "<h1>Записей не найдено.</h1>";
		echo "</div>";
		return;
	} 
?>
	<table class='table table-bordered table-hover'>
		<tr>
			<th>№</th>
			<th></th>
			<th>ФИО</th>
			<th>Телефон</th>
			<th>Номер ТС</th>
		</tr>

	<?php
		for ($i=0; $i<count($res); $i++){ ?>

		<tr class="data-row" data-row-id="<?php echo $res[$i]['id'];?>">
			<td><?php echo $i+1;?></td>
			<td class="action-column"><input type="checkbox"></td>
			<td><?php echo $res[$i]['name'];?></td>
			<td><?php echo $res[$i]['phone'];?></td>
			<td><?php echo $res[$i]['number_vehicle'];?></td>
		</tr>	

	<?php } ?>


	</table>
	<div class='new-apps-actions'>
		<button type='button' class='delete btn btn-primary'>Удалить</button>
		<button type='button' class='move-to-our-clients btn btn-primary'>Переместить в 'Наши клиенты'</button>
		<button type='button' class='move-to-other-clients btn btn-primary'>Переместить в 'Временный контроль'</button>
	</div>
</div>