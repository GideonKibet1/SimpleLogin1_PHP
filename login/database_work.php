<?php


/*
Reg_No:ENE212-0090/2019
Name: GIDEON KIBET.
*/ 

class database_work
{
	private $db_host="localhost";
	private $db_user="root";
	private $db_password="";
	private $db_database="test";
	private $con="";
	
	public function __construct($h,$u,$p,$db)
	{
		$this->db_host=$h;
		$this->db_user=$u;
		$this->db_password=$p;
		$this->db_database=$db;
		$this->con=mysqli_connect($this->db_host,$this->db_user,$this->db_password,$this->db_database) or die(mysqli_error());
	}
	
	
/*
	All Rows Select and return in array
*/
	function select_rows($table,$cols,$condtion)
	{
		if($cols=="")
			$cols="*";
		if($condtion)
			$condtion=" Where ".$condtion;
		$query="select $cols from $table $condtion";
		//echo $query;
		$result=mysqli_query($this->con,$query) or die(mysqli_error());
		$col_count=mysqli_num_fields($result) or die(mysqli_error());
		$data=array();
		$ii=0;
		while($row=mysqli_fetch_array($result))
		{
			$data[$ii]=array();
			for($num=0;$num<$col_count;$num++)
			{
				$data[$ii][$num]=$row[$num];
			}
			$ii++;
		}
		return $data;
	}
	
	
/*
	All colss Select and return in array
*/
	function select_cols($table,$cols)
	{
		if($cols=="")
			$cols="*";
		$query="select $cols from $table";
		$result=mysqli_query($query,$this->con) or die(mysqli_error());
		$col_count=mysqli_num_fields($result) or die(mysqli_error());
		$data=array();
		
		for($i=0;$i<$col_count;$i++)
		{
			$data[$i]=mysqli_field_name($result,$i);
		}
		return $data;
	}
	
	
	
/*
	Count Number Of colss and Return
*/
	function num_of_cols($table,$cols)
	{
		$col_count=count($this->select_cols($table,$cols));
		return $col_count;
	}
	
/*
	Print the table of Given colss and Rows with His Designing
*/
	function printtable($array_cols,$array_rows,$table_property,$tr_property,$th_property,$td_property)
	{
		echo"<table $table_property>";
		echo"<tr $tr_property>";
		foreach($array_cols as $cols)
		{
			echo"<th $th_property>".$cols."</th>";
		}
		echo"</tr>";
		
		/////////
		foreach($array_rows as $rows)
		{
			echo"<tr $tr_property>";
			foreach($rows as $values)
			{
				echo"<td $td_property>".$values."</td>";
			}
			echo"</tr>";
		}
		echo"</table>";
	}
	
	
/*
	Insert the New Values 
		we can insert array for field or values
		return true if success else false
*/
	function insert_data($table,$cols,$values)
	{
		$field="";
		$val="";
		//////
		if(is_array($cols))
		{
			for($i=0;$i<count($cols)-1;$i++)
			{
				$field=$field.$cols[$i].",";
			}
			$field=$field.$cols[$i];
		}
		else
		{
			$field="($cols)";
		}
		////////
		if(is_array($values))
		{
			for($i=0;$i<count($values)-1;$i++)
			{
				$val=$val."'".$values[$i]."',";
			}
			$val=$val."'".$values[$i]."'";
		}
		else
		{
			$val=$values;
		}
		/////////
		//echo "insert into $table $field values($val)";
		$r=mysqli_query($this->con,"insert into $table $field values($val)") or die(mysqli_error());
		
		if($r==1)
			return true;
		else
			return false;
	}

/*
	Update Values 
*/
	function update_data($table,$cols,$values,$condtion)
	{
		$query="update $table set ";
		//////
		if($condtion && count($this->select_rows($table,"",$condtion)))
		{	
			if(!is_array($cols))
			{
				$cols=explode(",",$cols);
			}
			////////
			if(!is_array($values))
			{
				$values=str_replace("'","",$values);
				$values=explode(",",$values);
			}
			
			for($i=0;$i<count($cols)-1;$i++)
			{
				$query=$query.$cols[$i]."='".$values[$i]."',";
			}
			$query=$query.$cols[$i]."='".$values[$i]."' where ".$condtion;
			
			$r=mysqli_query($query);// or die(mysqli_error());
			if($r==1)
				return true;
			else
				return false;
		}
		else
			return false;
	}
	
/*
	Delete Values 
*/
	function delete_data($table,$condtion)
	{
		if($condtion && count($this->select_rows($table,"",$condtion)))
		{
			$query="delete from $table where $condtion";
			$r=mysqli_query($query);// or die(mysqli_error());
			if($r==1)
				return true;
			else
				return false;
		}
		else
		{
			return false;
		}
	}
/*
	Check Exist Data
*/
	function check_exist_data($table,$col,$value)
	{
		$arr=$this->select_rows($table,$col,"");
		$bool=false;
		foreach($arr as $a)
		{
			if(!strcmp($a[0],$value))
				$bool=true;
		}
		return $bool;
	}
}	
	
	
	//$db=new database_work('localhost','root','','test1');
	
//print
	/*
	$arc=$ob->select_cols("expoptions","","");
	$arr=$ob->select_rows("expoptions","","");
	$ob->printtable($arc,$arr,"border=2 width=100% style='font-size:10px;'","","style='color:blue;'","");
	//*/
	
//insert
	/*if($ob->insert_data("mytable","","'1002','Sunil','Balram','C'"))
	{
		echo"success";
	}
	else
	{
		echo"not success";
	}
	//*/
	
//update
	/*
	if($ob->update_data("mytable","Name,Course","'ANIL KUMAR','PHOTOSHOP'","RollNo=1001"))
		echo"updated";
	else
		echo "Not updated";
	//*/	
	
//delete
	/*
	IF($ob->delete_data("mytest","COURSE='C'"))
		echo"deleted";
	else
		echo"not deleted";
	//*/