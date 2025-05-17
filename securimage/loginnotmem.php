<?
//==========check user ever log or not===========
					$sql_hacker="SELECT  N_wrong_log FROM BNCDT_USERS.dbo.tbluserlog where IpLog='".$_SERVER['REMOTE_ADDR']."'";
					$rs_hacker=mssql_query($sql_hacker);
					
					//========sql select number log wrong======================
					$sql_check_Nlogwrong="SELECT  N_wrong_log FROM BNCDT_USERS.dbo.tbluserlog where IpLog='".$_SERVER['REMOTE_ADDR']."'";
					$rs_check_Nlogwrong=mssql_query($sql_check_Nlogwrong);
					$row_check_Nlogwrong=mssql_fetch_assoc($rs_check_Nlogwrong);
					$NLogwrong=$row_check_Nlogwrong["N_wrong_log"]+1;
					
					if(mssql_num_rows($rs_hacker)>0){
						$H=date("H");
						$D_T_send=date("Y").'-'.date("m").'-'.date("d").' '.$H.':'.date("i").':'.date("s");
						mssql_query("update BNCDT_USERS.dbo.tbluserlog set LogTime='$D_T_send',N_wrong_log='$NLogwrong'  where BNCDT_USERS.dbo.tbluserlog.IpLog='".$_SERVER['REMOTE_ADDR']."'");
					}else{
						$H=date("H");
						$D_T_send=date("Y").'-'.date("m").'-'.date("d").' '.$H.':'.date("i").':'.date("s");
						mssql_query("insert into BNCDT_USERS.dbo.tbluserlog(BNCDT_USERS.dbo.tbluserlog.Uname,BNCDT_USERS.dbo.tbluserlog.IpLog,BNCDT_USERS.dbo.tbluserlog.LogDate,BNCDT_USERS.dbo.tbluserlog.TypeLog,BNCDT_USERS.dbo.tbluserlog.LogTime,BNCDT_USERS.dbo.tbluserlog.N_wrong_log) values('".$username."','".$_SERVER['REMOTE_ADDR']."','".$logdate."','1','$D_T_send','$NLogwrong')");
					}
?>