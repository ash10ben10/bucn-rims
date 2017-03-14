
	<div class="panel panel-default" style="box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19); font-family: Segoe UI;">
		<div class="panel-heading" style="font-family: Segoe UI Light;" align="center">
			<i class="zmdi zmdi-calendar-note zmdi-hc-lg"></i>&nbsp;&nbsp;<strong>Post Notes</strong>
		</div>
		<div class="panel-body">
			<div class="panel-body" style=" height: 235px; margin-right:-15px; margin-left:-15px; margin-top:-10px;">
				<div class="panel panel-success">
					<div class="panel-body" style="max-height: 212px; overflow-y: scroll; word-wrap: break-word; text-align: left; font-family: Segoe UI;">
							<?php 
								include ("connect.php");
								
								$load = mysql_query("SELECT * FROM notes ORDER BY time DESC");
								
								if(mysql_num_rows($load) == 0){
									print "<p align=center><i>Note anything you want to share with the users on this system.</i></p>";
								}else{
									while($display=mysql_fetch_array($load)){
											print "<table width=115% border=0 style='margin: 0 0 0 -10px;'>";
											print "<tbody>";
											print "<tr>";
											//display the lists
											print "<td width=80% style='max-width:0px;' align='center'>$display[data]<br /><i style='font-size:74%;'>";
											print date('D, M j, Y, h:i a', strtotime($display['time']));
											print "</i></td>";
											//echo "<td width=80% style='padding: 5px 0 20px 0; max-width:0px;' align='center'>$display[data]<br /><i style='font-size:74%;'>($display[time])</i></td>";
											//this column deletes the lists
											//echo "<td align='right' width=10% style='padding: 5px 0 5px 2px;'><form method='POST'><input type='hidden' name='delete' value='$display[note_id]' /><button class='btn btn-default'><i class='glyphicon glyphicon-trash'></i></button></form></td>";
											print "</tr>";
											print "<tr>";
											print "<td align='center' width=10% style='padding: 3px 0 10px 2px;'><form method='POST'><input type='hidden' name='delete' value='$display[note_id]' /><button class='btn btn-danger'><i class='zmdi zmdi-delete zmdi-hc-lg'></i></button></form></td>";
											print "</tr>";
											print "</tbody>";
											print "</table>";
								}
								}
								
								/* while($display=mysql_fetch_array($load)){
											echo "<table width=115% border=0 style='margin: 0 0 0 -10px;'>";
											echo "<tbody>";
											echo "<tr>";
											//display the lists
											echo "<td width=80% style='max-width:0px;' align='center'>$display[data]<br /><i style='font-size:74%;'>($display[time])</i></td>";
											//echo "<td width=80% style='padding: 5px 0 20px 0; max-width:0px;' align='center'>$display[data]<br /><i style='font-size:74%;'>($display[time])</i></td>";
											//this column deletes the lists
											//echo "<td align='right' width=10% style='padding: 5px 0 5px 2px;'><form method='POST'><input type='hidden' name='delete' value='$display[note_id]' /><button class='btn btn-default'><i class='glyphicon glyphicon-trash'></i></button></form></td>";
											echo "</tr>";
											echo "<tr>";
											echo "<td align='center' width=10% style='padding: 3px 0 10px 2px;'><form method='POST'><input type='hidden' name='delete' value='$display[note_id]' /><button class='btn btn-default'><i class='glyphicon glyphicon-trash'></i></button></form></td>";
											echo "</tr>";
											echo "</tbody>";
											echo "</table>";
								} */
								//echo "<p align=center><i>There are no available notes at this time.</i></p>";
							?>
					</div>
				</div>
			</div>
		</div>
		<div class="panel-footer">
			<div class="row" style="margin-top:10px; margin-bottom:10px;">
				<form method="POST" id="add-note">
					<div class="col-lg-12" align="center">
							<input type="text" class="form-control" name="content" placeholder="Write here . . ." max="500" style="resize: none; height:40px;" />
							<button name="submit" class="btn btn-success" style="margin-right: 5px;"><i class="fa fa-thumb-tack fa-fw"></i>&nbsp;</button>
							<button name="clear" class="btn btn-info" style="margin-left: 5px;"><i class="fa fa-eraser fa-fw"></i>&nbsp;</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	
	<?php
		date_default_timezone_set("Asia/Manila");
		$datetime = date("Y-m-d H:i:s");
	
		include ("connect.php");
			if(isset($_POST['submit'])){
				mysql_query("SET AUTOCOMMIT=0");
				mysql_query("START TRANSACTION");
				
				mysql_query("LOCK TABLE notes WRITE;");
				try{
					$add_note = mysql_query("INSERT INTO notes (`data`,`time`) VALUES ('".mysql_real_escape_string($_POST['content'])."', '".$datetime."')")or die(mysql_error());
					mysql_query("COMMIT");
					mysql_query("UNLOCK TABLE;");
				}catch(Exception $e){
					print "<script>alert('Something went wrong when taking your notes. Please check your connection.')</script>";
				}
				mysql_close();
				print "<script>window.location='index.php';</script>";
			}
			if(isset($_POST['clear'])){
				$delete = mysql_query("DELETE FROM notes");
				mysql_close();
				print "<script>window.location='index.php';</script>";
			}
			if(isset($_POST['delete'])){
				$process = mysql_query("DELETE FROM notes WHERE note_id ='".$_POST['delete']."'") or die(mysql_error());
				mysql_close();
				print "<script>window.location='index.php';</script>";
			}
	?>