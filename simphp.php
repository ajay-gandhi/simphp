<?php

/*----------------------------

-------- ++ simPHP ++ --------
A simple PHP hit counter

Version: 2.0.1
Description:
   simPHP counts both regular and unique views on multiple
   webpages. The stats can be displayed on any PHP-enabled
   webpage. You MUST have read/write permissions on files

Script by Ajay: me@ajay.ga

----------------------------*/



/*----------CONFIG----------*/

// NOTE: If you change any config after using simphp,
// remove the old files

// Relative URL of text file that holds hit info:
$lf_name = "hits.txt";

// Save new log file each month
//   0 = No
//   1 = Yes
$monthly = 1;

// Path to store old files:
// Default for June, 2012:
//   oldfiles/6-12.txt
$monthly_path = "oldfiles";

// Count unique hits or total hits:
//   0 = Total hits
//   1 = Unique hits
//   2 = Both unique and total
$type = 2;

// Text to display
// before total hits
$beforeTotalText = "Hits: ";

// Before unique hits
$beforeUniqueText = "Unique Visits: ";

// Display hits on this page:
//   0 = No
//   1 = Yes
$display = 1;

// Only change this if you are recording both values
// Separator for unique and total hits display - use HTML tags! (line break is default)
$separator = "<br \>";

// Default would output:
//   Visits: 10
//   Unique Visits: 10
/*--------------------------*/






/*--------BEGIN CODE--------*/

$log_file = dirname(__FILE__) . '/' . $lf_name;

// Check for "?display=true" in URL
if ($_GET['display'] == "true") {
	// Show include() info
	die("<pre>&#60;? include(\"" . dirname(__FILE__) . '/' . basename(__FILE__) . "\"); ?&#62;</pre>");

} else {
	// Get visitor IP
	$uIP = $_SERVER['REMOTE_ADDR'];

	// Check for "hits.txt" file
	if (file_exists($log_file)) {
		// Get contents of log file
		$log = file_get_contents($log_file);

		if ($monthly) {

			// Check if today is first day of month
			if (date('j') == 1) {
				// If it is first day of month,
				// move previous log file to subdir and create new file

				// Ensure that monthly dir exists
				if (!file_exists($monthly_path)) {
					mkdir($monthly_path);
				}

				// Check if prev month log file exists already
				$prev_name = $monthly_path . '/' . date("n-Y", strtotime("-1 month")) . '.txt';
				if (!file_exists($prev_name)) {
					// If not, copy current log file into subdir
					copy($log_file, $prev_name);

					// Write new data based on config
					if ($type == 0) {
						// Total hits
						$toWrite = "1";
						$info = $beforeTotalText . "1";
					} else if ($type == 1) {
						// Unique hits
						$toWrite = "1;" . $uIP . ",";
						$info = $beforeUniqueText . "1";
					} else if ($type == 2) {
						// Unique and total
						$toWrite = "1;1;" . $uIP . ",";
						$info = $beforeTotalText . "1" . $separator . $beforeUniqueText . "1";
					}
					write_logfile($toWrite, $info);
				}

			} else {
				// Still same month as before, so just increment counters

				// What to do depends on type from config
				if ($type == 0) {
					// Total hits
					// Create info to write to log file and info to show
					$toWrite = intval($log) + 1;
					$info = $beforeTotalText . $toWrite;

				} else if ($type == 1) {

					// Separate log file into hits and IPs
					$hits = reset(explode(";", $log));
					$IPs = end(explode(";", $log));
					$IPArray = explode(",", $IPs);

					// Check for visitor IP in list of IPs
					if (array_search($uIP, $IPArray, true) === false) {

						// IP doesnt' exist so increase hits and include IP
						$hits = intval($hits) + 1;
						$toWrite = $hits . ";" . $IPs . $uIP . ",";
					} else {

						// If IP exists don't change anything
						$toWrite = $log;
					}

					// Info to show
					$info = $beforeUniqueText . $hits;

				} else if ($type == 2) {
					// Both total hits and unique hits

					// Separate log file into regular hits, unique hits, and IPs
					$pieces = explode(";", $log);
					$totalHits = $pieces[0];
					$uniqueHits = $pieces[1];
					$IPs = $pieces[2];
					$IPArray = explode(",", $IPs);

					// Always increase regular hits, regardless of IP
					$totalHits = intval($totalHits) + 1;

					// Search for visitor IP in list of IPs
					if (array_search($uIP, $IPArray, true) === false) {

						// IP doesn't exist so increase unique hits and append IP
						$uniqueHits = intval($uniqueHits) + 1;
						$toWrite = $totalHits . ";" . $uniqueHits . ";" . $IPs . $uIP . ",";
					} else {

						// If IP already exists just keep unique hits unchanged
						$toWrite = $totalHits . ";" . $uniqueHits . ";" . $IPs;
					}
					// Info to show
					$info = $beforeTotalText . $totalHits . $separator . $beforeUniqueText . $uniqueHits;
				}
				write_logfile($toWrite, $info);
			}
		} else {
			// What to do depends on type from config
			if ($type == 0) {
				// Total hits
				// Create info to write to log file and info to show
				$toWrite = intval($log) + 1;
				$info = $beforeTotalText . $toWrite;

			} else if ($type == 1) {

				// Separate log file into hits and IPs
				$hits = reset(explode(";", $log));
				$IPs = end(explode(";", $log));
				$IPArray = explode(",", $IPs);

				// Check for visitor IP in list of IPs
				if (array_search($uIP, $IPArray, true) === false) {

					// IP doesnt' exist so increase hits and include IP
					$hits = intval($hits) + 1;
					$toWrite = $hits . ";" . $IPs . $uIP . ",";
				} else {

					// If IP exists don't change anything
					$toWrite = $log;
				}

				// Info to show
				$info = $beforeUniqueText . $hits;

			} else if ($type == 2) {
				// Both total hits and unique hits

				// Separate log file into regular hits, unique hits, and IPs
				$pieces = explode(";", $log);
				$totalHits = $pieces[0];
				$uniqueHits = $pieces[1];
				$IPs = $pieces[2];
				$IPArray = explode(",", $IPs);

				// Always increase regular hits, regardless of IP
				$totalHits = intval($totalHits) + 1;

				// Search for visitor IP in list of IPs
				if (array_search($uIP, $IPArray, true) === false) {

					// IP doesn't exist so increase unique hits and append IP
					$uniqueHits = intval($uniqueHits) + 1;
					$toWrite = $totalHits . ";" . $uniqueHits . ";" . $IPs . $uIP . ",";
				} else {

					// If IP already exists just keep unique hits unchanged
					$toWrite = $totalHits . ";" . $uniqueHits . ";" . $IPs;
				}
				// Info to show
				$info = $beforeTotalText . $totalHits . $separator . $beforeUniqueText . $uniqueHits;
			}
			write_logfile($toWrite, $info);
		}
	} else {
		// If "hits.txt" doesn't exist, create it
		$fp = fopen($log_file, "w");
		fclose($fp);

		// Write file according to config above
		if ($type == 0) {
			$toWrite = "1";
			$info = $beforeTotxalText . "1";
		} else if ($type == 1) {
			$toWrite = "1;" . $uIP . ",";
			$info = $beforeUniqueText . "1";
		} else if ($type == 2) {
			$toWrite = "1;1;" . $uIP . ",";
			$info = $beforeTotalText . "1" . $separator . $beforeUniqueText . "1";
		}
		write_logfile($toWrite, $info);
	}
}

/**
 * Writes given data to the logfile and echoes data if the option
 * 	 says so in config
 * Requires: A string of data to write to the file and a string
 *   of data to print
 */
function write_logfile($data, $output) {
	global $log_file;

	// Put $toWrite in log file
	file_put_contents($log_file, $data);

	// Display info if is set in config
	if ($display == 1) {
		echo $output;
	}
}
?>
