# parse GOKZ replay file
>HOW TO USE 如何食用：  
`include("utils.php");`  
ReadFile:  
`$file = new Replay('your_file_path.replay', 'read start from' , true);`  
ReadRawFile:  
`$file = new Replay($your_raw_file, 'read start from' , false);`  
`$data = $file -> ReplayData;`  
