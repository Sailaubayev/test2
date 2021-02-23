<?php
		$now=date("d-m-Y");
		header('P3P: CP="NOI ADM DEV PSAi COM NAV OUR OTRo STP IND DEM"');
		header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
		header('Cache-Control: no-store, no-cache, must-revalidate');
		header('Cache-Control: post-check=0, pre-check=0', FALSE);
		header('Pragma: no-cache');
		header('Content-transfer-encoding: binary');
		header('Content-Disposition: attachment; filename='.$now.'.xls');
		header ( "Content-type: application/vnd.ms-excel" );
?>
<table border>
	<tr>
		<th>№</th>
		<th>Клиент(ФИО)</th>
		<th>Телефон</th>
		<th>Номер автомобиля</th>
		<th>Номер пропуска</th>
		<th>Тип пропуска</th>
		<th>Зона (сейчас)</th>
		<th>Статус</th>
		<th>Дата выдачи</th>
		<th>Дата окончания</th>
		<th>ЭЦП</th>
		<th>Номер договора</th>
		<th>Кто делал</th>
		<th>Этап рассмотрения</th>
		<th>ДК</th>
		<th>Стоимость</th>
		<th>Способ рассчета</th>
		<th>Оплата</th>
		<th>Комментарий</th>
	</tr>
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
				if( $res[$i]['status']=="Активный" && $interval->days>2){
					$color="#c0fcb8";//Зеленый
				}
				elseif($res[$i]['status']=="Активный" && $interval->days<=2){
					$color="#faf796";//Желтый
				}
				else{
					$color="#fab6b6";//Красный
				}
			}
		?>

		<tr >
			<td><?php echo $i+1; ?></td>
			<td style="background-color:<?php echo $color;?>;"><?php echo $res[$i]['name'];?></td>
			<td style="background-color:<?php echo $color;?>;"><?php echo $res[$i]['phone'];?></td>
			<td style="background-color:<?php echo $color;?>;"><?php echo $res[$i]['number_vehicle'];?></td>
			<td style="background-color:<?php echo $color;?>;"><?php echo $res[$i]['pass_number'];?></td>
			<td style="background-color:<?php echo $color;?>;"><?php echo $res[$i]['pass_type'];?></td>
			<td style="background-color:<?php echo $color;?>;"><?php echo $res[$i]['restriction_zone'];?></td>
			<td style="background-color:<?php echo $color;?>;"><?php echo $res[$i]['status'];?></td>
			<td style="background-color:<?php echo $color;?>;"><?php echo $res[$i]['start_date'];?></td>
			<td style="background-color:<?php echo $color;?>;"><?php echo $res[$i]['end_date'];?></td>
			<td style="background-color:<?php echo $color;?>;"><?php echo $res[$i]['ECP'];?></td>
			<td style="background-color:<?php echo $color;?>;"><?php echo $res[$i]['doc_number'];?></td>
			<td style="background-color:<?php echo $color;?>;"><?php echo $res[$i]['created_by'];?></span></td>
			<td style="background-color:<?php echo $color;?>;"><?php echo $res[$i]['application_status'];?></td>
			<td style="background-color:<?php echo $color;?>;"><?php echo $res[$i]['DK'];?></td>
			<td style="background-color:<?php echo $color;?>;"><?php echo $res[$i]['price'];?></td>
			<td style="background-color:<?php echo $color;?>;"><?php echo $res[$i]['payment_type'];?></span></td>
			<td style="background-color:<?php echo $color;?>;"><?php echo $res[$i]['payment'];?></span></td>
			<td style="background-color:<?php echo $color;?>;"><?php echo $res[$i]['comment'];?></td>
		</tr>
			
	<?php } ?>
</table>

