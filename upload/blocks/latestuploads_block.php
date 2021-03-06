<?php
if (!$site_config["MEMBERSONLY"] || $CURUSER) {
	begin_block(T_("LATEST_TORRENTS"));

	$expire = 900; // time in seconds

	if (($latestuploadsrecords = $TTCache->Get("latestuploadsblock", $expire)) === false) {
		$latestuploadsquery = DB::run("SELECT id, name, size, seeders, leechers FROM torrents WHERE banned='no' AND visible = 'yes' ORDER BY id DESC LIMIT 5");

		$latestuploadsrecords = array();
		while ($latestuploadsrecord = $latestuploadsquery->fetch(PDO::FETCH_ASSOC))
			$latestuploadsrecords[] = $latestuploadsrecord;
		$TTCache->Set("latestuploadsblock", $latestuploadsrecords, $expire);
	}

	if ($latestuploadsrecords) {
		foreach ($latestuploadsrecords as $row) { 
			$char1 = 18; //cut length 
			$smallname = htmlspecialchars(CutName($row["name"], $char1));
			echo "<a href='torrentsdetails?id=$row[id]' title='".htmlspecialchars($row["name"])."'>$smallname</a><br />\n";
			echo "- [".T_("SIZE").": ".mksize($row["size"])."]<br /><br />\n";
		}
	} else {
		print("<center>".T_("NOTHING_FOUND")."</center>\n");
	}
	end_block();
}
?>