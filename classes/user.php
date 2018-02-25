<?php
include('password.php');
class User extends Password{

    private $_db;

    function __construct($db){
    	parent::__construct();
    	$this->_db = $db;
  		if(isset($_SESSION['loggedin']) && isset($_SESSION['username']) && isset($_SESSION['memberID'])) {
          try {
    			$stmt = $this->_db->prepare("UPDATE members SET last_activity = NOW() WHERE memberID=".$_SESSION['memberID']);
    			$stmt->execute();
          } catch(PDOException $e) {
      		    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
      		}
  			}
    }

	private function get_user_hash($username){

		try {
			$stmt = $this->_db->prepare('SELECT password, username, role, memberID FROM members WHERE username = :username AND active="Yes" ');
			$stmt->execute(array('username' => $username));

			return $stmt->fetch();

		} catch(PDOException $e) {
		    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
	}

	public function login($username,$password){

		$row = $this->get_user_hash($username);

		if($this->password_verify($password,$row['password']) == 1){

		    $_SESSION['loggedin'] = true;
		    $_SESSION['username'] = $row['username'];
		    $_SESSION['memberID'] = $row['memberID'];
			  $_SESSION['role'] = $row['role'];
		    return true;
		}
	}
	public function check_email($email) {
		try {
				$stmt = $this->_db->prepare('SELECT email FROM members WHERE email=:email');
				$stmt->execute(array(
					':email' => $email
					));
				$result = $stmt->fetchColumn();
				if($result) {
					return false;
				}
				else return true;
			} catch(PDOException $e) {
				echo '<p class="bg-danger">'.$e->getMessage().'</p>';
			}
	}
	public function check_username($username) {
		try {
				$stmt = $this->_db->prepare('SELECT username FROM members WHERE username=:username');
				$stmt->execute(array(
					':username' => $username
					));
				$result = $stmt->fetchColumn();
				if($result) {
					return false;
				}
				else return true;
			} catch(PDOException $e) {
				echo '<p class="bg-danger">'.$e->getMessage().'</p>';
			}
	}
	public function logout(){
		session_destroy();
	}

	public function is_logged_in(){
		if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true){
			return true;
		}
	}
	public function date_rev($date) {
		$rev = date('d.m.Y H:i:s', strtotime($date));
		return $rev;
	}
	public function get_num_results($sql) {
			$stmt = $this->_db->prepare($sql);
			$stmt->execute();
			return $stmt->fetchColumn();
		}

  public function setArrival($data) {
    $id = $data['id'];
    $time = date('Y-m-d').' '.$data['time'];
    try {
        $now = time();
          if($now > strtotime(MEETING_TIME)) {
            $stmt = $this->_db->prepare("INSERT INTO dolasci (radnik_id,vrijeme,islate) VALUES(:id,:times,1)");
    				$stmt->execute(array(':id' => $id, ':times' => $time));
            $add_count = $this->_db->prepare("UPDATE radnici SET counter = counter + 1 WHERE id = :id");
            if($add_count->execute(array(':id' => $id)))
            return "<span class='status-tbl bg-no'>NE</span>";
          }
          else {
            $stmt = $this->_db->prepare("INSERT INTO dolasci (radnik_id,vrijeme) VALUES(:id,NOW())");
    				$stmt->execute(array(':id' => $id));
            return "<span class='status-tbl bg-yes'>DA</span>";
        }

			} catch(PDOException $e) {
				echo '<p class="bg-danger">'.$e->getMessage().'</p>';
			}
  }
	public function get_scrum($page=0) {
		try {
				$total = $this->get_num_results("SELECT COUNT(*) FROM radnici");
				if($page == 0) $page = 1;
				$rpp = RPP;
				$tpages  = ceil($total/$rpp);
				$sql = "SELECT *,
        (SELECT dolasci.vrijeme from dolasci WHERE dolasci.radnik_id = radnici.id AND DATE(dolasci.vrijeme) = CURDATE()) as vrijeme
        FROM radnici LIMIT ".($page-1)*$rpp.", ".$rpp;
				$stmt = $this->_db->prepare($sql);
				$stmt->execute();
				echo $this->paginate($_SERVER['PHP_SELF'], $page, $tpages, 1, $total);
				return $stmt->fetchAll();

			} catch(PDOException $e) {
				echo '<p class="bg-danger">'.$e->getMessage().'</p>';
			}
	}
	  public function update($id,$fields) {
    try {

      if((!empty($fields['lozinka'])) && ($fields['lozinka'] == $fields['lozinka2'])) {
        $password = $this->password_hash($fields['lozinka'], PASSWORD_BCRYPT);
        $stmt = $this->_db->prepare('UPDATE members SET ime=:ime, prezime=:prezime, email=:email, password=:password WHERE memberID=:id');
        $stmt->execute(array(
            'ime' => $fields['ime'],
            'prezime' => $fields['prezime'],
            'email' => $fields['email'],
            'password' => $password,
			'id' => $id
        ));
      }
      else {
        $stmt = $this->_db->prepare('UPDATE members SET ime=:ime, prezime=:prezime, email=:email WHERE memberID=:id');
        $stmt->execute(array(
            'ime' => $fields['ime'],
            'prezime' => $fields['prezime'],
            'email' => $fields['email'],
            'id' => $id
        ));
      }
    } catch(PDOException $e) {
      echo '<p class="bg-danger">'.$e->getMessage().'</p>';
    }
  }

	public function check_new($fields) {
		try {
				$stmt = $this->_db->prepare('SELECT username FROM members WHERE username=:username');
				$stmt->execute(array(
					':username' => $fields['username'],
				));
				$result = $stmt->fetchColumn();
				if($result) {
					return 'Korisnik '.$result.' već postoji';
				}
				else return 0;
			} catch(PDOException $e) {
				echo '<p class="bg-danger">'.$e->getMessage().'</p>';
			}
	}

	public function search_members($string) {
		$search = "";
		$str = preg_split("/[\s,]+/", $string);
			for($i=0;$i<count($str);$i++) {
				$search .= " ime LIKE '".$str[$i]."%' OR prezime LIKE '".$str[$i]."%'";
				($i < count($str) - 1) ? $search .= " OR " : "";
			}
		$sql = "SELECT *,
    (SELECT dolasci.vrijeme from dolasci WHERE dolasci.radnik_id = radnici.id AND DATE(dolasci.vrijeme) = CURDATE()) as vrijeme
    FROM radnici WHERE ($search) ";
		//die($sql);
		try {
				$stmt = $this->_db->prepare($sql);
				$stmt->execute();
				return $stmt->fetchAll();

			} catch(PDOException $e) {
				echo '<p class="bg-danger">'.$e->getMessage().'</p>';
			}
		}

	public function paginate($reload, $page, $tpages, $adjacents, $ukupno, $mods=0,$class='m-page',$id = 0) {
		if(!$mods) {
			$reload .= '?users=1';
		}
		else if($mods == 1) {
			$reload .= '?mods=1';
		}
		else  {
			if($id) $reload .= '?users=1&id='.$id.'&my_clients=1';
			else 	$reload .= '?users=1&my_clients=1';
		}
		$prevlabel = '<i class="fa fa-fw fa-angle-double-left"></i>';
		$nextlabel = '<i class="fa fa-fw  fa-angle-double-right"></i>';

		$out = "<div class=\"pagin\"><b>Ukupno ".$ukupno." rezultata.</b><ul class='pagination pagination-sm pull-right'>\n";

		// previous
		if($page==1) {
			$out.= "<li><span>" . $prevlabel . "</span></li>\n";
		}
		elseif($page==2) {
			$out.= "<li><a class='".$class."' href=\"" . $reload . "\">" . $prevlabel . "</a></li>\n";
		}
		else {
			$out.= "<li><a class='".$class."' href=\"" . $reload . "&amp;page=" . ($page-1) . "\">" . $prevlabel . "</a></li>\n";
		}

		// first
		if($page>($adjacents+1)) {
			$out.= "<li><a class='".$class."' href=\"" . $reload . "\">1</a></li>\n";
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
				$out.= "<li><a class='".$class."' href=\"" . $reload . "\">" . $i . "</a></li>\n";
			}
			else {
				$out.= "<li><a class='".$class."' href=\"" . $reload . "&amp;page=" . $i . "\">" . $i . "</a></li>\n";
			}
		}

		// interval
		if($page<($tpages-$adjacents-1)) {
			$out.= "<li><span>...</span></li>\n";
		}

		// last
		if($page<($tpages-$adjacents)) {
			$out.= "<li><a class='".$class."' href=\"" . $reload . "&amp;page=" . $tpages . "\">" . $tpages . "</a></li>\n";
		}

		// next
		if($page<$tpages) {
			$out.= "<li><a class='".$class."' href=\"" . $reload . "&amp;page=" . ($page+1) . "\">" . $nextlabel . "</a></li>\n";
		}
		else {
			$out.= "<li><span>" . $nextlabel . "</span></li>\n";
		}

		$out.= "</ul></div>";

		return $out;
	}

}


?>
