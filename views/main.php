<?php
    
    MainDatabase::connect();
	$mysqli = MainDatabase::get();
    $res = $mysqli->query("SELECT * FROM  `customers`");
    $customers = array();
    $customerauto = array();
    
    while ($row = $res->fetch_assoc()) {
        if (!isset($row['id']) || !isset($row['first_name']) || !isset($row['last_name'])) continue;
        
        $customers[$row['id']] = array(
            'label' => $row['id']. ' '. $row['first_name'] . ' ' . $row['last_name']
        );
        
        $customerauto[] = array(
            'id' => $row['id'],
            'label' => htmlspecialchars($row['id']. ' '.$row['first_name'] . ' ' . $row['last_name'] . ', ' . $row['company_name'])
        );
    }
    MainDatabase::disconnect();
    
    $inlineScript = '<script>var customers = '.json_encode($customerauto).';</script>';
    $alphabet = Config::get('ALPHABET');
?>

<div class="panel panel-default">
    <div class="panel-heading">
        Scan Receipts
        <div class="btn-group pull-right" style="margin-top: -2px;">
            <span class="label label-default">Computer <?= $alphabet[Config::get('COMPUTER_ID')] ?></button>
        </div>
    </div>
    <div class="panel-body">
        <form class="form-inline" method="post">
            <div class="form-group">
                <label class="sr-only" for="customerLookup">Customer</label>
                <div class="input-group">
                    <div class="input-group-addon"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></div>
                    <input type="text" class="form-control" id="customerLookup" placeholder="Customer ID, First Name, Last Name or Company">
                </div>
            </div>
            <input type="hidden" class="form-control" id="customerId" name="customerId">
            <div class="form-group">
                    <select class="form-control" id="typeSelect" name="typeId">
                      <?php foreach(Config::get('RECEIPT_TYPES') as $id => $name): ?>
                      <option value="<?= $id ?>"><?= $name ?></option>
                      <?php endforeach; ?>
                      <option disabled><?php for ($i=0;$i<12;$i++){echo("&boxh;");} ?></option>
                      <?php foreach(Config::get('DOC_TYPES') as $id => $name): ?>
                      <option value="<?= $id ?>"><?= $name ?></option>
                      <?php endforeach; ?>
                    </select>
            </div>
            <div class="form-group">
        		<select class="form-control" id="quarterSelect" name="quarterId" style="display: none;">
            	<?php
            		MainDatabase::connect();
            		$mysqli = MainDatabase::get();
            		$future = new DateTime();
					$future->add(new DateInterval('P2Y'));
            		$res = $mysqli->query("SELECT * FROM  `application_quarters` WHERE claim_period_finish < '" . $future->format("Y-m-d") . "' ORDER BY period_start ASC");
					$quarters = array();
					while ($row = $res->fetch_assoc()) {
					    if (!isset($row['id'])) continue; ?>
						<option value="<?= $row['id'] ?>"><?=$row['year']?> Quarter <?=$row['quarter']?></option>
				<?php 
						if ($row['quarter'] == 4) {
					?><option disabled><?php for ($i=0;$i<12;$i++){echo("&boxh;");} ?></option><?php
						}
					}
					MainDatabase::disconnect();
				?>
            	</select>
            </div>
            
            <div class="form-group">
            	<select class="form-control" id="sourceSelect" name="sourceId" style="display: none;">
            		<option value="1">Faxed</option>
            		<option value="2">Emailed</option>
            		<option value="3">Mailed</option>
            	</select>
            </div>
            
            <div class="btn-group" data-toggle="buttons">
                <label class="btn btn-default" title="Duplex" data-toggle="tooltip">
                    <input type="checkbox" autocomplete="off" name="duplex"><span class="glyphicon glyphicon-retweet"></span>
                </label>
            </div>
            
            <button type="submit" class="btn btn-primary pull-right" id="uploadButton" disabled>Upload <span class="glyphicon glyphicon-cloud-upload" aria-hidden="true"></span></button>
        </form>
    </div>
</div>
