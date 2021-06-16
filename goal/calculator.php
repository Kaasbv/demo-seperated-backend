<?PHP

class Calculator {
  public static function calculateParentPosition($quantity)
  {
    if($quantity >= 10) return 1;
    else return 10 - $quantity;
  }

  public static function calculateChildCluster($quantity)
  {
    $cluster_array = [
      [0,1],
      [2,2],
      [3,5],
      [6,10],
      [11,20],
      [21,50],
      [51,100],
      [101,500],
      [501,1100],
    ];

    if($quantity >= 1101) return 10;

    foreach($cluster_array as $key => $value)
    {
      if($value[0] >= $quantity && $value[1] <= $quantity) return ++$key;
    }
  }
}
?>