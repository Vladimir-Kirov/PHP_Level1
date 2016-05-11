<!doctype html> 
<html>
	<head>
		<meta charset="UTF-8" />
		<title> На колко години съм? </title>
		<style type="text/css">
			.error {
				color: red;
			}
			.success {
				color: green;
			}
		</style>
	</head>
	<body>
		<form method="GET" action="">
			<input type="text" name="year" />
			<input type="hidden" name="action" value="calculate" />
			<input type="submit" value="Calculate" />
		</form>	
		<?php

		if (isset($_GET['action']) && $_GET['action'] == 'calculate')
		{
			$year = $_GET['year'];

		
			if (is_numeric($year))
			{
				if (is_negative($year))
				{
					show_message('This is negative number! It must be positive!', 0);
				}
				else
				{
					$currentYear = date('Y');
					$age = $currentYear - (1900 + $year);

					show_message('You are ' . $age . ' years old', 2);
				}
			}
			else
			{
				show_message('This is not a number!', 0);
			}
		}
		
		function is_negative($year) 
		{

			if ($year < 0) 
			{
				return true;
			}
			else 
			{
				return false;
			}
		
		}

		function show_message($message, $type) 
		{
			if($type == 0)
			{
				echo '<span class="error">' . $message . '</span>';
			}
			else
			{
				echo '<span class="success">' . $message . '</span>';
			}
		}
		
		?>
	</body>
</html> 