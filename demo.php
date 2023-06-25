<?
echo "基于KNN 的 KDtree临近查询点算法 in php<br>";
include("utils.php");

class KDNode 
{
    public $point;
    public $left;
    public $right;

    public function __construct($point) 
    {
        $this->point = $point;
        $this->left = null;
        $this->right = null;
    }
}

class KDTree 
{
    private $root;

    public function __construct()
    {
        $this->root = null;
    }

    public function insert($point) 
    {
        $this->root = $this->insertRecursive($this->root, $point, 0);
    }

    private function insertRecursive($node, $point, $depth) 
    {
        if ($node == null) 
        {
            return new KDNode($point);
        }

        $axis = $depth % 3;

        if ($point[$axis] < $node->point[$axis]) 
        {
            $node->left = $this->insertRecursive($node->left, $point, $depth + 1);
        } 
        else 
        {
            $node->right = $this->insertRecursive($node->right, $point, $depth + 1);
        }

        return $node;
    }

    public function nearestNeighborSearch($target) 
    {
        //$bestDistance = PHP_FLOAT_MAX;
        $bestDistance = 0x7f7fffff;//biggest float in IEEE754
        $bestPoint = null;

        $this->nearestNeighborSearchRecursive($this->root, $target, 0, $bestDistance, $bestPoint);

        return $bestPoint;
    }

    private function nearestNeighborSearchRecursive($node, $target, $depth, &$bestDistance, &$bestPoint) 
    {
        if ($node == null) 
        {
            return;
        }

        $axis = $depth % 3;

        $distance = $this->euclideanDistance($node->point, $target);

        if ($distance < $bestDistance) 
        {
            $bestDistance = $distance;
            $bestPoint = $node->point;
        }

        if ($target[$axis] < $node->point[$axis]) 
        {
            $this->nearestNeighborSearchRecursive($node->left, $target, $depth + 1, $bestDistance, $bestPoint);

            if ($target[$axis] + $bestDistance >= $node->point[$axis]) 
            {
                $this->nearestNeighborSearchRecursive($node->right, $target, $depth + 1, $bestDistance, $bestPoint);
            }
        } 
        else 
        {
            $this->nearestNeighborSearchRecursive($node->right, $target, $depth + 1, $bestDistance, $bestPoint);

            if ($target[$axis] - $bestDistance <= $node->point[$axis]) 
            {
                $this->nearestNeighborSearchRecursive($node->left, $target, $depth + 1, $bestDistance, $bestPoint);
            }
        }
    }

    private function euclideanDistance($point1, $point2) 
    {
        return sqrt(($point2[0] - $point1[0]) * ($point2[0] - $point1[0]) + ($point2[1] - $point1[1]) * ($point2[1] - $point1[1]) + ($point2[2] - $point1[2]) * ($point2[2] - $point1[2]));
    }
}

$start = getMillisecond();
echo "开始时间: ".$start."<br>";
$file = new Replay("1.replay", 0);
$replaydata = ($file -> ReplayData);
$replaydata = $replaydata['TickData'];

$loadreplaydata = getMillisecond();
echo "数据载入时间 : $loadreplaydata ms cost ".($loadreplaydata - $start)." ms"."<br>";
$kdtree = new KDTree();
echo"数据集合 累计：".count($replaydata)."<br>";
for($count = 0;$count <= count($replaydata);$count++)
{
    $kdtree->insert([$replaydata[$count]["7"], $replaydata[$count]["8"], $replaydata[$count]["9"]]);
}
$kdtreeload = getMillisecond();

echo "KDTree 生成结束时间 : $kdtreeload ms cost ".($kdtreeload - $loadreplaydata)." ms"."<br>";

$target = [-134, 231.1, 129];

echo "查询点: " . implode(", ", $target)."<br>";

$nearestNeighbor = $kdtree->nearestNeighborSearch($target);
$findtarget = getMillisecond();

echo "临近点查询所用时间 : $findtarget ms cost ".($findtarget - $kdtreeload)." ms"."<br>";
echo "查询到临近点: " . implode(", ", $nearestNeighbor)."<br>";
echo "GC 内存使用 ".formatBytes(memory_get_peak_usage());

function getMillisecond()
{
    list($msec, $sec) = explode(' ', microtime());
    $msectime = (float)sprintf('%.0f', (floatval($msec) + floatval($sec)) * 1000);
    return $msectimes = substr($msectime,0,13);
}

function formatBytes($bytes, $precision = 2) 
{
    $units = array("b", "kb", "mb", "gb", "tb");
    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);
    $bytes /= (1 << (10 * $pow));
    return round($bytes, $precision) . " " . $units[$pow];
}

?>