<?
    /*
        简易php gokzreplay parser
        auth bklol
    */
    class Replay 
    {
        private $replayfile;
        private $readpoint;
        
        public function __construct($path, $readpoint)
        {
            if(!file_exists($path))
                throw new Exception("$path dont exists");
                
            $this -> replayfile = file_get_contents($path);
            $this -> readpoint = $readpoint;
        }
        
        public function ReadInt32() 
        {
            $int32 = substr( $this -> replayfile, $this -> readpoint , 4);
            $this -> readpoint  += 4;
            return unpack("V", $int32)[1];
        }
        
        public function ReadInt8() 
        {
            $int8 = substr($this -> replayfile, $this -> readpoint , 1);
            $this -> readpoint  += 1;
            return unpack("c", $int8)[1];
        }
        
        public function ReadFloat32() 
        {
            $Float32 = substr($this -> replayfile, $this -> readpoint , 4);
            $this -> readpoint  += 4;
            return unpack("f", $Float32)[1];
        }
        
        public function ReadString($length) 
        {
            $buffer = substr($this -> replayfile, $this -> readpoint, $length);
            $this -> readpoint  += $length;
            return $buffer;
        }
        
        public function ReadTickData($tickCount)
        {
            
            $count = 0;
            for($i = 0; $i < $tickCount; $i++)
            { 
                $RPDELTA_DELTAFLAGS = $this -> ReadInt32();
                for($index = 1; $index <= 19; $index ++)
                {
                    $currentFlag = (1 << $index);
                    if ($RPDELTA_DELTAFLAGS & $currentFlag)
        			{
                        if(($index >= 2 && $index <= 4) || ($index >= 7 && $index <= 15) || $index == 17 || $index == 18)
                            $snap[$index] = $this -> ReadFloat32();
                        else
                            $snap[$index] = $this -> ReadInt32();
        			}
                }
                
                if($snap['7'] == 0 || $snap['8'] == 0 || $snap['9'] == 0 || $snap['10'] == 0|| $snap['11'] == 0)
                    continue;
                else
                {
                    $data[$count] = $snap;
                    $count++;
                }
            }
            return $data;
        }
        
        public function skip($length)
        {
            $this -> readpoint  += $length;
        }
    }

    /*demo 演示
    json array 数组
	result.deltaFlags          = array[0];
	result.deltaFlags2         = array[1];
	result.vel[0]              = array[2];
	result.vel[1]              = array[3];
	result.vel[2]              = array[4];
	result.mouse[0]            = array[5];
	result.mouse[1]            = array[6];
	result.origin[0]           = array[7];
	result.origin[1]           = array[8];
	result.origin[2]           = array[9];
	result.angles[0]           = array[10];
	result.angles[1]           = array[11];
	result.angles[2]           = array[12];
	result.velocity[0]         = array[13];
	result.velocity[1]         = array[14];
	result.velocity[2]         = array[15];
	result.flags               = array[16];
	result.packetsPerSecond    = array[17];
	result.laggedMovementValue = array[18];
	result.buttonsForced       = array[19];
    */
    
    $file = new Replay('1.replay', 0); //注意只支持 formatVersion 为 2 的 replaydemo，notice！only support replay file which formatVersion == 2
    $magicNumber = $file -> ReadInt32();
    $posdata['formatVersion'] = $file -> ReadInt8();
    $posdata['replayType'] = $file -> ReadInt8();
    $posdata['gokzVersion'] = $file -> ReadString($file -> ReadInt8());
    $posdata['mapName'] = $file -> ReadString($file -> ReadInt8());
    $posdata['mapFileSize'] = $file -> ReadInt32();
    $posdata['serverIP'] = $file -> ReadInt32();
    $posdata['timestamp'] = $file -> ReadInt32();
    $posdata['botAlias'] = $file -> ReadString($file -> ReadInt8());
    $posdata['steamID'] = $file -> ReadInt32();
    $posdata['botMode'] = $file -> ReadInt8();
    $posdata['botStyle'] = $file -> ReadInt8();
    $posdata['intPlayerSensitivity'] = $file -> ReadFloat32();
    $posdata['intPlayerMYaw'] = $file -> ReadFloat32();
    $posdata['tickrateAsInt'] = $file -> ReadFloat32();
    $posdata['tickCount'] = $file -> ReadInt32();
    $posdata['botWeapon'] = $file -> ReadInt32();
    $posdata['botKnife'] = $file -> ReadInt32();
    $posdata['timeAsInt'] = $file -> ReadInt32();
    $posdata['botCourse'] = $file -> ReadInt8();
    $posdata['botTeleportsUsed'] = $file -> ReadInt32();
    $posdata['TickData'] = $file ->ReadTickData($posdata['tickCount']);
    echo json_encode($posdata);
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    