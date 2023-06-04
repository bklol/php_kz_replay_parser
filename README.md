>HOW TO USE 如何食用：  

`$file = new Replay('YOUR FILE PATH', START POS);`  
`$magicNumber = $file -> ReadInt32();`  
`$posdata['formatVersion'] = $file -> ReadInt8();`  
`$posdata['replayType'] = $file -> ReadInt8();`  
`$posdata['gokzVersion'] = $file -> ReadString($file -> ReadInt8());`  
`$posdata['mapName'] = $file -> ReadString($file -> ReadInt8());`  
`$posdata['mapFileSize'] = $file -> ReadInt32();`  
`$posdata['serverIP'] = $file -> ReadInt32();`  
`$posdata['timestamp'] = $file -> ReadInt32();`  
`$posdata['botAlias'] = $file -> ReadString($file -> ReadInt8());`  
`$posdata['steamID'] = $file -> ReadInt32();`  
`$posdata['botMode'] = $file -> ReadInt8();`  
`$posdata['botStyle'] = $file -> ReadInt8();`  
`$posdata['intPlayerSensitivity'] = $file -> ReadFloat32();`  
`$posdata['intPlayerMYaw'] = $file -> ReadFloat32();`  
`$posdata['tickrateAsInt'] = $file -> ReadFloat32();`  
`$posdata['tickCount'] = $file -> ReadInt32();`  
`$posdata['botWeapon'] = $file -> ReadInt32();`  
`$posdata['botKnife'] = $file -> ReadInt32();`  
`$posdata['timeAsInt'] = $file -> ReadInt32();`  
`$posdata['botCourse'] = $file -> ReadInt8();`  
`$posdata['botTeleportsUsed'] = $file -> ReadInt32();`  
`$posdata['TickData'] = $file ->ReadTickData($posdata['tickCount']);`  
now `$posdata` is all your need  

**plz dont change the code order, will make it wont work**  
**注意，修改代码顺序会导致无法使用，除非你知道要修改什么.....**  

**only support replay formatVersion 2**  
**只支持 formatVersion 2 的 replay （好像没见过 1 就没写）**
