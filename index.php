<?php
	require_once($_SERVER["DOCUMENT_ROOT"]."/settings.php");
	
	$conn = new mysqli($servername, $username, $password);

	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
	$conn->select_db("metadata");
	
	$searchmode = false;
	if(isset($_POST["searchquery"]) && $_POST["searchquery"])
	{
		$searchmode = true;
	}
	if(!empty($_COOKIE['linesperpage']))
	{
		$linesperpage = $_COOKIE['linesperpage'];
	}
	else
	{
		$linesperpage = 25;
	}
	if(!empty($_GET['linesperpage']))
	{
		switch($_GET['linesperpage'])
		{
			case 25: 
				$linesperpage = 25;
				setcookie('linesperpage', 25);
				break;
			case 50: 
				$linesperpage = 50;
				setcookie('linesperpage', 50);
				break;
			case 100: 
				$linesperpage = 100;
				setcookie('linesperpage', 100);
				break;
			case 200: 
				$linesperpage = 200;
				setcookie('linesperpage', 200);
				break;
			case 400: 
				$linesperpage = 400;
				setcookie('linesperpage', 400);
				break;
			default: 
				$linesperpage = 25;
				setcookie('linesperpage', 25);
				break;
		}
	}
	
	$rowscount = $conn->query("select count(*) as rowscount from data")->fetch_assoc()['rowscount'];
	$pagescount = floor($rowscount/$linesperpage);
	
	$curpage = 0;
	if(!empty($_GET['curpage']) && $_GET['curpage'] <= $pagescount)
	{
		$curpage = $_GET['curpage'];
	}
	$offset = $linesperpage * $curpage;
?>

    
	<?php require_once($_SERVER["DOCUMENT_ROOT"]."/header.php");?>
	<?php require_once($_SERVER["DOCUMENT_ROOT"]."/filter.php");?>

<div class="container-fluid">
  <div class="row">
    <main role="main" class="col-md-12 ml-sm-auto col-lg-12 px-4">
      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3">
        <h1 class="h2"></h1>
        <div class="btn-toolbar mb-2 mb-md-0">
          <div class="btn-group mr-2">
            <a type="button" href="/?linesperpage=25" class="btn btn-sm btn-outline-secondary">25</a>
            <a type="button" href="/?linesperpage=50" class="btn btn-sm btn-outline-secondary">50</a>
            <a type="button" href="/?linesperpage=100" class="btn btn-sm btn-outline-secondary">100</a>
            <a type="button" href="/?linesperpage=200" class="btn btn-sm btn-outline-secondary">200</a>
            <a type="button" href="/?linesperpage=400" class="btn btn-sm btn-outline-secondary">400</a>
          </div>
        </div>
      </div>

      <div class="table-responsive">
        <table class="table table-striped table-sm">
          <thead>
            <tr>
              <th>timestamp</th>
              <th>qid</th>
              <th>from</th>
              <th>rcpt</th>
              <th>ip</th>
              <th>score</th>
              <th>action</th>
              <th>download</th>
            </tr>
          </thead>
          <tbody>
			<?php
				if($searchmode)
				{
					$rows = $conn->query($_POST["searchquery"]);
				}
				else
				{
					$rows = $conn->query("select id, timestamp, qid, sender, rcpt, ip, score, action from data order by timestamp desc limit ".$linesperpage." offset " . $offset);
				}
				if($rows)
				{
					while($row = $rows->fetch_assoc())
					{
						echo "<tr>";
						echo "<td>" . $row['timestamp'] . "</td>";
						echo "<td>" . $row['qid'] . "</td>";
						echo "<td>" . $row['sender'] . "</td>";
						echo "<td>" . $row['rcpt'] . "</td>";
						echo "<td>" . $row['ip'] . "</td>";
						echo "<td>" . $row['score'] . "</td>";
						echo "<td>" . $row['action'] . "</td>";
						?>
							<td>
								<form method="post" action="downloademl.php">
									<input type='hidden' name='emlid' value = '<?php echo $row['id']?>'>
									<button type="submit" class="btn ">
										<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-download"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
									</button>
								</form>
							</td>
						<?php
						echo "</tr>";
					}
				}
			?>
          </tbody>
        </table>
      </div>
		<?php if($pagescount && !$searchmode) {?>
		<nav aria-label="Page navigation example">
		  <ul class="pagination">
			<li class="page-item <?php if($curpage == 0) echo "disabled";?>">
			  <a class="page-link" href="/?curpage=<?php echo $curpage-1;?>" aria-label="Previous">
				<span aria-hidden="true">&laquo;</span>
				<span class="sr-only">Previous</span>
			  </a>
			</li>
			<?php 
				$navbuttq = 15;
				for($i = 0; $i<=$pagescount/$navbuttq; $i++)
				{
					if($i*$navbuttq <= $curpage && $curpage < ($i+1)*$navbuttq)
					{
						for($j = $i*$navbuttq; $j < ($i+1)*$navbuttq; $j++)
						{
							if($j <= $pagescount)
							{
								if($j == $curpage)
									echo '<li class="page-item active"><a class="page-link" href="/?curpage='.$j.'">'.$j.'</a></li>';
								else
									echo '<li class="page-item"><a class="page-link" href="/?curpage='.$j.'">'.$j.'</a></li>';
							}
						}
					}
				}
			?>
			<li class="page-item <?php if($curpage == $pagescount) echo "disabled";?>">
			  <a class="page-link" href="/?curpage=<?php echo $curpage+1;?>" aria-label="Next">
				<span aria-hidden="true">&raquo;</span>
				<span class="sr-only">Next</span>
			  </a>
			</li>
		  </ul>
		</nav>
		<?php } ?>
		<p>Total pages: <?php echo $pagescount;?>. Total items: <?php echo $rowscount;?></p>
    </main>
  </div>
</div>
<?php require_once($_SERVER["DOCUMENT_ROOT"]."/footer.php");?>
