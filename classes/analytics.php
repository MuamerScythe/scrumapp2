<?php
class Analytics {

  private $_db;

  function __construct($db){
    $this->_db = $db;
    }

  	public function today_activity() {
		try {
				$sql = "SELECT
        SUM(IF(vrijeme <= addtime(CURDATE(), '08:45:00'),1,0)) AS Pravovremeno,
				SUM(IF(vrijeme >= addtime(CURDATE(), '08:45:00'),1,0)) AS Kašnjenja,
        ((SELECT COUNT(*) FROM radnici) - SUM(IF(vrijeme <= addtime(CURDATE(), '08:45:00'),1,0)) - SUM(IF(vrijeme >= addtime(CURDATE(), '08:45:00'),1,0))) AS Neprijavljeno
				FROM dolasci WHERE vrijeme >= TIMESTAMP(CURDATE())";
				$stmt = $this->_db->prepare($sql);
				$stmt->execute();
				return json_encode($stmt->fetchAll(2));
			} catch(PDOException $e) {
				echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
	}
  public function week_late() {
    try {

        $sql = "SELECT
        SUM(IF(vrijeme >= addtime(DATE(vrijeme), '".MEETING_TIME."'),1,0)) AS late,
        DATE(vrijeme) as datum, DAYOFWEEK(vrijeme) as day 
	FROM `dolasci` WHERE vrijeme >= TIMESTAMP(CURDATE() - INTERVAL 6 DAY) 
	GROUP BY DAYOFYEAR(vrijeme) ORDER BY DATE(vrijeme) ASC LIMIT 7";
        $stmt = $this->_db->prepare($sql);
        $stmt->execute();
        return json_encode($stmt->fetchAll(2));
      } catch(PDOException $e) {
        echo '<p class="bg-danger">'.$e->getMessage().'</p>';
    }
  }

}
?>
