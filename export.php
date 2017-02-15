<?php
	$error = true;
	$data = [];
	if(is_array($_FILES) && count($_FILES) > 0){
		if(isset($_FILES['files']) && is_array($_FILES['files'])){
			if(isset($_FILES['files']['tmp_name']) && is_array($_FILES['files']['tmp_name']) && count($_FILES['files']['tmp_name']) > 0){
				foreach($_FILES['files']['tmp_name'] as $tmpFiles){
					$d = [];
					$handler = fopen('/'.$tmpFiles, 'r');
					$i = 0;
					$col = 0;
					while($line = fgets($handler)){
						if($i == 1){
							$tmp = explode('Alter',$line);
							$d[$col++] = str_replace('Patient: ','',trim($tmp[0]));
						}
						if($i >= 5 && $i <= 22){
							$line = trim($line);
							$line = str_replace('      ', ' ', $line);
							$line = str_replace('     ', ' ', $line);
							$line = str_replace('    ', ' ', $line);
							$line = str_replace('   ', ' ', $line);
							$line = str_replace('  ', ' ', $line);
							$line = str_replace(' ', ';',$line);
							$tmp = explode(';', $line);
							$d[$col] = trim($tmp[1]);
							$d[$col+18] = trim($tmp[6]);
							$col++;
						}
						if($i == 33){
							$d[$col+18] = trim(str_replace('Kontostand:','',$line));
						}
						$i++;
					}
					ksort($d);
					$data[] = implode(';',$d);
				}
				$error = false;
			}
		}
	}
	$data = implode(PHP_EOL, $data);
	if($error){
		header('Location:index.php?m');
		die();
	}
	header('Content-Type: application/csv');
	header('Content-Disposition: attachment; filename="export_tdah_'.date('Y-m-d-h-i-s').'.csv";');
	echo $data;
