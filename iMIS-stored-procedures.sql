/****************************************GET IMIS USER********************************************************/

USE [PRODIMIS]
GO
/****** Object:  StoredProcedure [dbo].[GetImisUser]    Script Date: 01/28/2015 09:41:41 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
ALTER PROCEDURE [dbo].[GetImisUser] @user integer
AS
SELECT
t1.ID,
FIRST_NAME,
MIDDLE_NAME,
LAST_NAME,
SUFFIX,
DESIGNATION,
ADDRESS_NUM,
ADDRESS_1,
ADDRESS_2,
t1.CITY,
t1.STATE_PROVINCE,
t1.ZIP,
t1.COUNTRY,
WORK_PHONE,
t1.FAX,
t1.EMAIL,
WEB_PASSWORD,
MEMBER_TYPE,
t1.COMPANY,
t1.CO_ID,
t1.TITLE,
PREFIX,
WEBSITE,
MOBILE_PHONE,
ASSISTANT_NAME,
ASSISTANT_PHONE,
ASSISTANT_EMAIL,
WEB_INTERESTS,
t1.INFORMAL
FROM PRODIMIS.dbo.Name t1
      INNER JOIN PRODIMIS.dbo.UD_SECURITY t2 ON t1.ID = t2.ID
      INNER JOIN PRODIMIS.dbo.Demographics t3 ON t3.ID = t2.ID
      INNER JOIN PRODIMIS.dbo.Name_Address t4 ON t4.ID = t2.ID
WHERE (MEMBER_TYPE = 'MIND' OR MEMBER_TYPE= 'STAFF')
      AND t1.EMAIL != ''
    AND t1.ID = @user


/**************************************** importUsers ********************************************************/
USE [PRODIMIS]
GO
/****** Object:  StoredProcedure [dbo].[importUsers]    Script Date: 01/28/2015 09:40:42 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
ALTER PROCEDURE [dbo].[importUsers] @start integer = 0, @limit integer = 1000
AS
SELECT TOP (@limit) * FROM
(SELECT
row_number() over (ORDER BY t1.ID) as RowNum,
t1.ID,
FIRST_NAME,
MIDDLE_NAME,
LAST_NAME,
SUFFIX,
DESIGNATION,
ADDRESS_NUM,
ADDRESS_1,
ADDRESS_2,
t1.CITY,
t1.STATE_PROVINCE,
t1.ZIP,
t1.COUNTRY,
WORK_PHONE,
t1.FAX,
t1.EMAIL,
WEB_PASSWORD,
MEMBER_TYPE,
t1.COMPANY,
t1.CO_ID,
t1.TITLE,
PREFIX,
WEBSITE,
MOBILE_PHONE,
ASSISTANT_NAME,
ASSISTANT_PHONE,
ASSISTANT_EMAIL,
WEB_INTERESTS,
t1.INFORMAL
FROM PRODIMIS.dbo.Name t1
      INNER JOIN dbo.UD_SECURITY t2 ON t1.ID = t2.ID
      INNER JOIN dbo.Demographics t3 ON t3.ID = t2.ID
      INNER JOIN dbo.Name_Address t4 ON t4.ID = t2.ID
WHERE (MEMBER_TYPE = 'MIND' OR MEMBER_TYPE= 'STAFF')
      AND t1.EMAIL != ''
) as temp

WHERE RowNum > @start



/**************************************** GetRowCount ********************************************************/

USE [PRODIMIS]
GO
/****** Object:  StoredProcedure [dbo].[GetRowCount]    Script Date: 01/28/2015 09:42:07 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
ALTER PROCEDURE [dbo].[GetRowCount]
AS
SELECT
COUNT (t1.ID) AS TOTAL_ROWS
FROM PRODIMIS.dbo.Name t1
      INNER JOIN PRODIMIS.dbo.UD_SECURITY t2 ON t1.ID = t2.ID
      INNER JOIN PRODIMIS.dbo.Demographics t3 ON t3.ID = t2.ID
      INNER JOIN PRODIMIS.dbo.Name_Address t4 ON t4.ID = t2.ID
WHERE (MEMBER_TYPE = 'MIND' OR MEMBER_TYPE= 'STAFF')
      AND t1.EMAIL != ''



/**************************************** MESH_UD_Security ********************************************************/


USE [PRODIMIS]
GO
/****** Object:  StoredProcedure [dbo].[MESH_UD_Security]    Script Date: 01/28/2015 09:42:45 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
ALTER PROCEDURE [dbo].[MESH_UD_Security] @user_id int, @logtime nvarchar(30)
AS
UPDATE PRODIMIS.dbo.UD_SECURITY
SET LAST_LOGIN_DATE=@logtime
WHERE ID = @user_id

/**************************************** DoesUserExist ********************************************************/


USE [PRODIMIS]
GO
/****** Object:  StoredProcedure [dbo].[DoesUserExist]    Script Date: 01/28/2015 09:43:22 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
ALTER PROCEDURE [dbo].[DoesUserExist] @email varchar(200)
AS
SELECT
t1.ID,
FIRST_NAME,
MIDDLE_NAME,
LAST_NAME,
SUFFIX,
DESIGNATION,
ADDRESS_NUM,
ADDRESS_1,
ADDRESS_2,
t1.CITY,
t1.STATE_PROVINCE,
t1.ZIP,
t1.COUNTRY,
WORK_PHONE,
t1.FAX,
t1.EMAIL,
WEB_PASSWORD,
MEMBER_TYPE,
t1.COMPANY,
t1.CO_ID,
t1.TITLE,
PREFIX,
WEBSITE,
MOBILE_PHONE,
ASSISTANT_NAME,
ASSISTANT_PHONE,
ASSISTANT_EMAIL,
WEB_INTERESTS,
t1.INFORMAL
FROM PRODIMIS.dbo.Name t1
      INNER JOIN PRODIMIS.dbo.UD_SECURITY t2 ON t1.ID = t2.ID
      INNER JOIN PRODIMIS.dbo.Demographics t3 ON t3.ID = t2.ID
      INNER JOIN PRODIMIS.dbo.Name_Address t4 ON t4.ID = t2.ID
WHERE (MEMBER_TYPE = 'MIND' OR MEMBER_TYPE= 'STAFF')
      AND t2.WEB_LOGIN = @email


/**************************************** GetWebInterests ********************************************************/


USE [PRODIMIS]
GO
/****** Object:  StoredProcedure [dbo].[GetWebInterests]    Script Date: 01/28/2015 09:44:05 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
ALTER PROCEDURE [dbo].[GetWebInterests]
AS
SELECT CODE, DESCRIPTION
FROM   PRODIMIS.dbo.Gen_Tables
WHERE (TABLE_NAME = 'WEB_INTERESTS')


/**************************************** GetCompanyList ********************************************************/



USE [PRODIMIS]
GO
/****** Object:  StoredProcedure [dbo].[GetCompanyList]    Script Date: 01/28/2015 09:44:28 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Author:        <Author,,Name>
-- Create date: <Create Date,,>
-- Description:   <Description,,>
-- =============================================
ALTER PROCEDURE [dbo].[GetCompanyList]
AS
SELECT HEADQUARTERS_NAME as HQ, ID, COMPANY, COMPANY_SORT, ADDRESS, CITY, STATE_PROVINCE AS STATE, ZIP, WORK_PHONE, FAX
FROM PRODIMIS.dbo._MESH_COMPANYLIST order by COMPANY



/**************************************** GetEmailList ********************************************************/


USE [PRODIMIS]
GO
/****** Object:  StoredProcedure [dbo].[GetEmailList]    Script Date: 01/28/2015 09:44:46 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Author:        <Author,,Name>
-- Create date: <Create Date,,>
-- Description:   <Description,,>
-- =============================================
ALTER PROCEDURE [dbo].[GetEmailList]
AS
SELECT DISTINCT SUBSTRING(EMAIL, CHARINDEX('@', EMAIL) + 1, 99) AS EMAIL_SUFFIX
FROM    dbo.Name
WHERE (MEMBER_TYPE IN ('mind'))
AND (EMAIL <> ' ') AND (EMAIL NOT LIKE '%naph.org%') AND (EMAIL NOT LIKE '%yahoo.com%') AND
            (EMAIL NOT LIKE '%sbcglobal.net%') AND (EMAIL NOT LIKE '%hotmail.com%') AND (EMAIL NOT LIKE '%aol.com%') AND (EMAIL NOT LIKE '%comcast.net%') AND (EMAIL NOT LIKE '%earthlink.net%') AND
            (EMAIL NOT LIKE '%yahoo.org%') AND (EMAIL NOT LIKE '%att.net%') AND (EMAIL NOT LIKE '%msn.com%') AND (EMAIL NOT LIKE '%bellsouth.net%') AND (EMAIL NOT LIKE '%verizon.net%') AND
            (EMAIL NOT LIKE '%mindspring.com%') AND (EMAIL NOT LIKE '%ymail.com%') AND (EMAIL NOT LIKE '%gmail.com%') AND (EMAIL NOT LIKE '%@me.com%')


 

