<?
//=========check Uname & Password correct=========
				$sql_Uname_Password="select * from BNCDT_USERS.dbo.tblUser where BNCDT_USERS.dbo.tblUser.login_name='".$username."' and BNCDT_USERS.dbo.tblUser.password='".$password."' and  BNCDT_USERS.dbo.tblUser.active='1' and (BNCDT_USERS.dbo.tblUser.position='1' or BNCDT_USERS.dbo.tblUser.position='4' or BNCDT_USERS.dbo.tblUser.position='5' or BNCDT_USERS.dbo.tblUser.position='6' or BNCDT_USERS.dbo.tblUser.position='9')";
				$rs_Uname_Password=mssql_query($sql_Uname_Password);
				
				if(mssql_num_rows($rs_Uname_Password)>0){
					//echo 'list project';
					$row_success=mssql_fetch_assoc($rs_Uname_Password);
					$_SESSION["userid"]=$row_success["id"];
					$_SESSION["full_name"]=$row_success["full_name"];			
					$_SESSION["position"]=$row_success["position"];
					$_SESSION["active"]=$row_success["active"];
					$_SESSION["view_type"]=$row_success["view_type"];
					$_SESSION["username"]=$username;
					$_SESSION["kh"]=1;
					
					//====update N_log_wrong=0==================
					$sql_user_exist_log="SELECT  Uname FROM BNCDT_USERS.dbo.tbluserlog where Uname='".$username."'";
					$rs_user_exist_log=mssql_query($sql_user_exist_log);
					if(mssql_num_rows($rs_user_exist_log)>0){
						$H=date("H");
						$D_T_send=date("Y").'-'.date("m").'-'.date("d").' '.$H.':'.date("i").':'.date("s");
						mssql_query("update BNCDT_USERS.dbo.tbluserlog set LogTime='$D_T_send',N_wrong_log='0',TypeLog='2'  where BNCDT_USERS.dbo.tbluserlog.UnameID='".$row_success["id"]."'");
					}else{
						$H=date("H");
						$D_T_send=date("Y").'-'.date("m").'-'.date("d").' '.$H.':'.date("i").':'.date("s");
						mssql_query("insert into BNCDT_USERS.dbo.tbluserlog(BNCDT_USERS.dbo.tbluserlog.UnameID,BNCDT_USERS.dbo.tbluserlog.Uname,BNCDT_USERS.dbo.tbluserlog.IpLog,BNCDT_USERS.dbo.tbluserlog.LogDate,BNCDT_USERS.dbo.tbluserlog.TypeLog,BNCDT_USERS.dbo.tbluserlog.LogTime,BNCDT_USERS.dbo.tbluserlog.N_wrong_log) values('".$row_success["id"]."','".$username."','".$_SERVER['REMOTE_ADDR']."','".$logdate."','2','$D_T_send','0')");
					}
					//=======list all project===================
					header("location: Index.php");
				}else{
					$sql_wrong="select id from BNCDT_USERS.dbo.tblUser where BNCDT_USERS.dbo.tblUser.login_name='".$username."'";
					$rs_wrong=mssql_query($sql_wrong);
					$row_wrong=mssql_fetch_assoc($rs_wrong);
					
					//=========check user exist or not in tbllog=========
					$sql_user_exist_log_wrong="SELECT  Uname FROM BNCDT_USERS.dbo.tbluserlog where Uname='".$username."'";
					$rs_user_exist_log_wrong=mssql_query($sql_user_exist_log_wrong);
					
					//========sql select number log wrong======================
					$sql_check_Nlogwrong="SELECT  N_wrong_log FROM BNCDT_USERS.dbo.tbluserlog where Uname='".$username."'";
					$rs_check_Nlogwrong=mssql_query($sql_check_Nlogwrong);
					$row_check_Nlogwrong=mssql_fetch_assoc($rs_check_Nlogwrong);
					$NLogwrong=$row_check_Nlogwrong["N_wrong_log"]+1;
					
					if(mssql_num_rows($rs_user_exist_log_wrong)>0){						
						$H=date("H");
						$D_T_send=date("Y").'-'.date("m").'-'.date("d").' '.$H.':'.date("i").':'.date("s");
						mssql_query("update BNCDT_USERS.dbo.tbluserlog set LogTime='$D_T_send',N_wrong_log='$NLogwrong'  where BNCDT_USERS.dbo.tbluserlog.UnameID='".$row_wrong["id"]."'");
					}else{
						$H=date("H");
						$D_T_send=date("Y").'-'.date("m").'-'.date("d").' '.$H.':'.date("i").':'.date("s");
						mssql_query("insert into BNCDT_USERS.dbo.tbluserlog(BNCDT_USERS.dbo.tbluserlog.UnameID,BNCDT_USERS.dbo.tbluserlog.Uname,BNCDT_USERS.dbo.tbluserlog.IpLog,BNCDT_USERS.dbo.tbluserlog.LogDate,BNCDT_USERS.dbo.tbluserlog.TypeLog,BNCDT_USERS.dbo.tbluserlog.LogTime,BNCDT_USERS.dbo.tbluserlog.N_wrong_log) values('".$row_wrong["id"]."','".$username."','".$_SERVER['REMOTE_ADDR']."','".$logdate."','2','$D_T_send','$NLogwrong')");
					}					
					$error="Enter the Valid Username and Password...";
				}
?>