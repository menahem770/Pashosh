
		<table CLASS="leftmenu">
			<?php if (isset($_SESSION['UWorkerNum'])): ?>
					<tr>
						<td style="background-image: url('images/index_09.jpg'); height:42px; cursor:auto; color:#AE1515;" class="side">
						  <div><?=$_SESSION["UWorkerName"]?></div>
						</td>
					</tr>
			<?php endif; ?>

			<tr>
				<td style="background-image: url('images/index_09.jpg'); height:42px;" class="side">
				  <div><a href="login.php" class="side">כניסה למערכת</a></div>
				</td>
			</tr>

			<?php if ((isset($_SESSION['mng'])) and ($_SESSION['mng'] == 1 or $_SESSION['mng'] == 2)): ?>
					<tr>
						<td style="background-image: url('images/index_09.jpg'); height:42px;" class="side">
						  <div><a href="Workers.php" class="side">עובדים</a></div>
						</td>
					</tr>
			<?php endif; ?>
			
			
			<?php if(isset($_SESSION['WorkerNum'])): ?>
					<tr>
						<td style="background-image: url('images/index_09.jpg'); height:42px;" class="side">
							<div><a href="Weeks.php?WorkerNum=<?=$_SESSION['WorkerNum']?>&WFixID=<?=$_SESSION['WFixID']?>&WorkerName=<?=$_SESSION['WorkerName']?>&DepMail=<?=$_SESSION['DepMail']?>&IncldInMadan=<?=$_SESSION['IncldInMadan']?>&IncldInTmhir=<?=$_SESSION['IncldInTmhir']?>&defDep=<?=$_SESSION['DefaultDep']?>&defJob=<?=$_SESSION['DefaultJob']?>&CardNumber=<?=$_SESSION['CardNumber']?>" class="side">ריכוז למספר שבועות </a></div>
						</td>
					</tr>
			<?php endif; ?>
			

			<?php if (isset($_SESSION['mng'])): ?>
					<tr>
						<td style="background-image: url('images/index_09.jpg'); height:42px;" class="side">
						  <div class="side" ONCLICK='javascript:openUpdW("misrep-w.php",0,0,"","")'>דיווח היעדרויות</div>
						</td>
					</tr>
			<?php endif; ?>
			

			<?php if (isset($_SESSION['mng']) and $_SESSION['mng'] == 2): ?>
					<tr>
						<td style="background-image: url('images/index_09.jpg'); height:42px;" class="side">
						  <div><a href="IniFile.php" class="side">ניהול פרמטרים</a></div>
						</td>
					</tr>
			<?php endif; ?>		
			

			<?php if (isset($_SESSION['UWorkerNum'])): ?>
					<tr>
						<td style="background-image: url('images/index_09.jpg'); height:42px;" class="side">
						  <div><a href="AskRepot.php" class="side">הזמנת דו''ח</a></div>
						</td>
					</tr>
			<?php endif; ?>	
			

			<tr>
				<td style="background-image: url('images/index_09.jpg'); height:42px;" class="side">
				  <div><a href="logout.php" class="side">יציאה מהמערכת</a></div>
				</td>
			</tr>
			
			
			<tr>
				<td style="background-image: url('images/index_10.jpg'); height:156px; cursor: auto;" class="side"></td>
			</tr>
		</table>

