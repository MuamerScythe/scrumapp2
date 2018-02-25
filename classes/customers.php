<?php

	class Customers{

		private $_db;

		function __construct($db){
			$this->_db = $db;
			}

		public function get_customer($id){
			try {
				$stmt = $this->_db->prepare('SELECT * FROM radnici WHERE id=:id');
				$stmt->execute(array(
					':id' => $id
					));
				return $stmt->fetch();

			} catch(PDOException $e) {
				echo '<p class="bg-danger">'.$e->getMessage().'</p>';
			}
		}

		public function update($fields) {
			try {
				$stmt = $this->_db->prepare('UPDATE radnici SET ime=:ime, prezime=:prezime, counter=:counter WHERE id=:id');
				$stmt->execute(array(
					':id' => $fields['update'],
					':ime' => $fields['ime'],
					':prezime' => $fields['prezime'],
					':counter' => $fields['counter']
					));
			} catch(PDOException $e) {
				echo '<p class="bg-danger">'.$e->getMessage().'</p>';
			}
		}

		public function get_num_results($sql) {
			$stmt = $this->_db->prepare($sql);
			$stmt->execute();
			return $stmt->fetchColumn();
		}
		public function get_customers($page=0){
			try {
				$total = $this->get_num_results('SELECT COUNT(*) FROM radnici');
				if($page == 0) $page = 1;
				$rpp = RPP;
				$tpages  = ceil($total/$rpp);
				$sql =
				"SELECT
				radnici.id,
				radnici.ime,
				radnici.prezime,
				radnici.counter
				FROM radnici ORDER BY counter DESC LIMIT ".($page-1)*$rpp.", ".$rpp;
				$stmt = $this->_db->prepare($sql);
				$stmt->execute();
				echo $this->paginate($_SERVER['PHP_SELF'], $page, $tpages, 1, $total);
				return $stmt->fetchAll();

			} catch(PDOException $e) {
				echo '<p class="bg-danger">'.$e->getMessage().'</p>';
			}
		}

		public function del_customer($id){
			try {
				$stmt = $this->_db->prepare('DELETE FROM radnici WHERE id=:id LIMIT 1');
				$stmt->bindValue(':id', $id, PDO::PARAM_INT);
				$stmt->execute();

			} catch(PDOException $e) {
				echo '<p class="bg-danger">'.$e->getMessage().'</p>';
			}
		}

		public function add_customer($fields){
			try {
				$stmt = $this->_db->prepare('INSERT INTO radnici (ime,prezime) VALUES(:ime,:prezime)');
				$test = $stmt->execute(array(
					':ime' => $fields['ime'],
					':prezime' => $fields['prezime']
				));
				return $test;
			} catch(PDOException $e) {
				echo '<p class="bg-danger">'.$e->getMessage().'</p>';
			}
		}

	public function search_clients($string, $page=0) {

		try {
				$total = $this->get_num_results('SELECT COUNT(DISTINCT radnici.id) FROM radnici');
				if($page == 0) $page = 1;
				$rpp = RPP;
				$tpages  = ceil($total/$rpp);
				$control = "SELECT
							radnici.id,
							radnici.ime,
							radnici.prezime,
							radnici.counter FROM radnici
							WHERE ime LIKE '".$string."%'
							OR prezime LIKE '".$string."%'
							LIMIT ".($page-1)*$rpp.", ".$rpp;
				//echo $control;
				//echo $sql;
				$stmt = $this->_db->prepare($control);
				$stmt->execute();
				//return $stmt->debugDumpParams();
				echo $this->paginate($_SERVER['PHP_SELF'], $page, $tpages, 1, $total);//return $sql;
				$z = $stmt->fetchAll();
				return $z;

			} catch(PDOException $e) {
				echo '<p class="bg-danger">'.$e->getMessage().'</p>';
			}
	}

	public function paginate($reload, $page, $tpages, $adjacents, $ukupno) {
	$reload .= '?users=1';
	/*foreach($array as $key => $value) {
		$reload .= '&'.$key.'='.$value;
	}*/
	$prevlabel = '<i class="fa fa-fw fa-angle-double-left"></i>';
	$nextlabel = '<i class="fa fa-fw  fa-angle-double-right"></i>';

	$out = "<div class=\"pagin\"><b class='hidden-xs'>Ukupno ".$ukupno." rezultata.</b><ul class='pagination pagination-sm pull-right'>\n";

	// previous
	if($page==1) {
		$out.= "<li class='hidden-xs'><span>" . $prevlabel . "</span></li>\n";
	}
	elseif($page==2) {
		$out.= "<li class='hidden-xs'><a class='pagge' href=\"" . $reload . "\">" . $prevlabel . "</a></li>\n";
	}
	else {
		$out.= "<li class='hidden-xs'><a class='pagge' href=\"" . $reload . "&amp;page=" . ($page-1) . "\">" . $prevlabel . "</a></li>\n";
	}

	// first
	if($page>($adjacents+1)) {
		$out.= "<li><a class='pagge' href=\"" . $reload . "\">1</a></li>\n";
	}

	// interval
	if($page>($adjacents+2)) {
		$out.= "<li><span>...</span></li>\n";
	}

	// pages
	$pmin = ($page>$adjacents) ? ($page-$adjacents) : 1;
	$pmax = ($page<($tpages-$adjacents)) ? ($page+$adjacents) : $tpages;
	for($i=$pmin; $i<=$pmax; $i++) {
		if($i==$page) {
			$out.= "<li class='active'><span class=\"current\">" . $i . "</span></li>\n";
		}
		elseif($i==1) {
			$out.= "<li><a class='pagge' href=\"" . $reload . "\">" . $i . "</a></li>\n";
		}
		else {
			$out.= "<li><a class='pagge' href=\"" . $reload . "&amp;page=" . $i . "\">" . $i . "</a></li>\n";
		}
	}

	// interval
	if($page<($tpages-$adjacents-1)) {
		$out.= "<li><span>...</span></li>\n";
	}

	// last
	if($page<($tpages-$adjacents)) {
		$out.= "<li><a class='pagge' href=\"" . $reload . "&amp;page=" . $tpages . "\">" . $tpages . "</a></li>\n";
	}

	// next
	if($page<$tpages) {
		$out.= "<li class='hidden-xs'><a class='pagge' href=\"" . $reload . "&amp;page=" . ($page+1) . "\">" . $nextlabel . "</a></li>\n";
	}
	else {
		$out.= "<li class='hidden-xs'><span>" . $nextlabel . "</span></li>\n";
	}

	$out.= "</ul></div>";

	return $out;
}

	}

?>
