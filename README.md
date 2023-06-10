# parse GOKZ replay file
>HOW TO USE 如何食用：  

`include("utils.php");`  
ReadFile:  
`$file = new Replay('your_file_path.replay', 'read start from, def = 0', true);`  
ReadRawFile:  
`$file = new Replay($your_raw_file, 'read start from, def = 0', false);`  
`$data = $file -> ReplayData;`  

>DEMO  

`include("utils.php");`  
`$file = file_get_contents("https://kztimerglobal.com/api/v2/records/replay/759530");`//read from http  
`$file = new Replay("https://kztimerglobal.com/api/v2/records/replay/759530", 0); `  
`echo json_encode($file -> ReplayData);`  

`include("utils.php");`  
`$file = new Replay("1.replay", 0);`//read from local file  
`echo json_encode($file -> ReplayData);`  

`include("utils.php");`  
`$file = file_get_contents("1.replay");`//read from raw file, sometimes you may need read it first    
`$file = new Replay($file, 0, false); `  
`echo json_encode($file -> ReplayData); `  
