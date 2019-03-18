


SELECT transdate a, weekday(transdate) weekday, sum(mamount) amount, (
    SELECT sum(mamount) FROM AK_PGW_Daily WHERE transdate = (a - INTERVAL 1 day)) priviousday , (100 + ( 100 * ((
        SELECT sum(mamount) FROM AK_PGW_Daily WHERE transdate = (a - INTERVAL 7 day)) - (
          SELECT sum(mamount) FROM AK_PGW_Daily WHERE transdate = (a - INTERVAL 8 day))) / (
            SELECT sum(mamount) FROM AK_PGW_Daily WHERE transdate = (a - INTERVAL 8 day)) ) ) per FROM AK_PGW_Daily WHERE transdate > 
             (SELECT max(transdate) - INTERVAL 7 day FROM AK_PGW_Daily) GROUP by transdate












SELECT smsMaster.company,smsMaster.stakeholder,s.smstype   ,smsMaster.stakeholder_type,s.month,s.year,if(smsMaster.SD_RAT=-1,0,smsMaster.SD_RAT),if(smsMaster.VAT_RAT=-1,0,smsMaster.VAT_RAT),if(smsMaster.Sarcharge_1=-1,0,smsMaster.Sarcharge_1),smsMaster.sms_rate unit_price,s.smscount smscount,s.netamount amount,
				(s.netamount*if(smsMaster.SD_RAT=-1,0,smsMaster.SD_RAT)) sd_amount,
				(s.netamount*if(smsMaster.SD_RAT=-1,0,smsMaster.SD_RAT) +s.netamount) amount_with_sd,
				(s.netamount*if(smsMaster.SD_RAT=-1,0,smsMaster.SD_RAT) +s.netamount)*(if(smsMaster.VAT_RAT=-1,0,smsMaster.VAT_RAT)) vat_amount,
				(s.netamount*if(smsMaster.SD_RAT=-1,0,smsMaster.SD_RAT) +s.netamount)*(if(smsMaster.VAT_RAT=-1,0,smsMaster.VAT_RAT))+(s.netamount*if(smsMaster.SD_RAT=-1,0,smsMaster.SD_RAT) +s.netamount) amount_with_vat,
				(s.netamount)*(if(smsMaster.Sarcharge_1=-1,0,smsMaster.Sarcharge_1)) sarcharge_amount,
				((s.netamount*if(smsMaster.SD_RAT=-1,0,smsMaster.SD_RAT) +s.netamount)*(if(smsMaster.VAT_RAT=-1,0,smsMaster.VAT_RAT))+(s.netamount*if(smsMaster.SD_RAT=-1,0,smsMaster.SD_RAT) +s.netamount) +
				(s.netamount)*(if(smsMaster.Sarcharge_1=-1,0,smsMaster.Sarcharge_1)))  invoice_amount
				from (SELECT sum(SMSData.amount) smscount,MonthName(SMSData.transdate) month,year(SMSData.transdate) year,sum(SMSData.sms_rate) netamount,SMSData.Stakeholder stakeholder,(case when SMSData.Operator!='INTERNATIONAL' then 'Local' when SMSData.Operator='INTERNATIONAL' then 'International' end) smstype from SMSData ";
	    	
/* CREATE TABLE */
CREATE TABLE IF NOT EXISTS smsMaster(
  Company VARCHAR(100), 
  stackholders VARCHAR(100), 
  Type VARCHAR(100), 
  SD_RAT DECIMAL(10, 2), 
  VAT_RAT DECIMAL(10, 2), 
  Sarcharge_1 % DECIMAL(10, 2)
);
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'BBS Cables Ltd.', 
  stackholders = 'BBS_CABLES', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
 Company = 'BBS Cables Ltd.' and 
  stackholders = 'BBS_CABLES' and  
  Type = 'LOCAL' ;
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'aamra networks limited', 
  stackholders = 'AAMRA_NETWORKS', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0, 
  Sarcharge_1 %= 0 
WHERE 
    Company = 'aamra networks limited' and 
  stackholders = 'AAMRA_NETWORKS' and
  Type = 'LOCAL';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'AARONG', 
  stackholders = 'AARONG', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'AARONG' and
  stackholders = 'AARONG' and 
  Type = 'LOCAL';
UPDATE 
  smsMaster 
SET 
  Company = 'AARONG', 
  stackholders = 'AARONG_CRM', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
   Company = 'AARONG' and 
  stackholders = 'AARONG_CRM' and 
  Type = 'LOCAL'; 
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'AARONG', 
  stackholders = 'AARONGCLOUDCHERRY', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'AARONG' and
  stackholders = 'AARONGCLOUDCHERRY'and 
  Type = 'LOCAL'; 
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'ACI Godrej Agrovet Limited', 
  stackholders = 'ACI_GODREJ', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
    Company = 'ACI Godrej Agrovet Limited' and 
  stackholders = 'ACI_GODREJ' and
  Type = 'LOCAL'; 
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'ACI Motors Limited', 
  stackholders = 'ACIMOTORS', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'ACI Motors Limited' and  
  stackholders = 'ACIMOTORS' and 
  Type = 'LOCAL' ;
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'ADN Telecom Ltd.', 
  stackholders = 'ADNBRAND', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.15, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'ADN Telecom Ltd.';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'ADN Telecom Ltd.', 
  stackholders = 'ADNTELCCD', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'ADN Telecom Ltd.';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Adrant Ltd', 
  stackholders = 'BLACKPHONE', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'Adrant Ltd';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Aegis Services Ltd', 
  stackholders = 'AEGIS', 
  Type = 'LOCAL', 
  SD_RAT = 0.05, 
  VAT_RAT = 0, 
  Sarcharge_1 %= 0.01 
WHERE 
  Company = 'Aegis Services Ltd';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Aegis Services Ltd', 
  stackholders = 'AEGIS_ALERT', 
  Type = 'LOCAL', 
  SD_RAT = 0.05, 
  VAT_RAT = 0, 
  Sarcharge_1 %= 0.01 
WHERE 
  Company = 'Aegis Services Ltd';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Aegis Services Ltd', 
  stackholders = 'AEGISALERTBRAND', 
  Type = 'LOCAL', 
  SD_RAT = 0.05, 
  VAT_RAT = 0, 
  Sarcharge_1 %= 0.01 
WHERE 
  Company = 'Aegis Services Ltd';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Aftab Bahumukhi Farms Ltd', 
  stackholders = 'ABFL', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'Aftab Bahumukhi Farms Ltd';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Aftab Bahumukhi Farms Ltd', 
  stackholders = 'ABFLBANGLA', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'Aftab Bahumukhi Farms Ltd';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Aftab Bahumukhi Farms Ltd', 
  stackholders = 'AFBLBANGLANONBRAND', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'Aftab Bahumukhi Farms Ltd';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Aftab Bahumukhi Farms Ltd', 
  stackholders = 'AFPL', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'Aftab Bahumukhi Farms Ltd';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Aftab Bahumukhi Farms Ltd', 
  stackholders = 'AHL', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'Aftab Bahumukhi Farms Ltd';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Aftab Bahumukhi Farms Ltd', 
  stackholders = 'IGFL', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'Aftab Bahumukhi Farms Ltd';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Aftab Bahumukhi Farms Ltd', 
  stackholders = 'MDABFL', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'Aftab Bahumukhi Farms Ltd';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'American International University-Bangladesh (AIUB)', 
  stackholders = 'AIUB', 
  Type = 'LOCAL', 
  SD_RAT = 0.05, 
  VAT_RAT = 0.15, 
  Sarcharge_1 %= 0.01 
WHERE 
  Company = 'American International University-Bangladesh (AIUB)';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'American International University-Bangladesh (AIUB)', 
  stackholders = 'AIUBNONBRAND', 
  Type = 'LOCAL', 
  SD_RAT = 0.05, 
  VAT_RAT = 0.15, 
  Sarcharge_1 %= 0.01 
WHERE 
  Company = 'American International University-Bangladesh (AIUB)';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'ANSARVDP', 
  stackholders = 'ANSARVDP', 
  Type = 'LOCAL', 
  SD_RAT = 0.05, 
  VAT_RAT = 0.15, 
  Sarcharge_1 %= 0.01 
WHERE 
  Company = 'ANSARVDP';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Ascent Group (Scholastica)', 
  stackholders = 'AURORAINTL', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'Ascent Group (Scholastica)';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Bagdoom.com', 
  stackholders = 'BAGDOOMBANGLA', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'Bagdoom.com';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Bagdoom.com', 
  stackholders = 'BAGDOOMENG', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'Bagdoom.com';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'BANANI_SCTY', 
  stackholders = 'BANANI_SCTY', 
  Type = 'LOCAL', 
  SD_RAT = 0.05, 
  VAT_RAT = 0.15, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'BANANI_SCTY';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Bangladesh Overseas Employment and Services Limited', 
  stackholders = 'BOESL', 
  Type = 'LOCAL', 
  SD_RAT = 0.05, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'Bangladesh Overseas Employment and Services Limited';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Bangladesh Water Development Board', 
  stackholders = 'BWDB', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'Bangladesh Water Development Board';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Baridhara Cosmopolitan Club Limited', 
  stackholders = 'BCCL', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'Baridhara Cosmopolitan Club Limited';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Baridhara Cosmopolitan Club Limited', 
  stackholders = 'BCCLERP', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'Baridhara Cosmopolitan Club Limited';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Baridhara Cosmopolitan Club Limited', 
  stackholders = 'BCCLPVT', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'Baridhara Cosmopolitan Club Limited';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'BD Educations', 
  stackholders = 'ABISBANGLA', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'BD Educations';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'BD Educations', 
  stackholders = 'ABISENG', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'BD Educations';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'BD Educations', 
  stackholders = 'ABSCHOOLBDBRAND', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'BD Educations';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'BD Educations', 
  stackholders = 'MKMABANGLA', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'BD Educations';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'BD Educations', 
  stackholders = 'NKABBANGLA', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'BD Educations';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'BD Educations', 
  stackholders = 'SOLVERBRAND', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'BD Educations';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'bdtender', 
  stackholders = 'BDTENDER', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'bdtender';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Best Electronics Ltd.', 
  stackholders = 'BESTELECT', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'Best Electronics Ltd.';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Best Electronics Ltd. (Khatoon Plastic Industries Ltd)', 
  stackholders = 'KPLBD', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'Best Electronics Ltd. (Khatoon Plastic Industries Ltd)';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Beximco Communication Ltd', 
  stackholders = 'BEXIMCOCOMMUNICATION', 
  Type = 'LOCAL', 
  SD_RAT = 0.03, 
  VAT_RAT = 0.15, 
  Sarcharge_1 %= 0.01 
WHERE 
  Company = 'Beximco Communication Ltd';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Beximco Communication Ltd', 
  stackholders = 'REALVUSUPPORT', 
  Type = 'LOCAL', 
  SD_RAT = 0.03, 
  VAT_RAT = 0.15, 
  Sarcharge_1 %= 0.01 
WHERE 
  Company = 'Beximco Communication Ltd';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Bikroy.com Limited', 
  stackholders = 'BIKROY', 
  Type = 'LOCAL', 
  SD_RAT = 0.05, 
  VAT_RAT = 0.15, 
  Sarcharge_1 %= 0.01 
WHERE 
  Company = 'Bikroy.com Limited';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Bikroy.com Limited', 
  stackholders = 'BIKROY6969', 
  Type = 'LOCAL', 
  SD_RAT = 0.05, 
  VAT_RAT = 0.15, 
  Sarcharge_1 %= 0.01 
WHERE 
  Company = 'Bikroy.com Limited';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Bikroy.com Limited', 
  stackholders = 'BIKROYBANGLA', 
  Type = 'LOCAL', 
  SD_RAT = 0.05, 
  VAT_RAT = 0.15, 
  Sarcharge_1 %= 0.01 
WHERE 
  Company = 'Bikroy.com Limited';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'cambrian college', 
  stackholders = 'CAMBRIAN', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'cambrian college';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'cambrian college', 
  stackholders = 'METROPOLITON', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'cambrian college';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'cambrian college', 
  stackholders = 'WINSOME', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'cambrian college';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Chaldal.com', 
  stackholders = 'CHALDAL.COM', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'Chaldal.com';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Cloud Well Limited', 
  stackholders = 'CLOUDWELLPMB', 
  Type = 'LOCAL', 
  SD_RAT = 0.03, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'Cloud Well Limited';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Cloud Well Limited', 
  stackholders = 'PAYWELL', 
  Type = 'LOCAL', 
  SD_RAT = 0.03, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'Cloud Well Limited';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Cloud Well Limited', 
  stackholders = 'PAYWELLBANGLA', 
  Type = 'LOCAL', 
  SD_RAT = 0.03, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'Cloud Well Limited';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Cookups Technologies Limited', 
  stackholders = 'COOKUPSENG', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'Cookups Technologies Limited';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'CrownCement', 
  stackholders = 'CROWNCEMENT', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'CrownCement';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Daffodil Computer Limited', 
  stackholders = 'DIPTINONBRAND', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'Daffodil Computer Limited';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Daffodil Computer Limited', 
  stackholders = 'DIUETENDERNONBRAND', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'Daffodil Computer Limited';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Daffodil Computer Limited', 
  stackholders = 'JOBSBDNONBRAND', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'Daffodil Computer Limited';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Datavoxel ltd', 
  stackholders = 'SAMBRAND', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'Datavoxel ltd';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'DCCI', 
  stackholders = 'DCCIPR', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.045, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'DCCI';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'DCCI', 
  stackholders = 'DCCIPS', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.045, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'DCCI';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'DCCI', 
  stackholders = 'DCCIRD', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.045, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'DCCI';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Delta Brac Housing Finance Corporation Ltd.', 
  stackholders = 'DBH', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.15, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'Delta Brac Housing Finance Corporation Ltd.';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Delta Brac Housing Finance Corporation Ltd.', 
  stackholders = 'DBHBANGLA', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.15, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'Delta Brac Housing Finance Corporation Ltd.';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Dhaka Nibash Developments & Housing Ltd', 
  stackholders = 'DHAKANIBASH', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'Dhaka Nibash Developments & Housing Ltd';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Dhaka Nibash Developments & Housing Ltd', 
  stackholders = 'NIBASH_BANGLA', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'Dhaka Nibash Developments & Housing Ltd';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Dhaka Nibash Developments & Housing Ltd', 
  stackholders = 'R_ALAM_DIPU_BANGLA', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'Dhaka Nibash Developments & Housing Ltd';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Dhanmondi Club Ltd', 
  stackholders = 'DNCLUB', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'Dhanmondi Club Ltd';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Digi Jadoo Broadband limited', 
  stackholders = 'DIGIJADOO', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'Digi Jadoo Broadband limited';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'DOCTOROLA', 
  stackholders = 'DISHARY', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'DOCTOROLA';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'DOCTOROLA', 
  stackholders = 'DOCTOROLA', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'DOCTOROLA';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'East West University', 
  stackholders = 'EWU', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'East West University';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'East West University', 
  stackholders = 'EWUCCC', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'East West University';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'East West University', 
  stackholders = 'EWUHRO', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'East West University';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'East West University', 
  stackholders = 'EWUMBA', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'East West University';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'East West University', 
  stackholders = 'EWURO', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'East West University';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'EDISON Group (Symphony Mobile )', 
  stackholders = 'SYMCAMPAIGNENG', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'EDISON Group (Symphony Mobile )';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'EDISON Group (Symphony Mobile )', 
  stackholders = 'SYMPHONYECOMENG', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'EDISON Group (Symphony Mobile )';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'EDISON Group (Symphony Mobile )', 
  stackholders = 'SYMPHONYEDISON', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'EDISON Group (Symphony Mobile )';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'EDISON Group (Symphony Mobile )', 
  stackholders = 'SYMSALESERVICEENG', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'EDISON Group (Symphony Mobile )';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'EDISON Group (Symphony Mobile )', 
  stackholders = 'SYMSALESERVICEENGAPI', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'EDISON Group (Symphony Mobile )';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'eDokandar.com', 
  stackholders = 'EDOKANDARENG', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'eDokandar.com';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'ELITE FORCE', 
  stackholders = 'ELITFORCE', 
  Type = 'LOCAL', 
  SD_RAT = 0.05, 
  VAT_RAT = 0, 
  Sarcharge_1 %= 0.01 
WHERE 
  Company = 'ELITE FORCE';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Emerging Credit Rating Limited', 
  stackholders = 'ECRL', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'Emerging Credit Rating Limited';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Emerging Credit Rating Limited', 
  stackholders = 'NKAMOBINFCA', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'Emerging Credit Rating Limited';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Emerging Credit Rating Limited', 
  stackholders = 'NKAMOBINFCABANGLA', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'Emerging Credit Rating Limited';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'ERC, Dhaka', 
  stackholders = 'ERCDHAKA', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'ERC, Dhaka';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'ERC, Dhaka', 
  stackholders = 'MIAHQUAYYUM', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'ERC, Dhaka';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Escorp Apparels Ltd (YELLOW)', 
  stackholders = 'YELLOW', 
  Type = 'LOCAL', 
  SD_RAT = 0.05, 
  VAT_RAT = 0.15, 
  Sarcharge_1 %= 0.01 
WHERE 
  Company = 'Escorp Apparels Ltd (YELLOW)';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'eSheba.org', 
  stackholders = 'ESHEBA', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'eSheba.org';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Excel telecom pvt ltd', 
  stackholders = 'SAMSUNG_EXCEL', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'Excel telecom pvt ltd';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Executive Lifestyles Ltd.', 
  stackholders = 'KOHLERENG', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'Executive Lifestyles Ltd.';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'FAITH BANGLADESH', 
  stackholders = 'FAITHBD', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.15, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'FAITH BANGLADESH';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Fine Homes Ltd', 
  stackholders = 'FINEHOMELTD', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'Fine Homes Ltd';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Fine Homes Ltd', 
  stackholders = 'FINEHOMELTDBD', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'Fine Homes Ltd';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Fine Homes Ltd', 
  stackholders = 'FPROPERTIES', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'Fine Homes Ltd';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'FINGERPRINT', 
  stackholders = 'APBNSCBOGRASCHOOL', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'FINGERPRINT';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Flight Expert', 
  stackholders = 'FLIGHTXPERTENG', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'Flight Expert';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'GD Assist Limited', 
  stackholders = 'GDASSISTBANGLA', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'GD Assist Limited';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Ghorebazar.com', 
  stackholders = 'GHOREBAZARBNG', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'Ghorebazar.com';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Ghorebazar.com', 
  stackholders = 'GHOREBAZARDOT', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'Ghorebazar.com';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Green Delta Insurance', 
  stackholders = 'GREENDELTABANGLA', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'Green Delta Insurance';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Green Delta Insurance', 
  stackholders = 'GREENDELTABRAND', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'Green Delta Insurance';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'greenuniverstiy', 
  stackholders = 'GREENUNIVERSTIY', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'greenuniverstiy';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'GWEEBARRA BAKERY INDUSTRY LTD.', 
  stackholders = 'COOPERSNONMASKING', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0.01 
WHERE 
  Company = 'GWEEBARRA BAKERY INDUSTRY LTD.';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'HG Aviation Limited (REGENT)', 
  stackholders = 'REGENT', 
  Type = 'INTERNATIONAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'HG Aviation Limited (REGENT)';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'HG Aviation Limited (REGENT)', 
  stackholders = 'BKKAPT_REGENT', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'HG Aviation Limited (REGENT)';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'HG Aviation Limited (REGENT)', 
  stackholders = 'CCUAPT_REGENT', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'HG Aviation Limited (REGENT)';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'HG Aviation Limited (REGENT)', 
  stackholders = 'CGPAPT_REGENT', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'HG Aviation Limited (REGENT)';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'HG Aviation Limited (REGENT)', 
  stackholders = 'CXBAPT_REGENT', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'HG Aviation Limited (REGENT)';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'HG Aviation Limited (REGENT)', 
  stackholders = 'DACAPT_REGENT', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'HG Aviation Limited (REGENT)';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'HG Aviation Limited (REGENT)', 
  stackholders = 'DMMAPTREGENT', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'HG Aviation Limited (REGENT)';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'HG Aviation Limited (REGENT)', 
  stackholders = 'DOHAPTREGENT', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'HG Aviation Limited (REGENT)';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'HG Aviation Limited (REGENT)', 
  stackholders = 'JSRAPT_REGENT', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'HG Aviation Limited (REGENT)';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'HG Aviation Limited (REGENT)', 
  stackholders = 'KULAPT_REGENT', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'HG Aviation Limited (REGENT)';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'HG Aviation Limited (REGENT)', 
  stackholders = 'MCTAPT_REGENT', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'HG Aviation Limited (REGENT)';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'HG Aviation Limited (REGENT)', 
  stackholders = 'REGENT', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'HG Aviation Limited (REGENT)';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'HG Aviation Limited (REGENT)', 
  stackholders = 'REVENUE_REGENT', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'HG Aviation Limited (REGENT)';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'HG Aviation Limited (REGENT)', 
  stackholders = 'SINAPT_REGENT', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'HG Aviation Limited (REGENT)';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'HG Aviation Limited (REGENT)', 
  stackholders = 'SPDAPTREGENT', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'HG Aviation Limited (REGENT)';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'HG Aviation Limited (REGENT)', 
  stackholders = 'ZYLAPT_REGENT', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'HG Aviation Limited (REGENT)';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Holcim Bangladesh', 
  stackholders = 'HOLCIM', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.15, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'Holcim Bangladesh';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Holcim Bangladesh', 
  stackholders = 'HOLCIM_SUBARMAN', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.15, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'Holcim Bangladesh';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'hungrynaki', 
  stackholders = 'HUNGRYNAKI', 
  Type = 'INTERNATIONAL', 
  SD_RAT = 0, 
  VAT_RAT = 0, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'hungrynaki';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'hungrynaki', 
  stackholders = 'HUNGRYNAKI', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'hungrynaki';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Hurdco International School', 
  stackholders = 'HURDCO', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'Hurdco International School';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'ICDDRB', 
  stackholders = 'ICDDRB', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'ICDDRB';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'ICDDRB', 
  stackholders = 'ICDDRB_TTU', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'ICDDRB';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'ICDDRB', 
  stackholders = 'ICDDRBHR', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'ICDDRB';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'ICDDRB', 
  stackholders = 'NOTIFYTB', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'ICDDRB';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'ICDDRB Employees Multipurpose Co-operative Society Ltd.', 
  stackholders = 'ICDDRBCOOP', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'ICDDRB Employees Multipurpose Co-operative Society Ltd.';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'idp', 
  stackholders = 'IDPBD', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.15, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'idp';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'IFAD Information & Technologies Ltd.', 
  stackholders = 'ITRACKER', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'IFAD Information & Technologies Ltd.';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Innoweb Ltd.', 
  stackholders = 'CCLDC', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'Innoweb Ltd.';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Innoweb Ltd.', 
  stackholders = 'CEOINFOFORT', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'Innoweb Ltd.';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Innoweb Ltd.', 
  stackholders = 'INNOWEB', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'Innoweb Ltd.';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Innoweb Ltd.', 
  stackholders = 'MAHBUBANAM', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'Innoweb Ltd.';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Innoweb Ltd.', 
  stackholders = 'MARIAHFCAENG', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'Innoweb Ltd.';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Innoweb Ltd.', 
  stackholders = 'MHKHUSRUFCA', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'Innoweb Ltd.';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Institute of Chartered Secretaries of Bangladesh (ICSB)', 
  stackholders = 'ICSBBRAND', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'Institute of Chartered Secretaries of Bangladesh (ICSB)';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Institute of Information & Communication Technology, BUET (IICT)', 
  stackholders = 'BUETADMISSION', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'Institute of Information & Communication Technology, BUET (IICT)';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Inter Cloud', 
  stackholders = 'BRILLIANT', 
  Type = 'INTERNATIONAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'Inter Cloud';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Inter Cloud', 
  stackholders = 'BRILLIANT', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'Inter Cloud';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Inter Cloud', 
  stackholders = 'NOVOTEL', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'Inter Cloud';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'IT Bank', 
  stackholders = 'CITYCOLLEGE', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'IT Bank';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'IT Bank', 
  stackholders = 'MASHRUBAMT', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'IT Bank';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'IT Bank', 
  stackholders = 'SAASENG', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'IT Bank';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Joban Private Ltd', 
  stackholders = 'JOBIKE', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'Joban Private Ltd';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Kazi Equities Ltd.', 
  stackholders = 'KAZI', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'Kazi Equities Ltd.';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Khaas Food Limited', 
  stackholders = 'KHASSFOOD', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'Khaas Food Limited';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'LCBS Dhaka', 
  stackholders = 'LCBS', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'LCBS Dhaka';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Le Meridien Dhaka', 
  stackholders = 'LEMERIDIEN', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'Le Meridien Dhaka';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Le Meridien Dhaka', 
  stackholders = 'LEROYAL', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'Le Meridien Dhaka';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Leisure Bangladesh Ltd.', 
  stackholders = 'BARRSARWATBNG', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'Leisure Bangladesh Ltd.';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Leisure Bangladesh Ltd.', 
  stackholders = 'BHALOGULSHNBNG', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'Leisure Bangladesh Ltd.';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Leisure Bangladesh Ltd.', 
  stackholders = 'BHALOGULSHNENG', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'Leisure Bangladesh Ltd.';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Lions Club International', 
  stackholders = 'LIONS2', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'Lions Club International';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Mango Digital Ltd.', 
  stackholders = 'MANGOPHONE', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'Mango Digital Ltd.';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Mangrove Consultancy Company', 
  stackholders = 'MANGROVEEMS', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'Mangrove Consultancy Company';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Mbrella', 
  stackholders = 'MUADBNG', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'Mbrella';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Mbrella', 
  stackholders = 'MUADENG', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'Mbrella';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'MEENABAZAR', 
  stackholders = 'MEENABAZAR', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'MEENABAZAR';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'MEENABAZAR', 
  stackholders = 'PRICECHNGEMENABAZAR', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'MEENABAZAR';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Melody Homes Ltd.', 
  stackholders = 'MELODY2', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.15, 
  Sarcharge_1 %= 0.01 
WHERE 
  Company = 'Melody Homes Ltd.';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Melody Homes Ltd.', 
  stackholders = 'MELODYHOMES', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.15, 
  Sarcharge_1 %= 0.01 
WHERE 
  Company = 'Melody Homes Ltd.';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'MIH Enterprise', 
  stackholders = 'APANJEWELRS', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'MIH Enterprise';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'MIH Enterprise', 
  stackholders = 'DHANMONDI_APAN', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'MIH Enterprise';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'MIH Enterprise', 
  stackholders = 'HELALAKBAR', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'MIH Enterprise';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'MIH Enterprise', 
  stackholders = 'LAREINA', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'MIH Enterprise';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'MIH Enterprise', 
  stackholders = 'UTTARA_APAN', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'MIH Enterprise';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'MirpurDOHS', 
  stackholders = 'MIRPURDOHS', 
  Type = 'LOCAL', 
  SD_RAT = 0.05, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0.01 
WHERE 
  Company = 'MirpurDOHS';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Mondol Group', 
  stackholders = 'MBRELLAENG', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'Mondol Group';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Monico Technologies Ltd', 
  stackholders = 'FINDER', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'Monico Technologies Ltd';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Monico Technologies Ltd', 
  stackholders = 'TRUCKKOTHAY', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'Monico Technologies Ltd';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Mount 2 Ocean Travel & Tours', 
  stackholders = 'M2OENG', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'Mount 2 Ocean Travel & Tours';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Munshi Enterprise Ltd.', 
  stackholders = 'MUNSHI', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'Munshi Enterprise Ltd.';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'MyCampus (TechAnts Technologies Ltd.)', 
  stackholders = 'MYCAMPUS', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.15, 
  Sarcharge_1 %= 0.01 
WHERE 
  Company = 'MyCampus (TechAnts Technologies Ltd.)';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Nahee Group', 
  stackholders = 'NAHEEGROUPENG', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'Nahee Group';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Neeshorgo Hotel And Resort', 
  stackholders = 'NEESHORGO', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'Neeshorgo Hotel And Resort';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'New Vision Ltd.', 
  stackholders = 'NEW_VISION_LTD', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.15, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'New Vision Ltd.';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'New Vision Ltd.', 
  stackholders = 'NEWVISION_BANGLA', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.15, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'New Vision Ltd.';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'NewVisionGroup', 
  stackholders = 'NEWVISION', 
  Type = 'INTERNATIONAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.15, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'NewVisionGroup';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'NewVisionGroup', 
  stackholders = 'NEWVISION', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.15, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'NewVisionGroup';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'NEXDECADE TECHNOLOGY (PVT.) LTD', 
  stackholders = 'DECADE', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'NEXDECADE TECHNOLOGY (PVT.) LTD';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'NEXDECADE TECHNOLOGY (PVT.) LTD', 
  stackholders = 'VIEWERSTVNEXDECADE', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'NEXDECADE TECHNOLOGY (PVT.) LTD';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Niloy Motors Limited', 
  stackholders = 'HEROBD', 
  Type = 'LOCAL', 
  SD_RAT = 0.05, 
  VAT_RAT = 0.15, 
  Sarcharge_1 %= 0.01 
WHERE 
  Company = 'Niloy Motors Limited';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Niloy Motors Limited', 
  stackholders = 'NILOYHERO', 
  Type = 'LOCAL', 
  SD_RAT = 0.05, 
  VAT_RAT = 0.15, 
  Sarcharge_1 %= 0.01 
WHERE 
  Company = 'Niloy Motors Limited';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Niloy Motors Limited', 
  stackholders = 'NILOYMOTORS', 
  Type = 'LOCAL', 
  SD_RAT = 0.05, 
  VAT_RAT = 0.15, 
  Sarcharge_1 %= 0.01 
WHERE 
  Company = 'Niloy Motors Limited';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Nippon Paint (Bangladesh) Private Limited', 
  stackholders = 'NIPPONBANGLA', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'Nippon Paint (Bangladesh) Private Limited';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Nitol Motors Limited', 
  stackholders = 'NITOL_MOTORS', 
  Type = 'LOCAL', 
  SD_RAT = 0.05, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0.01 
WHERE 
  Company = 'Nitol Motors Limited';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Nitol Motors Limited', 
  stackholders = 'NITOL_MOTORS_BG', 
  Type = 'LOCAL', 
  SD_RAT = 0.05, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0.01 
WHERE 
  Company = 'Nitol Motors Limited';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Nitol Motors Limited', 
  stackholders = 'SELIMAAHMADBD', 
  Type = 'LOCAL', 
  SD_RAT = 0.05, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0.01 
WHERE 
  Company = 'Nitol Motors Limited';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'novartis', 
  stackholders = 'NOVARTIS', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'novartis';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Novo Nordisk Pharma (Private) Ltd', 
  stackholders = 'NOVONORDISK', 
  Type = 'LOCAL', 
  SD_RAT = 0.05, 
  VAT_RAT = 0.15, 
  Sarcharge_1 %= 0.01 
WHERE 
  Company = 'Novo Nordisk Pharma (Private) Ltd';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Novo Nordisk Pharma (Private) Ltd', 
  stackholders = 'NOVONORDISK2', 
  Type = 'LOCAL', 
  SD_RAT = 0.05, 
  VAT_RAT = 0.15, 
  Sarcharge_1 %= 0.01 
WHERE 
  Company = 'Novo Nordisk Pharma (Private) Ltd';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'novoair', 
  stackholders = 'NOVOAIR_API', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'novoair';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'novoair', 
  stackholders = 'NOVOAIRBANGLA', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'novoair';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'novoair', 
  stackholders = 'NOVOAIRCRC', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'novoair';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'novoair', 
  stackholders = 'NOVOAIRHR', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'novoair';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'novoair', 
  stackholders = 'NOVOAIROPT', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'novoair';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'novoair', 
  stackholders = 'NOVOAIRSER', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'novoair';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'OBhai Solutions Ltd.', 
  stackholders = 'OBHAIBANGLA', 
  Type = 'AIRTEL', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'OBhai Solutions Ltd.';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'OBhai Solutions Ltd.', 
  stackholders = 'OBHAIBANGLA', 
  Type = 'BANGLALINK', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'OBhai Solutions Ltd.';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'OBhai Solutions Ltd.', 
  stackholders = 'OBHAIBANGLA', 
  Type = 'GRAMEEN', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'OBhai Solutions Ltd.';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'OBhai Solutions Ltd.', 
  stackholders = 'OBHAIBANGLA', 
  Type = 'ROBI', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'OBhai Solutions Ltd.';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'OBhai Solutions Ltd.', 
  stackholders = 'OBHAIBANGLA', 
  Type = 'TELETALK', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'OBhai Solutions Ltd.';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'OBhai Solutions Ltd.', 
  stackholders = 'OBHAIENG', 
  Type = 'AIRTEL', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'OBhai Solutions Ltd.';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'OBhai Solutions Ltd.', 
  stackholders = 'OBHAIENG', 
  Type = 'BANGLALINK', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'OBhai Solutions Ltd.';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'OBhai Solutions Ltd.', 
  stackholders = 'OBHAIENG', 
  Type = 'GRAMEEN', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'OBhai Solutions Ltd.';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'OBhai Solutions Ltd.', 
  stackholders = 'OBHAIENG', 
  Type = 'ROBI', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'OBhai Solutions Ltd.';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'OBhai Solutions Ltd.', 
  stackholders = 'OBHAIENG', 
  Type = 'TELETALK', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'OBhai Solutions Ltd.';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Old Cadets Association Of Sylhet (OCAS)', 
  stackholders = 'OCASBRAND', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.15, 
  Sarcharge_1 %= 0.01 
WHERE 
  Company = 'Old Cadets Association Of Sylhet (OCAS)';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Omex Courier and Logistics Ltd', 
  stackholders = 'OMEXCOURIER', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'Omex Courier and Logistics Ltd';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Omicon', 
  stackholders = 'LECTURE_OMICON', 
  Type = 'LOCAL', 
  SD_RAT = 0.05, 
  VAT_RAT = 0, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'Omicon';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Omicon', 
  stackholders = 'LECTUREBANGLA', 
  Type = 'LOCAL', 
  SD_RAT = 0.05, 
  VAT_RAT = 0, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'Omicon';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Orange Business Development Limited', 
  stackholders = 'KCPS', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.045, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'Orange Business Development Limited';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Oxford International School', 
  stackholders = 'OXFORDINTBRAND', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'Oxford International School';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Panjeree Publication Ltd', 
  stackholders = 'AKKHARPATRA', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'Panjeree Publication Ltd';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Panjeree Publication Ltd', 
  stackholders = 'AKKHARPATRABANGLA', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'Panjeree Publication Ltd';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Panjeree Publication Ltd', 
  stackholders = 'PANJEREE', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'Panjeree Publication Ltd';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Panjeree Publication Ltd', 
  stackholders = 'PANJEREEBANGLA', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'Panjeree Publication Ltd';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Panna Group', 
  stackholders = 'PANNAGROUP', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'Panna Group';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Paribahan.com (Electro Craft Corporation Limited)', 
  stackholders = 'GREENLINENONMASKEN', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'Paribahan.com (Electro Craft Corporation Limited)';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Paribahan.com (Electro Craft Corporation Limited)', 
  stackholders = 'GREENLINEPARIBAHAN', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'Paribahan.com (Electro Craft Corporation Limited)';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'PASSPORT (Data Edge Limited)', 
  stackholders = 'PASSPORT', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.15, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'PASSPORT (Data Edge Limited)';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'PI International Education', 
  stackholders = 'PIEENG', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'PI International Education';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Popular Medical College Hospital', 
  stackholders = 'PDC', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'Popular Medical College Hospital';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Popular Medical College Hospital', 
  stackholders = 'POPULARHOSENG', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'Popular Medical College Hospital';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'prince_bazar', 
  stackholders = 'MDPURBD', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.15, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'prince_bazar';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'prince_bazar', 
  stackholders = 'MIRPURBD', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.15, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'prince_bazar';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'prince_bazar', 
  stackholders = 'PALLABIBD', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.15, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'prince_bazar';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'prince_bazar', 
  stackholders = 'SHYAMOLIBD', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.15, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'prince_bazar';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Quantum Foundation', 
  stackholders = 'QUANTUMNON', 
  Type = 'LOCAL', 
  SD_RAT = 0.05, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0.01 
WHERE 
  Company = 'Quantum Foundation';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'qubee', 
  stackholders = 'QUBEE', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'qubee';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'qubee', 
  stackholders = 'QUBEE_APPS', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'qubee';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'qubee', 
  stackholders = 'QUBEEBD', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'qubee';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'rak_paints', 
  stackholders = 'RAK_PAINTS', 
  Type = 'LOCAL', 
  SD_RAT = 0.05, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0.01 
WHERE 
  Company = 'rak_paints';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Ratul Properties Ltd.', 
  stackholders = 'RUPAYANTOWNBD', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'Ratul Properties Ltd.';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'REHAB', 
  stackholders = 'REHAB', 
  Type = 'LOCAL', 
  SD_RAT = 0.05, 
  VAT_RAT = 0, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'REHAB';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'REHAB', 
  stackholders = 'REHAB_BD', 
  Type = 'LOCAL', 
  SD_RAT = 0.05, 
  VAT_RAT = 0, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'REHAB';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Runner Auto Mobile', 
  stackholders = 'RUNNER', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.15, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'Runner Auto Mobile';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Runner Auto Mobile', 
  stackholders = 'RUNNER_CAMPAIGN', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'Runner Auto Mobile';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Runner Auto Mobile', 
  stackholders = 'RUNNER_MOTORS_SVC', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.15, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'Runner Auto Mobile';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Runner Motors Limited', 
  stackholders = 'RMLBANGLA', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'Runner Motors Limited';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'S F X Greenherald International School', 
  stackholders = 'GREENHERALD', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'S F X Greenherald International School';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'sadakalo', 
  stackholders = 'SADAKALO', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'sadakalo';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Saifurs Private Limited', 
  stackholders = 'SAIFURSNONNILKHETENG', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'Saifurs Private Limited';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'SAP', 
  stackholders = 'HSBCATMSAP', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'SAP';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'SAP', 
  stackholders = 'HSBCSAP', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'SAP';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'SAP', 
  stackholders = 'SAPHASEHK', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'SAP';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'SAP', 
  stackholders = 'SAPHSBCHK', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'SAP';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'SAP', 
  stackholders = 'SAPUBERBD', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'SAP';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'SAP', 
  stackholders = 'STANCHARTBD', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'SAP';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Scholars Coaching Center', 
  stackholders = 'SCHOLARS', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'Scholars Coaching Center';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'scholastica', 
  stackholders = 'SCHOLASTICA', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'scholastica';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'School of Laureates International', 
  stackholders = 'LAUREATESENG', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'School of Laureates International';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Sheba.xyz', 
  stackholders = 'SHEBAXYZ', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'Sheba.xyz';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'shohoj', 
  stackholders = 'SHOHOJ', 
  Type = 'LOCAL', 
  SD_RAT = 0.05, 
  VAT_RAT = 0.15, 
  Sarcharge_1 %= 0.01 
WHERE 
  Company = 'shohoj';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'ShopUp', 
  stackholders = 'SHOPUP', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'ShopUp';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Show Motion Limited', 
  stackholders = 'STARCINEPLX', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'Show Motion Limited';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Splendor IT', 
  stackholders = 'SPLENDORIT', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'Splendor IT';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Spondon ICT Ltd.', 
  stackholders = 'ANDROMEDA', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'Spondon ICT Ltd.';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Spondon ICT Ltd.', 
  stackholders = 'DEABMDA', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'Spondon ICT Ltd.';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Spondon ICT Ltd.', 
  stackholders = 'NEXTMOTIONBD', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'Spondon ICT Ltd.';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Spondon ICT Ltd.', 
  stackholders = 'NEXTMOTIONENG', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'Spondon ICT Ltd.';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Spondon ICT Ltd.', 
  stackholders = 'SANGEN_INTL', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'Spondon ICT Ltd.';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Spondon ICT Ltd.', 
  stackholders = 'SPONDONNONBANGLA', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'Spondon ICT Ltd.';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Spondon ICT Ltd.', 
  stackholders = 'UNITREND', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'Spondon ICT Ltd.';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Spondon ICT Ltd.', 
  stackholders = 'UNITRENDBD', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'Spondon ICT Ltd.';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Square Toiletries Limited', 
  stackholders = 'KOOL', 
  Type = 'LOCAL', 
  SD_RAT = 0.05, 
  VAT_RAT = 0.15, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'Square Toiletries Limited';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Square Toiletries Limited', 
  stackholders = 'KOOLBANGLA', 
  Type = 'LOCAL', 
  SD_RAT = 0.05, 
  VAT_RAT = 0.15, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'Square Toiletries Limited';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Square Toiletries Limited', 
  stackholders = 'STL', 
  Type = 'LOCAL', 
  SD_RAT = 0.05, 
  VAT_RAT = 0.15, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'Square Toiletries Limited';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Square Toiletries Limited', 
  stackholders = 'STLBANGLA', 
  Type = 'LOCAL', 
  SD_RAT = 0.05, 
  VAT_RAT = 0.15, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'Square Toiletries Limited';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Square Toiletries Limited', 
  stackholders = 'SUPERMOM', 
  Type = 'LOCAL', 
  SD_RAT = 0.05, 
  VAT_RAT = 0.15, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'Square Toiletries Limited';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'SS Holidays Pvt. Ltd.', 
  stackholders = 'SSHOLIDAYSNONBRAN', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'SS Holidays Pvt. Ltd.';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Sundarban Courier Service (Pvt.)Ltd.', 
  stackholders = 'SUNDARBAN', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'Sundarban Courier Service (Pvt.)Ltd.';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Surecell Medical BD Ltd.', 
  stackholders = 'BODYBALANCE', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.15, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'Surecell Medical BD Ltd.';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Surecell Medical BD Ltd.', 
  stackholders = 'BUMRUNGRAD', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.15, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'Surecell Medical BD Ltd.';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Surecell Medical BD Ltd.', 
  stackholders = 'SURECELLMED', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.15, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'Surecell Medical BD Ltd.';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'System Unimax Limited', 
  stackholders = 'QKSYS', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.15, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'System Unimax Limited';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Tanveer Food Ltd', 
  stackholders = 'EFRESHSTORE', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'Tanveer Food Ltd';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'The Institute Of Chartered Accountants Of Bangladesh (ICAB)', 
  stackholders = 'ICABBRAND', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'The Institute Of Chartered Accountants Of Bangladesh (ICAB)';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'The Westin Dhaka', 
  stackholders = 'WESTIN_HOTEL', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'The Westin Dhaka';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'TIB', 
  stackholders = 'TIB', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'TIB';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'TigerIT', 
  stackholders = 'BRTA', 
  Type = 'LOCAL', 
  SD_RAT = 0.05, 
  VAT_RAT = 0.15, 
  Sarcharge_1 %= 0.01 
WHERE 
  Company = 'TigerIT';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'TigerIT', 
  stackholders = 'BRTAHSDL', 
  Type = 'LOCAL', 
  SD_RAT = 0.05, 
  VAT_RAT = 0.15, 
  Sarcharge_1 %= 0.01 
WHERE 
  Company = 'TigerIT';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'TOKYO TRAVELS INTERNATIONAL', 
  stackholders = 'TOKYOTRAVELSBNG', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'TOKYO TRAVELS INTERNATIONAL';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Tour Vision', 
  stackholders = 'TOUR_VISION', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'Tour Vision';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Transcom Electronics', 
  stackholders = 'TRANSCOMELECBRAND', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'Transcom Electronics';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Transcom Electronics', 
  stackholders = 'TRANSCOMELECECOM', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'Transcom Electronics';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Transcombd Ltd', 
  stackholders = 'KFC', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'Transcombd Ltd';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Transcombd Ltd', 
  stackholders = 'PIZZAHUT', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'Transcombd Ltd';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'TVS Auto Bangladesh', 
  stackholders = 'TVSABBNG', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'TVS Auto Bangladesh';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'UCC_Group', 
  stackholders = 'ICC', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'UCC_Group';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'UCC_Group', 
  stackholders = 'ILC', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'UCC_Group';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'UCC_Group', 
  stackholders = 'UCC_GROUP', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'UCC_Group';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'UCC_Group', 
  stackholders = 'UCCGROUPBD', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'UCC_Group';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'ulc', 
  stackholders = 'UNITEDFNANC', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.15, 
  Sarcharge_1 %= 0.01 
WHERE 
  Company = 'ulc';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'uniaid_mmz', 
  stackholders = 'UNIAID_MMZ', 
  Type = 'LOCAL', 
  SD_RAT = 0.05, 
  VAT_RAT = 0, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'uniaid_mmz';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'UNICEF Bangladesh', 
  stackholders = 'UNICEF16629', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'UNICEF Bangladesh';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'UNILEVER BANGLADESH LIMITED', 
  stackholders = 'CSATPUREIT', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.15, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'UNILEVER BANGLADESH LIMITED';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'UNILEVER BANGLADESH LIMITED', 
  stackholders = 'PUREIT2', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.15, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'UNILEVER BANGLADESH LIMITED';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'UNILEVER BANGLADESH LIMITED', 
  stackholders = 'PUREITANALYZENUBLBD', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.15, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'UNILEVER BANGLADESH LIMITED';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'US EMBASSY', 
  stackholders = 'ACS', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'US EMBASSY';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'US EMBASSY', 
  stackholders = 'PAS', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'US EMBASSY';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'US EMBASSY', 
  stackholders = 'RSO', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'US EMBASSY';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'US-Bangla Airlines', 
  stackholders = 'USBANGLA_AIR', 
  Type = 'LOCAL', 
  SD_RAT = 0.05, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'US-Bangla Airlines';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'US-Bangla Airlines', 
  stackholders = 'USBANGLAAIRAPI', 
  Type = 'LOCAL', 
  SD_RAT = 0.05, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'US-Bangla Airlines';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Uttara Club Limited', 
  stackholders = 'BOATCLUB', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'Uttara Club Limited';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Uttara Club Limited', 
  stackholders = 'UCLPVTADS', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'Uttara Club Limited';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Uttara Club Limited', 
  stackholders = 'UTTARACLUB', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'Uttara Club Limited';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Uttara Club Limited', 
  stackholders = 'UTTARACLUBDSOFT', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'Uttara Club Limited';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Uttara University', 
  stackholders = 'UTTARAUNIV', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'Uttara University';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Uttara University', 
  stackholders = 'UTTARAUNIVBANGLA', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'Uttara University';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'VIBES HEALTHCARE (BANGLADESH) PVT. LTD.', 
  stackholders = 'VIBESCLINICENG', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'VIBES HEALTHCARE (BANGLADESH) PVT. LTD.';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'vlcc', 
  stackholders = 'VLCCGULSHAN', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'vlcc';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Zero Gravity', 
  stackholders = 'KHIKSHA', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'Zero Gravity';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Zero Gravity', 
  stackholders = 'KIKSHABD', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'Zero Gravity';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'Zero Gravity', 
  stackholders = 'SINDABAD', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0.05, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'Zero Gravity';
/* UPDATE QUERY */
UPDATE 
  smsMaster 
SET 
  Company = 'zx_online', 
  stackholders = 'ZX_ONLINE', 
  Type = 'LOCAL', 
  SD_RAT = 0, 
  VAT_RAT = 0, 
  Sarcharge_1 %= 0 
WHERE 
  Company = 'zx_online';
