<?
    /*
        简易php gokzreplay parser
        auth bklol
    */
	class Replay 
	{
		
		public $ReplayData;        
		private $replayfile;
		private $readpoint;

		public function __construct($file, $readpoint = 0, $usefilepath = false)
		{
			$this -> replayfile = $usefilepath ? file_get_contents($file) : $file;
			if($this -> replayfile == null)
				throw new Exception("file $file dont exists");
			$this -> readpoint = $readpoint;
			$this -> ReplayData['magicNumber'] = $this -> ReadInt32();
			if($this -> ReplayData['magicNumber'] != 0x676F6B7A)
				throw new Exception("Failed to load invalid replay file $file");
			$this -> ReplayData['formatVersion'] = $this -> ReadInt8();
			switch($this -> ReplayData['formatVersion'])
			{
				case 1: $this -> LoadFormatVersion1Replay(); break;
				case 2: $this -> LoadFormatVersion2Replay(); break;
				default: throw new Exception("Failed to load replay file with unsupported format version:". $this -> ReplayData['formatVersion']);
			}
		}
		
		private function LoadFormatVersion1Replay()
		{
			$this -> ReplayData['gokzVersion'] = $this -> ReadString($this -> ReadInt8());
			$this -> ReplayData['mapName'] = $this -> ReadString($this -> ReadInt8());
			$this -> ReplayData['botCourse'] = $this -> ReadInt32();
			$this -> ReplayData['botMode'] = $this -> ReadInt32();
			$this -> ReplayData['botStyle'] = $this -> ReadInt32();
			$this -> ReplayData['timeAsInt'] = $this -> ReadInt32();
			$this -> ReplayData['botTeleportsUsed'] = $this -> ReadInt32();
			$this -> ReplayData['steamID'] = $this -> ReadString($this -> ReadInt8());
			$this -> ReplayData['serverIP'] = $this -> ReadString($this -> ReadInt8());
			$this -> ReplayData['botAlias'] = $this -> ReadString($this -> ReadInt8());	
			$this -> ReplayData['tickCount'] = $this -> ReadInt32();
			$this -> ReplayData['TickData'] = $this -> ReadTickData_1($this -> ReplayData['tickCount']);   
		}
		
		private function LoadFormatVersion2Replay()
		{
			$this -> ReplayData['replayType'] = $this -> ReadInt8();
			$this -> ReplayData['gokzVersion'] = $this -> ReadString($this -> ReadInt8());
			$this -> ReplayData['mapName'] = $this -> ReadString($this -> ReadInt8());
			$this -> ReplayData['mapFileSize'] = $this -> ReadInt32();
			$this -> ReplayData['serverIP'] = $this -> ReadInt32();
			$this -> ReplayData['timestamp'] = $this -> ReadInt32();			
			$this -> ReplayData['botAlias'] = $this -> ReadString($this -> ReadInt8());	
			$this -> ReplayData['steamID'] = $this -> ReadInt32();				
			$this -> ReplayData['botMode'] = $this -> ReadInt8();			
			$this -> ReplayData['botStyle'] = $this -> ReadInt8();	
			$this -> ReplayData['intPlayerSensitivity'] = $this -> ReadFloat32();	
			$this -> ReplayData['intPlayerMYaw'] = $this -> ReadFloat32();	
			$this -> ReplayData['tickrateAsInt'] = $this -> ReadFloat32();
			$this -> ReplayData['tickCount'] = $this -> ReadInt32();
			$this -> ReplayData['botWeapon'] = $this -> ReadInt32();
			$this -> ReplayData['botKnife'] = $this -> ReadInt32();
			$this -> ReplayData['timeAsInt'] = $this -> ReadInt32();
			$this -> ReplayData['botCourse'] = $this -> ReadInt8();
			$this -> ReplayData['botTeleportsUsed'] = $this -> ReadInt32();
			$this -> ReplayData['TickData'] = $this -> ReadTickData_2($this -> ReplayData['tickCount']);    
		}  
		
		private function ReadInt32() 
		{
			$int32 = substr( $this -> replayfile, $this -> readpoint , 4);
			$this -> readpoint  += 4;
			return unpack("V", $int32)[1];
		}
		
		private function ReadInt8() 
		{
			$int8 = substr($this -> replayfile, $this -> readpoint , 1);
			$this -> readpoint  += 1;
			return unpack("c", $int8)[1];
		}
		
		private function ReadFloat32() 
		{
			$Float32 = substr($this -> replayfile, $this -> readpoint , 4);
			$this -> readpoint  += 4;
			return unpack("f", $Float32)[1];
		}
		
		private function ReadString($length) 
		{
			$buffer = substr($this -> replayfile, $this -> readpoint, $length);
			$this -> readpoint  += $length;
			return $buffer;
		}
		
		private function ReadTickData_1($length)
		{
			//$TickDataArray = $this -> ReadTickDataArray(); // cant test bcz i dont have one file with FormatVersion1
			for($i = 0; $i < $length; $i++)
			{
				$data[0] = $this -> ReadFloat32();	// origin[0]
				$data[1] = $this -> ReadFloat32();	// origin[1]
				$data[2] = $this -> ReadFloat32();	// origin[2]
				$data[3] = $this -> ReadFloat32();	// angles[0]
				$data[4] = $this -> ReadFloat32();	// angles[1]
				$data[5] = $this -> ReadInt32(); // buttons
				$data[6] = $this -> ReadInt32(); // flags
			}
			return $data;
		}
		
		private function ReadTickDataArray() 
		{
			$TickDataArray = substr( $this -> replayfile, $this -> readpoint , 4 * 7); // RP_V1_TICK_DATA_BLOCKSIZE with 4 byte
			$this -> readpoint  += 28; // RP_V1_TICK_DATA_BLOCKSIZE with 4 byte
			return unpack("s*", $TickDataArray); // cant test bcz i dont have one file with FormatVersion1
		}
		
		private function ReadTickData_2($tickCount)
		{
			/*demo 演示
			json array 数组 FormatVersion2
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
		
		private function skip($length)
		{
			$this -> readpoint  += $length;
		}
	}